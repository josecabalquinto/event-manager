<template>
    <AppLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Upcoming Events</h2>
                <div v-if="events.length === 0" class="text-gray-500 text-center py-8">
                    No upcoming events available.
                </div>
                <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div v-for="event in events" :key="event.id" 
                         class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <h3 class="text-xl font-semibold mb-2">{{ event.title }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ event.description }}</p>
                        <div class="space-y-2 text-sm text-gray-500 mb-4">
                            <div>📅 {{ event.event_date }} at {{ event.event_time }}</div>
                            <div>📍 {{ event.location }}</div>
                            <div v-if="event.max_participants">
                                👥 {{ event.registered_count }} / {{ event.max_participants }} registered
                            </div>
                        </div>
                        <Link :href="route('events.show', event.id)" 
                              class="inline-block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            View Details
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
    events: Array,
});
</script>
