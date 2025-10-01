<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-2">Check-Ins for {{ event.title }}</h2>
                <p class="text-gray-600 mb-6">{{ event.event_date }} at {{ event.event_time }}</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Checked In At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Checked In By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="checkIn in check_ins" :key="checkIn.id">
                                <td class="px-6 py-4">{{ checkIn.user.name }}</td>
                                <td class="px-6 py-4">{{ checkIn.user.email }}</td>
                                <td class="px-6 py-4">{{ checkIn.checked_in_at }}</td>
                                <td class="px-6 py-4">{{ checkIn.checked_in_by.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <Link :href="route('admin.check-in.index')" 
                          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Back to Scanner
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    event: Object,
    check_ins: Array,
});
</script>
