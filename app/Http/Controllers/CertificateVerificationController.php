<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\BlockchainCertificateService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CertificateVerificationController extends Controller
{
    protected BlockchainCertificateService $blockchainService;

    public function __construct()
    {
        $this->blockchainService = new BlockchainCertificateService();
    }

    /**
     * Show certificate verification page
     */
    public function show(Request $request, ?string $hash = null)
    {
        $certificate = null;
        $blockchainData = null;
        $verificationResult = null;

        if ($hash) {
            // Look up certificate in local database
            $certificate = Certificate::where('certificate_hash', $hash)
                ->with(['eventRegistration.event', 'eventRegistration.user'])
                ->first();

            // Verify on blockchain
            $blockchainResult = $this->blockchainService->verifyCertificate($hash);

            if ($blockchainResult['success']) {
                $blockchainData = $blockchainResult;

                // Compare local and blockchain data
                if ($certificate) {
                    $verificationResult = [
                        'local_exists' => true,
                        'blockchain_exists' => $blockchainResult['is_valid'],
                        'data_matches' => $this->compareData($certificate, $blockchainResult),
                        'is_authentic' => $certificate && $blockchainResult['is_valid'],
                    ];
                } else {
                    $verificationResult = [
                        'local_exists' => false,
                        'blockchain_exists' => $blockchainResult['is_valid'],
                        'data_matches' => false,
                        'is_authentic' => false,
                    ];
                }
            } else {
                $verificationResult = [
                    'local_exists' => $certificate !== null,
                    'blockchain_exists' => false,
                    'data_matches' => false,
                    'is_authentic' => false,
                    'error' => $blockchainResult['error'] ?? 'Blockchain verification failed',
                ];
            }
        }

        return Inertia::render('Certificates/Verify', [
            'hash' => $hash,
            'certificate' => $certificate ? [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'participant_name' => $certificate->participant_name,
                'event_title' => $certificate->event_title,
                'event_date' => $certificate->event_date->format('Y-m-d'),
                'completion_date' => $certificate->completion_date->format('Y-m-d'),
                'is_generated' => $certificate->is_generated,
                'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
                'blockchain_issued_at' => $certificate->blockchain_issued_at,
                'is_blockchain_verified' => $certificate->is_blockchain_verified,
                'event' => [
                    'title' => $certificate->eventRegistration->event->title,
                    'location' => $certificate->eventRegistration->event->location,
                ],
            ] : null,
            'blockchain_data' => $blockchainData,
            'verification_result' => $verificationResult,
        ]);
    }

    /**
     * Verify certificate via API
     */
    public function verify(Request $request)
    {
        $request->validate([
            'hash' => 'required|string',
        ]);

        $hash = $request->input('hash');

        // Look up certificate in local database
        $certificate = Certificate::where('certificate_hash', $hash)->first();

        // Verify on blockchain
        $blockchainResult = $this->blockchainService->verifyCertificate($hash);

        $result = [
            'success' => true,
            'hash' => $hash,
            'local_exists' => $certificate !== null,
            'blockchain_exists' => $blockchainResult['success'] && $blockchainResult['is_valid'],
            'is_authentic' => false,
            'data' => null,
        ];

        if ($certificate && $blockchainResult['success'] && $blockchainResult['is_valid']) {
            $result['is_authentic'] = $this->compareData($certificate, $blockchainResult);
            $result['data'] = [
                'participant_name' => $certificate->participant_name,
                'event_title' => $certificate->event_title,
                'event_date' => $certificate->event_date->format('Y-m-d'),
                'completion_date' => $certificate->completion_date->format('Y-m-d'),
                'certificate_number' => $certificate->certificate_number,
                'issued_at' => $certificate->blockchain_issued_at,
            ];
        }

        if (!$blockchainResult['success']) {
            $result['error'] = $blockchainResult['error'] ?? 'Blockchain verification failed';
        }

        return response()->json($result);
    }

    /**
     * Search for certificate by certificate number or participant name
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = $request->input('query');

        $certificates = Certificate::where(function ($q) use ($query) {
            $q->where('certificate_number', 'like', "%{$query}%")
                ->orWhere('participant_name', 'like', "%{$query}%")
                ->orWhere('event_title', 'like', "%{$query}%");
        })
            ->where('is_blockchain_verified', true)
            ->with(['eventRegistration.event'])
            ->limit(10)
            ->get()
            ->map(function ($certificate) {
                return [
                    'certificate_hash' => $certificate->certificate_hash,
                    'certificate_number' => $certificate->certificate_number,
                    'participant_name' => $certificate->participant_name,
                    'event_title' => $certificate->event_title,
                    'completion_date' => $certificate->completion_date->format('Y-m-d'),
                    'verification_url' => route('certificates.verify', ['hash' => $certificate->certificate_hash]),
                ];
            });

        return response()->json([
            'success' => true,
            'certificates' => $certificates,
        ]);
    }

    /**
     * Compare local certificate data with blockchain data
     */
    private function compareData(Certificate $certificate, array $blockchainData): bool
    {
        if (!$blockchainData['is_valid']) {
            return false;
        }

        return $certificate->participant_name === $blockchainData['participant_name'] &&
            $certificate->event_title === $blockchainData['event_title'] &&
            $certificate->event_date->format('Y-m-d') === $blockchainData['event_date'] &&
            $certificate->completion_date->format('Y-m-d') === $blockchainData['completion_date'];
    }
}
