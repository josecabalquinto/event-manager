<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Registration Approvals</h2>
                    
                    <!-- Bulk approve selected button -->
                    <div class="flex space-x-4">
                        <button v-if="selectedRegistrations.length > 0" 
                                @click="bulkApproveSelected"
                                :disabled="bulkProcessing"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50">
                            <span v-if="bulkProcessing">Processing...</span>
                            <span v-else>Approve Selected ({{ selectedRegistrations.length }})</span>
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select v-model="filters.status" @change="applyFilters"
                                class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Event</label>
                        <select v-model="filters.event_id" @change="applyFilters"
                                class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">All Events</option>
                            <option v-for="event in events" :key="event.id" :value="event.id">
                                {{ event.title }}
                            </option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button @click="clearFilters"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Registrations Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" @change="toggleSelectAll" 
                                           :checked="allCurrentPageSelected"
                                           class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Participant
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Event
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Registered At
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="registration in registrations.data" :key="registration.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" 
                                           :value="registration.id"
                                           v-model="selectedRegistrations"
                                           :disabled="registration.status !== 'pending'"
                                           class="rounded border-gray-300">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ registration.registrant_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ registration.registrant_email }}
                                            </div>
                                            <div v-if="registration.registrant_student_id" class="text-xs text-gray-400">
                                                {{ registration.registrant_student_id }} - {{ registration.registrant_course }} 
                                                {{ registration.registrant_year_level }} {{ registration.registrant_section }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ registration.event.title }}</div>
                                    <div class="text-sm text-gray-500">{{ registration.event.event_date }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusClass(registration.status)" 
                                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ registration.status.charAt(0).toUpperCase() + registration.status.slice(1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(registration.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <Link :href="route('admin.registrations.show', registration.id)"
                                          class="text-indigo-600 hover:text-indigo-900">
                                        View
                                    </Link>
                                    <button v-if="registration.status === 'pending'"
                                            @click="approveRegistration(registration.id)"
                                            class="text-green-600 hover:text-green-900">
                                        Approve
                                    </button>
                                    <button v-if="registration.status === 'pending'"
                                            @click="openRejectModal(registration)"
                                            class="text-red-600 hover:text-red-900">
                                        Reject
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6" v-if="registrations.links">
                    <nav class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link v-if="registrations.prev_page_url" 
                                  :href="registrations.prev_page_url"
                                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </Link>
                            <Link v-if="registrations.next_page_url"
                                  :href="registrations.next_page_url"
                                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing {{ registrations.from }} to {{ registrations.to }} of {{ registrations.total }} results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <template v-for="link in registrations.links" :key="link.label">
                                        <Link v-if="link.url" 
                                              :href="link.url"
                                              :class="[
                                                  'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium',
                                                  link.active ? 'bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white text-gray-500 hover:bg-gray-50'
                                              ]">
                                            {{ link.label }}
                                        </Link>
                                        <span v-else 
                                              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                            {{ link.label }}
                                        </span>
                                    </template>
                                </nav>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div v-if="showRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Registration</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Are you sure you want to reject the registration for {{ selectedRegistrationForReject?.registrant_name }}?
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
import { ref, computed } from 'vue';

const props = defineProps({
    registrations: Object,
    events: Array,
    filters: Object,
});

const selectedRegistrations = ref([]);
const bulkProcessing = ref(false);
const showRejectModal = ref(false);
const selectedRegistrationForReject = ref(null);
const rejectReason = ref('');

const filters = ref({
    status: props.filters.status || 'all',
    event_id: props.filters.event_id || '',
});

const allCurrentPageSelected = computed(() => {
    if (!props.registrations.data.length) return false;
    const pendingRegistrations = props.registrations.data.filter(r => r.status === 'pending');
    return pendingRegistrations.length > 0 && pendingRegistrations.every(r => selectedRegistrations.value.includes(r.id));
});

const toggleSelectAll = () => {
    const pendingRegistrations = props.registrations.data.filter(r => r.status === 'pending');
    if (allCurrentPageSelected.value) {
        selectedRegistrations.value = selectedRegistrations.value.filter(id => 
            !pendingRegistrations.some(r => r.id === id)
        );
    } else {
        pendingRegistrations.forEach(registration => {
            if (!selectedRegistrations.value.includes(registration.id)) {
                selectedRegistrations.value.push(registration.id);
            }
        });
    }
};

const applyFilters = () => {
    router.get(route('admin.registrations.index'), filters.value, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    filters.value = { status: 'all', event_id: '' };
    applyFilters();
};

const getStatusClass = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const approveRegistration = (registrationId) => {
    router.post(route('admin.registrations.approve', registrationId), {}, {
        onSuccess: () => {
            selectedRegistrations.value = selectedRegistrations.value.filter(id => id !== registrationId);
        },
    });
};

const bulkApproveSelected = () => {
    if (selectedRegistrations.value.length === 0) return;
    
    bulkProcessing.value = true;
    router.post(route('admin.registrations.bulk-approve'), {
        registration_ids: selectedRegistrations.value
    }, {
        onSuccess: () => {
            selectedRegistrations.value = [];
            bulkProcessing.value = false;
        },
        onError: () => {
            bulkProcessing.value = false;
        }
    });
};

const openRejectModal = (registration) => {
    selectedRegistrationForReject.value = registration;
    rejectReason.value = '';
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    selectedRegistrationForReject.value = null;
    rejectReason.value = '';
};

const confirmReject = () => {
    if (!selectedRegistrationForReject.value) return;
    
    router.post(route('admin.registrations.reject', selectedRegistrationForReject.value.id), {
        reason: rejectReason.value
    }, {
        onSuccess: () => {
            closeRejectModal();
            selectedRegistrations.value = selectedRegistrations.value.filter(id => 
                id !== selectedRegistrationForReject.value.id
            );
        },
    });
};
</script>