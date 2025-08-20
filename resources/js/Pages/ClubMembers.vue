<template>
    <MainLayout class="z-50 no-print">
        <div class="page">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{ club?.club?.name }}</h1>

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex flex-col gap-2 flex-1">
                        <input v-model="searchInput" type="text" placeholder="Search by name, @gender, @section or @grade" class="flex-grow p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <!-- <span class="text-sm text-gray-400 ita">Note: Use @gender, @section or @grade to filter by gender, section or grade level.</span> -->
                    </div>
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
                <!-- <span v-if="maximumMembers" class="text-red-500">Maximum number of members reached.</span> -->
                <!-- <div v-if="searchResults.length > 0" class="flex flex-col gap-2 py-4">
                    <table class="w-full">
                        <tbody>
                            <tr v-for="(learner,index) in searchResults" :key="index" class="border-b hover:bg-gray-100">
                                <td class="py-2">{{learner.last_name}}, {{learner.first_name}}</td>
                                <td class="py-2">{{learner.current_enrollment.section.grade_level_id + 6 + ' - ' + learner.current_enrollment.section.section_name}}</td>
                                <td class="py-2 text-gray-500">{{ joinedClubs(learner) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="{'bg-green-100 text-green-800': learner.gender === 'male', 'bg-red-100 text-red-800': learner.gender === 'female'}"
                                    >{{ ucWords(learner.gender) }}</span>
                                </td>
                                <td>
                                    <div class="flex w-full justify-end">
                                        <button v-if="!hasClub(learner)" @click="addNewMember(learner)" class="px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">+ Add</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->
                <!-- <div v-if="searchResults.length === 0 && searchInput.length > 1" class="py-4">
                    <span class="text-left text-gray-500">No results found.</span>
                </div> -->
                <div class="flex flex-wrap gap-4">
                    <!-- <button @click="addNewMember" v-if="checkedLearners.length > 0" class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Add New Member</button> -->
                    <!-- <button v-else class="px-5 py-2 bg-gray-300 text-white font-semibold rounded-lg shadow-md">Add New Member</button> -->
                    <!-- <button class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200">Bulk Import</button> -->
                    <!-- <button class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200">Assign from School DB</button> -->
                </div>
            </div>
            <!-- <div v-else class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
                <span class="text-left text-gray-500">Maximum number of members reached.</span>
            </div> -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-x-auto p-6">
                <table class="min-w-full divide-y divide-gray-200 border border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Grade/Section</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Gender</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <!-- <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="member,index in searchResults" :key="index">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ member.last_name }}, {{ member.first_name }} {{ middleInitials(member.middle_name ?? '') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ parseInt(member.current_enrollment.section.grade_level_id) + 6 + ' - ' + member.current_enrollment.section.section_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="{'bg-green-100 text-green-800': member.gender === 'male', 'bg-red-100 text-red-800': member.gender === 'female'}"
                                >{{ ucWords(member.gender) }}</span>
                                <!-- <span :class="{'bg-green-100 text-green-800': member.status === 'Active', 'bg-red-100 text-red-800': member.status === 'Inactive'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">{{ member.status }}</span> -->
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Member</td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"> -->
                                <!-- <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a> -->
                                <!-- <a @click.prevent="unregisterMember(member)" href="#" class="text-red-600 hover:text-red-900">Remove</a> -->
                            <!-- </td> -->
                        </tr>
                        <tr v-if="searchResults.length === 0 && searchInput.length > 1">
                            <td colspan="5" class="text-left px-6 py-4 text-gray-500">No results found.</td>
                        </tr>
                        <tr v-if="clubMembers.length === 0">
                            <td colspan="5" class="text-left px-6 py-4 text-gray-500">No members yet.</td>
                        </tr>
                        <tr v-if="searchInput.length > 0">
                            <td colspan="5" class="text-left px-6 py-4 text-gray-500">
                                <span class="mr-4">
                                    Total Results: {{ searchResults.length }}
                                </span>
                                <span class="mr-4">
                                    Male: {{ searchResults.filter((member: any) => member.gender === 'male').length }}
                                </span>
                                <span>
                                    Female: {{ searchResults.filter((member: any) => member.gender === 'female').length }}
                                </span>
                            </td>
                        </tr>
                        <tr v-else>
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
                    </tbody>
                </table>
                <div class="flex justify-end gap-4 w-full py-12 no-print">
                    <button @click.prevent="downloadCSV" class="flex items-center gap-2 px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white hover:text-green-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <!-- File icon with folded corner -->
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <!-- Download arrow -->
                            <path d="M12 11v6"/>
                            <path d="M9 14l3 3 3-3"/>
                        </svg>

                        Download CSV
                    </button>
                    <button @click.prevent="printClubMembers" class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-indigo-50 font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-5 h-5 text-white dark:text-white"
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
                    <tr>
                        <td>
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
                                                {{ parseInt(learner.current_enrollment?.section.grade_level_id) + 6 }}
                                            </td>
                                            <td class="border border-black px-2 py-1 text-center uppercase">
                                                {{ learner.current_enrollment?.section.section_name }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="flex flex-col gap-6 mt-2" :class="{ 'page-break-before': breakpoint }">
                                    <div class="flex justify-start">
                                        <div class="flex flex-col flex-1">
                                            <span class="mb-6 text-sm">Prepared by:</span>
                                            <span class="font-bold underline text-md uppercase">
                                                {{ page.props.auth.user.name }}
                                            </span>
                                            <span class="text-md">
                                                {{ club?.club?.name }} Adviser
                                            </span>
                                            <span class="text-sm">Date Printed: <span id="printed-date">{{ new Date().toLocaleString() }}</span></span>
                                        </div>

                                        <div class="flex flex-col flex-1">
                                            <span class="mb-6 text-sm">Reviewed by:</span>
                                            <span class="font-bold underline text-md">
                                                GRETCHEN MAE B. EMPUESTO, PhD
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
                        </td>
                    </tr>
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
import { exportToCSV } from '@/composables/utilities'

const page = usePage()
const props = defineProps({
    clubs: {
        type: Array,
        required: true,
        default: () => []
    }
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
    return props.clubs
})

const searchInput = ref('')
const startSearch = computed(() => {
    if(searchInput.value?.toLocaleLowerCase().includes('@')) {
        return 8
    } else {
        return 2
    }
})
const searchResults = computed(() => {
    if (searchInput.value.length < startSearch.value) {
        return sortedMembers.value
    }
    if(searchInput.value?.includes('@section')) {
        const value = searchInput.value?.split('@section')[1].trim().toLocaleLowerCase()
        return sortedMembers.value.filter((learner: any) => {
            return learner.current_enrollment.section.section_name.toLocaleLowerCase().includes(value)
        })
    }
    if(searchInput.value?.includes('@grade')) {
        const value = searchInput.value?.split('@grade')[1].trim()
        return sortedMembers.value.filter((learner: any) => {
            return parseInt(learner.current_enrollment.section.grade_level_id) + 6 === parseInt(value)
        })
    }
    if(searchInput.value?.includes('@gender')) {
        const value = searchInput.value?.split('@gender')[1].trim().toLocaleLowerCase()
        return sortedMembers.value.filter((learner: any) => {
            return learner.gender.toLocaleLowerCase()?.[0] === value[0]
        })
    }
    return sortedMembers.value.filter((learner: any) => {
        return learner.last_name.toLocaleLowerCase().includes(searchInput.value?.toLocaleLowerCase()) ||
        learner.first_name.toLocaleLowerCase().includes(searchInput.value?.toLocaleLowerCase())
    })
})

const club = computed(() => {
    return clubs.value?.filter((clb: any) => {
        return parseInt(clb.id) === parseInt(selectedClub.value)
    })?.[0]
})
const sortedMembers = computed(() => {
  return [...clubMembers.value].sort((a, b) =>
    a.last_name.localeCompare(b.last_name)
  )
})
const breakpoint = computed(() => {
    return false
    return (sortedMembers.value.length > 25 && sortedMembers.value.length < 33)
})
const printClubMembers = () => {
    window.print()
}
const clubMembers = computed(() => {
    return club.value?.club?.learners ?? []
})

const csvFormat = computed(() => {
    let data = []
    sortedMembers.value.map((learner: any) => {
        data.push({
            'Last Name': learner.last_name,
            'First Name': learner.first_name,
            'Middle Name': learner.middle_name ?? '',
            'Gender': learner.gender,
            'Grade Level': learner.current_enrollment?.section?.grade_level_id + 6,
            'Section': learner.current_enrollment?.section?.section_name,
            'Club': club.value?.club?.name,
        })
    })
    return data
})

const downloadCSV = () => {
    exportToCSV(csvFormat.value, `${club.value?.club?.name} members.csv`)
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
    /* body {
        transform: scale(0.75);
        transform-origin: top left;
        width: 133.33%;
    } */
    .to-print {
        display: table;
    }
    .page-break-before {
        break-before: page; /* Standard property */
        page-break-before: always; /* Older property for broader compatibility */
    }
}
</style>
