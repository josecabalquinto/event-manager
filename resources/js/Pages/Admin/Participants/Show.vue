<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Participant Details</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">User Information</h3>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Name:</span> {{ participant.user.name }}</p>
                            <p><span class="font-semibold">Email:</span> {{ participant.user.email }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Event Information</h3>
                        <div class="space-y-2">
                            <p><span class="font-semibold">Event:</span> {{ participant.event.title }}</p>
                            <p><span class="font-semibold">Date:</span> {{ participant.event.event_date }} at {{ participant.event.event_time }}</p>
                            <p><span class="font-semibold">Location:</span> {{ participant.event.location }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Registration Information</h3>
                        <div class="space-y-2">
                            <p><span class="font-semibold">QR Code:</span> {{ participant.qr_code }}</p>
                            <p><span class="font-semibold">Status:</span> 
                                <span :class="participant.is_checked_in ? 'text-green-600' : 'text-yellow-600'">
                                    {{ participant.is_checked_in ? 'Checked In' : 'Not Checked In' }}
                                </span>
                            </p>
                            <p v-if="participant.check_in">
                                <span class="font-semibold">Checked In At:</span> {{ participant.check_in.checked_in_at }}
                            </p>
                            <p v-if="participant.check_in">
                                <span class="font-semibold">Checked In By:</span> {{ participant.check_in.checked_in_by.name }}
                            </p>
                        </div>
                    </div>
                    <Link :href="route('admin.participants.index')" 
                          class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Back to List
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
    participant: Object,
});
</script>
