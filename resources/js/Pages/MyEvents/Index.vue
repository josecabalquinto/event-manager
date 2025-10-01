<template>
    <AppLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">My Registered Events</h2>
                <div v-if="registrations.length === 0" class="text-gray-500 text-center py-8">
                    You haven't registered for any events yet.
                </div>
                <div v-else class="space-y-4">
                    <div v-for="registration in registrations" :key="registration.id" 
                         class="border rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold">{{ registration.event.title }}</h3>
                            <p class="text-sm text-gray-600">{{ registration.event.event_date }} at {{ registration.event.event_time }}</p>
                            <p class="text-sm text-gray-600">📍 {{ registration.event.location }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs rounded"
                                  :class="registration.is_checked_in ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                {{ registration.is_checked_in ? 'Checked In' : 'Not Checked In' }}
                            </span>
                        </div>
                        <Link :href="route('my-events.show', registration.id)" 
                              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            View QR Code
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    registrations: Array,
});
</script>
