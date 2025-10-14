<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Edit Event</h2>
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
                            
                            <!-- Current Banner Display -->
                            <div v-if="currentBannerUrl && !bannerPreview && !form.remove_banner_image" class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current banner:</p>
                                <div class="relative">
                                    <img :src="currentBannerUrl" alt="Current banner" class="w-full h-48 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                        <div class="flex space-x-2">
                                            <button @click="$refs.bannerInput.click()" type="button" class="px-3 py-1 bg-white text-gray-800 rounded text-sm hover:bg-gray-100">
                                                Change
                                            </button>
                                            <button @click="removeCurrentBanner" type="button" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Area (shown when no current banner or after removal) -->
                            <div v-if="(!currentBannerUrl || form.remove_banner_image) && !bannerPreview" 
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

                            <!-- New Banner Preview -->
                            <div v-if="bannerPreview" class="relative">
                                <img :src="bannerPreview" alt="New banner preview" class="w-full h-48 object-cover rounded-lg">
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

                            <!-- Removal Notice -->
                            <div v-if="form.remove_banner_image && !bannerPreview" class="text-sm text-gray-600 mt-2 p-2 bg-yellow-50 rounded border">
                                Banner will be removed when you save changes.
                                <button @click="undoRemoveBanner" type="button" class="ml-2 text-indigo-600 hover:text-indigo-800">Undo</button>
                            </div>
                        </div>
                        <div v-if="form.errors.banner_image" class="text-red-500 text-sm mt-1">{{ form.errors.banner_image }}</div>
                    </div>

                    <!-- Event Type and Organizer -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Type</label>
                            <select v-model="form.event_type_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Event Type</option>
                                <option v-for="eventType in eventTypes" :key="eventType.id" :value="eventType.id">
                                    {{ eventType.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.event_type_id" class="text-red-500 text-sm mt-1">{{ form.errors.event_type_id }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Organizer</label>
                            <select v-model="form.event_organizer_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Organizer</option>
                                <option v-for="organizer in eventOrganizers" :key="organizer.id" :value="organizer.id">
                                    {{ organizer.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.event_organizer_id" class="text-red-500 text-sm mt-1">{{ form.errors.event_organizer_id }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Max Participants (optional)</label>
                        <input v-model="form.max_participants" type="number" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <div v-if="form.errors.max_participants" class="text-red-500 text-sm mt-1">{{ form.errors.max_participants }}</div>
                    </div>
                    <!-- Certificate Configuration -->
                    <div class="border-t pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <input v-model="form.has_certificate" type="checkbox" id="has_certificate"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                <label for="has_certificate" class="ml-2 text-sm font-medium text-gray-700">
                                    Enable Certificates for this Event
                                </label>
                            </div>
                            <div v-if="form.has_certificate" class="text-sm text-gray-600">
                                {{ event.registrations_count || 0 }} registrations • {{ event.checked_in_count || 0 }} checked in • {{ event.certificates_generated_count || 0 }} certificates
                            </div>
                        </div>

                        <div v-if="form.has_certificate" class="space-y-4 pl-6 border-l-2 border-indigo-200">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Certificate Title (Optional)</label>
                                <input v-model="form.certificate_title" type="text" 
                                       :placeholder="form.title || 'Same as event title'"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                <p class="text-xs text-gray-500 mt-1">Leave blank to use event title</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Certificate Description</label>
                                <textarea v-model="form.certificate_description" rows="2" 
                                          placeholder="Brief description of what this certificate represents"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Certificate Template</label>
                                <textarea v-model="form.certificate_template" rows="4" 
                                          placeholder="This is to certify that {participant_name} has successfully completed {event_title} on {event_date}."
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    Use placeholders: {participant_name}, {event_title}, {event_date}, {completion_date}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Signatories</label>
                                <div class="space-y-3">
                                    <div v-for="(signatory, index) in form.certificate_signatories" :key="index" 
                                         class="flex space-x-3 items-start">
                                        <div class="flex-1">
                                            <input v-model="signatory.name" type="text" 
                                                   placeholder="Signatory Name"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                        </div>
                                        <div class="flex-1">
                                            <input v-model="signatory.title" type="text" 
                                                   placeholder="Title/Position"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                        </div>
                                        <button type="button" @click="removeSignatory(index)" 
                                                class="text-red-500 hover:text-red-700 p-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <button type="button" @click="addSignatory" 
                                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        + Add Signatory
                                    </button>
                                </div>
                            </div>

                            <!-- Certificate Management Actions -->
                            <div v-if="event.registrations_count > 0" class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-800 mb-3">Certificate Management</h4>
                                <div class="flex flex-wrap gap-3">
                                    <Link v-if="event.checked_in_count > 0" 
                                          :href="route('admin.certificates.create', { event_id: event.id })"
                                          class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                        Generate Certificates ({{ event.checked_in_count }} eligible)
                                    </Link>
                                    <Link v-if="event.certificates_generated_count > 0"
                                          :href="route('admin.certificates.index', { event_id: event.id })"
                                          class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                        View Certificates ({{ event.certificates_generated_count }})
                                    </Link>
                                    <span v-if="event.checked_in_count === 0" class="text-sm text-gray-500 py-2">
                                        No participants checked in yet
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input v-model="form.is_published" type="checkbox" id="is_published"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <label for="is_published" class="ml-2 text-sm text-gray-700">Published</label>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" :disabled="form.processing"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Update Event
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
import { ref, nextTick, onMounted } from 'vue';

const props = defineProps({
    event: Object,
    eventTypes: Array,
    eventOrganizers: Array,
});

const locationInput = ref(null);
const mapContainer = ref(null);
const showMap = ref(false);
const map = ref(null);
const marker = ref(null);
const autocomplete = ref(null);
const selectedCoordinates = ref(null);
const bannerInput = ref(null);
const bannerPreview = ref(null);
const currentBannerUrl = ref(props.event.banner_image_url);

const form = useForm({
    title: props.event.title,
    description: props.event.description,
    event_date: props.event.event_date,
    event_time: props.event.event_time,
    location: props.event.location,
    latitude: props.event.latitude,
    longitude: props.event.longitude,
    banner_image: null,
    remove_banner_image: false,
    max_participants: props.event.max_participants,
    event_type_id: props.event.event_type_id,
    event_organizer_id: props.event.event_organizer_id,
    is_published: props.event.is_published,
    // Certificate fields
    has_certificate: props.event.has_certificate,
    certificate_title: props.event.certificate_title || '',
    certificate_description: props.event.certificate_description || '',
    certificate_template: props.event.certificate_template || '',
    certificate_signatories: props.event.certificate_signatories?.length ? props.event.certificate_signatories : [{ name: '', title: '' }],
});

// Signatory management functions
const addSignatory = () => {
    form.certificate_signatories.push({ name: '', title: '' });
};

const removeSignatory = (index) => {
    if (form.certificate_signatories.length > 1) {
        form.certificate_signatories.splice(index, 1);
    }
};

// Initialize coordinates if they exist
onMounted(() => {
    if (props.event.latitude && props.event.longitude) {
        selectedCoordinates.value = {
            lat: parseFloat(props.event.latitude),
            lng: parseFloat(props.event.longitude)
        };
    }
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

    // Use existing coordinates or default to Manila
    const defaultLocation = selectedCoordinates.value || { lat: 14.5995, lng: 120.9842 };
    
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
        position: selectedCoordinates.value ? defaultLocation : null,
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

    const apiKey = 'YOUR_GOOGLE_MAPS_API_KEY';
    
    if (!apiKey || apiKey === 'YOUR_GOOGLE_MAPS_API_KEY') {
        mapContainer.value.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-500">
                <div class="text-center p-4">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Google Maps Not Available</h3>
                    <p class="text-sm text-gray-600">API key not configured.</p>
                </div>
            </div>
        `;
        return;
    }

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initGoogleMapsEdit`;
    script.async = true;
    script.defer = true;
    
    window.initGoogleMapsEdit = () => {
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
    form.remove_banner_image = false;
    
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
    // If we had a current banner, restore it
    if (currentBannerUrl.value) {
        form.remove_banner_image = false;
    }
};

const removeCurrentBanner = () => {
    form.remove_banner_image = true;
    form.banner_image = null;
    bannerPreview.value = null;
};

const undoRemoveBanner = () => {
    form.remove_banner_image = false;
};

const submit = () => {
    const errorHandler = (errors) => {
        if (errors.message && errors.message.includes('419')) {
            // CSRF token expired, reload the page to get a fresh token
            window.location.reload();
        }
    };

    // Check if we have a file upload or need special handling
    if (form.banner_image || form.remove_banner_image) {
        // Use the special POST route for file uploads
        form.post(route('admin.events.update-with-files', props.event.id), {
            preserveScroll: true,
            onError: errorHandler
        });
    } else {
        // Use regular PUT for normal updates
        form.put(route('admin.events.update', props.event.id), {
            preserveScroll: true,
            onError: errorHandler
        });
    }
};
</script>
