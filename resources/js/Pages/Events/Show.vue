<template>
    <AppLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-4">{{ event.title }}</h1>
                <div class="prose max-w-none mb-6">
                    <p>{{ event.description }}</p>
                </div>
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-700">
                        <span class="font-semibold w-32">Date:</span>
                        <span>{{ event.event_date }} at {{ event.event_time }}</span>
                    </div>
                    <div class="flex items-center text-gray-700">
                        <span class="font-semibold w-32">Location:</span>
                        <span>{{ event.location }}</span>
                    </div>
                    <div v-if="event.max_participants" class="flex items-center text-gray-700">
                        <span class="font-semibold w-32">Available Spots:</span>
                        <span>{{ event.available_spots }} of {{ event.max_participants }}</span>
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
                    <button @click="register" :disabled="event.is_full"
                            class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                        {{ event.is_full ? 'Event Full' : 'Register for Event' }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    event: Object,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('events.register', props.event.id));
};

const register = () => {
    router.post(route('events.register', props.event.id));
};
</script>
