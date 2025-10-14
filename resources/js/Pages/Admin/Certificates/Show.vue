<template>
    <AdminLayout title="Certificate Details">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Certificate Details</h2>
                                <p class="text-gray-600">{{ certificate.certificate_number }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <Link 
                                    :href="route('admin.certificates.index')"
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200"
                                >
                                    Back to List
                                </Link>
                                <button 
                                    v-if="!certificate.is_generated"
                                    @click="markAsGenerated"
                                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200"
                                >
                                    Mark as Generated
                                </button>
                                <a 
                                    v-if="certificate.is_generated"
                                    :href="route('admin.certificates.download', certificate.id)"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200"
                                >
                                    Download Certificate
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Information -->
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- Certificate Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Certificate Information</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Certificate Number</label>
                                    <p class="text-gray-900 font-mono">{{ certificate.certificate_number }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Participant Name</label>
                                    <p class="text-gray-900">{{ certificate.participant_name }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Event Title</label>
                                    <p class="text-gray-900">{{ certificate.event_title }}</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Event Date</label>
                                        <p class="text-gray-900">{{ formatDate(certificate.event_date) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Completion Date</label>
                                        <p class="text-gray-900">{{ formatDate(certificate.completion_date) }}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Generation Status</label>
                                    <div class="flex items-center space-x-2">
                                        <span 
                                            v-if="certificate.is_generated"
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
                                        >
                                            Generated
                                        </span>
                                        <span 
                                            v-else
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"
                                        >
                                            Pending
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Participant Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Participant Information</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Full Name</label>
                                    <p class="text-gray-900">{{ certificate.user.name }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Email Address</label>
                                    <p class="text-gray-900">{{ certificate.user.email }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Event Location</label>
                                    <p class="text-gray-900">{{ certificate.event.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Preview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Certificate Preview</h3>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8">
                            <div class="max-w-2xl mx-auto bg-white border-4 border-gray-800 rounded-lg p-8 shadow-lg">
                                <!-- Certificate Header -->
                                <div class="text-center mb-8">
                                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Certificate of Completion</h1>
                                    <div class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-yellow-600 mx-auto"></div>
                                </div>

                                <!-- Certificate Body -->
                                <div class="text-center space-y-6">
                                    <p class="text-lg text-gray-600">This is to certify that</p>
                                    
                                    <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-gray-300 pb-2 inline-block">
                                        {{ certificate.participant_name }}
                                    </h2>
                                    
                                    <p class="text-lg text-gray-600">has successfully completed</p>
                                    
                                    <h3 class="text-xl font-semibold text-gray-800">{{ certificate.event_title }}</h3>
                                    
                                    <p class="text-gray-600">
                                        on {{ formatDate(certificate.completion_date) }}
                                    </p>
                                </div>

                                <!-- Certificate Footer -->
                                <div class="mt-12">
                                    <!-- Certificate Number -->
                                    <div class="text-center mb-6">
                                        <p class="text-xs text-gray-500">Certificate #{{ certificate.certificate_number }}</p>
                                    </div>
                                    
                                    <!-- Dynamic Signatories -->
                                    <div v-if="certificate.event && certificate.event.certificate_signatories && certificate.event.certificate_signatories.length > 0" 
                                         :class="[
                                             'flex items-end',
                                             certificate.event.certificate_signatories.length === 1 ? 'justify-center' : 
                                             certificate.event.certificate_signatories.length === 2 ? 'justify-between' : 'justify-around'
                                         ]">
                                        <div v-for="(signatory, index) in certificate.event.certificate_signatories" :key="index" class="text-center">
                                            <div class="w-32 border-b border-gray-400 mb-2"></div>
                                            <p class="text-sm text-gray-800 font-semibold">{{ signatory.name || 'Signatory' }}</p>
                                            <p class="text-xs text-gray-600">{{ signatory.title || 'Position' }}</p>
                                        </div>
                                    </div>
                                    <!-- Fallback for no signatories -->
                                    <div v-else class="flex justify-center">
                                        <div class="text-center">
                                            <div class="w-32 border-b border-gray-400 mb-2"></div>
                                            <p class="text-sm text-gray-800 font-semibold">Authorized Signatory</p>
                                            <p class="text-xs text-gray-600">Position</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-500">This is a preview of the certificate. The actual certificate may have different styling.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

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

function markAsGenerated() {
    if (confirm('Mark this certificate as generated?')) {
        router.patch(route('admin.certificates.mark-generated', props.certificate.id))
    }
}
</script>