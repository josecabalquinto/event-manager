<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">QR Code Check-In Scanner</h2>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Manual QR Code Entry</label>
                    <div class="flex space-x-2">
                        <input v-model="qrCode" type="text" placeholder="Enter QR Code"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <button @click="checkIn" :disabled="!qrCode || processing"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                            Check In
                        </button>
                    </div>
                </div>

                <div v-if="result" class="mb-6 p-4 rounded-lg"
                     :class="result.success ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'">
                    <p class="font-semibold">{{ result.message }}</p>
                    <div v-if="result.registration" class="mt-2 text-sm">
                        <p>User: {{ result.registration.user }}</p>
                        <p>Event: {{ result.registration.event }}</p>
                        <p v-if="result.registration.checked_in_at">Checked in at: {{ result.registration.checked_in_at }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Upcoming Events</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <Link v-for="event in events" :key="event.id" 
                              :href="route('admin.events.check-ins', event.id)"
                              class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <h4 class="font-semibold">{{ event.title }}</h4>
                            <p class="text-sm text-gray-600">{{ event.event_date }} at {{ event.event_time }}</p>
                            <p class="text-sm text-gray-600">📍 {{ event.location }}</p>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

defineProps({
    events: Array,
});

const qrCode = ref('');
const result = ref(null);
const processing = ref(false);

const checkIn = async () => {
    processing.value = true;
    result.value = null;

    try {
        const response = await axios.post(route('admin.check-in.scan'), {
            qr_code: qrCode.value,
        });
        
        result.value = response.data;
        if (response.data.success) {
            qrCode.value = '';
        }
    } catch (error) {
        if (error.response) {
            result.value = error.response.data;
        } else {
            result.value = {
                success: false,
                message: 'An error occurred. Please try again.',
            };
        }
    } finally {
        processing.value = false;
    }
};
</script>
