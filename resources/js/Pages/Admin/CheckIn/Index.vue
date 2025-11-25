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
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-6 border border-indigo-200">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                </div>
                                <label class="text-lg font-bold text-gray-800">Manual QR Code Entry</label>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4">
                                Enter the UUID code from the student's QR code display. The code is shown below their QR code image in their dashboard.
                            </p>

                            <div class="bg-white rounded-lg p-4 border-2 border-dashed border-indigo-300 mb-4">
                                <p class="text-xs font-semibold text-gray-700 mb-2">Expected Format:</p>
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded text-indigo-600 font-mono">
                                    xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
                                </code>
                                <p class="text-xs text-gray-500 mt-2">Example: a1b2c3d4-e5f6-7890-abcd-ef1234567890</p>
                            </div>

                            <div class="flex space-x-2">
                                <div class="flex-1">
                                    <input v-model="qrCode" 
                                           type="text" 
                                           placeholder="Paste or type the UUID code here"
                                           @keyup.enter="checkIn()"
                                           class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 shadow-sm font-mono text-sm" />
                                    <p v-if="qrCode" class="text-xs text-gray-500 mt-1">
                                        Length: {{ qrCode.length }} characters
                                    </p>
                                </div>
                                <button @click="checkIn()" :disabled="!qrCode || processing"
                                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-bold hover:from-indigo-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center">
                                    <svg v-if="processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ processing ? 'Processing...' : 'Check In' }}
                                </button>
                            </div>
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
                        <div v-show="cameraActive" class="relative border rounded-lg overflow-hidden bg-black">
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

                <div v-if="result" class="mb-6 p-6 rounded-lg shadow-lg"
                     :class="result.success ? 'bg-green-50 border-2 border-green-500 text-green-800' : 'bg-red-50 border-2 border-red-500 text-red-800'">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg v-if="result.success" class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <svg v-else class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-lg font-bold">{{ result.message }}</p>
                            <div v-if="result.registration" class="mt-3 space-y-1 text-sm">
                                <p class="font-semibold text-base">
                                    <span class="text-gray-600">Student:</span> {{ result.registration.user }}
                                </p>
                                <p v-if="result.registration.student_id">
                                    <span class="text-gray-600">Student ID:</span> {{ result.registration.student_id }}
                                </p>
                                <p v-if="result.registration.email">
                                    <span class="text-gray-600">Email:</span> {{ result.registration.email }}
                                </p>
                                <p v-if="result.registration.course">
                                    <span class="text-gray-600">Course:</span> {{ result.registration.course }}
                                    <span v-if="result.registration.year_level"> - {{ result.registration.year_level }}</span>
                                    <span v-if="result.registration.section"> (Section {{ result.registration.section }})</span>
                                </p>
                                <p class="font-semibold mt-2">
                                    <span class="text-gray-600">Event:</span> {{ result.registration.event }}
                                </p>
                                <p v-if="result.registration.checked_in_at" class="text-xs opacity-75 mt-2">
                                    ⏰ {{ result.registration.checked_in_at }}
                                </p>
                            </div>
                        </div>
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
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';
import { BrowserMultiFormatReader, NotFoundException } from '@zxing/library';

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
const scanInterval = ref(null);
const lastScannedCode = ref('');
const lastScannedTime = ref(0);

const checkIn = async (scannedCode = null) => {
    const code = scannedCode || qrCode.value;
    if (!code) return;

    // Prevent duplicate scans within 3 seconds
    const now = Date.now();
    if (scannedCode && code === lastScannedCode.value && (now - lastScannedTime.value) < 3000) {
        console.log('Duplicate scan ignored');
        return;
    }

    processing.value = true;
    result.value = null;

    // Update last scanned info
    if (scannedCode) {
        lastScannedCode.value = code;
        lastScannedTime.value = now;
    }

    try {
        const response = await axios.post(route('admin.check-in.scan'), {
            qr_code: String(code),
        });
        
        result.value = response.data;
        if (response.data.success) {
            qrCode.value = '';
            
            // Play success sound or provide haptic feedback
            if (window.navigator && window.navigator.vibrate) {
                window.navigator.vibrate(200);
            }
            
            // Auto-clear success message after 5 seconds
            setTimeout(() => {
                if (result.value && result.value.success) {
                    result.value = null;
                }
            }, 5000);
        } else {
            // Auto-clear error message after 4 seconds
            setTimeout(() => {
                if (result.value && !result.value.success) {
                    result.value = null;
                }
            }, 4000);
        }
    } catch (error) {
        console.error('Check-in error:', error);
        if (error.response) {
            result.value = error.response.data;
        } else {
            result.value = {
                success: false,
                message: 'An error occurred. Please check your connection and try again.',
            };
        }
        
        // Auto-clear error message after 4 seconds
        setTimeout(() => {
            if (result.value && !result.value.success) {
                result.value = null;
            }
        }, 4000);
    } finally {
        processing.value = false;
    }
};

// Camera functionality
const startCamera = async (facingMode = 'environment') => {
    cameraError.value = null;
    scanning.value = false;
    
    try {
        // Wait for DOM to be ready
        await nextTick();
        
        // Wait a bit more to ensure the video element is rendered
        await new Promise(resolve => setTimeout(resolve, 100));
        
        if (!videoElement.value) {
            throw new Error('Video element not found. Please ensure the Camera Scanner tab is active and try again.');
        }
        
        // Check if browser supports getUserMedia
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error('Your browser does not support camera access. Please use a modern browser like Chrome, Firefox, or Safari.');
        }
        
        // Initialize code reader
        if (!codeReader.value) {
            codeReader.value = new BrowserMultiFormatReader();
            console.log('ZXing code reader initialized');
        }
        
        // List available devices to check camera availability
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput');
        
        if (videoDevices.length === 0) {
            throw new Error('No camera detected on your device. Please ensure a camera is connected.');
        }
        
        console.log('Available cameras:', videoDevices.length);
        
        // Request camera permissions and start video stream
        const constraints = { 
            video: { 
                facingMode: { ideal: facingMode },
                width: { ideal: 1280 },
                height: { ideal: 720 }
            } 
        };
        
        console.log('Requesting camera with constraints:', constraints);
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        
        if (!videoElement.value) {
            // Stop the stream if video element disappeared
            stream.getTracks().forEach(track => track.stop());
            throw new Error('Video element is no longer available');
        }
        
        videoElement.value.srcObject = stream;
        
        // Set camera active first so the video container shows
        cameraActive.value = true;
        
        // Wait for video to be ready
        await new Promise((resolve, reject) => {
            const timeout = setTimeout(() => {
                reject(new Error('Video loading timeout'));
            }, 10000);
            
            const onLoadedMetadata = () => {
                clearTimeout(timeout);
                if (videoElement.value) {
                    videoElement.value.removeEventListener('loadedmetadata', onLoadedMetadata);
                }
                resolve();
            };
            
            if (videoElement.value) {
                videoElement.value.addEventListener('loadedmetadata', onLoadedMetadata);
            } else {
                reject(new Error('Video element disappeared'));
            }
        });
        
        // Play video
        try {
            await videoElement.value.play();
            console.log('Video playing successfully');
        } catch (playError) {
            console.warn('Autoplay blocked, but video is ready:', playError);
            // Many browsers block autoplay, but we can still scan
        }
        
        currentFacingMode.value = facingMode;
        
        // Start continuous scanning
        await nextTick();
        startContinuousScanning();
        
    } catch (error) {
        console.error('Camera start error:', error);
        
        // Try fallback to front camera if back camera fails
        if (facingMode === 'environment' && error.name !== 'NotAllowedError') {
            console.log('Trying front camera as fallback...');
            try {
                await startCamera('user');
                return;
            } catch (fallbackError) {
                console.error('Fallback camera error:', fallbackError);
            }
        }
        
        // Handle specific error types
        let errorMessage = 'Failed to access camera. ';
        if (error.name === 'NotAllowedError') {
            errorMessage += 'Camera permission was denied. Please allow camera access in your browser settings.';
        } else if (error.name === 'NotFoundError') {
            errorMessage += 'No camera found on your device.';
        } else if (error.name === 'NotReadableError') {
            errorMessage += 'Camera is already in use by another application.';
        } else {
            errorMessage += error.message || 'Please check your browser permissions and try again.';
        }
        
        cameraError.value = errorMessage;
        cameraActive.value = false;
        scanning.value = false;
    }
};

const startContinuousScanning = () => {
    if (!codeReader.value || !videoElement.value || !cameraActive.value) {
        console.warn('Cannot start scanning: missing requirements');
        return;
    }
    
    // Check if video is ready
    if (videoElement.value.readyState < 2) {
        console.log('Video not ready, waiting...');
        setTimeout(startContinuousScanning, 500);
        return;
    }
    
    scanning.value = true;
    console.log('Started continuous QR code scanning');
    
    // Clear any existing interval
    if (scanInterval.value) {
        clearInterval(scanInterval.value);
    }
    
    // Continuous scanning loop
    const performScan = async () => {
        if (!scanning.value || !cameraActive.value || processing.value) {
            return;
        }
        
        // Double-check elements are still available
        if (!videoElement.value || !codeReader.value) {
            console.warn('Scanning elements no longer available');
            scanning.value = false;
            return;
        }
        
        try {
            const result = await codeReader.value.decodeOnceFromVideoElement(videoElement.value);
            
            if (result && result.getText()) {
                const qrText = result.getText().trim();
                console.log('✓ QR Code detected:', qrText);
                
                // Process the QR code
                await checkIn(qrText);
            }
        } catch (error) {
            // NotFoundException is expected when no QR code is in view
            if (!(error instanceof NotFoundException)) {
                // Only log unexpected errors
                if (error.message && !error.message.includes('No MultiFormat Readers')) {
                    console.warn('Scan error:', error.message);
                }
            }
        }
    };
    
    // Scan every 300ms for better performance
    scanInterval.value = setInterval(performScan, 300);
};

const switchCamera = async () => {
    if (!videoElement.value) {
        cameraError.value = 'Cannot switch camera. Please refresh the page and try again.';
        return;
    }
    
    const newFacingMode = currentFacingMode.value === 'environment' ? 'user' : 'environment';
    console.log('Switching camera to:', newFacingMode);
    
    stopCamera();
    
    // Wait for cleanup before starting new camera
    setTimeout(() => {
        startCamera(newFacingMode);
    }, 500);
};

const stopCamera = () => {
    console.log('Stopping camera...');
    
    scanning.value = false;
    cameraActive.value = false;
    
    // Clear scan interval
    if (scanInterval.value) {
        clearInterval(scanInterval.value);
        scanInterval.value = null;
    }
    
    // Reset code reader
    if (codeReader.value) {
        try {
            codeReader.value.reset();
        } catch (e) {
            console.warn('Error resetting code reader:', e);
        }
    }
    
    // Stop video stream and release camera
    if (videoElement.value && videoElement.value.srcObject) {
        const tracks = videoElement.value.srcObject.getTracks();
        tracks.forEach(track => {
            track.stop();
            console.log('Track stopped:', track.kind);
        });
        videoElement.value.srcObject = null;
    }
    
    // Reset last scanned info
    lastScannedCode.value = '';
    lastScannedTime.value = 0;
    
    console.log('Camera stopped');
};

// Helper functions
const retryCamera = () => {
    console.log('Retrying camera...');
    cameraError.value = null;
    startCamera(currentFacingMode.value);
};

const refreshPage = () => {
    window.location.reload();
};

// Lifecycle hooks
onMounted(() => {
    console.log('Check-in scanner component mounted');
    console.log('Video element available:', !!videoElement.value);
});

onUnmounted(() => {
    console.log('Check-in scanner component unmounting');
    // Clean up camera and intervals
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
