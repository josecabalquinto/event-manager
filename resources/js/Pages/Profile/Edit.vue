<template>
    <AppLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Profile Settings</h2>
                <form @submit.prevent="submit" class="space-y-6">
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

                    <!-- Student Information Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Student Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Student ID</label>
                                <input v-model="form.student_id" type="text"
                                       placeholder="e.g., 20-1956"
                                       pattern="[0-9]{2}-[0-9]{4}"
                                       title="Please enter student ID in format: XX-XXXX (e.g., 20-1956)"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                <div v-if="form.errors.student_id" class="text-red-500 text-sm mt-1">{{ form.errors.student_id }}</div>
                                <p class="text-xs text-gray-500 mt-1">Format: XX-XXXX (e.g., 20-1956)</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Course</label>
                                    <select v-model="form.course"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select Course</option>
                                        <option v-for="course in courses" :key="course" :value="course">{{ course }}</option>
                                    </select>
                                    <div v-if="form.errors.course" class="text-red-500 text-sm mt-1">{{ form.errors.course }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Year Level</label>
                                    <select v-model="form.year_level"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select Year</option>
                                        <option v-for="year in year_levels" :key="year" :value="year">{{ year }}</option>
                                    </select>
                                    <div v-if="form.errors.year_level" class="text-red-500 text-sm mt-1">{{ form.errors.year_level }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Section</label>
                                    <input v-model="form.section" type="text"
                                           placeholder="e.g., A, B, C"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                    <div v-if="form.errors.section" class="text-red-500 text-sm mt-1">{{ form.errors.section }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" :disabled="form.processing"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Update Profile
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

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
