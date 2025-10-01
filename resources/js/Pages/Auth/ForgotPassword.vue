<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-4">Forgot Password</h2>
            <p>Password reset functionality is not yet implemented.</p>
            <Link :href="route('login')" class="mt-4 inline-block text-indigo-600 hover:text-indigo-500">
                Back to Login
            </Link>
        </div>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({ status: String });
</script>
