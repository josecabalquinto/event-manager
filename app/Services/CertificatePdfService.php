<?php

namespace App\Services;

use App\Models\Certificate;
use App\Services\BlockchainCertificateService;
use TCPDF;
use Illuminate\Support\Facades\Storage;

class CertificatePdfService
{
    public function generatePdf(Certificate $certificate): string
    {
        // Load the event data to access signatories
        $certificate->load('event');
        $event = $certificate->event;

        // Create new PDF document
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('CICTE CertChain');
        $pdf->SetAuthor('CICTE CertChain');
        $pdf->SetTitle('Certificate - ' . $certificate->certificate_number);
        $pdf->SetSubject('Certificate of Completion');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(20, 20, 20);
        $pdf->SetAutoPageBreak(false, 0);

        // Add a page
        $pdf->AddPage();

        // Get page dimensions
        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();
        $margin = 20;
        $contentWidth = $pageWidth - (2 * $margin);

        // Set background color
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(0, 0, $pageWidth, $pageHeight, 'F');

        // Outer certificate border - matching HTML preview (gray-800)
        $pdf->SetLineWidth(4);
        $pdf->SetDrawColor(31, 41, 55); // Gray-800 equivalent
        $pdf->Rect($margin, $margin, $contentWidth, $pageHeight - 2 * $margin, 'D');

        // Calculate vertical positions with better spacing
        $currentY = $margin + 50;

        // Certificate Title - matching HTML preview
        $pdf->SetFont('helvetica', 'B', 24); // Better size for landscape
        $pdf->SetTextColor(31, 41, 55); // Gray-800
        $pdf->SetXY($margin, $currentY);
        $pdf->Cell($contentWidth, 15, 'Certificate of Completion', 0, 0, 'C');
        $currentY += 25;

        // Decorative line under title - matching HTML yellow gradient
        $pdf->SetLineWidth(3);
        $pdf->SetDrawColor(251, 191, 36); // Yellow-400
        $lineWidth = 80; // Slightly smaller for better proportion
        $lineX = ($pageWidth - $lineWidth) / 2;
        $pdf->Line($lineX, $currentY, $lineX + $lineWidth, $currentY);
        $currentY += 35;

        // "This is to certify that" text - matching HTML preview
        $pdf->SetFont('helvetica', '', 14);
        $pdf->SetTextColor(75, 85, 99); // Gray-600
        $pdf->SetXY($margin, $currentY);
        $pdf->Cell($contentWidth, 10, 'This is to certify that', 0, 0, 'C');
        $currentY += 25;

        // Participant name - matching HTML preview
        $pdf->SetFont('helvetica', 'B', 20); // Slightly smaller for better fit
        $pdf->SetTextColor(31, 41, 55); // Gray-800
        $pdf->SetXY($margin, $currentY);
        $pdf->Cell($contentWidth, 15, $certificate->participant_name, 0, 0, 'C');

        // Underline for name - matching HTML preview
        $nameWidth = $pdf->GetStringWidth($certificate->participant_name);
        $nameX = ($pageWidth - $nameWidth) / 2;
        $pdf->SetLineWidth(1.5);
        $pdf->SetDrawColor(209, 213, 219); // Gray-300
        $pdf->Line($nameX, $currentY + 17, $nameX + $nameWidth, $currentY + 17);
        $currentY += 35;

        // "has successfully completed" text
        $pdf->SetFont('helvetica', '', 14);
        $pdf->SetTextColor(75, 85, 99); // Gray-600
        $pdf->SetXY($margin, $currentY);
        $pdf->Cell($contentWidth, 10, 'has successfully completed', 0, 0, 'C');
        $currentY += 25;

        // Event title - matching HTML preview
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(31, 41, 55); // Gray-800
        $pdf->SetXY($margin, $currentY);
        $pdf->Cell($contentWidth, 12, $certificate->event_title, 0, 0, 'C');
        $currentY += 25;

        // Event date - matching HTML preview
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(75, 85, 99); // Gray-600
        $pdf->SetXY($margin, $currentY);
        $pdf->Cell($contentWidth, 10, 'on ' . $certificate->completion_date->format('F j, Y'), 0, 0, 'C');
        $currentY += 40;

        // Footer section - Dynamic signatories
        // Ensure footer has enough space from content
        $footerStartY = max($currentY + 30, $pageHeight - 80);

        // Get signatories from event (simplified for debugging)
        $signatories = [];
        if ($event && isset($event->certificate_signatories) && is_array($event->certificate_signatories)) {
            $signatories = $event->certificate_signatories;
        }

        // If no signatories or empty, use default
        if (empty($signatories)) {
            $signatories = [['name' => 'Authorized Signatory', 'title' => 'Position']];
        }

        $signatoryCount = count($signatories);
        $sectionWidth = 120;

        // Simple positioning - two signatories left and right, or centered for one
        if ($signatoryCount == 1) {
            $positions = [($pageWidth - $sectionWidth) / 2];
        } else {
            // For 2+ signatories, place them evenly across the page
            $totalAvailableWidth = $contentWidth - 40; // Leave some margin
            $spaceBetween = $totalAvailableWidth / $signatoryCount;
            $positions = [];
            for ($i = 0; $i < $signatoryCount; $i++) {
                $positions[] = $margin + 20 + ($i * $spaceBetween);
            }
        }

        // Draw signature lines and labels
        $pdf->SetLineWidth(1);
        $pdf->SetDrawColor(156, 163, 175); // Gray-400
        $pdf->SetTextColor(75, 85, 99); // Gray-600

        foreach ($signatories as $index => $signatory) {
            if ($index >= count($positions) || $index >= 4) break; // Limit to 4 signatories max

            $x = $positions[$index];

            // Signature line
            $pdf->Line($x, $footerStartY, $x + $sectionWidth, $footerStartY);

            // Signatory name
            $pdf->SetXY($x, $footerStartY + 5);
            $pdf->SetFont('helvetica', 'B', 10);
            $name = isset($signatory['name']) && !empty($signatory['name']) ? $signatory['name'] : 'Authorized Signatory';
            $pdf->Cell($sectionWidth, 6, $name, 0, 0, 'C');

            // Signatory title
            $pdf->SetXY($x, $footerStartY + 12);
            $pdf->SetFont('helvetica', '', 9);
            $title = isset($signatory['title']) && !empty($signatory['title']) ? $signatory['title'] : 'Position';
            $pdf->Cell($sectionWidth, 6, $title, 0, 0, 'C');
        }

        // Certificate number (below the signature lines)
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(107, 114, 128); // Gray-500
        $pdf->SetXY($margin, $footerStartY + 25);
        $pdf->Cell($contentWidth, 6, 'Certificate #' . $certificate->certificate_number, 0, 0, 'C');

        // Add blockchain verification section if certificate has blockchain hash
        if ($certificate->certificate_hash) {
            // Blockchain verification QR code
            $qrCodeSize = 40;
            $qrCodeX = $pageWidth - $margin - $qrCodeSize - 10;
            $qrCodeY = $footerStartY - 50;

            // Generate verification URL
            $verificationUrl = route('certificates.verify', ['hash' => $certificate->certificate_hash]);

            // Create QR code (simplified - you might want to use a proper QR library)
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(75, 85, 99);

            // QR code placeholder (in production, generate actual QR code image)
            $pdf->Rect($qrCodeX, $qrCodeY, $qrCodeSize, $qrCodeSize, 'D');
            $pdf->SetXY($qrCodeX - 30, $qrCodeY + $qrCodeSize + 5);
            $pdf->Cell($qrCodeSize + 60, 4, 'Scan to verify on blockchain', 0, 0, 'C');

            // Blockchain hash info
            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetTextColor(107, 114, 128);
            $pdf->SetXY($margin, $footerStartY + 30);
            $pdf->Cell($contentWidth - 100, 4, 'Blockchain Hash: ' . substr($certificate->certificate_hash, 0, 32) . '...', 0, 0, 'L');

            if ($certificate->blockchain_tx_hash) {
                $pdf->SetXY($margin, $footerStartY + 35);
                $pdf->Cell($contentWidth - 100, 4, 'TX Hash: ' . substr($certificate->blockchain_tx_hash, 0, 32) . '...', 0, 0, 'L');
            }
        }

        // Generate unique filename
        $filename = 'certificates/' . $certificate->certificate_number . '.pdf';
        $fullPath = storage_path('app/public/' . $filename);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Output PDF to file
        $pdf->Output($fullPath, 'F');

        // Update certificate record
        $certificate->update([
            'certificate_path' => $filename,
            'is_generated' => true,
        ]);

        return $filename;
    }
}
