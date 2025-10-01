<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
                <p class="text-gray-600">Welcome to the admin panel!</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <Link :href="route('admin.events.index')" 
                          class="p-6 border rounded-lg hover:shadow-lg transition-shadow text-center">
                        <div class="text-4xl mb-2">📅</div>
                        <h3 class="text-lg font-semibold">Events</h3>
                        <p class="text-sm text-gray-600">Manage events</p>
                    </Link>
                    <Link :href="route('admin.users.index')" 
                          class="p-6 border rounded-lg hover:shadow-lg transition-shadow text-center">
                        <div class="text-4xl mb-2">👥</div>
                        <h3 class="text-lg font-semibold">Users</h3>
                        <p class="text-sm text-gray-600">Manage users</p>
                    </Link>
                    <Link :href="route('admin.check-in.index')" 
                          class="p-6 border rounded-lg hover:shadow-lg transition-shadow text-center">
                        <div class="text-4xl mb-2">📱</div>
                        <h3 class="text-lg font-semibold">Check-In</h3>
                        <p class="text-sm text-gray-600">Scan QR codes</p>
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>
