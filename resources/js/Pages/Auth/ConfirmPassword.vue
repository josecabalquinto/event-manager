<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-4">Confirm Password</h2>
            <Link :href="route('dashboard')" class="mt-4 inline-block text-indigo-600 hover:text-indigo-500">
                Back to Dashboard
            </Link>
        </div>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
</script>
