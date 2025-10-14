<template>
    <AdminLayout title="Create Certificates">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Create & Assign Certificates</h2>
                                <p class="text-gray-600">Generate certificates for event participants who have completed the event</p>
                            </div>
                            <Link 
                                :href="route('admin.certificates.index')"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200"
                            >
                                View All Certificates
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Event Selection -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Event</h3>
                        
                        <!-- No Events Available -->
                        <div v-if="events.length === 0" class="text-center py-12">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">No Events Available</h4>
                            <p class="text-gray-600 mb-6">There are no published events available for certificate generation.</p>
                            <Link 
                                :href="route('admin.events.create')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-200"
                            >
                                Create New Event
                            </Link>
                        </div>

                        <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <div 
                                v-for="event in events" 
                                :key="event.id"
                                class="border rounded-lg p-4 cursor-pointer transition-all duration-200"
                                :class="selectedEvent && selectedEvent.id === event.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                                @click="selectEvent(event.id)"
                            >
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-800">{{ event.title }}</h4>
                                    <span 
                                        v-if="event.has_certificate"
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
                                    >
                                        Certificate Enabled
                                    </span>
                                    <span 
                                        v-else
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"
                                    >
                                        No Certificate
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ formatDate(event.event_date) }}</p>
                                <div class="flex justify-between text-sm">
                                    <span class="text-blue-600">{{ event.checked_in_count }} checked in</span>
                                    <span class="text-green-600">{{ event.certificates_generated_count }} certificates</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Creation Form -->
                <div v-if="selectedEvent" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Create Certificates for {{ selectedEvent.title }}</h3>
                        
                        <form @submit.prevent="createCertificates">
                            <!-- Certificate Settings -->
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Completion Date</label>
                                    <input 
                                        v-model="form.completion_date" 
                                        type="date" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Title (Optional)</label>
                                    <input 
                                        v-model="form.certificate_title" 
                                        type="text" 
                                        :placeholder="selectedEvent.title"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>
                            </div>

                            <!-- Custom Template -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Certificate Template (Optional)
                                    <span class="text-gray-500 text-xs">Use {participant_name}, {event_title}, {event_date} as placeholders</span>
                                </label>
                                <textarea 
                                    v-model="form.certificate_template" 
                                    :placeholder="selectedEvent.certificate_template || 'This is to certify that {participant_name} has successfully completed {event_title} on {event_date}.'"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                    rows="4"
                                ></textarea>
                            </div>

                            <!-- Certificate Preview -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Certificate Preview</label>
                                    <button 
                                        type="button"
                                        @click="showPreview = !showPreview"
                                        class="text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded"
                                    >
                                        {{ showPreview ? 'Hide Preview' : 'Show Preview' }}
                                    </button>
                                </div>
                                
                                <div v-if="showPreview" class="border rounded-lg p-6 bg-gray-50">
                                    <div class="max-w-2xl mx-auto bg-white border-4 border-gray-800 rounded-lg p-6 shadow-lg">
                                        <!-- Certificate Header -->
                                        <div class="text-center mb-6">
                                            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                                                {{ certificatePreviewTitle }}
                                            </h1>
                                            <div class="w-20 h-1 bg-gradient-to-r from-yellow-400 to-yellow-600 mx-auto"></div>
                                        </div>

                                        <!-- Certificate Body -->
                                        <div class="text-center space-y-4">
                                            <p class="text-base text-gray-600">This is to certify that</p>
                                            
                                            <h2 class="text-xl font-bold text-gray-800 border-b-2 border-gray-300 pb-2 inline-block">
                                                [Participant Name]
                                            </h2>
                                            
                                            <div class="text-sm text-gray-700 leading-relaxed" v-html="certificatePreviewText"></div>
                                        </div>

                                        <!-- Certificate Footer -->
                                        <div class="mt-8">
                                            <div v-if="selectedEvent && selectedEvent.certificate_signatories && selectedEvent.certificate_signatories.length > 0" 
                                                 :class="[
                                                     'flex items-end text-xs',
                                                     selectedEvent.certificate_signatories.length === 1 ? 'justify-center' : 
                                                     selectedEvent.certificate_signatories.length === 2 ? 'justify-between' : 'justify-around'
                                                 ]">
                                                <div v-for="(signatory, index) in selectedEvent.certificate_signatories" :key="index" class="text-center">
                                                    <div class="w-24 border-b border-gray-400 mb-1"></div>
                                                    <p class="text-gray-800 font-semibold">{{ signatory.name || 'Signatory' }}</p>
                                                    <p class="text-gray-600 text-xs">{{ signatory.title || 'Position' }}</p>
                                                </div>
                                            </div>
                                            <div v-else class="flex justify-between items-end text-xs">
                                                <div class="text-center">
                                                    <div class="w-24 border-b border-gray-400 mb-1"></div>
                                                    <p class="text-gray-800 font-semibold">Authorized Signatory</p>
                                                    <p class="text-gray-600 text-xs">Position</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <p class="text-xs text-gray-500">Preview - actual certificate styling may vary</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Participant Selection -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Select Participants ({{ selectedEvent.participants.length }} eligible)
                                    </label>
                                    <div class="space-x-2">
                                        <button 
                                            type="button" 
                                            @click="selectAllParticipants"
                                            class="text-sm bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded"
                                        >
                                            Select All
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="deselectAllParticipants"
                                            class="text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1 rounded"
                                        >
                                            Deselect All
                                        </button>
                                    </div>
                                </div>
                                
                                <div v-if="selectedEvent.participants.length === 0" class="text-center py-8 text-gray-500">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-lg font-semibold mb-2">No Eligible Participants</p>
                                    <p>All checked-in participants already have certificates, or no participants have checked in yet.</p>
                                </div>

                                <div v-else class="border rounded-lg">
                                    <div class="max-h-80 overflow-y-auto">
                                        <div 
                                            v-for="participant in selectedEvent.participants" 
                                            :key="participant.id"
                                            class="flex items-center justify-between p-4 border-b last:border-b-0 hover:bg-gray-50"
                                        >
                                            <div class="flex items-center">
                                                <input 
                                                    :id="`participant-${participant.id}`"
                                                    v-model="form.participants" 
                                                    :value="participant.id" 
                                                    type="checkbox"
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                />
                                                <label 
                                                    :for="`participant-${participant.id}`"
                                                    class="ml-3 cursor-pointer"
                                                >
                                                    <div class="font-medium text-gray-900">{{ participant.user_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ participant.user_email }}</div>
                                                </label>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600">Checked in:</div>
                                                <div class="text-sm font-medium text-green-600">{{ formatDateTime(participant.checked_in_at) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-4">
                                <Link 
                                    :href="route('admin.certificates.index')"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-6 rounded-md transition-colors duration-200"
                                >
                                    Cancel
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="form.participants.length === 0 || processing"
                                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium py-2 px-6 rounded-md transition-colors duration-200"
                                >
                                    <span v-if="processing">Creating...</span>
                                    <span v-else>Create {{ form.participants.length }} Certificate{{ form.participants.length !== 1 ? 's' : '' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    events: {
        type: Array,
        default: () => []
    },
    selectedEvent: {
        type: Object,
        default: null
    }
})

const processing = ref(false)
const showPreview = ref(false)

const form = ref({
    event_id: props.selectedEvent?.id || null,
    completion_date: new Date().toISOString().split('T')[0],
    certificate_title: '',
    certificate_template: '',
    participants: []
})

// Certificate Preview Computed Properties
const certificatePreviewTitle = computed(() => {
    return form.value.certificate_title || props.selectedEvent?.certificate_title || 'Certificate of Completion'
})

const certificatePreviewText = computed(() => {
    let template = form.value.certificate_template || 
                   props.selectedEvent?.certificate_template || 
                   'has successfully completed {event_title} on {event_date}.'
    
    // Replace placeholders for preview
    template = template.replace('{participant_name}', '[Participant Name]')
    template = template.replace('{event_title}', props.selectedEvent?.title || '[Event Title]')
    template = template.replace('{event_date}', formatDate(form.value.completion_date))
    
    return template
})

function selectEvent(eventId) {
    router.get(route('admin.certificates.create'), { event_id: eventId })
}

function selectAllParticipants() {
    if (props.selectedEvent) {
        form.value.participants = props.selectedEvent.participants.map(p => p.id)
    }
}

function deselectAllParticipants() {
    form.value.participants = []
}

function createCertificates() {
    if (form.value.participants.length === 0) {
        alert('Please select at least one participant.')
        return
    }

    processing.value = true
    
    router.post(route('admin.certificates.store'), {
        event_id: props.selectedEvent.id,
        completion_date: form.value.completion_date,
        certificate_title: form.value.certificate_title || null,
        certificate_template: form.value.certificate_template || null,
        participants: form.value.participants
    }, {
        onFinish: () => {
            processing.value = false
        }
    })
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

function formatDateTime(dateTimeString) {
    return new Date(dateTimeString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>