<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Users Management</h2>
                    <Link :href="route('admin.users.create')" 
                          class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Create User
                    </Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrations</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users" :key="user.id">
                                <td class="px-6 py-4">{{ user.name }}</td>
                                <td class="px-6 py-4">{{ user.email }}</td>
                                <td class="px-6 py-4">
                                    <span :class="user.is_admin ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'" 
                                          class="px-2 py-1 text-xs rounded">
                                        {{ user.is_admin ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ user.event_registrations_count }}</td>
                                <td class="px-6 py-4 space-x-2">
                                    <Link :href="route('admin.users.edit', user.id)" 
                                          class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                    <Link :href="route('admin.users.destroy', user.id)" method="delete" as="button"
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
import { Link } from '@inertiajs/vue3';

defineProps({
    users: Array,
});
</script>
