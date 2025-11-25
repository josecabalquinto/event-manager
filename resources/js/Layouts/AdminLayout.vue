<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-yellow-50">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-gray-900 to-black shadow-2xl transform -translate-x-full transition-transform duration-200 ease-in-out lg:translate-x-0" :class="{ 'translate-x-0': sidebarOpen }">
            <div class="flex flex-col h-full overflow-y-auto">
                <!-- Logo/Brand -->
                <div class="flex items-center justify-between h-20 px-6 bg-gradient-to-r from-gray-800 to-black border-b border-gray-700">
                    <Link :href="route('admin.dashboard')" class="flex items-center">
                        <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 p-2 rounded-xl shadow-lg mr-3">
                            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-bold bg-gradient-to-r from-yellow-400 to-yellow-500 bg-clip-text text-transparent">CICTE CertChain</p>
                            <p class="text-xs text-gray-400 font-medium">Blockchain Powered Certificates</p>
                        </div>
                    </Link>
                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-300 hover:text-white p-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-6 py-8 space-y-1">
                    <div class="mb-8">
                        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Overview</h3>
                    </div>
                    
                    <Link :href="route('admin.dashboard')" 
                          class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                          :class="$page.url === '/admin/dashboard' ? 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-black shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200"
                             :class="$page.url === '/admin/dashboard' ? 'bg-black/20' : 'bg-gray-700 group-hover:bg-gray-600'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                        </div>
                        Dashboard
                    </Link>
                    
                    <div class="mt-8 mb-4">
                        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</h3>
                    </div>
                    
                    <Link :href="route('admin.events.index')" 
                          class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                          :class="$page.url.startsWith('/admin/events') ? 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-black shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200"
                             :class="$page.url.startsWith('/admin/events') ? 'bg-black/20' : 'bg-gray-700 group-hover:bg-gray-600'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        Events
                    </Link>
                    
                    <Link :href="route('admin.users.index')" 
                          class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                          :class="$page.url.startsWith('/admin/users') ? 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-black shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200"
                             :class="$page.url.startsWith('/admin/users') ? 'bg-black/20' : 'bg-gray-700 group-hover:bg-gray-600'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        Users
                    </Link>
                    
                    <Link :href="route('admin.participants.index')" 
                          class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                          :class="$page.url.startsWith('/admin/participants') ? 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-black shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200"
                             :class="$page.url.startsWith('/admin/participants') ? 'bg-black/20' : 'bg-gray-700 group-hover:bg-gray-600'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        Participants
                    </Link>

                    <Link :href="route('admin.registrations.index')" 
                          class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                          :class="$page.url.startsWith('/admin/registrations') ? 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-black shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200"
                             :class="$page.url.startsWith('/admin/registrations') ? 'bg-black/20' : 'bg-gray-700 group-hover:bg-gray-600'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.5-5a4.002 4.002 0 00-7.07.77 2.5 2.5 0 103.5 3.5A4 4 0 0015.5 3.5z"></path>
                            </svg>
                        </div>
                        Registration Approvals
                    </Link>
                    
                    <div class="mt-8 mb-4">
                        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Operations</h3>
                    </div>
                    
                    <Link :href="route('admin.check-in.index')" 
                          class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                          :class="$page.url.startsWith('/admin/check-in') ? 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-black shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200"
                             :class="$page.url.startsWith('/admin/check-in') ? 'bg-black/20' : 'bg-gray-700 group-hover:bg-gray-600'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Check-In Scanner
                    </Link>

                    <Link :href="route('admin.certificates.index')" 
                          class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                          :class="$page.url.startsWith('/admin/certificates') ? 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-black shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-700/50'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200"
                             :class="$page.url.startsWith('/admin/certificates') ? 'bg-black/20' : 'bg-gray-700 group-hover:bg-gray-600'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        Certificates
                    </Link>
                </nav>

                <!-- Bottom Actions -->
                <div class="px-6 py-6 space-y-2 border-t border-gray-700">
                    <div class="mb-4">
                        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Quick Actions</h3>
                    </div>
                    
                    <Link :href="route('events.index')" 
                          class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group text-gray-300 hover:text-white hover:bg-gray-700/50">
                        <div class="w-8 h-8 bg-gray-700 group-hover:bg-gray-600 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </div>
                        View Public Site
                    </Link>
                    
                    <Link :href="route('logout')" method="post" as="button"
                          class="flex items-center w-full px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group text-gray-300 hover:text-white hover:bg-red-600/20">
                        <div class="w-8 h-8 bg-gray-700 group-hover:bg-red-600 rounded-lg flex items-center justify-center mr-3 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        Sign Out
                    </Link>
                </div>
            </div>
        </div>

        <!-- Overlay for mobile -->
        <div v-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-25 lg:hidden"></div>

        <!-- Main content -->
        <div class="flex-1 lg:ml-72">
            <!-- Top bar for mobile -->
            <div class="lg:hidden bg-white shadow-lg border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-6">
                    <button @click="sidebarOpen = true" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 p-2 rounded-lg shadow-md mr-3">
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h1 class="text-lg font-bold bg-gradient-to-r from-yellow-600 to-yellow-500 bg-clip-text text-transparent">Admin Panel</h1>
                    </div>
                    <div class="w-6"></div> <!-- Spacer for centering -->
                </div>
            </div>

            <!-- Main content area -->
            <main class="flex-1 min-h-screen p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Flash Messages -->
                    <div v-if="$page.props.flash.success" class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-4 rounded-r-xl shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-800 font-medium">{{ $page.props.flash.success }}</p>
                        </div>
                    </div>
                    <div v-if="$page.props.flash.error" class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-400 p-4 rounded-r-xl shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-800 font-medium">{{ $page.props.flash.error }}</p>
                        </div>
                    </div>
                    
                    <!-- Page Content -->
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const sidebarOpen = ref(false);
</script>