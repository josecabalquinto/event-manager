<template>
    <AppLayout title="Certificate Details">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <Link 
                        :href="route('certificates.index')"
                        class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Certificates
                    </Link>
                </div>

                <!-- Certificate Card -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <!-- Certificate Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-8">
                        <div class="flex items-center justify-between text-white">
                            <div>
                                <h1 class="text-3xl font-bold mb-2">Certificate of Completion</h1>
                                <p class="text-blue-100">{{ certificate.certificate_number }}</p>
                            </div>
                            <div class="text-center">
                                <div 
                                    class="inline-flex px-4 py-2 rounded-full font-semibold"
                                    :class="certificate.is_generated ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                >
                                    {{ certificate.is_generated ? 'Generated' : 'Pending Generation' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Body -->
                    <div class="p-8">
                        <!-- Certificate Content -->
                        <div class="border-4 border-gray-200 rounded-lg p-8 mb-8 bg-gradient-to-br from-blue-50 to-purple-50">
                            <div class="text-center">
                                <div class="mb-6">
                                    <svg class="w-16 h-16 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">This certifies that</h2>
                                <h3 class="text-3xl font-bold text-blue-600 mb-6">{{ certificate.participant_name }}</h3>
                                <p class="text-lg text-gray-700 mb-6">has successfully completed</p>
                                <h4 class="text-2xl font-bold text-gray-800 mb-6">{{ certificate.event_title }}</h4>
                                
                                <div class="flex justify-center space-x-8 text-sm text-gray-600">
                                    <div>
                                        <p class="font-semibold text-gray-800">Event Date</p>
                                        <p>{{ formatDate(certificate.event_date) }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Completion Date</p>
                                        <p>{{ formatDate(certificate.completion_date) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Details</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Event Title</p>
                                    <p class="font-medium text-gray-800">{{ certificate.event.title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Location</p>
                                    <p class="font-medium text-gray-800">{{ certificate.event.location }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Certificate Number</p>
                                    <p class="font-medium text-gray-800">{{ certificate.certificate_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Issued Date</p>
                                    <p class="font-medium text-gray-800">{{ formatDate(certificate.completion_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Blockchain Verification -->
                        <div v-if="certificate.certificate_hash" class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-6 mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Blockchain Verification
                                </h3>
                                <div class="flex items-center">
                                    <span v-if="certificate.is_blockchain_verified" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Verified on Polygon Amoy
                                    </span>
                                    <span v-else class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pending Blockchain Verification
                                    </span>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 text-sm mb-4">
                                <div>
                                    <p class="text-gray-600 mb-1">Certificate Hash:</p>
                                    <p class="font-mono text-gray-800 break-all text-xs">{{ certificate.certificate_hash }}</p>
                                </div>
                                <div v-if="certificate.blockchain_tx_hash">
                                    <p class="text-gray-600 mb-1">Transaction Hash:</p>
                                    <p class="font-mono text-gray-800 break-all text-xs">{{ certificate.blockchain_tx_hash }}</p>
                                </div>
                                <div v-if="certificate.blockchain_issued_at">
                                    <p class="text-gray-600 mb-1">Blockchain Issued:</p>
                                    <p class="text-gray-800">{{ new Date(certificate.blockchain_issued_at).toLocaleString() }}</p>
                                </div>
                            </div>

                            <!-- Public Verification -->
                            <div class="bg-white rounded-lg p-4 border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-1">Public Verification</h4>
                                        <p class="text-sm text-gray-600">Anyone can verify this certificate's authenticity on the blockchain</p>
                                    </div>
                                    <div class="flex space-x-3">
                                        <Link 
                                            :href="route('certificates.verify', { hash: certificate.certificate_hash })"
                                            class="bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors"
                                        >
                                            🔗 Verify Public
                                        </Link>
                                        <button 
                                            @click="copyVerificationUrl"
                                            class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors"
                                        >
                                            Copy Link
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <Link 
                                v-if="certificate.is_generated && certificate.certificate_url"
                                :href="route('certificates.download', certificate.id)"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md text-center transition-colors duration-200 flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Certificate
                            </Link>
                            <div 
                                v-else
                                class="flex-1 bg-gray-300 text-gray-500 font-medium py-3 px-6 rounded-md text-center cursor-not-allowed flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Certificate Being Generated
                            </div>
                            
                            <Link 
                                :href="route('my-events.index')"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-md text-center transition-colors duration-200 flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                View My Events
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    certificate: {
        type: Object,
        required: true
    }
})

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

function copyVerificationUrl() {
    if (props.certificate.certificate_hash) {
        const verificationUrl = route('certificates.verify', { hash: props.certificate.certificate_hash })
        const fullUrl = `${window.location.origin}${verificationUrl}`
        
        navigator.clipboard.writeText(fullUrl).then(() => {
            alert('Verification URL copied to clipboard!')
        }).catch(() => {
            alert('Failed to copy URL. Please copy manually: ' + fullUrl)
        })
    }
}
</script>