<template>
  <div>
        <div v-if="delinquents.length > 0" class="overflow-x-auto py-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                Previous Attendance Infractions
            </h2>
            <table class="min-w-full divide-y divide-gray-200 shadow-md rounded-lg">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">Student Name</th>
                    <th class="px-4 py-3 text-left">Attendance Status</th>
                    <th class="px-4 py-3 text-left">Actions Taken</th>
                    <th class="px-4 py-3 text-left"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                <!-- Example Row -->
                <tr v-for="delinquent in sortedDelinquents" :key="delinquent.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ ucWords(delinquent.learner_last_name) }}, {{ ucWords(delinquent.learner_first_name) }} {{ middleInitials(delinquent.learner_middle_name ?? '') }}</td>
                    <td class="px-4 py-3 text-red-600 font-semibold">{{ ucWords(removeUnderScore(delinquent.attendance_status)) }}</td>
                    <td class="px-4 py-3 max-w-[200px] overflow-hidden text-ellipsis">
                        <span class="text-gray-500 italic">{{ delinquent.actions_taken ? ucWords(removeUnderScore(delinquent.actions_taken)) : 'N/A' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <button v-if="!delinquent.resolved" @click.prevent="showModal(delinquent)" class="px-2 py-1 bg-indigo-600 text-xs text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">
                            Resolve
                        </button>
                        <span v-else class="text-green-500 italic">Resolved</span>
                    </td>
                </tr>

                <!-- Repeat for more rows... -->
                </tbody>
            </table>
        </div>
        <SleekModal :is-visible="modal" size="4xl" @close="modal = false">
            <template #header>
                <h2 class="text-xl font-semibold text-gray-800 leadin-none">
                    Resolve Attendance Infraction
                </h2>
            </template>
            <template #body>
                <div>
                    <!-- Delinquent Student Info -->
                    <div class="flex justify-between items-start my-4">
                        <div>
                        <h2 class="text-xl font-semibold text-indigo-600">{{ ucWords(delinquent.learner_last_name) }}, {{ ucWords(delinquent.learner_first_name) }} {{ middleInitials(delinquent.learner_middle_name ?? '') }}</h2>
                        <p class="text-sm text-gray-500 mb-6">Status: <span class="font-medium text-red-400">{{ ucWords(removeUnderScore(delinquent.attendance_status)) }}</span></p>
                        </div>
                        <div class="text-right">
                        </div>
                    </div>

                    <!-- Actions Taken Checkboxes -->
                     <h2 class="text-sm font-semibold text-gray-800 mb-4 uppercase">Action/s Taken</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <label class="inline-flex items-center text-sm text-gray-700 space-x-2">
                            <input @change="updateActionsTaken($event, 'parent_notified')" type="checkbox" class="form-checkbox h-4 w-4 text-blue-500">
                            <span>Parent Notified</span>
                        </label>

                        <label class="inline-flex items-center text-sm text-gray-700 space-x-2">
                            <input @change="updateActionsTaken($event, 'guidance_intervention')" type="checkbox" class="form-checkbox h-4 w-4 text-blue-500">
                            <span>Guidance Intervention</span>
                        </label>

                        <label class="inline-flex items-center text-sm text-gray-700 space-x-2">
                            <input @change="updateActionsTaken($event, 'verbal_reprimand')" type="checkbox" class="form-checkbox h-4 w-4 text-blue-500">
                            <span>Verbal Reprimand</span>
                        </label>

                        <label class="inline-flex items-center text-sm text-gray-700 space-x-2">
                            <input @change="updateActionsTaken($event, 'homeroom_adviser_notified')" type="checkbox" class="form-checkbox h-4 w-4 text-blue-500">
                            <span>Homeroom Adviser Notified</span>
                        </label>

                        <label class="inline-flex items-center text-sm text-gray-700 space-x-2">
                            <input @change="updateActionsTaken($event, 'community_service')" type="checkbox" class="form-checkbox h-4 w-4 text-blue-500">
                            <span>Community Service</span>
                        </label>

                        <label class="inline-flex items-center text-sm text-gray-700 space-x-2">
                            <input @change="updateActionsTaken($event, 'admission_slip')" type="checkbox" class="form-checkbox h-4 w-4 text-blue-500">
                            <span>Admission Slip</span>
                        </label>
                    </div>

                    <!-- Others (Textarea) -->
                    <label class="inline-flex items-center text-sm text-gray-700 space-x-2 mt-4 mb-4">
                            <input @change="updateActionsTaken($event, 'other_actions')" type="checkbox" class="form-checkbox h-4 w-4 text-blue-500">
                            <span>Other Actions</span>
                        </label>
                    <div v-if="othersCheck">
                        <textarea :disabled="!othersCheck" v-model="otherActions" rows="3" class="w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none p-3 text-sm resize-none" placeholder="Specify other actions taken..."></textarea>
                    </div>
                    <div v-if="requiredActions">
                        <p class="text-red-500">Please select at least one action taken.</p>
                    </div>
                    <div class="flex justify-end gap-4 py-8">
                        <button @click.prevent="modal = false" class="px-5 py-2 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Close
                        </button>
                        <button @click.prevent="resolveDelinquent" class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Submit
                        </button>
                    </div>
                </div>
            </template>
        </SleekModal>
  </div>
</template>

<script lang="ts" setup>
import { removeUnderScore, ucWords, middleInitials } from '@/composables/utilities'
import { computed, ref } from 'vue';
import SleekModal from '../SleekModal.vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'



const props = defineProps({
    delinquents: {
        type: Array,
        required: true
    }
})
const actionsTaken = ref([])
const modal = ref(false)
const othersCheck = ref(false)
const otherActions = ref('')
const requiredActions = ref(false)
const delinquent = ref({
    learner_last_name: '',
    learner_first_name: '',
    learner_middle_name: '',
    attendance_status: '',
    actions_taken: '',
    resolved: false
})

const showModal = (del: any) => {
    modal.value = true
    delinquent.value = del
    actionsTaken.value = []
    otherActions.value = ''
    othersCheck.value = false
    requiredActions.value = false
}

const sortedDelinquents = computed(() => {
    return [...props.delinquents].sort((a, b) => {
        return a.learner_last_name.localeCompare(b.learner_last_name)
    })
})

const updateActionsTaken = (event: Event, action: string) => {
    const checkbox = event.target as HTMLInputElement;
    requiredActions.value = false
    if(action !== 'other_actions') {
        if (checkbox.checked) {
            actionsTaken.value.push(action);
        } else {
            actionsTaken.value = actionsTaken.value.filter((acn: string) => acn !== action);
        }
    } else {
        othersCheck.value = checkbox.checked
        if(!othersCheck.value) {
            otherActions.value = ''
        }
    }
}

const resolveDelinquent = () => {
    if(othersCheck.value && otherActions.value.trim() !== '') {
        actionsTaken.value.push(otherActions.value.trim())
    }
    if(actionsTaken.value.length === 0) {
        requiredActions.value = true
        return
    } else {
        requiredActions.value = false
        delinquent.value.actions_taken = actionsTaken.value.join(', ')
        router.put(route('club.attendance.resolve', { id: delinquent.value.id, action_taken: delinquent.value.actions_taken }),{}, {
            onSuccess: () => {
            toast.success('Attendance resolved successfully', {
                autoClose: 1000,
            })
            },
            onError: () => {
                toast.error('Failed to resolve attendance', {
                    autoClose: 1000,
                })
            },
        })
    }
    modal.value = false
}

</script>

<style>

</style>
