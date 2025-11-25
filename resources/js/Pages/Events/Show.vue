<template>
    <AppLayout>
        <!-- Hero Section with Banner -->
        <div class="relative">
            <div v-if="event.has_banner_image && event.banner_image_url" class="relative h-96 md:h-[500px] overflow-hidden rounded-3xl mb-12 shadow-2xl">
                <img :src="event.banner_image_url" 
                     :alt="event.title + ' banner'" 
                     class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-all duration-700"
                     @click="showImageModal = true"
                     title="Click to view full image">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                    <div class="max-w-4xl">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="text-yellow-400 font-semibold text-sm tracking-wider uppercase">CICTE CertChain</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">{{ event.title }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-lg">
                            <div class="flex items-center bg-white/20 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/10">
                                <svg class="w-5 h-5 mr-3 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-semibold">{{ formatEventDateTime(event.event_date, event.event_time) }}</span>
                            </div>
                            <div class="flex items-center bg-white/20 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/10">
                                <svg class="w-5 h-5 mr-3 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-semibold">{{ event.location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center mb-12">
                <div class="bg-gradient-to-br from-white via-yellow-50/50 to-amber-50/30 backdrop-blur-sm rounded-3xl shadow-2xl p-12 border border-yellow-200/20">
                    <div class="flex items-center justify-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg transform rotate-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-yellow-600 font-bold text-lg tracking-wider uppercase">CICTE CertChain</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">{{ event.title }}</h1>
                    <div class="flex flex-wrap justify-center items-center gap-4 text-lg">
                        <div class="flex items-center bg-gradient-to-r from-yellow-100 to-amber-100 rounded-2xl px-6 py-3 border border-yellow-200 shadow-sm">
                            <svg class="w-5 h-5 mr-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold text-yellow-800">{{ formatEventDateTime(event.event_date, event.event_time) }}</span>
                        </div>
                        <div class="flex items-center bg-gradient-to-r from-orange-100 to-yellow-100 rounded-2xl px-6 py-3 border border-orange-200 shadow-sm">
                            <svg class="w-5 h-5 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-semibold text-orange-800">{{ event.location }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-3 gap-8 mb-12">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Event Description -->
                <div class="bg-gradient-to-br from-white via-yellow-50/30 to-amber-50/20 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-yellow-200/20">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg transform -rotate-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent">About This Event</span>
                    </h2>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        <p class="text-lg">{{ event.description }}</p>
                    </div>
                </div>

                <!-- Location Map -->
                <div v-if="event.latitude && event.longitude" class="bg-gradient-to-br from-white via-orange-50/30 to-yellow-50/20 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-orange-200/20">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-yellow-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg transform rotate-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="bg-gradient-to-r from-orange-600 to-yellow-600 bg-clip-text text-transparent">Event Location</span>
                        </h2>
                        <button @click="showLocationMap = !showLocationMap"
                                class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-amber-500 text-white rounded-2xl font-semibold hover:from-yellow-500 hover:to-amber-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            {{ showLocationMap ? 'Hide Map' : 'Show Map' }}
                        </button>
                    </div>
                    
                    <div v-if="showLocationMap" 
                         ref="eventMapContainer" 
                         class="w-full h-80 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl overflow-hidden shadow-inner border border-yellow-200/30"
                         style="min-height: 320px;">
                        <div class="flex items-center justify-center h-full text-gray-500">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg animate-pulse">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <p class="font-semibold text-gray-600">Loading interactive map...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- User Profile Card (for authenticated users) -->
                <div v-if="$page.props.auth.user && userProfile" class="bg-gradient-to-br from-white via-purple-50/50 to-indigo-50/30 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-purple-200/20">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-indigo-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Your Profile</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Full Name -->
                        <div class="flex items-start group">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-xl flex items-center justify-center mr-3 flex-shrink-0 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Full Name</p>
                                <p class="text-gray-900 font-bold">{{ userProfile.name }}</p>
                            </div>
                        </div>

                        <!-- Student ID -->
                        <div v-if="userProfile.student_id" class="flex items-start group">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center mr-3 flex-shrink-0 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Student ID</p>
                                <p class="text-gray-900 font-bold">{{ userProfile.student_id }}</p>
                            </div>
                        </div>

                        <!-- Course -->
                        <div v-if="userProfile.course" class="flex items-start group">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center mr-3 flex-shrink-0 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Course</p>
                                <p class="text-gray-900 font-bold">{{ userProfile.course }}</p>
                            </div>
                        </div>

                        <!-- Year & Section -->
                        <div v-if="userProfile.year_level && userProfile.section" class="flex items-start group">
                            <div class="w-10 h-10 bg-gradient-to-br from-violet-400 to-purple-500 rounded-xl flex items-center justify-center mr-3 flex-shrink-0 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Year & Section</p>
                                <p class="text-gray-900 font-bold">{{ userProfile.year_level }} - Section {{ userProfile.section }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Details Card -->
                <div class="bg-gradient-to-br from-white via-yellow-50/50 to-amber-50/30 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-yellow-200/20">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent">Event Details</h3>
                    </div>
                    
                    <div class="space-y-8">
                        <!-- Date & Time -->
                        <div class="flex items-start group">
                            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-2xl flex items-center justify-center mr-4 flex-shrink-0 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 mb-2 text-lg">Date & Time</p>
                                <p class="text-gray-700 font-medium">{{ formatEventDateTime(event.event_date, event.event_time) }}</p>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="flex items-start group">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-yellow-500 rounded-2xl flex items-center justify-center mr-4 flex-shrink-0 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 mb-2 text-lg">Location</p>
                                <p class="text-gray-700 font-medium">{{ event.location }}</p>
                            </div>
                        </div>

                        <!-- Target Courses -->
                        <div v-if="event.allowed_courses && event.allowed_courses.length > 0" class="flex items-start group">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-2xl flex items-center justify-center mr-4 flex-shrink-0 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 mb-2 text-lg">Target Courses</p>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="course in event.allowed_courses" :key="course" 
                                          class="px-3 py-1 bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-800 rounded-full text-sm font-medium">
                                        {{ course }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Available Spots -->
                        <div v-if="event.max_participants" class="flex items-start group">
                            <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mr-4 flex-shrink-0 shadow-lg transform transition-transform group-hover:scale-110">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 mb-2 text-lg">Availability</p>
                                <p class="text-gray-700 font-medium mb-3">{{ event.available_spots }} of {{ event.max_participants }} spots available</p>
                                <div class="w-full bg-gradient-to-r from-gray-200 to-gray-300 rounded-full h-3 overflow-hidden shadow-inner">
                                    <div class="bg-gradient-to-r from-yellow-400 via-amber-500 to-orange-500 h-3 rounded-full transition-all duration-500 shadow-sm" 
                                         :style="{ width: ((event.max_participants - event.available_spots) / event.max_participants * 100) + '%' }"></div>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 mt-2">
                                    <span>{{ event.max_participants - event.available_spots }} registered</span>
                                    <span>{{ event.available_spots }} remaining</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!-- Already Registered (for authenticated users) -->
                <div v-if="$page.props.auth.user && event.is_registered" class="bg-gradient-to-br from-green-50 via-emerald-50 to-green-50 rounded-3xl shadow-xl p-8 border-2 border-green-400">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-green-800 mb-3">You're Registered!</h3>
                        <p class="text-green-700 mb-2">You have successfully registered for this event.</p>
                        <p class="text-sm text-green-600 font-medium mb-6">Registered on {{ event.registered_at }}</p>
                        <!-- <div class="bg-white/60 rounded-2xl p-4 border border-green-200">
                            <p class="text-sm text-gray-700 mb-2">Check your dashboard for your QR code</p>
                            <Link :href="route('my-events.index')" 
                                  class="inline-block px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                View My Events
                            </Link>
                        </div> -->
                    </div>
                </div>

                <!-- Guest Registration Form -->
                <div v-if="!$page.props.auth.user" class="bg-gradient-to-br from-white via-green-50/30 to-yellow-50/20 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-green-200/20">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-yellow-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold bg-gradient-to-r from-green-600 to-yellow-600 bg-clip-text text-transparent">Register for this Event</h3>
                    </div>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                            <input v-model="form.name" type="text" required
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30" />
                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input v-model="form.email" type="email" required
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30" />
                            <div v-if="form.errors.email" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.email }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <input v-model="form.password" type="password" required
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30" />
                            <div v-if="form.errors.password" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.password }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                            <input v-model="form.password_confirmation" type="password" required
                                   class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30" />
                        </div>

                        <!-- Student Information Section -->
                        <div class="border-t border-yellow-200/50 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Student Information</h4>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Student ID <span class="text-red-500">*</span></label>
                                <input v-model="form.student_id" type="text" required
                                       placeholder="e.g., 20-1956"
                                       pattern="[0-9]{2}-[0-9]{4}"
                                       title="Please enter student ID in format: XX-XXXX (e.g., 20-1956)"
                                       class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30" />
                                <div v-if="form.errors.student_id" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.student_id }}</div>
                                <p class="text-xs text-gray-500 mt-1">Format: XX-XXXX (e.g., 20-1956)</p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Course <span class="text-red-500">*</span></label>
                                    <select v-model="form.course" required
                                            class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30">
                                        <option value="">Select Course</option>
                                        <option v-for="course in courses" :key="course" :value="course">{{ course }}</option>
                                    </select>
                                    <div v-if="form.errors.course" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.course }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Year Level <span class="text-red-500">*</span></label>
                                    <select v-model="form.year_level" required
                                            class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30">
                                        <option value="">Select Year</option>
                                        <option v-for="year in yearLevels" :key="year" :value="year">{{ year }}</option>
                                    </select>
                                    <div v-if="form.errors.year_level" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.year_level }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Section <span class="text-red-500">*</span></label>
                                    <input v-model="form.section" type="text" required
                                           placeholder="e.g., A, B, C"
                                           class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30" />
                                    <div v-if="form.errors.section" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.section }}</div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" :disabled="form.processing || event.is_full"
                                class="w-full px-6 py-4 bg-gradient-to-r from-yellow-400 to-amber-500 text-white rounded-2xl font-bold text-lg hover:from-yellow-500 hover:to-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ event.is_full ? 'Event Full' : (form.processing ? 'Creating Account...' : 'Register & Join Event') }}
                        </button>
                    </form>
                </div>
                
                <!-- Authenticated User Registration -->
                <div v-else-if="!event.is_registered" class="bg-gradient-to-br from-white via-blue-50/30 to-yellow-50/20 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-blue-200/20">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-yellow-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-yellow-600 bg-clip-text text-transparent">Join this Event</h3>
                    </div>
                    <button @click="register" :disabled="event.is_full || isRegistering"
                            class="w-full px-6 py-4 bg-gradient-to-r from-yellow-400 to-amber-500 text-white rounded-2xl font-bold text-lg hover:from-yellow-500 hover:to-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                        <svg v-if="isRegistering" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ event.is_full ? 'Event Full' : (isRegistering ? 'Registering...' : 'Register for Event') }}
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Image Modal -->
        <div v-if="showImageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm" @click="showImageModal = false">
            <div class="relative max-w-6xl max-h-screen p-6">
                <button @click="showImageModal = false" 
                        class="absolute -top-4 -right-4 z-10 bg-gradient-to-r from-yellow-400 to-amber-500 text-white rounded-full p-3 hover:from-yellow-500 hover:to-amber-600 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img :src="event.banner_image_url" 
                     :alt="event.title + ' banner'" 
                     class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl border-4 border-white/20"
                     @click.stop>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref, watch, nextTick } from 'vue';

const props = defineProps({
    event: Object,
    userProfile: Object,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    student_id: '',
    course: '',
    year_level: '',
    section: '',
});

const courses = ['BSIT', 'BSIS', 'BLIS', 'BSEMC'];
const yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

const isRegistering = ref(false);
const showLocationMap = ref(false);
const showImageModal = ref(false);
const eventMapContainer = ref(null);
const eventMap = ref(null);

// Handle escape key for modal
const handleKeydown = (event) => {
    if (event.key === 'Escape' && showImageModal.value) {
        showImageModal.value = false;
    }
};

// Add event listener for escape key
window.addEventListener('keydown', handleKeydown);

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

// Watch for map visibility changes
watch(showLocationMap, (newValue) => {
    if (newValue && props.event.latitude && props.event.longitude) {
        nextTick(() => {
            initializeEventMap();
        });
    }
});

const initializeEventMap = () => {
    if (!window.google) {
        loadGoogleMapsForEvent();
        return;
    }

    if (!eventMapContainer.value || eventMap.value) {
        return;
    }

    const eventLocation = {
        lat: parseFloat(props.event.latitude),
        lng: parseFloat(props.event.longitude)
    };

    eventMap.value = new window.google.maps.Map(eventMapContainer.value, {
        zoom: 15,
        center: eventLocation,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: true,
    });

    // Add marker for event location
    new window.google.maps.Marker({
        position: eventLocation,
        map: eventMap.value,
        title: props.event.title,
        icon: {
            url: 'data:image/svg+xml;base64,' + btoa(`
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#dc2626">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
            `),
            scaledSize: new window.google.maps.Size(32, 32),
            anchor: new window.google.maps.Point(16, 32)
        }
    });
};

const loadGoogleMapsForEvent = () => {
    if (window.google) {
        initializeEventMap();
        return;
    }

    const apiKey = 'YOUR_GOOGLE_MAPS_API_KEY'; // This should be replaced with actual API key
    
    if (!apiKey || apiKey === 'YOUR_GOOGLE_MAPS_API_KEY') {
        eventMapContainer.value.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-500">
                <div class="text-center p-4">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-sm text-gray-600">Map not available - API key not configured</p>
                </div>
            </div>
        `;
        return;
    }

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initGoogleMapsForEvent`;
    script.async = true;
    script.defer = true;
    
    window.initGoogleMapsForEvent = () => {
        initializeEventMap();
    };
    
    document.head.appendChild(script);
};

const submit = () => {
    form.post(route('events.register', props.event.id));
};

const register = () => {
    if (isRegistering.value) return;
    
    isRegistering.value = true;
    router.post(route('events.register', props.event.id), {}, {
        onSuccess: () => {
            // Registration successful - user will be redirected by the controller
            isRegistering.value = false;
        },
        onError: (errors) => {
            console.error('Registration error:', errors);
            isRegistering.value = false;
        },
        onFinish: () => {
            isRegistering.value = false;
        }
    });
};
</script>
