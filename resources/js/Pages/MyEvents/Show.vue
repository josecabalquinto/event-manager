<template>
    <AppLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">{{ registration.event.title }}</h2>
                <div class="mb-6">
                    <p class="text-gray-600">{{ registration.event.description }}</p>
                    <div class="mt-4 space-y-2">
                        <p><span class="font-semibold">Date:</span> {{ registration.event.event_date }} at {{ registration.event.event_time }}</p>
                        <p><span class="font-semibold">Location:</span> {{ registration.event.location }}</p>
                        <p><span class="font-semibold">Registration Status:</span> 
                            <span :class="getStatusTextClass(registration.status)">
                                {{ getStatusText(registration.status) }}
                            </span>
                        </p>
                        <p v-if="registration.status === 'approved'">
                            <span class="font-semibold">Check-in Status:</span> 
                            <span :class="registration.is_checked_in ? 'text-green-600' : 'text-yellow-600'">
                                {{ registration.is_checked_in ? 'Checked In' : 'Not Checked In' }}
                            </span>
                        </p>
                        <p v-if="registration.has_certificate"><span class="font-semibold">Certificate:</span> 
                            <span class="text-blue-600">{{ registration.certificate.certificate_number }}</span>
                        </p>
                    </div>
                </div>
                <!-- Certificate Section -->
                <div v-if="registration.has_certificate" class="border-t pt-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4 text-center flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Your Certificate
                    </h3>
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <p class="text-lg font-semibold text-gray-800 mb-2">Certificate of Completion</p>
                            <p class="text-blue-600 font-medium">{{ registration.certificate.certificate_number }}</p>
                            <p class="text-sm text-gray-600 mt-2">Completed on {{ formatDate(registration.certificate.completion_date) }}</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <Link :href="route('certificates.show', registration.certificate.id)" 
                                  class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                View Certificate
                            </Link>
                            <Link v-if="registration.certificate.is_generated && registration.certificate.certificate_url"
                                  :href="route('certificates.download', registration.certificate.id)" 
                                  class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                Download PDF
                            </Link>
                            <span v-else class="bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-md cursor-not-allowed">
                                PDF Not Ready
                            </span>
                        </div>
                    </div>
                </div>

                <!-- QR Code Section - Only show for approved registrations -->
                <div v-if="registration.status === 'approved'" class="border-t pt-6">
                    <h3 class="text-xl font-semibold mb-4 text-center">Your QR Code</h3>
                    <div class="flex flex-col items-center">
                        <div class="bg-white p-6 rounded-2xl shadow-lg border-2 border-indigo-200">
                            <QRCodeVue3 :value="registration.qr_code" :size="300" />
                        </div>
                        <div class="mt-6 text-center max-w-md">
                            <p class="text-sm text-gray-600 mb-3">
                                Present this QR code at the event for check-in
                            </p>
                            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Your QR Code (UUID):</p>
                                <div class="flex items-center justify-center space-x-2">
                                    <code class="text-xs bg-white px-3 py-2 rounded border border-indigo-300 text-indigo-700 font-mono break-all">
                                        {{ registration.qr_code }}
                                    </code>
                                    <button @click="copyToClipboard(registration.qr_code)" 
                                            class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors flex-shrink-0"
                                            title="Copy to clipboard">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    💡 Admin can also manually enter this code for check-in
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status Messages -->
                <div v-else-if="registration.status === 'pending'" class="border-t pt-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                        <div class="flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Registration Pending</h3>
                        <p class="text-yellow-700">Your registration is awaiting admin approval. You'll be notified once it's approved and your QR code will be available.</p>
                    </div>
                </div>
                
                <div v-else-if="registration.status === 'rejected'" class="border-t pt-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <div class="flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Registration Rejected</h3>
                        <p class="text-red-700">Unfortunately, your registration for this event has been rejected. Please contact the event organizer for more information.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import QRCodeVue3 from 'qrcode.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    registration: Object,
});

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

const getStatusText = (status) => {
    switch (status) {
        case 'pending':
            return 'Pending Approval';
        case 'approved':
            return 'Approved';
        case 'rejected':
            return 'Rejected';
        default:
            return 'Unknown';
    }
};

const getStatusTextClass = (status) => {
    switch (status) {
        case 'pending':
            return 'text-yellow-600';
        case 'approved':
            return 'text-green-600';
        case 'rejected':
            return 'text-red-600';
        default:
            return 'text-gray-600';
    }
};

const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        alert('QR Code copied to clipboard!');
    } catch (err) {
        console.error('Failed to copy:', err);
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            alert('QR Code copied to clipboard!');
        } catch (err) {
            alert('Failed to copy to clipboard');
        }
        document.body.removeChild(textArea);
    }
};
</script>
