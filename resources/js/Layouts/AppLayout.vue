<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('events.index')" class="text-xl font-bold text-gray-800">
                                Event Manager
                            </Link>
                        </div>
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <Link :href="route('events.index')" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                  :class="$page.url.startsWith('/events') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700'">
                                Events
                            </Link>
                            <Link v-if="$page.props.auth.user" 
                                  :href="route('my-events.index')" 
                                  class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                  :class="$page.url.startsWith('/my-events') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700'">
                                My Events
                            </Link>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div v-if="$page.props.auth.user" class="ml-3 relative">
                            <div class="flex items-center space-x-4">
                                <Link v-if="$page.props.auth.user.is_admin" 
                                      :href="route('admin.dashboard')" 
                                      class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                                    Admin
                                </Link>
                                <Link :href="route('dashboard')" 
                                      class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                                    Dashboard
                                </Link>
                                <Link :href="route('logout')" method="post" as="button"
                                      class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                                    Logout
                                </Link>
                            </div>
                        </div>
                        <div v-else class="flex items-center space-x-4">
                            <Link :href="route('login')" 
                                  class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                                Login
                            </Link>
                            <Link :href="route('register')" 
                                  class="px-3 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700">
                                Register
                            </Link>
                        </div>
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
