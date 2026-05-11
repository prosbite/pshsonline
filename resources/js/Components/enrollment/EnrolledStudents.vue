<template>
    <div class="w-full md:w-4/5 bg-white p-8 rounded-xl shadow-md border border-gray-200">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-6">
    <div>
      <h3 class="text-2xl font-semibold text-gray-800">Enrolled Students</h3>
      <p class="text-gray-600 text-sm">Filter by section or search all enrolled students.</p>
    </div>
    <div class="flex flex-col gap-3 pb-8 w-full md:w-auto">
      <div class="flex flex-col gap-2 md:flex-row md:items-start md:gap-2">
        <div class="relative w-full md:w-[28rem]">
          <input
            v-model="searchInput"
            type="text"
            placeholder="Search enrolled students..."
            @input="performSearch"
            class="w-full px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
          <div
            v-if="showSearchDropdown"
            class="absolute left-0 right-0 top-full z-20 mt-2 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl"
          >
            <div v-if="loading" class="px-4 py-3 text-sm text-gray-500">
              Searching...
            </div>
            <div v-else-if="searchResults.length > 0">
              <div
                v-for="result in searchResults"
                :key="result.id"
                class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left text-sm hover:bg-indigo-50"
                @click="pickSearchResult(result)"
              >
                <div>
                  <p class="font-semibold text-gray-800">{{ formatLearnerName(result) }}</p>
                  <p class="text-xs text-gray-500">
                    {{ formatGradeSection(result) }} · {{ ucWords(result?.learner?.gender) }}
                  </p>
                </div>
                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                  Enrolled
                </span>
                <button
                  v-if="page?.props?.auth?.user?.role === 'admin'"
                  type="button"
                  @click.stop="removeEnrollment(result)"
                  class="inline-flex items-center gap-1 rounded-lg bg-red-100 px-3 py-2 text-xs font-semibold text-red-700 transition-colors duration-200 hover:bg-red-200"
                >
                  Unenroll
                </button>
              </div>
            </div>
            <div v-else class="px-4 py-3 text-sm text-gray-500">
              No matches found.
            </div>
          </div>
        </div>
        <select
          id="sectionFilter"
          v-model="selectedSection"
          class="w-full md:w-72 px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
  </div>

  <div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
            <th v-if="page?.props?.auth?.user?.role === 'admin'" scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
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
            <td v-if="page?.props?.auth?.user?.role === 'admin'" class="px-6 py-4 whitespace-nowrap text-right">
                <button
                    @click="removeEnrollment(learner)"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-100 px-4 py-2 text-sm font-semibold text-red-700 transition-colors duration-200 hover:bg-red-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-3-3v3m-7 0h14" />
                    </svg>
                    Unenroll
                </button>
            </td>
        </tr>
        <tr v-if="filteredLearners.length === 0">
            <td :colspan="page?.props?.auth?.user?.role === 'admin' ? 5 : 4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
import { router, usePage } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';

const page = usePage()
const selectedSection = ref(1)
const searchInput = ref('')
const searchResults = ref<any[]>([])
const loading = ref(false)
let searchTimeout: ReturnType<typeof setTimeout> | null = null
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
const showSearchDropdown = computed(() => searchInput.value.trim().length >= 2)
const performSearch = () => {
    const query = searchInput.value.trim()

    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }

    if (query.length < 2) {
        searchResults.value = []
        loading.value = false
        return
    }

    loading.value = true
    searchTimeout = setTimeout(() => {
        axios.get(route('student.search', { search: query }))
            .then((response) => {
                searchResults.value = response.data
            })
            .catch((error) => {
                console.error(error)
                searchResults.value = []
            })
            .finally(() => {
                loading.value = false
            })
    }, 350)
}
const pickSearchResult = (result: any) => {
    searchInput.value = formatLearnerName(result)
    searchResults.value = []
}
const formatLearnerName = (learner: any) => {
    const source = learner?.learner ?? learner ?? {}
    return `${ucWords(source?.last_name)} ${ucWords(source?.first_name)} ${middleInitials(source?.middle_name ?? '')}`.trim().replace(/\s+/g, ' ')
}
const formatGradeSection = (learner: any) => {
    const source = learner?.section ?? learner?.learner?.current_enrollment?.section ?? {}
    return source?.section_name
        ? `${parseInt(source?.grade_level_id) + 6} - ${source?.section_name}`
        : 'N/A'
}
const filteredLearners = computed(() => {
    const bySection = !selectedSection.value
        ? props.learners
        : props.learners.filter((learner) => learner.section_id === selectedSection.value)
    return bySection
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
const removeEnrollment = (learner: any) => {
    if (!confirm('Unenroll this student for the current school year?')) {
        return
    }

    router.delete(route('enrollment.destroy', { enrollment: learner.id }), {
        preserveScroll: true,
        onSuccess: () => {
            searchInput.value = ''
            searchResults.value = []
        },
        onError: () => {
            toast.error('Failed to remove enrollment.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
    })
}
const props = defineProps({
    learners: Array,
    sections: Array,
})
</script>
