<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Create Event</h2>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input v-model="form.title" type="text" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="form.description" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Date</label>
                            <input v-model="form.event_date" type="date" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <div v-if="form.errors.event_date" class="text-red-500 text-sm mt-1">{{ form.errors.event_date }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Time</label>
                            <input v-model="form.event_time" type="time" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <div v-if="form.errors.event_time" class="text-red-500 text-sm mt-1">{{ form.errors.event_time }}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <div class="relative">
                            <input 
                                ref="locationInput"
                                v-model="form.location" 
                                type="text" 
                                required
                                placeholder="Enter event location or search on map"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <button 
                                type="button" 
                                @click="toggleMap" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-indigo-600 hover:text-indigo-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                        </div>
                        <div v-if="form.errors.location" class="text-red-500 text-sm mt-1">{{ form.errors.location }}</div>
                        
                        <!-- Map Container -->
                        <div v-show="showMap" class="mt-4">
                            <div class="bg-gray-50 rounded-lg p-4 border">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="text-sm font-medium text-gray-700">Select Location on Map</h3>
                                    <button type="button" @click="showMap = false" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div 
                                    ref="mapContainer" 
                                    class="w-full h-64 bg-gray-200 rounded-md"
                                    style="min-height: 256px;">
                                    <div class="flex items-center justify-center h-full text-gray-500">
                                        <div class="text-center">
                                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <p class="text-sm">Click to load Google Maps</p>
                                            <button 
                                                type="button" 
                                                @click="initializeMap" 
                                                class="mt-2 px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                                                Load Map
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="selectedCoordinates" class="mt-3 text-sm text-gray-600">
                                    <strong>Selected:</strong> {{ selectedCoordinates.lat.toFixed(6) }}, {{ selectedCoordinates.lng.toFixed(6) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banner Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Event Banner/Poster (optional)</label>
                        <div class="mt-1">
                            <input 
                                ref="bannerInput"
                                @change="handleBannerUpload"
                                type="file" 
                                accept="image/jpeg,image/jpg,image/png"
                                class="hidden" />
                            
                            <!-- Upload Area -->
                            <div v-if="!bannerPreview" 
                                 @click="$refs.bannerInput.click()"
                                 @dragover.prevent
                                 @drop.prevent="handleDrop"
                                 class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 cursor-pointer transition-colors">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-indigo-600">Click to upload</span>
                                        or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG up to 2MB</p>
                                </div>
                            </div>

                            <!-- Preview Area -->
                            <div v-if="bannerPreview" class="relative">
                                <img :src="bannerPreview" alt="Banner preview" class="w-full h-48 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <div class="flex space-x-2">
                                        <button @click="$refs.bannerInput.click()" type="button" class="px-3 py-1 bg-white text-gray-800 rounded text-sm hover:bg-gray-100">
                                            Change
                                        </button>
                                        <button @click="removeBanner" type="button" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.errors.banner_image" class="text-red-500 text-sm mt-1">{{ form.errors.banner_image }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Max Participants (optional)</label>
                        <input v-model="form.max_participants" type="number" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <div v-if="form.errors.max_participants" class="text-red-500 text-sm mt-1">{{ form.errors.max_participants }}</div>
                    </div>
                    <div class="flex items-center">
                        <input v-model="form.is_published" type="checkbox" id="is_published"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <label for="is_published" class="ml-2 text-sm text-gray-700">Publish immediately</label>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" :disabled="form.processing"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Create Event
                        </button>
                        <Link :href="route('admin.events.index')" 
                              class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted, nextTick } from 'vue';

const locationInput = ref(null);
const mapContainer = ref(null);
const showMap = ref(false);
const map = ref(null);
const marker = ref(null);
const autocomplete = ref(null);
const selectedCoordinates = ref(null);
const bannerInput = ref(null);
const bannerPreview = ref(null);

const form = useForm({
    title: '',
    description: '',
    event_date: '',
    event_time: '',
    location: '',
    latitude: null,
    longitude: null,
    banner_image: null,
    max_participants: null,
    is_published: false,
});

const toggleMap = () => {
    showMap.value = !showMap.value;
    if (showMap.value && !map.value) {
        nextTick(() => {
            initializeMap();
        });
    }
};

const initializeMap = () => {
    if (!window.google) {
        loadGoogleMaps();
        return;
    }

    // Default to Manila, Philippines
    const defaultLocation = { lat: 14.5995, lng: 120.9842 };
    
    map.value = new window.google.maps.Map(mapContainer.value, {
        zoom: 13,
        center: defaultLocation,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false,
    });

    // Create marker
    marker.value = new window.google.maps.Marker({
        map: map.value,
        draggable: true,
    });

    // Setup autocomplete for location input
    if (locationInput.value) {
        autocomplete.value = new window.google.maps.places.Autocomplete(locationInput.value, {
            types: ['establishment', 'geocode'],
        });

        autocomplete.value.addListener('place_changed', () => {
            const place = autocomplete.value.getPlace();
            if (place.geometry) {
                updateLocation(place);
            }
        });
    }

    // Map click listener
    map.value.addListener('click', (event) => {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();
        
        marker.value.setPosition({ lat, lng });
        selectedCoordinates.value = { lat, lng };
        form.latitude = lat;
        form.longitude = lng;

        // Reverse geocoding to get address
        const geocoder = new window.google.maps.Geocoder();
        geocoder.geocode({ location: { lat, lng } }, (results, status) => {
            if (status === 'OK' && results[0]) {
                form.location = results[0].formatted_address;
            }
        });
    });

    // Marker drag listener
    marker.value.addListener('dragend', (event) => {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();
        
        selectedCoordinates.value = { lat, lng };
        form.latitude = lat;
        form.longitude = lng;

        // Reverse geocoding to get address
        const geocoder = new window.google.maps.Geocoder();
        geocoder.geocode({ location: { lat, lng } }, (results, status) => {
            if (status === 'OK' && results[0]) {
                form.location = results[0].formatted_address;
            }
        });
    });
};

const updateLocation = (place) => {
    if (!place.geometry) return;

    const lat = place.geometry.location.lat();
    const lng = place.geometry.location.lng();
    
    form.location = place.formatted_address || place.name;
    form.latitude = lat;
    form.longitude = lng;
    selectedCoordinates.value = { lat, lng };

    if (map.value) {
        map.value.setCenter({ lat, lng });
        marker.value.setPosition({ lat, lng });
    }
};

const loadGoogleMaps = () => {
    if (window.google) {
        initializeMap();
        return;
    }

    // Check if API key is configured
    const apiKey = 'YOUR_GOOGLE_MAPS_API_KEY'; // This should be replaced with actual API key
    
    if (!apiKey || apiKey === 'YOUR_GOOGLE_MAPS_API_KEY') {
        console.warn('Google Maps API key not configured. Maps functionality will be limited.');
        // Show fallback message
        mapContainer.value.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-500">
                <div class="text-center p-4">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Google Maps Not Available</h3>
                    <p class="text-sm text-gray-600 mb-4">Google Maps API key is not configured.</p>
                    <p class="text-xs text-gray-500">Please add your Google Maps API key to enable map functionality.</p>
                </div>
            </div>
        `;
        return;
    }

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initGoogleMaps`;
    script.async = true;
    script.defer = true;
    
    script.onerror = () => {
        console.error('Failed to load Google Maps API');
        mapContainer.value.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-500">
                <div class="text-center p-4">
                    <svg class="w-12 h-12 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Maps Unavailable</h3>
                    <p class="text-sm text-gray-600">Failed to load Google Maps. Please check your internet connection.</p>
                </div>
            </div>
        `;
    };
    
    window.initGoogleMaps = () => {
        initializeMap();
    };
    
    document.head.appendChild(script);
};

const handleBannerUpload = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        alert('Please select a PNG, JPG, or JPEG image.');
        return;
    }

    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB.');
        return;
    }

    form.banner_image = file;
    
    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
        bannerPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const handleDrop = (event) => {
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        const file = files[0];
        // Simulate file input change
        const changeEvent = { target: { files: [file] } };
        handleBannerUpload(changeEvent);
    }
};

const removeBanner = () => {
    form.banner_image = null;
    bannerPreview.value = null;
    if (bannerInput.value) {
        bannerInput.value.value = '';
    }
};

const submit = () => {
    form.post(route('admin.events.store'));
};
</script>
