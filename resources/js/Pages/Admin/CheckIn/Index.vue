<template>
    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">QR Code Check-In Scanner</h2>
                
                <!-- Scanning Options -->
                <div class="mb-6">
                    <div class="flex space-x-4 mb-4">
                        <button @click="activeTab = 'manual'" 
                                :class="activeTab === 'manual' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-4 py-2 rounded-md font-medium">
                            Manual Entry
                        </button>
                        <button @click="activeTab = 'camera'" 
                                :class="activeTab === 'camera' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'"
                                class="px-4 py-2 rounded-md font-medium">
                            Camera Scanner
                        </button>
                    </div>

                    <!-- Manual QR Code Entry -->
                    <div v-if="activeTab === 'manual'" class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Manual QR Code Entry</label>
                        <div class="flex space-x-2">
                            <input v-model="qrCode" type="text" placeholder="Enter QR Code"
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <button @click="checkIn()" :disabled="!qrCode || processing"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                                Check In
                            </button>
                        </div>
                    </div>

                    <!-- Camera Scanner -->
                    <div v-if="activeTab === 'camera'" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="block text-sm font-medium text-gray-700">QR Code Camera Scanner</label>
                            <div class="flex space-x-2">
                                <button v-if="cameraActive" @click="switchCamera" 
                                        :disabled="processing"
                                        class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 disabled:opacity-50"
                                        title="Switch Camera">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                                <button v-if="!cameraActive" @click="startCamera" 
                                        :disabled="processing"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Start Camera
                                </button>
                                <button v-if="cameraActive" @click="stopCamera" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Stop Camera
                                </button>
                            </div>
                        </div>
                        
                        <!-- Camera Preview -->
                        <div v-if="cameraActive" class="relative border rounded-lg overflow-hidden bg-black">
                            <video ref="videoElement" 
                                   class="w-full max-h-96 object-contain"
                                   autoplay 
                                   muted 
                                   playsinline>
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-48 h-48 border-2 border-white border-dashed rounded-lg opacity-50"></div>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 bg-black bg-opacity-50 text-white text-sm p-2 rounded">
                                <div class="flex items-center justify-between">
                                    <span>Position the QR code within the frame</span>
                                    <div class="flex items-center space-x-2">
                                        <div v-if="scanning" class="flex items-center space-x-1">
                                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                            <span class="text-xs">Scanning...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Camera Error Message -->
                        <div v-if="cameraError" class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <p class="font-semibold">Camera Error</p>
                            <p class="text-sm">{{ cameraError }}</p>
                            <p class="text-sm mt-2">Please ensure you have granted camera permissions and try again.</p>
                            <div class="mt-3 flex space-x-2">
                                <button @click="retryCamera" 
                                        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                    Retry Camera
                                </button>
                                <button @click="refreshPage" 
                                        class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                                    Refresh Page
                                </button>
                            </div>
                        </div>

                        <!-- Camera Instructions -->
                        <div v-if="!cameraActive && !cameraError" class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg">
                            <p class="font-semibold">How to use QR Scanner:</p>
                            <ul class="text-sm mt-2 space-y-1 list-disc list-inside">
                                <li>Click "Start Camera" to activate your device's camera</li>
                                <li>Allow camera permissions when prompted by your browser</li>
                                <li>Point the camera at the QR code on the attendee's device</li>
                                <li>The system will automatically detect and process the QR code</li>
                                <li>Use the switch camera button to toggle between front/back cameras</li>
                            </ul>
                            <p class="text-xs mt-2 text-blue-600">
                                <strong>Note:</strong> If you get a camera error, try refreshing the page and ensure camera permissions are granted.
                            </p>
                        </div>

                        <!-- Scanning Status -->
                        <div v-if="cameraActive && scanning" class="flex items-center space-x-2 text-sm text-gray-600">
                            <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Scanning for QR codes...</span>
                        </div>
                    </div>
                </div>

                <div v-if="result" class="mb-6 p-4 rounded-lg"
                     :class="result.success ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'">
                    <p class="font-semibold">{{ result.message }}</p>
                    <div v-if="result.registration" class="mt-2 text-sm">
                        <p>User: {{ result.registration.user }}</p>
                        <p>Event: {{ result.registration.event }}</p>
                        <p v-if="result.registration.checked_in_at">Checked in at: {{ result.registration.checked_in_at }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Upcoming Events</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <Link v-for="event in events" :key="event.id" 
                              :href="route('admin.events.check-ins', event.id)"
                              class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <h4 class="font-semibold">{{ event.title }}</h4>
                            <p class="text-sm text-gray-600">{{ formatEventDateTime(event.event_date, event.event_time) }}</p>
                            <p class="text-sm text-gray-600">📍 {{ event.location }}</p>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { BrowserMultiFormatReader } from '@zxing/library';

defineProps({
    events: Array,
});

// Form and UI state
const qrCode = ref('');
const result = ref(null);
const processing = ref(false);
const activeTab = ref('manual');

// Camera scanning state
const cameraActive = ref(false);
const cameraError = ref(null);
const scanning = ref(false);
const videoElement = ref(null);
const codeReader = ref(null);
const currentFacingMode = ref('environment'); // 'environment' for back camera, 'user' for front

const checkIn = async (scannedCode = null) => {
    const code = scannedCode || qrCode.value;
    if (!code) return;

    processing.value = true;
    result.value = null;

    try {
        const response = await axios.post(route('admin.check-in.scan'), {
            qr_code: String(code),
        });
        
        result.value = response.data;
        if (response.data.success) {
            qrCode.value = '';
            // If this was from camera scanning, briefly stop scanning to show result
            if (scannedCode && cameraActive.value) {
                scanning.value = false;
                setTimeout(() => {
                    if (cameraActive.value) {
                        startScanning();
                    }
                }, 2000); // Resume scanning after 2 seconds
            }
        }
    } catch (error) {
        if (error.response) {
            result.value = error.response.data;
        } else {
            result.value = {
                success: false,
                message: 'An error occurred. Please try again.',
            };
        }
    } finally {
        processing.value = false;
    }
};

// Camera functionality
const startCamera = async (facingMode = 'environment') => {
    cameraError.value = null;
    
    try {
        // Check if video element is available
        if (!videoElement.value) {
            throw new Error('Video element not found. Please refresh the page and try again.');
        }
        
        codeReader.value = new BrowserMultiFormatReader();
        
        // Request camera permissions and start video stream
        const constraints = { 
            video: { 
                facingMode: facingMode
            } 
        };
        
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        
        if (videoElement.value) {
            videoElement.value.srcObject = stream;
            
            // Add event listener before playing
            const handleMetadata = () => {
                if (videoElement.value) {
                    startScanning();
                    videoElement.value.removeEventListener('loadedmetadata', handleMetadata);
                }
            };
            
            // Only add event listener if videoElement is still valid
            if (videoElement.value) {
                videoElement.value.addEventListener('loadedmetadata', handleMetadata);
                
                try {
                    await videoElement.value.play();
                } catch (playError) {
                    console.warn('Video play failed:', playError);
                    // Some browsers require user interaction first, but we can still scan
                    setTimeout(() => {
                        if (videoElement.value && videoElement.value.readyState >= 2) {
                            startScanning();
                        }
                    }, 1000);
                }
            }
        } else {
            throw new Error('Video element became unavailable during camera initialization');
        }
        
        currentFacingMode.value = facingMode;
        cameraActive.value = true;
        scanning.value = true;
        
    } catch (error) {
        console.error('Camera start error:', error);
        // Try with user facing camera if environment camera fails
        if (facingMode === 'environment') {
            try {
                await startCamera('user');
                return;
            } catch (fallbackError) {
                console.error('Fallback camera error:', fallbackError);
            }
        }
        cameraError.value = error.message || 'Failed to access camera. Please check your browser permissions.';
        cameraActive.value = false;
        scanning.value = false;
    }
};

const switchCamera = async () => {
    if (!videoElement.value) {
        cameraError.value = 'Video element not available. Please refresh the page and try again.';
        return;
    }
    
    const newFacingMode = currentFacingMode.value === 'environment' ? 'user' : 'environment';
    stopCamera();
    
    // Wait a bit longer for cleanup before starting new camera
    setTimeout(() => {
        startCamera(newFacingMode);
    }, 300);
};

const startScanning = () => {
    if (!codeReader.value || !videoElement.value) {
        console.warn('Scanner not ready: missing codeReader or videoElement');
        return;
    }
    
    // Check if video is ready
    if (videoElement.value.readyState < 2) {
        // Video not ready yet, wait a bit and try again
        setTimeout(startScanning, 500);
        return;
    }
    
    scanning.value = true;
    
    const scan = async () => {
        if (!scanning.value || !cameraActive.value || processing.value) return;
        
        // Double-check video element is still available
        if (!videoElement.value || !codeReader.value) {
            scanning.value = false;
            return;
        }
        
        try {
            const result = await codeReader.value.decodeOnceFromVideoElement(videoElement.value);
            if (result && result.getText()) {
                const qrText = result.getText().trim();
                console.log('QR Code detected:', qrText);
                
                // Temporarily stop scanning while processing
                scanning.value = false;
                await checkIn(qrText);
            }
        } catch (error) {
            // No QR code found or scanning error, continue scanning
            if (error.message && !error.message.includes('No MultiFormat Readers')) {
                console.warn('Scanning error:', error.message);
            }
        }
        
        // Continue scanning if still active and not processing
        if (scanning.value && cameraActive.value && !processing.value) {
            setTimeout(scan, 250); // Scan every 250ms to reduce CPU usage
        }
    };
    
    scan();
};

const stopCamera = () => {
    scanning.value = false;
    cameraActive.value = false;
    cameraError.value = null;
    
    if (codeReader.value) {
        codeReader.value.reset();
        codeReader.value = null;
    }
    
    // Stop video stream
    if (videoElement.value && videoElement.value.srcObject) {
        const tracks = videoElement.value.srcObject.getTracks();
        tracks.forEach(track => track.stop());
        videoElement.value.srcObject = null;
    }
};

// Helper functions
const retryCamera = () => {
    cameraError.value = null;
    startCamera(currentFacingMode.value);
};

const refreshPage = () => {
    window.location.reload();
};

// Lifecycle hooks
onMounted(() => {
    // Ensure video element is available
    console.log('Component mounted, video element:', videoElement.value);
});

onUnmounted(() => {
    // Clean up camera when component is destroyed
    stopCamera();
});

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
