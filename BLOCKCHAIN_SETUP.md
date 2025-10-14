# Blockchain Certificate Integration - Polygon Amoy Testnet

This document describes the blockchain integration for certificate verification on the Polygon Amoy testnet.

## Overview

The Event Manager application now integrates with Polygon Amoy testnet to issue and verify certificates on the blockchain. Each certificate is hashed and stored on-chain, allowing for tamper-proof verification.

## Features

- **Blockchain Issuance**: Certificates are automatically issued to the blockchain when generated
- **Public Verification**: Anyone can verify certificate authenticity using the certificate hash
- **QR Code Integration**: PDFs include QR codes linking to verification pages
- **Tamper Detection**: Compare local data with blockchain records for authenticity

## Smart Contract

### Contract Address

The smart contract is deployed at: `[TO_BE_DEPLOYED]`

### Contract Functions

- `issueCertificate()`: Issues a new certificate on-chain (admin only)
- `verifyCertificate()`: Publicly verifiable function to check certificate validity
- `revokeCertificate()`: Revoke a certificate (admin only)

### Contract ABI

The contract ABI is stored in `app/Services/BlockchainCertificateService.php`

## Setup Instructions

### 1. Environment Configuration

Add the following variables to your `.env` file:

```bash
# Blockchain Configuration
AMOY_RPC_URL=https://rpc-amoy.polygon.technology/
BLOCKCHAIN_CONTRACT_ADDRESS=your_deployed_contract_address
BLOCKCHAIN_PRIVATE_KEY=your_private_key_here
BLOCKCHAIN_WALLET_ADDRESS=your_wallet_address_here
BLOCKCHAIN_CHAIN_ID=80002
BLOCKCHAIN_GAS_PRICE=20000000000
BLOCKCHAIN_GAS_LIMIT=200000
```

### 2. Smart Contract Deployment

1. **Install Hardhat or Truffle** (for contract deployment)

```bash
npm install --save-dev hardhat
# or
npm install -g truffle
```

2. **Deploy the Contract**

```bash
# Using Hardhat
npx hardhat run scripts/deploy.js --network amoy

# Using Remix IDE (recommended for beginners)
# 1. Go to https://remix.ethereum.org/
# 2. Upload contracts/CertificateRegistry.sol
# 3. Compile and deploy to Amoy testnet
```

3. **Update Environment**

```bash
# Add the deployed contract address to .env
BLOCKCHAIN_CONTRACT_ADDRESS=0x1234567890abcdef...
```

### 3. Get Testnet MATIC

1. Visit the [Polygon Faucet](https://faucet.polygon.technology/)
2. Select "Amoy Testnet"
3. Enter your wallet address
4. Request testnet MATIC tokens

### 4. Configure MetaMask (Optional)

For frontend wallet integration:

1. **Add Amoy Network to MetaMask**:
   - Network Name: Polygon Amoy Testnet
   - RPC URL: https://rpc-amoy.polygon.technology/
   - Chain ID: 80002
   - Currency Symbol: MATIC
   - Block Explorer: https://www.oklink.com/amoy

## Usage

### Certificate Generation with Blockchain

1. **Admin generates certificates** via the admin panel
2. **System automatically**:
   - Generates certificate hash from participant data
   - Issues certificate to blockchain smart contract
   - Updates local database with transaction hash
   - Generates PDF with QR code for verification

### Certificate Verification

1. **Public verification** at `/certificates/verify`
2. **Search by**:
   - Certificate hash
   - Participant name
   - Certificate number
3. **Verification process**:
   - Checks local database
   - Verifies on blockchain
   - Compares data consistency
   - Shows verification status

### API Endpoints

- `GET /certificates/verify/{hash}` - Verification page
- `POST /certificates/verify` - API verification
- `POST /certificates/search` - Search certificates

## Certificate Hash Generation

Certificates are hashed using SHA-256 of the following data:

```json
{
  "certificate_number": "CERT-ABC12345-2025",
  "participant_name": "John Doe",
  "event_title": "Workshop Title",
  "event_date": "2025-01-15",
  "completion_date": "2025-01-15"
}
```

## Security Considerations

1. **Private Key Security**: Store private keys securely, never commit to version control
2. **Rate Limiting**: Implement rate limiting for verification endpoints
3. **Input Validation**: All blockchain inputs are validated
4. **Error Handling**: Graceful handling of blockchain connectivity issues

## Troubleshooting

### Common Issues

1. **Gas Estimation Failed**

   ```bash
   # Increase gas limit in config/blockchain.php
   'gas_limit' => 300000,
   ```

2. **RPC Connection Failed**

   ```bash
   # Try alternative RPC URLs
   AMOY_RPC_URL=https://polygon-amoy-bor-rpc.publicnode.com/
   ```

3. **Transaction Pending**
   ```bash
   # Check transaction on explorer
   # https://www.oklink.com/amoy/tx/{tx_hash}
   ```

## Development

### Testing

```bash
# Run tests
php artisan test

# Test specific blockchain functionality
php artisan test --filter=BlockchainCertificateTest
```

### Local Development

For local development without blockchain:

```bash
# Disable blockchain in .env
BLOCKCHAIN_CONTRACT_ADDRESS=
BLOCKCHAIN_PRIVATE_KEY=
```

The application will work without blockchain features enabled.

## Production Deployment

### Mainnet Considerations

For production deployment to Polygon mainnet:

1. **Update Configuration**:

   ```bash
   AMOY_RPC_URL=https://polygon-rpc.com/
   BLOCKCHAIN_CHAIN_ID=137
   ```

2. **Security Audits**: Audit smart contract before mainnet deployment
3. **Gas Optimization**: Optimize contract for lower gas costs
4. **Monitoring**: Set up monitoring for blockchain transactions

## Support

- **Polygon Documentation**: https://docs.polygon.technology/
- **Amoy Testnet Explorer**: https://www.oklink.com/amoy
- **Web3 Documentation**: https://web3js.readthedocs.io/
