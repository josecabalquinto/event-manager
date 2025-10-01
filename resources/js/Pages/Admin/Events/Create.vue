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
                        <input v-model="form.location" type="text" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <div v-if="form.errors.location" class="text-red-500 text-sm mt-1">{{ form.errors.location }}</div>
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

const form = useForm({
    title: '',
    description: '',
    event_date: '',
    event_time: '',
    location: '',
    max_participants: null,
    is_published: false,
});

const submit = () => {
    form.post(route('admin.events.store'));
};
</script>
