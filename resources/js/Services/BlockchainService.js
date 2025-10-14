import Web3 from 'web3';

class BlockchainService {
    constructor() {
        this.web3 = null;
        this.contract = null;
        this.contractAddress = null;
        this.contractAbi = [
            {
                "inputs": [
                    { "name": "certificateHash", "type": "string" },
                    { "name": "participantName", "type": "string" },
                    { "name": "eventTitle", "type": "string" },
                    { "name": "eventDate", "type": "string" },
                    { "name": "completionDate", "type": "string" }
                ],
                "name": "issueCertificate",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{ "name": "certificateHash", "type": "string" }],
                "name": "verifyCertificate",
                "outputs": [
                    { "name": "isValid", "type": "bool" },
                    { "name": "participantName", "type": "string" },
                    { "name": "eventTitle", "type": "string" },
                    { "name": "eventDate", "type": "string" },
                    { "name": "completionDate", "type": "string" },
                    { "name": "issuedAt", "type": "uint256" }
                ],
                "stateMutability": "view",
                "type": "function"
            }
        ];

        this.init();
    }

    async init() {
        try {
            // Initialize Web3 with Amoy testnet RPC
            const rpcUrl = 'https://rpc-amoy.polygon.technology/';
            this.web3 = new Web3(new Web3.providers.HttpProvider(rpcUrl));

            // Set contract address (this would be set after deployment)
            this.contractAddress = import.meta.env.VITE_BLOCKCHAIN_CONTRACT_ADDRESS;

            if (this.contractAddress) {
                this.contract = new this.web3.eth.Contract(this.contractAbi, this.contractAddress);
            }
        } catch (error) {
            console.error('Failed to initialize blockchain service:', error);
        }
    }

    async verifyCertificate(certificateHash) {
        try {
            if (!this.contract) {
                throw new Error('Contract not initialized');
            }

            const result = await this.contract.methods.verifyCertificate(certificateHash).call();

            return {
                success: true,
                isValid: result.isValid,
                participantName: result.participantName,
                eventTitle: result.eventTitle,
                eventDate: result.eventDate,
                completionDate: result.completionDate,
                issuedAt: new Date(parseInt(result.issuedAt) * 1000)
            };
        } catch (error) {
            console.error('Certificate verification failed:', error);
            return {
                success: false,
                error: error.message
            };
        }
    }

    async connectWallet() {
        try {
            if (typeof window.ethereum !== 'undefined') {
                // Request account access
                await window.ethereum.request({ method: 'eth_requestAccounts' });

                // Initialize Web3 with MetaMask provider
                this.web3 = new Web3(window.ethereum);

                // Check if we're on the correct network (Amoy testnet)
                const chainId = await this.web3.eth.getChainId();
                const targetChainId = 80002; // Amoy testnet chain ID

                if (chainId !== targetChainId) {
                    await this.switchToAmoyNetwork();
                }

                return {
                    success: true,
                    account: (await this.web3.eth.getAccounts())[0]
                };
            } else {
                throw new Error('MetaMask is not installed');
            }
        } catch (error) {
            return {
                success: false,
                error: error.message
            };
        }
    }

    async switchToAmoyNetwork() {
        try {
            await window.ethereum.request({
                method: 'wallet_switchEthereumChain',
                params: [{ chainId: '0x13882' }], // 80002 in hex
            });
        } catch (switchError) {
            // If network doesn't exist, add it
            if (switchError.code === 4902) {
                await window.ethereum.request({
                    method: 'wallet_addEthereumChain',
                    params: [{
                        chainId: '0x13882',
                        chainName: 'Polygon Amoy Testnet',
                        nativeCurrency: {
                            name: 'MATIC',
                            symbol: 'MATIC',
                            decimals: 18
                        },
                        rpcUrls: ['https://rpc-amoy.polygon.technology/'],
                        blockExplorerUrls: ['https://www.oklink.com/amoy']
                    }]
                });
            } else {
                throw switchError;
            }
        }
    }

    generateQRCodeData(certificateHash) {
        const verificationUrl = `${window.location.origin}/certificates/verify/${certificateHash}`;
        return verificationUrl;
    }

    isWalletConnected() {
        return typeof window.ethereum !== 'undefined' && window.ethereum.isConnected();
    }
}

export default new BlockchainService();