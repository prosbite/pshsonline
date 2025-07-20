<template>
    <div class="w-full md:w-4/5 bg-white p-8 rounded-xl shadow-md border border-gray-200">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-6">
    <div>
      <h3 class="text-2xl font-semibold text-gray-800">Enrolled Students</h3>
      <p class="text-gray-600 text-sm">Filter and view enrolled students by section.</p>
    </div>
    <div class="pb-8">
      <!-- <label for="sectionFilter" class="sr-only">Filter by section</label> -->
      <select
        id="sectionFilter"
        v-model="selectedSection"
        class="w-full md:w-64 px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
      >
        <option :value="null" disabled>Select Section</option>
        <option
          v-for="section in props.sections"
          :key="section.id"
          :value="section.id"
        >
          {{ parseInt(section.grade_level_id) + 6 }} - {{ section.section_name }}
        </option>
      </select>
    </div>
  </div>

  <div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr
          v-for="(learner, index) in filteredLearners"
          :key="learner.id"
        >
            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ index + 1 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ ucWords(learner?.learner?.last_name) + ', ' + ucWords(learner?.learner?.first_name) + ' ' + middleInitials(learner?.learner?.middle_name?? '') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ parseInt(learner?.section?.grade_level_id) + 6 }} - {{ learner?.section?.section_name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ ucWords(learner?.learner?.gender) }}
            </td>
            <td>
                <button @click="editLearner(learner)" class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-green-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <svg width="24" height="24" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="6" y="6" width="30" height="36" rx="2" ry="2" stroke="black" stroke-width="2" fill="white"/>
                        <line x1="10" y1="14" x2="30" y2="14" stroke="black" stroke-width="2"/>
                        <line x1="10" y1="20" x2="30" y2="20" stroke="black" stroke-width="2"/>
                        <line x1="10" y1="26" x2="24" y2="26" stroke="black" stroke-width="2"/>
                        <path d="M32 30l6 6-10 10H22v-6l10-10z" stroke="black" stroke-width="2" fill="none"/>
                        <line x1="30" y1="32" x2="36" y2="38" stroke="black" stroke-width="2"/>
                    </svg>

                </button>
            </td>
        </tr>
        <tr v-if="filteredLearners.length === 0">
            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                No learners found.
            </td>
        </tr>
      </tbody>
    </table>
  </div>
  <SleekModal :is-visible="showModal" @close="showModal = false" size="2xl">
        <template #header>
            <div class="flex justify-between w-full flex-1 pr-8 gap-4">
                <div class="flex-1">
                    <h3 class="text-2xl font-semibold text-gray-800">Edit Student</h3>
                    <!-- <p class="text-gray-600 text-sm">Search new students to enlist.</p> -->
                </div>
            </div>
        </template>
        <template #body>
            <form @submit.prevent="updateStudent" class="space-y-4">
                <!-- Last Name -->
                <div>
                    <label for="lastName" class="block text-gray-700 text-md font-medium mb-1">Last Name:</label>
                    <input type="text" id="lastName" required
                        v-model="selectedLearner.last_name"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Middle Name -->
                <div>
                    <label for="middleName" class="block text-gray-700 text-md font-medium mb-1">Middle Name:</label>
                    <input type="text" id="middleName"
                        v-model="selectedLearner.middle_name"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- First Name -->
                <div>
                    <label for="firstName" class="block text-gray-700 text-md font-medium mb-1">First Name:</label>
                    <input type="text" id="firstName" required
                        v-model="selectedLearner.first_name"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 text-md font-medium mb-1">Email:</label>
                    <input type="email" id="email"
                        v-model="selectedLearner.email"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>


                <!-- Gender Dropdown -->
                <div>
                    <label for="gender" class="block text-gray-700 text-md font-medium mb-1">Gender:</label>
                    <select id="gender" required
                        v-model="selectedLearner.gender"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" disabled>Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4 pt-4">
                    <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Update Student
                    </button>
                </div>
            </form>
        </template>

    </SleekModal>
</div>
</template>

<script lang="ts" setup>
import { ref, computed }  from 'vue';
import { ucWords, middleInitials } from '@/composables/utilities';
import SleekModal from '../SleekModal.vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
const selectedSection = ref(1)
const selectedLearner = ref({
    last_name: '',
    first_name: '',
    middle_name: '',
    gender: '',
    email: '',
    section_id: '',
})
const editMode = ref(false)
const showModal = ref(false)
const filteredLearners = computed(() => {
    if (!selectedSection.value) {
        return props.learners
    }
    return props.learners.filter((learner) => learner.section_id === selectedSection.value)
})
const editLearner = (learner: any) => {
    showModal.value = true
    editMode.value = true
    selectedLearner.value = learner?.learner
}
const updateStudent = () => {
    router.post(route('admin.learner.update'), selectedLearner.value, {
        onSuccess: () => {
            toast.success('Member updated successfully.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onError: () => {
            toast.error('Failed to update member.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onFinish: () => {
            showModal.value = false
            editMode.value = false
        }
    })
}
const props = defineProps({
    learners: Array,
    sections: Array,
})
</script>
