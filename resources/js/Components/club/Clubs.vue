<template>
<div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
        <!-- Left: Title -->
        <div>
            <h3 class="text-2xl font-semibold text-gray-800">Clubs</h3>
            <p class="text-gray-600 text-sm">Available clubs this school year.</p>
        </div>

            <!-- Right: Statistics -->
            <div class="flex space-x-4">
                <a href="#" @click="showEnlisted = true; showModal = true" class="bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow-sm hover:bg-green-200 transition-colors duration-200 cursor-pointer">
                    <div class="text-sm font-medium">Enlisted</div>
                    <div class="text-xl font-bold">{{ enlisted.length }}</div>
                </a>
                <Link href="{{ route('admin.club.unlisted') }}" class="bg-red-100 text-red-800 px-4 py-3 rounded-lg shadow-sm hover:bg-red-200 transition-colors duration-200 cursor-pointer">
                    <div class="text-sm font-medium">Not Enlisted</div>
                    <div class="text-xl font-bold">{{ 0 }}</div>
                </Link>
            </div>
        </div>

  <div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Club Name</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Male</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Female</th>
            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr
          v-for="(club, index) in sortedClubs" :key="club.id"
          class="hover:bg-gray-100 cursor-pointer"
        >
            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ index + 1 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <div class="flex flex-col">
                    <span class="font-semibold text-lg">{{ ucWords(club.club.name ?? '') }}</span>
                    <span class="text-gray-500 text-sm">Adviser: {{ club.user.name }}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ club.club.learners?.length }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ club.club.learners?.filter((learner) => learner.gender === 'male').length }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ club.club.learners?.filter((learner) => learner.gender === 'female').length }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                <button @click="showClubDetails(club)" class="px-3 mr-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-blue-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 hover:text-white text-blue-100 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                <button @click="editClub(club)" class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-green-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
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
        <tr v-if="props.clubs.length === 0">
            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                No clubs found.
            </td>
        </tr>
      </tbody>
    </table>
  </div>
  <SleekModal :is-visible="showModal" @close="showModal = false" size="7xl">
        <template v-if="showEnlisted" #header>
            <div class="flex justify-between flex-1 pr-8">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Enlisted Students</h3>
                    <p class="text-gray-600 text-sm">Summary of students who joined the club</p>
                </div>
                <select
                    id="sectionFilter"
                    v-model="selectedSection"
                    class="block w-full md:w-64 px-4 border h-11 border-gray-300 text-gray-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option :value="null" disabled>Select Section</option>
                    <option
                        v-for="section in enlistedSections"
                        :key="section.id"
                        :value="section.id"
                    >
                    {{ parseInt(section.grade_level_id) + 6 }} - {{ section.section_name }}
                    </option>
                </select>
            </div>
        </template>
        <template #body>
            <Enlisted :enlisted="enlisted" :currentSection="selectedSection" />
        </template>
        <template v-if="!showEnlisted" #header>
            <div class="flex justify-between flex-1 pr-8">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Unregistered Students</h3>
                    <p class="text-gray-600 text-sm">Summary of students who have not joined the club</p>
                </div>
                <select
                    id="sectionFilter"
                    v-model="selectedNotListedSection"
                    class="block w-full md:w-64 px-4 border h-11 border-gray-300 text-gray-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option :value="null" disabled>Select Section</option>
                    <option :value="null">All Sections</option>
                    <option
                        v-for="section in notListedSections"
                        :key="section.id"
                        :value="section.id"
                    >
                    {{ parseInt(section.grade_level_id) + 6 }} - {{ section.section_name }}
                    </option>
                </select>
            </div>
        </template>
        <template v-if="!showEnlisted" #body>
            <Notlisted :notlisted="unlisted" :currentSection="selectedNotListedSection" />
        </template>
  </SleekModal>

  <SleekModal :is-visible="showClubDetailsModal" @close="showClubDetailsModal = false" size="7xl">
    <template #header>
        <div class="flex justify-between items-center flex-1 pr-8">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">{{ selectedClubDetails.club.name }}</h3>
                <p class="text-gray-600 text-sm">Adviser: {{ selectedClubDetails.user.name }}</p>
            </div>
            <select
                id="sectionFilter"
                v-model="selectedClub"
                class="block w-full md:w-64 px-4 border h-11 border-gray-300 text-gray-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <option :value="null" disabled>Select Club</option>
            <option
                v-for="club in sortedClubs"
                :key="club.id"
                :value="club.id"
            >
            {{ club.club.name }}
            </option>
        </select>
    </div>
    </template>
    <template #body>
        <ClubDetails :club="selectedClubDetails" />
    </template>
  </SleekModal>
</div>

</template>

<script lang="ts" setup>
import { ucWords } from '@/composables/utilities';
import { computed, onMounted, ref } from 'vue';
import SleekModal from '../SleekModal.vue';
import Enlisted from './Enlisted.vue';
import Notlisted from './Notlisted.vue';
import ClubDetails from './ClubDetails.vue';
import { Link } from '@inertiajs/vue3';

const showModal = ref(false)
const showClubDetailsModal =ref(false)
const showEnlisted = ref(true)
const selectedSection = ref(null)
const selectedNotListedSection = ref(null)
const selectedClub = ref(null)

const enlistedSections = computed(() => {
    let sections = []
    props.sections.map((section: any) => {
        if(enlisted.value.filter((learner: any) => learner.section.id === section.id).length > 0) {
            sections.push(section)
        }
    })
    return sections
})
const notListedSections = computed(() => {
    let sections = []
    props.sections.map((section: any) => {
        if(unlisted.value.filter((learner: any) => learner.section.id === section.id).length > 0) {
            sections.push(section)
        }
    })
    return sections
})
const enlisted = computed(() => {
    let students = []
    props.entrants?.map((entrant: any) => {
        if(entrant.learner?.current_club?.filter((club: any) => club.nature.slice(0, 3).toLowerCase() === 'alp').length > 0) {
            students.push(entrant)
        }
    })
    return students
})
const unlisted = computed(() => {
    let students = []
    props.entrants?.map((entrant: any) => {
        if(entrant.learner?.current_club?.filter((club: any) => club.nature.slice(0, 3).toLowerCase() === 'alp').length === 0) {
            students.push(entrant)
        }
    })
    return students
})
const emit = defineEmits(['edit'])
const props = defineProps({
    clubs: Array,
    entrants: Array,
    sections: Array
})
const editClub = (club: any) => {
    emit('edit', club)
}
const showClubDetails = (club: any) => {
    showClubDetailsModal.value = true
    selectedClub.value = club.id
}
const selectedClubDetails = computed(() => {
    return props.clubs.filter((club: any) => club.id === selectedClub.value)[0]
})
onMounted(() => {
    selectedSection.value = enlistedSections?.value[0].id
    selectedNotListedSection.value = notListedSections?.value[0].id
    selectedClub.value = sortedClubs?.value[0].id
    // console.log(unlisted.value)
})

const sortedClubs = computed(() => {
  return [...props.clubs].sort((a, b) => b.club.learners.length - a.club.learners.length)
})
</script>
