<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Participants Management</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Participant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="participant in participants" :key="participant.id">
                                <td class="px-6 py-4">
                                    <div>{{ participant.user.name }}</div>
                                    <div class="text-sm text-gray-500">{{ participant.user.email }}</div>
                                </td>
                                <td class="px-6 py-4">{{ participant.event.title }}</td>
                                <td class="px-6 py-4">{{ participant.event.event_date }}</td>
                                <td class="px-6 py-4">
                                    <span :class="participant.is_checked_in ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" 
                                          class="px-2 py-1 text-xs rounded">
                                        {{ participant.is_checked_in ? 'Checked In' : 'Not Checked In' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <Link :href="route('admin.participants.show', participant.id)" 
                                          class="text-indigo-600 hover:text-indigo-900">View</Link>
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
import { Link } from '@inertiajs/vue3';

defineProps({
    participants: Array,
});
</script>
