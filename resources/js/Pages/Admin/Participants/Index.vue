<template>
    <AdminLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Participant Management</h1>
                        <p class="text-gray-600 mt-1">Manage event registrations, approvals, and attendance</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <select v-model="selectedEventForReport" 
                                @change="generateReport"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">Generate Report for Event...</option>
                            <option v-for="event in events" :key="event.id" :value="event.id">
                                {{ event.title }} ({{ formatDate(event.event_date) }})
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stats.total_participants }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending</p>
                            <p class="text-2xl font-semibold text-yellow-600">{{ stats.pending }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Approved</p>
                            <p class="text-2xl font-semibold text-green-600">{{ stats.approved }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Rejected</p>
                            <p class="text-2xl font-semibold text-red-600">{{ stats.rejected }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Checked In</p>
                            <p class="text-2xl font-semibold text-purple-600">{{ stats.checked_in }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Participants</label>
                        <input v-model="localFilters.search" 
                               type="text" 
                               placeholder="Search by name or email..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Event</label>
                        <select v-model="localFilters.event_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">All Events</option>
                            <option v-for="event in events" :key="event.id" :value="event.id">
                                {{ event.title }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                        <select v-model="localFilters.status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Status</label>
                        <select v-model="localFilters.checked_in" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">All</option>
                            <option value="1">Checked In</option>
                            <option value="0">Not Checked In</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button @click="clearFilters" 
                            class="text-gray-500 hover:text-gray-700 text-sm">
                        Clear Filters
                    </button>
                    <button @click="applyFilters" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div v-if="selectedParticipants.length > 0" class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        {{ selectedParticipants.length }} participant(s) selected
                    </p>
                    <div class="flex items-center gap-3">
                        <button @click="bulkApprove" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Approve Selected
                        </button>
                        <button @click="bulkReject" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Reject Selected
                        </button>
                        <button @click="bulkDelete" 
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Remove Selected
                        </button>
                    </div>
                </div>
            </div>

            <!-- Participants Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Participants</h2>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" 
                                   :checked="allSelected" 
                                   @change="toggleSelectAll"
                                   class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500" />
                            <label class="text-sm text-gray-600">Select All</label>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Select
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
                                    Check-in
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="participant in participants.data" :key="participant.id" 
                                class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" 
                                           :value="participant.id" 
                                           v-model="selectedParticipants"
                                           class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ participant.participant.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ participant.participant.email }}
                                            </div>
                                            <div v-if="participant.participant.student_id" class="text-xs text-gray-400">
                                                ID: {{ participant.participant.student_id }} | 
                                                {{ participant.participant.course }} {{ participant.participant.year_level }} {{ participant.participant.section }}
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1"
                                                  :class="participant.participant.type === 'registered' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'">
                                                {{ participant.participant.type === 'registered' ? 'Registered User' : 'Guest' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ participant.event.title }}</div>
                                    <div class="text-sm text-gray-500">{{ participant.event.event_date }}</div>
                                    <div class="text-xs text-gray-400">{{ participant.event.location }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="getStatusClass(participant.status)">
                                        {{ getStatusText(participant.status) }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-1">
                                        Reg: {{ participant.registration_date }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="participant.is_checked_in ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                        {{ participant.is_checked_in ? 'Checked In' : 'Not Checked In' }}
                                    </span>
                                    <div v-if="participant.check_in_time" class="text-xs text-gray-400 mt-1">
                                        {{ participant.check_in_time }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <Link :href="route('admin.participants.show', participant.id)" 
                                              class="text-indigo-600 hover:text-indigo-900">
                                            View
                                        </Link>
                                        <button v-if="participant.status === 'pending'" 
                                                @click="approveParticipant(participant.id)"
                                                class="text-green-600 hover:text-green-900">
                                            Approve
                                        </button>
                                        <button v-if="participant.status === 'pending'" 
                                                @click="rejectParticipant(participant.id)"
                                                class="text-red-600 hover:text-red-900">
                                            Reject
                                        </button>
                                        <button @click="deleteParticipant(participant.id)" 
                                                class="text-red-600 hover:text-red-900">
                                            Remove
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="participants.links" class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ participants.from }} to {{ participants.to }} of {{ participants.total }} results
                        </div>
                        <div class="flex items-center gap-2">
                            <Link v-for="link in participants.links" 
                                  :key="link.label"
                                  :href="link.url"
                                  :class="[
                                      'px-3 py-2 text-sm rounded-lg border',
                                      link.active 
                                          ? 'bg-yellow-600 text-white border-yellow-600' 
                                          : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50',
                                      !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                  ]"
                                  :disabled="!link.url">
                                  {{ link.label }}
                            </Link>
                        </div>
                    </div>
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
    participants: Object,
    events: Array,
    filters: Object,
    stats: Object,
});

// Reactive data
const selectedParticipants = ref([]);
const selectedEventForReport = ref('');
const localFilters = ref({
    search: props.filters.search || '',
    event_id: props.filters.event_id || '',
    status: props.filters.status || '',
    checked_in: props.filters.checked_in || '',
});

// Computed properties
const allSelected = computed(() => {
    return props.participants.data.length > 0 && 
           selectedParticipants.value.length === props.participants.data.length;
});

// Status helpers
const getStatusClass = (status) => {
    switch (status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'approved':
            return 'bg-green-100 text-green-800';
        case 'rejected':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'pending':
            return 'Pending';
        case 'approved':
            return 'Approved';
        case 'rejected':
            return 'Rejected';
        default:
            return 'Unknown';
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Methods
const toggleSelectAll = () => {
    if (allSelected.value) {
        selectedParticipants.value = [];
    } else {
        selectedParticipants.value = props.participants.data.map(p => p.id);
    }
};

const clearFilters = () => {
    localFilters.value = {
        search: '',
        event_id: '',
        status: '',
        checked_in: '',
    };
    applyFilters();
};

const applyFilters = () => {
    router.get(route('admin.participants.index'), localFilters.value, {
        preserveState: true,
        replace: true,
    });
};

// Individual actions
const approveParticipant = (id) => {
    router.post(route('admin.participants.approve', id), {}, {
        preserveScroll: true,
    });
};

const rejectParticipant = (id) => {
    router.post(route('admin.participants.reject', id), {}, {
        preserveScroll: true,
    });
};

const deleteParticipant = (id) => {
    if (confirm('Are you sure you want to remove this participant? This action cannot be undone.')) {
        router.delete(route('admin.participants.destroy', id), {
            preserveScroll: true,
        });
    }
};

// Bulk actions
const bulkApprove = () => {
    if (confirm(`Are you sure you want to approve ${selectedParticipants.value.length} selected registrations?`)) {
        router.post(route('admin.participants.bulk-approve'), {
            registration_ids: selectedParticipants.value
        }, {
            preserveScroll: true,
            onSuccess: () => {
                selectedParticipants.value = [];
            }
        });
    }
};

const bulkReject = () => {
    if (confirm(`Are you sure you want to reject ${selectedParticipants.value.length} selected registrations?`)) {
        router.post(route('admin.participants.bulk-reject'), {
            registration_ids: selectedParticipants.value
        }, {
            preserveScroll: true,
            onSuccess: () => {
                selectedParticipants.value = [];
            }
        });
    }
};

const bulkDelete = () => {
    if (confirm(`Are you sure you want to remove ${selectedParticipants.value.length} selected participants? This action cannot be undone.`)) {
        router.post(route('admin.participants.bulk-destroy'), {
            registration_ids: selectedParticipants.value
        }, {
            preserveScroll: true,
            onSuccess: () => {
                selectedParticipants.value = [];
            }
        });
    }
};

const generateReport = () => {
    if (selectedEventForReport.value) {
        window.open(route('admin.participants.attendance-report', selectedEventForReport.value), '_blank');
        selectedEventForReport.value = '';
    }
};
</script>
