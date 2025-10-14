<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Events Management</h2>
                    <Link :href="route('admin.events.create')" 
                          class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Create Event
                    </Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="event in events" :key="event.id">
                                <td class="px-6 py-4">{{ event.title }}</td>
                                <td class="px-6 py-4">{{ formatEventDateTime(event.event_date, event.event_time) }}</td>
                                <td class="px-6 py-4">{{ event.location }}</td>
                                <td class="px-6 py-4">{{ event.registrations_count }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span v-if="event.has_certificate" 
                                              class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Enabled
                                        </span>
                                        <span v-else 
                                              class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                            Disabled
                                        </span>
                                        <Link v-if="event.has_certificate && event.registrations_count > 0" 
                                              :href="route('admin.certificates.create', { event_id: event.id })"
                                              class="text-blue-600 hover:text-blue-800 text-xs">
                                            Generate
                                        </Link>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="event.is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" 
                                          class="px-2 py-1 text-xs rounded">
                                        {{ event.is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <Link :href="route('admin.events.edit', event.id)" 
                                          class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                    <button v-if="event.registrations_count > 0"
                                            @click="approveAllRegistrations(event)"
                                            class="text-green-600 hover:text-green-900">
                                        Approve All
                                    </button>
                                    <Link :href="route('admin.events.destroy', event.id)" method="delete" as="button"
                                          class="text-red-600 hover:text-red-900">Delete</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    events: Array,
});

const approveAllRegistrations = (event) => {
    if (confirm(`Are you sure you want to approve all pending registrations for "${event.title}"?`)) {
        router.post(route('admin.events.approve-all-registrations', event.id), {}, {
            onSuccess: () => {
                // Success message will be handled by the backend
            },
        });
    }
};

// Date formatting function
const formatEventDateTime = (eventDate, eventTime) => {
    try {
        // Parse the date
        const date = new Date(eventDate);
        
        // Format the date part
        const dateOptions = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        const formattedDate = date.toLocaleDateString('en-US', dateOptions);
        
        // Handle time formatting
        let timeStr = '';
        if (eventTime) {
            // Parse time string (assuming format like "14:30:00")
            const [hours, minutes] = eventTime.split(':');
            const hour24 = parseInt(hours);
            const hour12 = hour24 === 0 ? 12 : hour24 > 12 ? hour24 - 12 : hour24;
            const ampm = hour24 >= 12 ? 'PM' : 'AM';
            timeStr = ` ${hour12}${minutes !== '00' ? ':' + minutes : ''}${ampm}`;
        }
        
        return `${formattedDate}${timeStr}`;
    } catch (error) {
        console.error('Date formatting error:', error);
        return `${eventDate} ${eventTime || ''}`.trim();
    }
};
</script>
