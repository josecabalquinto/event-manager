<?php

namespace App\Services;

use App\Models\Certificate;
use App\Services\BlockchainCertificateService;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class CertificatePdfService
{
    public function generatePdf(Certificate $certificate): string
    {
        Log::info('Generating PDF for certificate ID: ' . $certificate->id);
        // Load the event data to access signatories
        $certificate->load('event');
        $event = $certificate->event;

        // Get signatories from event
        $signatories = [];
        if ($event && isset($event->certificate_signatories) && is_array($event->certificate_signatories)) {
            $signatories = $event->certificate_signatories;
        }

        // If no signatories or empty, use default
        if (empty($signatories)) {
            $signatories = [['name' => 'Signatory', 'title' => '']];
        }

        // Limit to 4 signatories max
        $signatories = array_slice($signatories, 0, 4);

        // Generate QR code if certificate has blockchain hash
        $qrCodeBase64 = null;
        if ($certificate->certificate_hash) {
            $verificationUrl = route('certificates.verify', ['hash' => $certificate->certificate_hash]);
            $fullUrl = url($verificationUrl);

            // Generate QR code as SVG first (no imagick needed), then convert to base64 PNG
            try {
                // Use svg format which doesn't require imagick
                $qrCodeSvg = QrCode::format('svg')
                    ->size(300)
                    ->margin(0)
                    ->errorCorrection('H')
                    ->generate($fullUrl);

                // Convert SVG to base64 data URI
                $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            } catch (\Exception $e) {
                Log::warning('Failed to generate QR code: ' . $e->getMessage());
                // Continue without QR code if generation fails
            }
        }

        // Generate PDF from blade template with options
        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'defaultFont' => 'helvetica',
        ])
            ->loadView('certificates.template', [
                'certificate' => $certificate,
                'signatories' => $signatories,
                'qrCodeBase64' => $qrCodeBase64,
            ]);

        // Set paper size to A4 landscape
        $pdf->setPaper('a4', 'landscape');

        // Generate unique filename
        $filename = 'certificates/' . $certificate->certificate_number . '.pdf';
        $fullPath = storage_path('app/public/' . $filename);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save PDF to file
        $pdf->save($fullPath);

        // Update certificate record
        $certificate->update([
            'certificate_path' => $filename,
            'is_generated' => true,
        ]);

        return $filename;
    }
}
