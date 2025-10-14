<template>
    <AdminLayout title="Certificate Management">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Certificate Management</h2>
                                <p class="text-gray-600">Manage and generate certificates for event participants</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <Link 
                                    :href="route('admin.certificates.create')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200"
                                >
                                    Create Certificates
                                </Link>
                                <div class="bg-blue-50 px-4 py-2 rounded-lg">
                                    <p class="text-blue-800 font-semibold">{{ certificates.total }} Total Certificate{{ certificates.total !== 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificates Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div v-if="certificates.data.length > 0" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Certificate
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Participant
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Event
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dates
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="certificate in certificates.data" :key="certificate.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ certificate.certificate_number }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ certificate.participant_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ certificate.user.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ certificate.user.email }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ certificate.event_title }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ certificate.event.title }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>
                                            <div>Event: {{ formatDate(certificate.event_date) }}</div>
                                            <div class="text-gray-500">Completed: {{ formatDate(certificate.completion_date) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span 
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                            :class="certificate.is_generated ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                        >
                                            {{ certificate.is_generated ? 'Generated' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <Link 
                                                :href="route('admin.certificates.show', certificate.id)"
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                View
                                            </Link>
                                            <Link 
                                                v-if="!certificate.is_generated"
                                                :href="route('admin.certificates.mark-generated', certificate.id)"
                                                method="patch"
                                                as="button"
                                                class="text-green-600 hover:text-green-900"
                                            >
                                                Mark Generated
                                            </Link>
                                            <Link 
                                                :href="route('admin.certificates.destroy', certificate.id)"
                                                method="delete"
                                                as="button"
                                                class="text-red-600 hover:text-red-900"
                                                @click="confirm('Are you sure you want to delete this certificate?')"
                                            >
                                                Delete
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Empty State -->
                    <div v-else class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">No Certificates Found</h3>
                        <p class="text-gray-500 mb-6">Certificates will appear here after events are completed and certificates are generated.</p>
                        <Link 
                            :href="route('admin.events.index')" 
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Manage Events
                        </Link>
                    </div>

                    <!-- Pagination -->
                    <div v-if="certificates.data.length > 0" class="px-6 py-4 bg-gray-50 border-t">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-700">
                                Showing {{ certificates.from }} to {{ certificates.to }} of {{ certificates.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <Link 
                                    v-for="link in certificates.links" 
                                    :key="link.label"
                                    :href="link.url"
                                    :class="[
                                        'px-3 py-2 text-sm rounded-md',
                                        link.active 
                                            ? 'bg-blue-600 text-white' 
                                            : link.url 
                                                ? 'bg-white text-gray-700 hover:bg-gray-50 border' 
                                                : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                    ]"
                                >
                                    <span v-html="link.label"></span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
    certificates: {
        type: Object,
        required: true
    }
})

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

function confirm(message) {
    return window.confirm(message)
}
</script>