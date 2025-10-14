<?php

namespace App\Services;

use App\Models\Certificate;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlockchainCertificateService
{
    private string $rpcUrl;
    private string $contractAddress;
    private string $privateKey;
    private string $contractAbi;

    public function __construct()
    {
        $this->rpcUrl = config('blockchain.amoy_rpc_url', 'https://rpc-amoy.polygon.technology/');
        $this->contractAddress = config('blockchain.contract_address');
        $this->privateKey = config('blockchain.private_key');

        // Smart contract ABI for certificate verification
        $this->contractAbi = '[
            {
                "inputs": [
                    {"name": "certificateHash", "type": "string"},
                    {"name": "participantName", "type": "string"},
                    {"name": "eventTitle", "type": "string"},
                    {"name": "eventDate", "type": "string"},
                    {"name": "completionDate", "type": "string"}
                ],
                "name": "issueCertificate",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{"name": "certificateHash", "type": "string"}],
                "name": "verifyCertificate",
                "outputs": [
                    {"name": "isValid", "type": "bool"},
                    {"name": "participantName", "type": "string"},
                    {"name": "eventTitle", "type": "string"},
                    {"name": "eventDate", "type": "string"},
                    {"name": "completionDate", "type": "string"},
                    {"name": "issuedAt", "type": "uint256"}
                ],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [{"name": "", "type": "string"}],
                "name": "certificates",
                "outputs": [
                    {"name": "participantName", "type": "string"},
                    {"name": "eventTitle", "type": "string"},
                    {"name": "eventDate", "type": "string"},
                    {"name": "completionDate", "type": "string"},
                    {"name": "issuedAt", "type": "uint256"},
                    {"name": "isValid", "type": "bool"}
                ],
                "stateMutability": "view",
                "type": "function"
            }
        ]';
    }

    /**
     * Issue a certificate on the blockchain
     */
    public function issueCertificate(Certificate $certificate): array
    {
        try {
            Log::info('BlockchainCertificateService: Starting certificate issuance', [
                'certificate_id' => $certificate->id,
                'contract_address' => $this->contractAddress,
                'rpc_url' => $this->rpcUrl,
                'has_private_key' => !empty($this->privateKey)
            ]);

            // Check if required configuration is present
            if (empty($this->contractAddress)) {
                Log::error('BlockchainCertificateService: Missing contract address');
                return [
                    'success' => false,
                    'error' => 'Contract address not configured',
                ];
            }

            if (empty($this->privateKey)) {
                Log::error('BlockchainCertificateService: Missing private key');
                return [
                    'success' => false,
                    'error' => 'Private key not configured',
                ];
            }

            // Generate the certificate hash if not already generated
            if (!$certificate->certificate_hash) {
                $hash = $certificate->generateCertificateHash();
                $certificate->update(['certificate_hash' => $hash]);
                Log::info('BlockchainCertificateService: Generated certificate hash', ['hash' => $hash]);
            }

            // For now, simulate successful blockchain issuance without actual transaction
            // This allows testing the UI while we set up proper Web3 integration
            $mockTxHash = '0x' . bin2hex(random_bytes(32));

            Log::info('BlockchainCertificateService: Simulating blockchain issuance', [
                'mock_tx_hash' => $mockTxHash,
                'certificate_hash' => $certificate->certificate_hash
            ]);

            // Update certificate with simulated blockchain information
            $certificate->update([
                'blockchain_tx_hash' => $mockTxHash,
                'blockchain_address' => $this->contractAddress,
                'is_blockchain_verified' => true,
                'blockchain_issued_at' => now(),
            ]);

            Log::info('BlockchainCertificateService: Certificate updated with blockchain info');

            return [
                'success' => true,
                'tx_hash' => $mockTxHash,
                'certificate_hash' => $certificate->certificate_hash,
            ];
        } catch (Exception $e) {
            Log::error('Blockchain certificate issuance failed: ' . $e->getMessage(), [
                'certificate_id' => $certificate->id ?? 'unknown',
                'exception' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a certificate on the blockchain
     */
    public function verifyCertificate(string $certificateHash): array
    {
        try {
            $functionData = $this->encodeFunctionCall('verifyCertificate', [$certificateHash]);

            $response = Http::post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_call',
                'params' => [
                    [
                        'to' => $this->contractAddress,
                        'data' => $functionData,
                    ],
                    'latest'
                ],
                'id' => 1,
            ]);

            Log::info('BlockchainCertificateService: Verifying certificate', [
                'certificate_hash' => $certificateHash
            ]);

            // For now, check if we have the certificate in our local database
            // and simulate blockchain verification
            $certificate = \App\Models\Certificate::where('certificate_hash', $certificateHash)
                ->where('is_blockchain_verified', true)
                ->first();

            if ($certificate) {
                Log::info('BlockchainCertificateService: Certificate found and verified', [
                    'certificate_id' => $certificate->id
                ]);

                return [
                    'success' => true,
                    'is_valid' => true,
                    'participant_name' => $certificate->participant_name,
                    'event_title' => $certificate->event_title,
                    'event_date' => $certificate->event_date->format('Y-m-d'),
                    'completion_date' => $certificate->completion_date->format('Y-m-d'),
                    'issued_at' => $certificate->blockchain_issued_at ? $certificate->blockchain_issued_at->timestamp : time(),
                ];
            }

            Log::info('BlockchainCertificateService: Certificate not found or not verified');

            return [
                'success' => true,
                'is_valid' => false,
                'participant_name' => '',
                'event_title' => '',
                'event_date' => '',
                'completion_date' => '',
                'issued_at' => 0,
            ];
        } catch (Exception $e) {
            Log::error('Certificate verification failed: ' . $e->getMessage(), [
                'certificate_hash' => $certificateHash,
                'exception' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Encode function data for smart contract call
     */
    private function encodeFunctionData(string $functionName, array $params): string
    {
        // This is a simplified implementation
        // In production, use proper ABI encoding libraries
        $functionSignature = '';

        switch ($functionName) {
            case 'issueCertificate':
                $functionSignature = '0x' . substr(hash('sha3-256', 'issueCertificate(string,string,string,string,string)'), 0, 8);
                break;
            case 'verifyCertificate':
                $functionSignature = '0x' . substr(hash('sha3-256', 'verifyCertificate(string)'), 0, 8);
                break;
        }

        // Simplified parameter encoding - in production use proper ABI encoding
        $encodedParams = '';
        foreach ($params as $param) {
            $encodedParams .= bin2hex(str_pad($param, 32, "\0", STR_PAD_RIGHT));
        }

        return $functionSignature . $encodedParams;
    }

    /**
     * Encode function call for eth_call
     */
    private function encodeFunctionCall(string $functionName, array $params): string
    {
        return $this->encodeFunctionData($functionName, $params);
    }

    /**
     * Send transaction to blockchain
     */
    private function sendTransaction(string $data): array
    {
        try {
            // Get nonce
            $nonceResponse = Http::post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_getTransactionCount',
                'params' => [$this->getAddressFromPrivateKey(), 'latest'],
                'id' => 1,
            ]);

            if (!$nonceResponse->successful()) {
                return ['success' => false, 'error' => 'Failed to get nonce'];
            }

            $nonce = hexdec($nonceResponse->json()['result']);

            // Prepare transaction
            $transaction = [
                'nonce' => '0x' . dechex($nonce),
                'gasPrice' => '0x' . dechex(20000000000), // 20 Gwei
                'gasLimit' => '0x' . dechex(200000),
                'to' => $this->contractAddress,
                'value' => '0x0',
                'data' => $data,
            ];

            // Sign transaction (simplified - use proper signing in production)
            $signedTx = $this->signTransaction($transaction);

            // Send transaction
            $response = Http::post($this->rpcUrl, [
                'jsonrpc' => '2.0',
                'method' => 'eth_sendRawTransaction',
                'params' => [$signedTx],
                'id' => 1,
            ]);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['result'])) {
                    return [
                        'success' => true,
                        'tx_hash' => $result['result'],
                    ];
                }
            }

            return ['success' => false, 'error' => 'Transaction broadcast failed'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Sign transaction (simplified implementation)
     */
    private function signTransaction(array $transaction): string
    {
        // This is a placeholder - implement proper transaction signing
        // Use libraries like web3p/ethereum-tx for production
        return '0x' . bin2hex(json_encode($transaction));
    }

    /**
     * Get address from private key
     */
    private function getAddressFromPrivateKey(): string
    {
        // Simplified implementation - use proper key derivation in production
        return config('blockchain.wallet_address');
    }

    /**
     * Decode blockchain result
     */
    private function decodeResult(string $result): array
    {
        // Simplified decoding - implement proper ABI decoding in production
        return [
            'is_valid' => true,
            'participant_name' => '',
            'event_title' => '',
            'event_date' => '',
            'completion_date' => '',
            'issued_at' => time(),
        ];
    }
}
