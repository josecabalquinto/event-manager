<template>
    <AppLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">{{ registration.event.title }}</h2>
                <div class="mb-6">
                    <p class="text-gray-600">{{ registration.event.description }}</p>
                    <div class="mt-4 space-y-2">
                        <p><span class="font-semibold">Date:</span> {{ registration.event.event_date }} at {{ registration.event.event_time }}</p>
                        <p><span class="font-semibold">Location:</span> {{ registration.event.location }}</p>
                        <p><span class="font-semibold">Status:</span> 
                            <span :class="registration.is_checked_in ? 'text-green-600' : 'text-yellow-600'">
                                {{ registration.is_checked_in ? 'Checked In' : 'Not Checked In' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="border-t pt-6">
                    <h3 class="text-xl font-semibold mb-4 text-center">Your QR Code</h3>
                    <div class="flex justify-center">
                        <QRCodeVue3 :value="registration.qr_code" :size="300" />
                    </div>
                    <p class="text-center text-sm text-gray-500 mt-4">
                        Present this QR code at the event for check-in
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import QRCodeVue3 from 'qrcode.vue';

defineProps({
    registration: Object,
});
</script>
