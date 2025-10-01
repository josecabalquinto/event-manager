<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Header/Navigation -->
        <header class="bg-white shadow-lg border-b-4 border-blue-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo and Brand -->
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center">
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-xl shadow-md mr-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <Link :href="route('events.index')" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hover:from-purple-600 hover:to-blue-600 transition-all duration-300">
                                EventHub
                            </Link>
                        </div>
                        
                        <!-- Main Navigation -->
                        <nav class="hidden md:ml-10 md:flex md:space-x-1">
                            <Link :href="route('events.index')" 
                                  class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                                  :class="$page.url.startsWith('/events') ? 'bg-blue-100 text-blue-700 shadow-sm' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    All Events
                                </span>
                            </Link>
                            <Link v-if="$page.props.auth.user" 
                                  :href="route('my-events.index')" 
                                  class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                                  :class="$page.url.startsWith('/my-events') ? 'bg-blue-100 text-blue-700 shadow-sm' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    My Events
                                </span>
                            </Link>
                        </nav>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <div v-if="$page.props.auth.user" class="flex items-center space-x-3">
                            <Link v-if="$page.props.auth.user.is_admin" 
                                  :href="route('admin.dashboard')" 
                                  class="px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700 shadow-md transition-all duration-200 transform hover:scale-105">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Admin
                                </span>
                            </Link>
                            <Link :href="route('dashboard')" 
                                  class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2h14a2 2 0 012 2v2M9 5l2 2 4-4"></path>
                                    </svg>
                                    Dashboard
                                </span>
                            </Link>
                            <Link :href="route('logout')" method="post" as="button"
                                  class="px-4 py-2 rounded-lg text-sm font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 transition-all duration-200">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </span>
                            </Link>
                        </div>
                        <div v-else class="flex items-center space-x-3">
                            <Link :href="route('login')" 
                                  class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                Sign In
                            </Link>
                            <Link :href="route('register')" 
                                  class="px-6 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 shadow-md transition-all duration-200 transform hover:scale-105">
                                Get Started
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="relative">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 opacity-60"></div>
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.05) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(139, 92, 246, 0.05) 0%, transparent 50%);"></div>
            
            <div class="relative">
                <!-- Flash Messages -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                    <div v-if="$page.props.flash.success" class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-800 font-medium">{{ $page.props.flash.success }}</p>
                        </div>
                    </div>
                    <div v-if="$page.props.flash.error" class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-800 font-medium">{{ $page.props.flash.error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
                    <slot />
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-12">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Brand Column -->
                        <div class="col-span-1 md:col-span-2">
                            <div class="flex items-center mb-4">
                                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-xl shadow-md mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">EventHub</h3>
                            </div>
                            <p class="text-slate-300 text-sm leading-relaxed max-w-md">
                                Your premier destination for discovering and managing amazing events. Connect with your community and create unforgettable experiences.
                            </p>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h4 class="text-sm font-semibold text-slate-200 uppercase tracking-wider mb-4">Quick Links</h4>
                            <ul class="space-y-2">
                                <li><Link :href="route('events.index')" class="text-slate-300 hover:text-white transition-colors duration-200 text-sm">Browse Events</Link></li>
                                <li v-if="$page.props.auth.user"><Link :href="route('my-events.index')" class="text-slate-300 hover:text-white transition-colors duration-200 text-sm">My Events</Link></li>
                                <li v-if="!$page.props.auth.user"><Link :href="route('register')" class="text-slate-300 hover:text-white transition-colors duration-200 text-sm">Join Us</Link></li>
                            </ul>
                        </div>

                        <!-- Support -->
                        <div>
                            <h4 class="text-sm font-semibold text-slate-200 uppercase tracking-wider mb-4">Support</h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 text-sm">Help Center</a></li>
                                <li><a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 text-sm">Contact Us</a></li>
                                <li><a href="#" class="text-slate-300 hover:text-white transition-colors duration-200 text-sm">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="border-t border-slate-800 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-slate-400 text-sm">
                            © {{ new Date().getFullYear() }} EventHub. All rights reserved.
                        </p>
                        <div class="flex items-center space-x-6 mt-4 md:mt-0">
                            <span class="text-slate-400 text-xs">Made with ❤️ for amazing events</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
