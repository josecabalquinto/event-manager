<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Polygon Amoy Testnet Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for blockchain certificate issuance on Polygon Amoy testnet
    |
    */

    'amoy_rpc_url' => env('AMOY_RPC_URL', 'https://rpc-amoy.polygon.technology/'),

    'contract_address' => env('BLOCKCHAIN_CONTRACT_ADDRESS'),

    'private_key' => env('BLOCKCHAIN_PRIVATE_KEY'),

    'wallet_address' => env('BLOCKCHAIN_WALLET_ADDRESS'),

    'chain_id' => env('BLOCKCHAIN_CHAIN_ID', 80002), // Amoy testnet chain ID

    'gas_price' => env('BLOCKCHAIN_GAS_PRICE', 20000000000), // 20 Gwei

    'gas_limit' => env('BLOCKCHAIN_GAS_LIMIT', 200000),

    'verification_url' => env('APP_URL') . '/certificates/verify',
];
