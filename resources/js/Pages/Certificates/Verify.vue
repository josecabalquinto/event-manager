<template>
    <AppLayout>
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-yellow-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12">
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 p-3 rounded-xl shadow-md mx-auto w-16 h-16 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-500 bg-clip-text text-transparent mb-4">
                        Certificate Verification
                    </h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Verify the authenticity of certificates using blockchain technology. 
                        Enter a certificate hash or search by participant name.
                    </p>
                </div>

                <!-- Search Section -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8" v-if="!hash">
                    <div class="max-w-2xl mx-auto">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Search Certificate</h2>
                        
                        <!-- Hash Input -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Hash</label>
                            <div class="flex gap-3">
                                <input 
                                    v-model="searchHash"
                                    type="text" 
                                    placeholder="Enter certificate hash..."
                                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                                />
                                <button 
                                    @click="verifyHash"
                                    :disabled="!searchHash || loading"
                                    class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    <span v-if="loading">Verifying...</span>
                                    <span v-else>Verify</span>
                                </button>
                            </div>
                        </div>

                        <div class="text-center text-gray-500 mb-6">or</div>

                        <!-- Search Input -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search by Name or Certificate Number</label>
                            <div class="flex gap-3">
                                <input 
                                    v-model="searchQuery"
                                    type="text" 
                                    placeholder="Enter participant name or certificate number..."
                                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                                    @keyup.enter="searchCertificates"
                                />
                                <button 
                                    @click="searchCertificates"
                                    :disabled="!searchQuery || searching"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    <span v-if="searching">Searching...</span>
                                    <span v-else>Search</span>
                                </button>
                            </div>
                        </div>

                        <!-- Search Results -->
                        <div v-if="searchResults.length > 0" class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800">Search Results</h3>
                            <div class="grid gap-4">
                                <div 
                                    v-for="result in searchResults" 
                                    :key="result.certificate_hash"
                                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 cursor-pointer transition-colors"
                                    @click="selectCertificate(result.certificate_hash)">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-800">{{ result.participant_name }}</h4>
                                            <p class="text-gray-600">{{ result.event_title }}</p>
                                            <p class="text-sm text-gray-500">Certificate #{{ result.certificate_number }}</p>
                                            <p class="text-sm text-gray-500">Completed: {{ result.completion_date }}</p>
                                        </div>
                                        <button class="text-yellow-600 hover:text-yellow-700 font-medium text-sm">
                                            Verify →
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Results -->
                <div v-if="verification_result" class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="text-center mb-8">
                        <!-- Status Icon -->
                        <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-4"
                             :class="verification_result.is_authentic ? 'bg-green-100' : 'bg-red-100'">
                            <svg v-if="verification_result.is_authentic" class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg v-else class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>

                        <h2 class="text-3xl font-bold mb-2" 
                            :class="verification_result.is_authentic ? 'text-green-600' : 'text-red-600'">
                            {{ verification_result.is_authentic ? 'Certificate Verified' : 'Verification Failed' }}
                        </h2>
                        
                        <p class="text-gray-600">
                            {{ verification_result.is_authentic 
                                ? 'This certificate is authentic and verified on the blockchain.' 
                                : 'This certificate could not be verified or does not exist.' }}
                        </p>
                    </div>

                    <!-- Certificate Details -->
                    <div v-if="certificate && verification_result.is_authentic" 
                         class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Certificate Details</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <div class="space-y-3">
                                    <div>
                                        <span class="font-medium text-gray-700">Participant:</span>
                                        <p class="text-gray-800">{{ certificate.participant_name }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Event:</span>
                                        <p class="text-gray-800">{{ certificate.event_title }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Certificate Number:</span>
                                        <p class="text-gray-800">{{ certificate.certificate_number }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="space-y-3">
                                    <div>
                                        <span class="font-medium text-gray-700">Event Date:</span>
                                        <p class="text-gray-800">{{ certificate.event_date }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Completion Date:</span>
                                        <p class="text-gray-800">{{ certificate.completion_date }}</p>
                                    </div>
                                    <div v-if="certificate.blockchain_issued_at">
                                        <span class="font-medium text-gray-700">Blockchain Issued:</span>
                                        <p class="text-gray-800">{{ new Date(certificate.blockchain_issued_at).toLocaleString() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Status -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Database Verification</h4>
                            <div class="flex items-center">
                                <span :class="verification_result.local_exists ? 'text-green-600' : 'text-red-600'">
                                    {{ verification_result.local_exists ? '✓ Found in database' : '✗ Not found in database' }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Blockchain Verification</h4>
                            <div class="flex items-center">
                                <span :class="verification_result.blockchain_exists ? 'text-green-600' : 'text-red-600'">
                                    {{ verification_result.blockchain_exists ? '✓ Verified on blockchain' : '✗ Not found on blockchain' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Blockchain Details -->
                    <div v-if="certificate && certificate.blockchain_tx_hash" class="bg-blue-50 rounded-lg p-4 mb-8">
                        <h4 class="font-semibold text-gray-800 mb-2">Blockchain Information</h4>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="font-medium">Transaction Hash:</span>
                                <p class="font-mono text-gray-600 break-all">{{ certificate.blockchain_tx_hash }}</p>
                            </div>
                            <div>
                                <span class="font-medium">Certificate Hash:</span>
                                <p class="font-mono text-gray-600 break-all">{{ hash }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="text-center">
                        <button 
                            @click="reset"
                            class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Verify Another Certificate
                        </button>
                    </div>
                </div>

                <!-- Error Display -->
                <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-red-800">{{ error }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import axios from 'axios'

const props = defineProps({
    hash: String,
    certificate: Object,
    blockchain_data: Object,
    verification_result: Object,
})

const searchHash = ref('')
const searchQuery = ref('')
const searchResults = ref([])
const loading = ref(false)
const searching = ref(false)
const error = ref('')

onMounted(() => {
    if (props.hash) {
        searchHash.value = props.hash
    }
})

const verifyHash = () => {
    if (searchHash.value) {
        router.visit(route('certificates.verify', { hash: searchHash.value }))
    }
}

const searchCertificates = async () => {
    if (!searchQuery.value) return
    
    searching.value = true
    error.value = ''
    
    try {
        const response = await axios.post(route('certificates.search'), {
            query: searchQuery.value
        })
        
        searchResults.value = response.data.certificates
    } catch (err) {
        error.value = 'Search failed. Please try again.'
        console.error(err)
    } finally {
        searching.value = false
    }
}

const selectCertificate = (certificateHash) => {
    router.visit(route('certificates.verify', { hash: certificateHash }))
}

const reset = () => {
    router.visit(route('certificates.verify'))
}
</script>