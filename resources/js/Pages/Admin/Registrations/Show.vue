<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Registration Details</h2>
                    <Link :href="route('admin.registrations.index')"
                          class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Back to List
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Registration Status -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span :class="getStatusClass(registration.status)" 
                                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ registration.status.charAt(0).toUpperCase() + registration.status.slice(1) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">QR Code:</span>
                                <span class="font-mono text-sm">{{ registration.qr_code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Registered At:</span>
                                <span>{{ registration.created_at }}</span>
                            </div>
                            <div v-if="registration.approved_at" class="flex justify-between">
                                <span class="text-gray-600">{{ registration.status === 'approved' ? 'Approved' : 'Rejected' }} At:</span>
                                <span>{{ registration.approved_at }}</span>
                            </div>
                            <div v-if="registration.approved_by" class="flex justify-between">
                                <span class="text-gray-600">{{ registration.status === 'approved' ? 'Approved' : 'Rejected' }} By:</span>
                                <span>{{ registration.approved_by.name }}</span>
                            </div>
                            <div v-if="registration.rejection_reason" class="pt-2 border-t">
                                <span class="text-gray-600 block mb-2">Rejection Reason:</span>
                                <p class="text-gray-800 bg-red-50 p-2 rounded">{{ registration.rejection_reason }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Participant Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Participant Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">{{ registration.registrant.name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span>{{ registration.registrant.email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Type:</span>
                                <span>{{ registration.registrant.is_guest ? 'Guest Registration' : 'Registered User' }}</span>
                            </div>
                            <div v-if="registration.registrant.student_id" class="flex justify-between">
                                <span class="text-gray-600">Student ID:</span>
                                <span class="font-mono">{{ registration.registrant.student_id }}</span>
                            </div>
                            <div v-if="registration.registrant.course" class="flex justify-between">
                                <span class="text-gray-600">Course:</span>
                                <span>{{ registration.registrant.course }}</span>
                            </div>
                            <div v-if="registration.registrant.year_level" class="flex justify-between">
                                <span class="text-gray-600">Year Level:</span>
                                <span>{{ registration.registrant.year_level }}</span>
                            </div>
                            <div v-if="registration.registrant.section" class="flex justify-between">
                                <span class="text-gray-600">Section:</span>
                                <span>{{ registration.registrant.section }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Event Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Title:</span>
                                <span class="font-medium">{{ registration.event.title }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span>{{ registration.event.event_date }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Time:</span>
                                <span>{{ registration.event.event_time }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Location:</span>
                                <span>{{ registration.event.location }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance & Certificate Status -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance & Certificate</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-in Status:</span>
                                <span :class="registration.is_checked_in ? 'text-green-600' : 'text-gray-500'">
                                    {{ registration.is_checked_in ? 'Checked In' : 'Not Checked In' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Certificate:</span>
                                <span :class="registration.has_certificate ? 'text-green-600' : 'text-gray-500'">
                                    {{ registration.has_certificate ? 'Generated' : 'Not Generated' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div v-if="registration.status === 'pending'" class="mt-8 flex justify-center space-x-4">
                    <button @click="approveRegistration"
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Approve Registration
                    </button>
                    <button @click="openRejectModal"
                            class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Reject Registration
                    </button>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div v-if="showRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Registration</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Are you sure you want to reject the registration for {{ registration.registrant.name }}?
                </p>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason (Optional)</label>
                    <textarea v-model="rejectReason" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md"
                              rows="3"
                              placeholder="Enter reason for rejection..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button @click="closeRejectModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button @click="confirmReject"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    registration: Object,
});

const showRejectModal = ref(false);
const rejectReason = ref('');

const getStatusClass = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const approveRegistration = () => {
    router.post(route('admin.registrations.approve', props.registration.id), {}, {
        onSuccess: () => {
            // Registration status will be updated via props
        },
    });
};

const openRejectModal = () => {
    rejectReason.value = '';
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    rejectReason.value = '';
};

const confirmReject = () => {
    router.post(route('admin.registrations.reject', props.registration.id), {
        reason: rejectReason.value
    }, {
        onSuccess: () => {
            closeRejectModal();
        },
    });
};
</script>