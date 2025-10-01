<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-indigo-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('admin.dashboard')" class="text-xl font-bold text-white">
                                Admin Panel
                            </Link>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <Link :href="route('admin.dashboard')" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-white"
                                  :class="$page.url === '/admin/dashboard' ? 'border-white' : 'border-transparent hover:border-gray-300'">
                                Dashboard
                            </Link>
                            <Link :href="route('admin.events.index')" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-white"
                                  :class="$page.url.startsWith('/admin/events') ? 'border-white' : 'border-transparent hover:border-gray-300'">
                                Events
                            </Link>
                            <Link :href="route('admin.users.index')" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-white"
                                  :class="$page.url.startsWith('/admin/users') ? 'border-white' : 'border-transparent hover:border-gray-300'">
                                Users
                            </Link>
                            <Link :href="route('admin.participants.index')" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-white"
                                  :class="$page.url.startsWith('/admin/participants') ? 'border-white' : 'border-transparent hover:border-gray-300'">
                                Participants
                            </Link>
                            <Link :href="route('admin.check-in.index')" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium text-white"
                                  :class="$page.url.startsWith('/admin/check-in') ? 'border-white' : 'border-transparent hover:border-gray-300'">
                                Check-In
                            </Link>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <Link :href="route('events.index')" 
                              class="px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                            View Site
                        </Link>
                        <Link :href="route('logout')" method="post" as="button"
                              class="ml-4 px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-indigo-700">
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="$page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ $page.props.flash.error }}
                </div>
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
