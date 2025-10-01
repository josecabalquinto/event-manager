<template>
    <AppLayout>
        <!-- Hero Section -->
        <div class="relative py-24 mb-12 overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img 
                    src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
                    alt="People enjoying events" 
                    class="w-full h-full object-cover"
                />
            </div>
            
            <!-- Gradient Overlays -->
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-purple-900/85 to-indigo-900/90"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/30"></div>
            
            <!-- Animated Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full animate-pulse"></div>
                <div class="absolute top-32 right-20 w-16 h-16 bg-white rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white rounded-full animate-pulse" style="animation-delay: 2s;"></div>
                <div class="absolute bottom-32 right-1/3 w-14 h-14 bg-white rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
            </div>
            
            <div class="relative text-center text-white px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
                <!-- Main Heading with Animation -->
                <div class="mb-8">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 tracking-tight leading-tight">
                        <span class="block mb-2 bg-gradient-to-r from-white via-blue-100 to-white bg-clip-text text-transparent animate-pulse">
                            Discover Amazing
                        </span>
                        <span class="block bg-gradient-to-r from-yellow-300 via-white to-purple-200 bg-clip-text text-transparent">
                            Events
                        </span>
                    </h1>
                    
                    <!-- Decorative Line -->
                    <div class="w-32 h-1 bg-gradient-to-r from-yellow-400 to-purple-400 mx-auto mb-6 rounded-full shadow-lg"></div>
                    
                    <p class="text-xl md:text-2xl opacity-95 max-w-3xl mx-auto leading-relaxed font-light">
                        Connect with your community and create unforgettable experiences that bring people together
                    </p>
                </div>

                <!-- Stats and CTA Section -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mt-10">
                    <!-- Event Counter -->
                    <div class="group bg-white/15 backdrop-blur-md border border-white/20 rounded-2xl px-8 py-4 hover:bg-white/25 transition-all duration-300 hover:scale-105 shadow-2xl">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-3 rounded-xl mr-4 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="text-3xl font-bold text-white">{{ events.length }}</div>
                                <div class="text-sm text-white/80 font-medium">Events Available</div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div v-if="events.length > 0" class="group">
                        <a href="#events" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 shadow-2xl hover:shadow-purple-500/25">
                            <span class="mr-3">Explore Events</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Floating Elements -->
                <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
                    <div class="absolute top-1/4 left-10 opacity-20">
                        <div class="w-8 h-8 bg-yellow-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                    </div>
                    <div class="absolute top-1/3 right-16 opacity-20">
                        <div class="w-6 h-6 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
                    </div>
                    <div class="absolute bottom-1/4 left-1/4 opacity-20">
                        <div class="w-4 h-4 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 2s;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div id="events" class="py-20 bg-gradient-to-br from-gray-50 via-white to-blue-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center mb-4">
                        <div class="w-12 h-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mr-4"></div>
                        <h2 class="text-5xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Upcoming Events</h2>
                        <div class="w-12 h-1 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full ml-4"></div>
                    </div>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Join us for extraordinary experiences and meaningful connections in your community
                    </p>
                </div>

            <div v-if="events.length === 0" class="text-center py-16">
                <div class="bg-white rounded-2xl shadow-lg p-12 max-w-md mx-auto">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Events Yet</h3>
                    <p class="text-gray-600">Check back soon for exciting upcoming events!</p>
                </div>
            </div>

            <div v-else class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <article v-for="event in events" :key="event.id" 
                         class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <!-- Event Banner -->
                    <div class="relative overflow-hidden">
                        <div v-if="event.has_banner_image && event.banner_image_url" class="w-full h-56">
                            <img :src="event.banner_image_url" 
                                 :alt="event.title + ' banner'" 
                                 class="w-full h-full object-cover cursor-pointer group-hover:scale-110 transition-transform duration-300"
                                 @click="openImageModal(event.banner_image_url, event.title)"
                                 title="Click to view full image">
                        </div>
                        <div v-else class="w-full h-56 bg-gradient-to-br from-blue-400 via-purple-500 to-indigo-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <!-- Event Status Badge -->
                        <div class="absolute top-4 right-4">
                            <div v-if="event.max_participants && event.registered_count >= event.max_participants" 
                                 class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                Full
                            </div>
                            <div v-else-if="event.max_participants && event.registered_count / event.max_participants > 0.8" 
                                 class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                Almost Full
                            </div>
                            <div v-else class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                Available
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Event Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                            {{ event.title }}
                        </h3>
                        
                        <!-- Event Description -->
                        <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">{{ event.description }}</p>
                        
                        <!-- Event Details -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-500">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">{{ formatEventDateTime(event.event_date, event.event_time) }}</span>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700 truncate">{{ event.location }}</span>
                            </div>
                            
                            <div v-if="event.max_participants" class="flex items-center text-sm text-gray-500">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">{{ event.registered_count }} / {{ event.max_participants }} registered</span>
                            </div>
                        </div>
                        
                        <!-- CTA Button -->
                        <Link :href="route('events.show', event.id)" 
                              class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center">
                                Learn More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
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
