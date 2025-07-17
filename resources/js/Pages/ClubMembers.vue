<template>
    <MainLayout class="z-50 no-print">
        <div class="page">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{ club?.club?.name }} Enlistment</h1>

            <div v-if="!maximumMembers" class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <input v-model="searchInput" type="text" placeholder="Search by Name" class="flex-grow p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <select v-if="clubs?.length > 1" v-model="selectedClub" class="p-3 border border-gray-300 w-56 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <!-- <option disabled value="">Select Club</option> -->
                        <option v-for="club in clubs" :key="club.id" :value="club.id">{{ club.club.name }}</option>
                    </select>
                   <!--  <select class="p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Filter by Role</option>
                        <option value="leader">Leader</option>
                        <option value="member">Member</option>
                    </select>
                    <select class="p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Filter by Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select> -->
                </div>
                <div v-if="searchResults.length > 0" class="flex flex-col gap-2 py-4">
                    <table class="w-full">
                        <tbody>
                            <tr v-for="(learner,index) in searchResults" :key="index" class="border-b hover:bg-gray-100">
                                <!-- <td class="py-2">
                                    <input v-model="learner.checked" type="checkbox">
                                </td> -->
                                <td class="py-2">{{learner.last_name}}, {{learner.first_name}}</td>
                                <td class="py-2">{{learner.current_enrollment.section.grade_level.grade_level + ' - ' + learner.current_enrollment.section.section_name}}</td>
                                <td class="py-2 text-gray-500">{{ joinedClubs(learner) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="{'bg-green-100 text-green-800': learner.gender === 'male', 'bg-red-100 text-red-800': learner.gender === 'female'}"
                                    >{{ ucWords(learner.gender) }}</span>
                                    <!-- <span :class="{'bg-green-100 text-green-800': member.status === 'Active', 'bg-red-100 text-red-800': member.status === 'Inactive'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">{{ member.status }}</span> -->
                                </td>
                                <td>
                                    <div class="flex w-full justify-end">
                                        <button v-if="!hasClub(learner)" @click="addNewMember(learner)" class="px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">+ Add</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="searchResults.length === 0 && searchInput.length > 1" class="py-4">
                    <span class="text-left text-gray-500">No results found.</span>
                </div>
                <div class="flex flex-wrap gap-4">
                    <!-- <button @click="addNewMember" v-if="checkedLearners.length > 0" class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Add New Member</button> -->
                    <!-- <button v-else class="px-5 py-2 bg-gray-300 text-white font-semibold rounded-lg shadow-md">Add New Member</button> -->
                    <!-- <button class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200">Bulk Import</button> -->
                    <!-- <button class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200">Assign from School DB</button> -->
                </div>
            </div>
            <div v-else class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
                <span class="text-left text-gray-500">Maximum number of members reached.</span>
            </div>
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="member,index in sortedMembers" :key="index">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ member.last_name }}, {{ member.first_name }} {{ middleInitials(member.middle_name ?? '') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ member.grade_section.grade_level.grade_level + ' - ' + member.grade_section.section_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="{'bg-green-100 text-green-800': member.gender === 'male', 'bg-red-100 text-red-800': member.gender === 'female'}"
                                >{{ ucWords(member.gender) }}</span>
                                <!-- <span :class="{'bg-green-100 text-green-800': member.status === 'Active', 'bg-red-100 text-red-800': member.status === 'Inactive'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">{{ member.status }}</span> -->
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Member</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <!-- <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a> -->
                                <a @click.prevent="unregisterMember(member)" href="#" class="text-red-600 hover:text-red-900">Remove</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-left px-6 py-4 text-gray-500">
                                <span class="mr-4">
                                    Total members: {{ clubMembers.length }}
                                </span>
                                <span class="mr-4">
                                    Male: {{ clubMembers.filter((member: any) => member.gender === 'male').length }}
                                </span>
                                <span>
                                    Female: {{ clubMembers.filter((member: any) => member.gender === 'female').length }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="club?.learners?.length === 0">
                            <td colspan="5" class="text-left px-6 py-4 text-gray-500">No members yet.</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-end w-full pb-12 px-8">
                    <button @click.prevent="printClubMembers" class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-indigo-50 font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-5 h-5 text-gray-900 dark:text-white"
                        >
                            <!-- Printer body -->
                            <path d="M6 9V2h12v7" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect x="6" y="14" width="12" height="8" rx="2" ry="2" />
                            <!-- Paper coming out -->
                            <line x1="8" y1="18" x2="8" y2="22" />
                            <line x1="16" y1="18" x2="16" y2="22" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
        <Teleport to="body">
            <table class="to-print w-full">
                <thead>
                    <tr>
                        <th id="header" class="flex items-center justify-between mb-6">
                            <div class="flex gap-2 items-center">
                                <img src="/img/pisaylogo.png" class="h-[70px]" alt="Pisay Logo">
                                <div class="flex flex-col gap-0">
                                    <span class="text-left">
                                        Republic of the Philippines
                                    </span>
                                    <span class="text-left text-xs leading-normal">
                                        DEPARTMENT OF SCIENCE AND TECHNOLOGY
                                    </span>
                                    <span class="text-left font-bold text-xs leading-normal">
                                        PHILIPPINE SCIENCE HIGH SCHOOL CARAGA-REGION CAMPUS
                                    </span>
                                    <span class="text-left text-xs leading-normal">
                                        CURRICULUM AND INSTRUCTION DIVISION
                                    </span>
                                </div>
                            </div>
                            <img src="/img/bplogo.png" class="h-[70px]" alt="Pisay Logo">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <div id="printContent" class="w-full flex flex-col bg-white">
                        <div class="flex-col w-full font-bold text-lg mb-6">
                            <span class="text-center block uppercase">
                                {{ club?.club?.name }}
                            </span>
                            <span class="text-center block uppercase mb-4">
                                SY {{ page.props.sy?.year_start }} - {{ page.props.sy?.year_end }}
                            </span>
                            <span class="text-center block uppercase">
                                List of Club Members
                            </span>
                        </div>

                        <table class="w-full border border-black border-collapse mb-10">
                            <thead>
                                <tr class="text-center">
                                    <th></th>
                                    <th class="flex flex-col text-lg" colspan="2">
                                        <span clas="block">
                                            Name
                                        </span>
                                    </th>
                                    <th class="border border-black">
                                        Grade Level
                                    </th>
                                    <th class="border border-black">
                                        Section
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="learner,index in sortedMembers">
                                    <td class="border border-black px-0 py-1 text-center">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="border border-black px-2 py-1 text-left">
                                        {{ ucWords(learner?.last_name) + ', ' + ucWords(learner?.first_name) + ' ' + middleInitials(learner?.middle_name?? '') }}
                                    </td>
                                    <td class="border border-black px-2 py-1 text-center">
                                        {{ learner.grade_section?.grade_level.grade_level }}
                                    </td>
                                    <td class="border border-black px-2 py-1 text-center uppercase">
                                        {{ learner.grade_section?.section_name }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="flex flex-col gap-6 mt-12" :class="{ 'page-break-before': breakpoint }">
                            <div class="flex justify-between">
                                <div class="flex flex-col">
                                    <span class="mb-6 text-sm">Prepared by:</span>
                                    <span class="font-bold underline text-md uppercase">
                                        {{ page.props.auth.user.name }}
                                    </span>
                                    <span class="text-md">
                                        {{ club?.club?.name }} Adviser
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="mb-6 text-sm">Reviewed by:</span>
                                    <span class="font-bold underline text-md uppercase">
                                        Gretchen Mae B. Empuesto
                                    </span>
                                    <span class="text-md">
                                        ALP Coordinator
                                    </span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <div class="flex flex-col">
                                    <span class="mb-6 text-sm">Approved by:</span>
                                    <span class="font-bold underline text-md uppercase">
                                        JOHN RIDAN D. DECHUSA
                                    </span>
                                    <span class="text-md">
                                        Assistant CID Chief for Student Affairs
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </tbody>
            </table>
        </Teleport>
    </MainLayout>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { computed, onMounted, ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { middleInitials, ucWords } from '@/composables/utilities'

const page = usePage()
const props = defineProps({
    learners: {
        type: Array,
        required: true,
        default: () => []
    },
})
const printHeight = ref(0)
const maximumMembers = computed(() => {
    return club.value?.club?.learners?.length >= 40
})
const selectedClub = ref({})
const hasClub = (learner: any) => {
    let hasClub = false
    if(club.value?.club?.nature.slice(0, 3).toLowerCase() === 'alp') {
        learner.current_club?.map((clb: any) => {
            if(clb.nature.slice(0, 3).toLowerCase() === club.value?.club?.nature.slice(0, 3).toLowerCase()) {
                hasClub = true
            }
        })
    } else {
        learner.current_club?.map((clb: any) => {
            if(clb.id === club.value?.club?.id) {
                hasClub = true
            }
        })
    }
    return hasClub
}
const joinedClubs = (learner: any) => {
    let clubs = []
    if(learner.current_club?.length === 0) {
        return 'No clubs yet'
    }
    learner.current_club?.map((clb: any) => {
        clubs.push(clb.name)
    })
    return clubs.join(', ')
}
const addNewMember = (learner: any) => {
    const data = {
        learner_id: learner.id,
        club_id: club.value?.id,
        grade_level: learner.current_enrollment.section.grade_level.grade_level,
    }
    router.post(route('club.register'), data, {
        onSuccess: () => {
            toast.success('Member registered successfully.', {
                autoClose: 3000,
                position: toast.POSITION.TOP_RIGHT,
            })
            searchInput.value = ''
        },
        onError: () => {
            toast.error('Failed to register member.', {
                autoClose: 3000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onFinish: () => {

        }
    })
}

const clubMembers = computed(() => {
    let members = club.value?.club?.learners ? JSON.parse(JSON.stringify(club.value?.club?.learners)) : []
    members.map((member: any) => {
        props.learners.map((learner: any) => {
            if (learner.id === member.id) {
                member.grade_section = learner.current_enrollment.section
            }
        })
    })
    return members
})

const unregisterMember = (learner: any) => {
    if (!confirm('Are you sure you want to unregister this member?')) {
        return
    }
    const data = {
        learner_id: learner.id,
        club_id: club.value?.id,
    }
    router.post(route('club.unregister'), data, {
        onSuccess: () => {
            toast.success('Member unregistered successfully.', {
                autoClose: 3000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onError: () => {
            toast.error('Failed to unregister member.', {
                autoClose: 3000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onFinish: () => {

        }
    })
}

const clubs = computed(() => {
    return page.props.auth.user?.club_registers
})

const searchInput = ref('')
const searchResults = computed(() => {
    if (searchInput.value?.length < 2) {
        return []
    }
    return props.learners.filter((learner: any) => {
        return learner.last_name.toLowerCase().includes(searchInput.value?.toLowerCase()) ||
        learner.first_name.toLowerCase().includes(searchInput.value?.toLowerCase())
    })
})

const club = computed(() => {
    return clubs.value?.filter((club: any) => {
        return club.id === selectedClub.value
    })[0]
})
const sortedMembers = computed(() => {
  return [...clubMembers.value].sort((a, b) =>
    a.last_name.localeCompare(b.last_name)
  )
})
const breakpoint = computed(() => {
    return sortedMembers.value.length > 15 && sortedMembers.value.length < 23
})
const printClubMembers = () => {
    window.print()
}
onMounted(() => {
    printHeight.value = document.getElementById('printContent')?.offsetHeight
    if(clubs.value?.length > 0) {
        selectedClub.value = clubs.value[0].id
    }
})
</script>

<style>
.to-print {
    display: none;
}
@page {
    margin-left: 1in;
    margin-right: 1in;
    margin-top: 1.27cm;
    margin-bottom: 1.27cm;
    @top-center {
        content: element(header);
    }
}

header {
  position: running(header);
}
@media print {
    .no-print {
        display: none;
    }
    .to-print {
        display: table;
    }
    .page-break-before {
        break-before: page; /* Standard property */
        page-break-before: always; /* Older property for broader compatibility */
    }
}
</style>
