<template>
    <AppLayout>
        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-br from-white via-yellow-50/50 to-amber-50/30 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-yellow-200/20">
                <div class="flex items-center mb-2">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg transform -rotate-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent">Profile Settings</h1>
                </div>
                <p class="text-gray-600 ml-16">Update your personal information and student details</p>
            </div>
        </div>

        <!-- Success Message -->
        <div v-if="status === 'profile-updated'" class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 p-4 rounded-r-2xl shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 font-medium">Profile updated successfully!</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-white via-yellow-50/30 to-amber-50/20 backdrop-blur-sm rounded-3xl shadow-xl border border-yellow-200/20">
            <div class="p-8">
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Basic Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Basic Information</h3>
                        </div>

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
                    </div>

                    <!-- Student Information Section -->
                    <div class="border-t border-yellow-200/50 pt-8">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-indigo-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Student Information</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Student ID</label>
                                <input v-model="form.student_id" type="text"
                                       placeholder="e.g., 20-1956"
                                       pattern="[0-9]{2}-[0-9]{4}"
                                       title="Please enter student ID in format: XX-XXXX (e.g., 20-1956)"
                                       class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30" />
                                <div v-if="form.errors.student_id" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.student_id }}</div>
                                <p class="text-xs text-gray-500 mt-2">Format: XX-XXXX (e.g., 20-1956)</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Course</label>
                                    <select v-model="form.course"
                                            class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30">
                                        <option value="">Select Course</option>
                                        <option v-for="course in courses" :key="course" :value="course">{{ course }}</option>
                                    </select>
                                    <div v-if="form.errors.course" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.course }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Year Level</label>
                                    <select v-model="form.year_level"
                                            class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30">
                                        <option value="">Select Year</option>
                                        <option v-for="year in year_levels" :key="year" :value="year">{{ year }}</option>
                                    </select>
                                    <div v-if="form.errors.year_level" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.year_level }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
                                    <input v-model="form.section" type="text"
                                           placeholder="e.g., A, B, C"
                                           class="w-full px-4 py-3 rounded-2xl border-2 border-yellow-200 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-200/50 transition-all duration-300 bg-gradient-to-r from-white to-yellow-50/30" />
                                    <div v-if="form.errors.section" class="text-red-500 text-sm mt-2 font-medium">{{ form.errors.section }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-yellow-200/50">
                        <Link :href="route('dashboard')" 
                              class="px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-2xl font-semibold hover:from-gray-200 hover:to-gray-300 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </Link>
                        <button type="submit" :disabled="form.processing"
                                class="px-8 py-3 bg-gradient-to-r from-yellow-400 to-amber-500 text-white rounded-2xl font-bold text-lg hover:from-yellow-500 hover:to-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ form.processing ? 'Updating...' : 'Update Profile' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    user: Object,
    courses: Array,
    year_levels: Array,
});

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    student_id: props.user?.student_id || '',
    course: props.user?.course || '',
    year_level: props.user?.year_level || '',
    section: props.user?.section || '',
});

const submit = () => {
    form.patch(route('profile.update'));
};
</script>
