<template>
    <AppLayout>
        <!-- Hero Section -->
        <div class="relative py-32 mb-16 overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img 
                    src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
                    alt="People enjoying events" 
                    class="w-full h-full object-cover"
                />
            </div>
            
            <!-- Modern Gradient Overlays -->
            <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-gray-900/70 to-yellow-900/60"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-yellow-600/20 via-transparent to-transparent"></div>
            
            <!-- Animated Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full animate-pulse"></div>
                <div class="absolute top-32 right-20 w-16 h-16 bg-white rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white rounded-full animate-pulse" style="animation-delay: 2s;"></div>
                <div class="absolute bottom-32 right-1/3 w-14 h-14 bg-white rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
            </div>
            
            <div class="relative text-center text-white px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
                <!-- Main Heading with Enhanced Animation -->
                <div class="mb-12">
                    <!-- Brand Icon -->
                    <div class="flex justify-center mb-8">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-400 p-4 rounded-2xl shadow-2xl transform hover:scale-110 transition-all duration-300">
                            <svg class="w-12 h-12 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-6xl md:text-7xl lg:text-8xl font-black mb-8 tracking-tight leading-none">
                        <span class="block mb-4 bg-gradient-to-r from-white via-yellow-100 to-white bg-clip-text text-transparent drop-shadow-2xl">
                            CICTE CertChain
                        </span>
                        <span class="text-3xl md:text-4xl lg:text-5xl block bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 bg-clip-text text-transparent drop-shadow-xl">
                            Blockchain Powered Certificates of Participation
                        </span>
                    </h1>
                    
                    <!-- Enhanced Decorative Elements -->
                    <div class="flex items-center justify-center mb-8">
                        <div class="w-20 h-1 bg-gradient-to-r from-transparent via-yellow-400 to-transparent rounded-full"></div>
                        <div class="w-3 h-3 bg-yellow-400 rounded-full mx-4 animate-pulse"></div>
                        <div class="w-20 h-1 bg-gradient-to-r from-transparent via-yellow-400 to-transparent rounded-full"></div>
                    </div>
                    
                    <p class="text-lg md:text-xl lg:text-2xl opacity-95 max-w-4xl mx-auto leading-relaxed font-light text-gray-100">
                        CICTE CertChain is a secure blockchain system for issuing and verifying Certificates of Participation in CICTE events such as seminars, trainings, and workshops. Create tamper-proof digital certificates with embedded QR codes for instant validation. Integrate QR-based event attendance to scan on-site and automatically mark participants present. Deliver authentic, efficient verification for every CICTE event.
                    </p>
                </div>

                <!-- Enhanced Stats and CTA Section -->
                <div class="flex flex-col lg:flex-row items-center justify-center gap-8 mt-16">
                    <!-- Event Counter with Enhanced Design -->
                    <div class="group bg-white/10 backdrop-blur-xl border border-white/30 rounded-3xl px-10 py-6 hover:bg-white/20 transition-all duration-500 hover:scale-110 shadow-2xl hover:shadow-yellow-400/20">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 p-4 rounded-2xl mr-6 group-hover:rotate-12 transition-transform duration-500 shadow-xl">
                                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="text-4xl font-black text-white mb-1">{{ events.length }}</div>
                                <div class="text-yellow-200 font-semibold uppercase tracking-wide text-sm">Events Available</div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced CTA Button -->
                    <div v-if="events.length > 0" class="group">
                        <a href="#events" class="inline-flex items-center px-10 py-5 bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-400 hover:to-yellow-300 text-black font-bold text-lg rounded-3xl transition-all duration-500 transform hover:scale-110 hover:-translate-y-2 shadow-2xl hover:shadow-yellow-400/40 border-2 border-yellow-300/20 hover:border-yellow-200">
                            <span class="mr-4">Explore Events</span>
                            <div class="bg-black/20 rounded-full p-2 group-hover:bg-black/30 transition-colors duration-300">
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Floating Elements -->
                <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
                    <div class="absolute top-1/4 left-10 opacity-20">
                        <div class="w-8 h-8 bg-yellow-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                    </div>
                    <div class="absolute top-1/3 right-16 opacity-20">
                        <div class="w-6 h-6 bg-yellow-300 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
                    </div>
                    <div class="absolute bottom-1/4 left-1/4 opacity-20">
                        <div class="w-4 h-4 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 2s;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid Section -->
        <div id="events" class="py-24 bg-gradient-to-br from-gray-50 via-yellow-50/30 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <!-- Enhanced Section Header -->
                    <div class="flex justify-center mb-6">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-400 p-3 rounded-2xl shadow-xl">
                            <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <h2 class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent">
                        Upcoming Events
                    </h2>
                    
                    <!-- Enhanced Decorative Elements -->
                    <div class="flex items-center justify-center mb-8">
                        <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-yellow-500 to-transparent rounded-full"></div>
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mx-4"></div>
                        <div class="w-8 h-0.5 bg-yellow-400 rounded-full"></div>
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mx-4"></div>
                        <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-yellow-500 to-transparent rounded-full"></div>
                    </div>
                    
                    <p class="text-xl md:text-2xl text-gray-700 max-w-4xl mx-auto leading-relaxed font-light">
                        Join us for <span class="text-yellow-600 font-semibold">extraordinary experiences</span> and meaningful connections in your community
                    </p>
                </div>

            <!-- Enhanced No Events State -->
            <div v-if="events.length === 0" class="text-center py-24">
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-16 max-w-lg mx-auto border border-yellow-100">
                    <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-xl">
                        <svg class="w-12 h-12 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">No Events Yet</h3>
                    <p class="text-lg text-gray-600 mb-8">We're preparing something amazing! Check back soon for exciting upcoming events.</p>
                    <div class="flex items-center justify-center">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-yellow-500 rounded-full animate-bounce mx-2" style="animation-delay: 0.2s;"></div>
                        <div class="w-2 h-2 bg-yellow-600 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Events Grid -->
            <div v-else class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
                <article v-for="event in events" :key="event.id" 
                         class="group bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 hover:scale-105 overflow-hidden border border-yellow-100/50 hover:border-yellow-200">
                    <!-- Event Banner -->
                    <div class="relative overflow-hidden">
                        <div v-if="event.has_banner_image && event.banner_image_url" class="w-full h-56">
                            <img :src="event.banner_image_url" 
                                 :alt="event.title + ' banner'" 
                                 class="w-full h-full object-cover cursor-pointer group-hover:scale-110 transition-transform duration-300"
                                 @click="openImageModal(event.banner_image_url, event.title)"
                                 title="Click to view full image">
                        </div>
                        <div v-else class="w-full h-56 bg-gradient-to-br from-yellow-400 via-gray-600 to-gray-800 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <!-- Enhanced Event Status Badge -->
                        <div class="absolute top-6 right-6">
                            <div v-if="event.max_participants && event.registered_count >= event.max_participants" 
                                 class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-2xl text-xs font-bold shadow-lg backdrop-blur-sm border border-white/20">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-white rounded-full mr-2"></div>
                                    Full
                                </div>
                            </div>
                            <div v-else-if="event.max_participants && event.registered_count / event.max_participants > 0.8" 
                                 class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 rounded-2xl text-xs font-bold shadow-lg backdrop-blur-sm border border-white/20">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></div>
                                    Almost Full
                                </div>
                            </div>
                            <div v-else class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-2xl text-xs font-bold shadow-lg backdrop-blur-sm border border-white/20">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-white rounded-full mr-2"></div>
                                    Available
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <!-- Event Title -->
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-yellow-600 transition-colors duration-300 leading-tight">
                            {{ event.title }}
                        </h3>
                        
                        <!-- Event Description -->
                        <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">{{ event.description }}</p>
                        
                        <!-- Enhanced Event Details -->
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center mr-4 shadow-md group-hover:rotate-3 transition-transform duration-300">
                                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-800 text-base">{{ formatEventDateTime(event.event_date, event.event_time) }}</span>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl flex items-center justify-center mr-4 shadow-md group-hover:rotate-3 transition-transform duration-300">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-800 truncate text-base">{{ event.location }}</span>
                            </div>
                            
                            <div v-if="event.allowed_courses && event.allowed_courses.length > 0" class="flex items-center text-sm text-gray-600">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-xl flex items-center justify-center mr-4 shadow-md group-hover:rotate-3 transition-transform duration-300">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    <span v-for="course in event.allowed_courses" :key="course" 
                                          class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                        {{ course }}
                                    </span>
                                </div>
                            </div>
                            
                            <div v-if="event.max_participants" class="flex items-center text-sm text-gray-600">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center mr-4 shadow-md group-hover:rotate-3 transition-transform duration-300">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-800 text-base">{{ event.registered_count }} / {{ event.max_participants }} registered</span>
                            </div>
                        </div>
                        
                        <!-- Registration Status or CTA Button -->
                        <div v-if="event.is_registered" class="w-full bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 rounded-2xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-green-500 rounded-full p-2 mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-green-800">Registered</p>
                                        <p class="text-xs text-green-600">{{ event.registered_at }}</p>
                                    </div>
                                </div>
                                <Link :href="route('events.show', event.id)" 
                                      class="px-4 py-2 bg-green-600 text-white font-medium text-sm rounded-lg hover:bg-green-700 transition-colors">
                                    View Details
                                </Link>
                            </div>
                        </div>
                        <Link v-else :href="route('events.show', event.id)" 
                              class="block w-full text-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-400 text-black font-bold text-lg rounded-2xl hover:from-yellow-400 hover:to-yellow-300 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 shadow-xl hover:shadow-2xl border-2 border-yellow-300/30 hover:border-yellow-200">
                            <span class="flex items-center justify-center">
                                <span class="mr-3">Learn More</span>
                                <div class="bg-black/20 rounded-full p-1.5 group-hover:bg-black/30 transition-colors duration-300">
                                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </span>
                        </Link>
                    </div>
                </article>
            </div>
            </div>
        </div>
        
        <!-- Image Modal -->
        <div v-if="modalImage" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" @click="closeImageModal">
            <div class="relative max-w-4xl max-h-screen p-4">
                <button @click="closeImageModal" class="absolute top-2 right-2 z-10 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img :src="modalImage" 
                     :alt="modalTitle + ' banner'" 
                     class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
                     @click.stop>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    events: Array,
});

const modalImage = ref(null);
const modalTitle = ref('');

const openImageModal = (imageUrl, eventTitle) => {
    modalImage.value = imageUrl;
    modalTitle.value = eventTitle;
};

const closeImageModal = () => {
    modalImage.value = null;
    modalTitle.value = '';
};

// Handle escape key for modal
const handleKeydown = (event) => {
    if (event.key === 'Escape' && modalImage.value) {
        closeImageModal();
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
</script>
