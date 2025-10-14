<template>
    <AppLayout title="My Certificates">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">My Certificates</h2>
                                <p class="text-gray-600">View and download your event completion certificates</p>
                            </div>
                            <div class="bg-blue-50 px-4 py-2 rounded-lg">
                                <p class="text-blue-800 font-semibold">{{ certificates.length }} Certificate{{ certificates.length !== 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificates Grid -->
                <div v-if="certificates.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div 
                        v-for="certificate in certificates" 
                        :key="certificate.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-200 border-l-4 border-blue-500"
                    >
                        <div class="p-6">
                            <!-- Certificate Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <span 
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="certificate.is_generated ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                    >
                                        {{ certificate.is_generated ? 'Generated' : 'Pending' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Certificate Info -->
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ certificate.event_title }}</h3>
                                <p class="text-sm text-gray-600 mb-1">Certificate #: {{ certificate.certificate_number }}</p>
                                <p class="text-sm text-gray-600 mb-1">Participant: {{ certificate.participant_name }}</p>
                                <p class="text-sm text-gray-600 mb-1">Event Date: {{ formatDate(certificate.event_date) }}</p>
                                <p class="text-sm text-gray-600 mb-2">Completed: {{ formatDate(certificate.completion_date) }}</p>
                                
                                <!-- Blockchain Status -->
                                <div class="flex items-center mb-2">
                                    <svg v-if="certificate.is_blockchain_verified" class="w-4 h-4 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <svg v-else class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs" :class="certificate.is_blockchain_verified ? 'text-green-600 font-medium' : 'text-gray-500'">
                                        {{ certificate.is_blockchain_verified ? 'Blockchain Verified' : 'Not on Blockchain' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="space-y-2">
                                <div class="flex space-x-2">
                                    <Link 
                                        :href="route('certificates.show', certificate.id)"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md text-center transition-colors duration-200"
                                    >
                                        View Details
                                    </Link>
                                    <Link 
                                        v-if="certificate.is_generated && certificate.certificate_url"
                                        :href="route('certificates.download', certificate.id)"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-md text-center transition-colors duration-200"
                                    >
                                        Download
                                    </Link>
                                    <span 
                                        v-else
                                        class="flex-1 bg-gray-300 text-gray-500 text-sm font-medium py-2 px-4 rounded-md text-center cursor-not-allowed"
                                    >
                                        Not Ready
                                    </span>
                                </div>
                                
                                <!-- Verify Link -->
                                <Link 
                                    v-if="certificate.certificate_hash"
                                    :href="route('certificates.verify', { hash: certificate.certificate_hash })"
                                    class="w-full bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium py-1.5 px-3 rounded-md text-center transition-colors duration-200 block"
                                >
                                    🔗 Verify on Blockchain
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">No Certificates Yet</h3>
                        <p class="text-gray-500 mb-6">Complete events to earn certificates that you can view and download here.</p>
                        <Link 
                            :href="route('events.index')" 
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Browse Events
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    certificates: {
        type: Array,
        default: () => []
    }
})

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}
</script>