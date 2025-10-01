<template>
    <AppLayout>
        <!-- Hero Section with Banner -->
        <div class="relative">
            <div v-if="event.has_banner_image && event.banner_image_url" class="relative h-96 md:h-[500px] overflow-hidden rounded-2xl mb-12 shadow-2xl">
                <img :src="event.banner_image_url" 
                     :alt="event.title + ' banner'" 
                     class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-700"
                     @click="showImageModal = true"
                     title="Click to view full image">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                    <div class="max-w-4xl">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">{{ event.title }}</h1>
                        <div class="flex flex-wrap items-center space-x-6 text-lg">
                            <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">{{ formatEventDateTime(event.event_date, event.event_time) }}</span>
                            </div>
                            <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium">{{ event.location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ event.title }}</h1>
                    <div class="flex flex-wrap justify-center items-center space-x-6 text-lg text-gray-600">
                        <div class="flex items-center bg-blue-50 rounded-full px-4 py-2 mb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-blue-800">{{ formatEventDateTime(event.event_date, event.event_time) }}</span>
                        </div>
                        <div class="flex items-center bg-purple-50 rounded-full px-4 py-2 mb-2">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium text-purple-800">{{ event.location }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-3 gap-12 mb-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Event Description -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        About This Event
                    </h2>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        <p>{{ event.description }}</p>
                    </div>
                </div>

                <!-- Location Map -->
                <div v-if="event.latitude && event.longitude" class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            Event Location
                        </h2>
                        <button @click="showLocationMap = !showLocationMap"
                                class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg font-medium hover:bg-purple-200 transition-colors duration-200">
                            {{ showLocationMap ? 'Hide Map' : 'Show Map' }}
                        </button>
                    </div>
                    
                    <div v-if="showLocationMap" 
                         ref="eventMapContainer" 
                         class="w-full h-80 bg-gray-100 rounded-xl overflow-hidden shadow-inner"
                         style="min-height: 320px;">
                        <div class="flex items-center justify-center h-full text-gray-500">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto mb-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="font-medium">Loading interactive map...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Event Details Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Event Details</h3>
                    
                    <div class="space-y-6">
                        <!-- Date & Time -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Date & Time</p>
                                <p class="text-gray-600">{{ formatEventDateTime(event.event_date, event.event_time) }}</p>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Location</p>
                                <p class="text-gray-600">{{ event.location }}</p>
                            </div>
                        </div>

                        <!-- Available Spots -->
                        <div v-if="event.max_participants" class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Availability</p>
                                <p class="text-gray-600">{{ event.available_spots }} of {{ event.max_participants }} spots available</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-300" 
                                         :style="{ width: ((event.max_participants - event.available_spots) / event.max_participants * 100) + '%' }"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!$page.props.auth.user" class="border-t pt-6">
                    <h3 class="text-xl font-bold mb-4">Register for this Event</h3>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input v-model="form.name" type="text" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="form.email" type="email" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input v-model="form.password" type="password" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input v-model="form.password_confirmation" type="password" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <button type="submit" :disabled="form.processing || event.is_full"
                                class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                            {{ event.is_full ? 'Event Full' : 'Register' }}
                        </button>
                    </form>
                </div>
                <div v-else class="border-t pt-6">
                    <button @click="register" :disabled="event.is_full || isRegistering"
                            class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 flex items-center justify-center">
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
        <div v-if="showImageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" @click="showImageModal = false">
            <div class="relative max-w-4xl max-h-screen p-4">
                <button @click="showImageModal = false" class="absolute top-2 right-2 z-10 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img :src="event.banner_image_url" 
                     :alt="event.title + ' banner'" 
                     class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
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
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

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
