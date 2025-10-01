<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-2">Check-Ins for {{ event.title }}</h2>
                <p class="text-gray-600 mb-6">{{ formatEventDateTime(event.event_date, event.event_time) }}</p>

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
                                <td class="px-6 py-4">{{ formatDateTime(checkIn.checked_in_at) }}</td>
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
            timeStr = ` at ${hour12}${minutes !== '00' ? ':' + minutes : ''}${ampm}`;
        }
        
        return `${formattedDate}${timeStr}`;
    } catch (error) {
        console.error('Date formatting error:', error);
        return `${eventDate} ${eventTime || ''}`.trim();
    }
};

// Format full datetime (for check-in times)
const formatDateTime = (datetime) => {
    try {
        if (!datetime) return 'Not checked in';
        
        const date = new Date(datetime);
        const dateOptions = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        };
        const timeOptions = {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        };
        
        const formattedDate = date.toLocaleDateString('en-US', dateOptions);
        const formattedTime = date.toLocaleTimeString('en-US', timeOptions);
        
        return `${formattedDate} at ${formattedTime}`;
    } catch (error) {
        console.error('DateTime formatting error:', error);
        return datetime;
    }
};
</script>
