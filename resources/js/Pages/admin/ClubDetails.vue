<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <!-- Left: Title -->
                <div class="flex justify-between w-full items-center pb-6 pt-6">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ club?.club?.name }}</h3>
                        <p class="text-gray-600 text-sm">Adviser: {{ club.user.name }}</p>
                    </div>
                    <div v-if="page.props.auth.user?.role === 'admin'" class="flex items-center gap-2">
                        <div>
                            <label for="sectionFilter" class="sr-only">Filter by section</label>
                            <select
                                id="sectionFilter"
                                v-model="selectedClub"
                                @change="switchClub"
                                class="block w-full md:w-64 px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option :value="null" disabled>Select Section</option>
                                <option v-for="club in props.registered_clubs" :value="club.id" :key="club.id">
                                    {{ club.club.name }}
                                </option>
                            </select>
                        </div>
                        <button type="submit"
                                @click="showModal = true"
                                class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            + Add New Member
                        </button>
                        <button
                                v-if="!props.has_manager"
                                type="button"
                                @click="openManagerModal"
                                class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg shadow-md hover:bg-emerald-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            {{ managerButtonLabel }}
                        </button>
                        <!-- <div
                                v-else-if="props.current_manager"
                                class="px-4 py-3 bg-emerald-50 text-emerald-800 font-semibold rounded-lg border border-emerald-200">
                            Manager: {{ props.current_manager?.user?.name }}
                        </div> -->
                        <div class="relative">
                            <button
                                type="button"
                                @click="toggleGenerateMenu"
                                class="inline-flex items-center gap-2 px-5 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                            >
                                Generate
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div
                                v-if="showGenerateMenu"
                                class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-200 bg-white shadow-lg overflow-hidden z-20"
                            >
                                <button
                                    type="button"
                                    @click="downloadCSV"
                                    class="w-full px-4 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                                >
                                    CSV File
                                </button>
                                <button
                                    type="button"
                                    @click="generateConsentForms"
                                    class="w-full px-4 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors duration-150"
                                >
                                    Consent Form
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Statistics -->
                <!-- <div class="flex space-x-4">
                    <a href="#" @click="showEnlisted = true showModal = true" class="bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow-sm hover:bg-green-200 transition-colors duration-200 cursor-pointer">
                        <div class="text-sm font-medium">Enlisted</div>
                        <div class="text-xl font-bold">{{ enlisted.length }}</div>
                    </a>
                    <a href="#" @click="showEnlisted = false showModal = true" class="bg-red-100 text-red-800 px-4 py-3 rounded-lg shadow-sm hover:bg-red-200 transition-colors duration-200 cursor-pointer">
                        <div class="text-sm font-medium">Not Enlisted</div>
                        <div class="text-xl font-bold">{{ unlisted.length }}</div>
                    </a>
                </div> -->
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5 hover:text-blue-500 cursor-pointer" @click="sortBy = 'name'">Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'members'">Grade/Section</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Elistment Date</th>
                    <th v-if="page.props.auth.user?.role === 'admin'" scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="(learner, index) in clubMembers" :key="club.id"
                  class="hover:bg-gray-100 cursor-pointer"
                >
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <div class="flex flex-col">
                            <span class="font-semibold text-sm">{{ formatMemberName(learner) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ learner.current_enrollment.section.grade_level.grade_level }} - {{ learner.current_enrollment.section.section_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ ucWords(learner.gender) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Member
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ fullDateTime(learner.pivot.created_at) }}
                    </td>
                    <td v-if="page.props.auth.user?.role === 'admin'" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                        <button @click="unlist(learner)" class="bg-red-100 hover:bg-red-200 text-red-100 hover:text-red-600 px-4 py-2 rounded-lg transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-3-3v3m-7 0h14" />
                            </svg>

                        </button>
                    </td>
                </tr>
                <tr v-if="props.club?.learners?.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No members yet.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <SleekModal :is-visible="showModal" @close="showModal = false" size="7xl">
            <template #header>
                <div class="flex justify-between w-full flex-1 pr-8 gap-4">
                    <div class="flex-1">
                        <h3 class="text-2xl font-semibold text-gray-800">Student Enlistment</h3>
                        <p class="text-gray-600 text-sm">Search new students to enlist.</p>
                    </div>
                    <div class="flex flex-col gap-2 flex-1">
                        <input v-model="searchInput" type="text" placeholder="Search by name..." class="flex-grow p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <!-- <span class="text-sm text-gray-400 ita">Note: Use @gender, @section or @grade to filter by gender, section or grade level.</span> -->
                    </div>
                </div>
            </template>
            <template #body>
                <div v-if="searchResults.length > 0" class="flex flex-col gap-2 py-4">
                    <table class="w-full">
                        <tbody>
                            <tr v-for="(learner,index) in searchResults" :key="index" class="border-b hover:bg-gray-100">
                                <td class="py-2">{{ formatMemberName(learner.learner) }}</td>
                                <td class="py-2">{{learner.section.grade_level_id + 6 + ' - ' + learner.section.section_name}}</td>
                                <td class="py-2 text-gray-500">{{ joinedClubs(learner.learner.current_club) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="{'bg-green-100 text-green-800': learner.learner.gender === 'male', 'bg-red-100 text-red-800': learner.learner.gender === 'female'}"
                                    >{{ ucWords(learner.learner.gender) }}</span>
                                </td>
                                <td>
                                    <div v-if="!hasClub(learner)" class="flex w-full justify-end">
                                        <button @click="enlist(learner)" class="px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">+ Add</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="searchResults.length === 0 && !loading" class="flex justify-center items-center w-full h-full bg-gray-100">
                    <span class="text-gray-500 text-lg">
                        No results yet.
                    </span>
                </div>
                <div v-if="loading" class="flex flex-col justify-center items-center w-full h-full bg-gray-100">
                    <img src="/img/ellipses_loader.gif" alt="" class="w-14 h-14">
                    <span class="text-gray-500 text-lg">Searching...</span>
                </div>
                <!-- <Enlisted :enlisted="enlisted" :currentSection="selectedSection" /> -->
            </template>

        </SleekModal>
        <SleekModal :is-visible="showManagerModal" @close="showManagerModal = false" size="2xl">
            <template #header>
                <div class="flex justify-between w-full flex-1 pr-8 gap-4">
                    <div class="flex-1">
                        <h3 class="text-2xl font-semibold text-gray-800">{{ managerModalTitle }}</h3>
                        <p class="text-gray-600 text-sm">{{ managerModalDescription }}</p>
                    </div>
                </div>
            </template>
            <template #body>
                <form id="manager-form" @submit.prevent="submitManager" class="space-y-4">
                    <div v-if="managerForm.errors.manager" class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ managerForm.errors.manager }}
                    </div>
                    <div>
                        <label for="manager-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input
                            id="manager-name"
                            v-model="managerForm.name"
                            type="text"
                            class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Full name"
                        >
                        <p v-if="managerForm.errors.name" class="mt-1 text-sm text-red-600">{{ managerForm.errors.name }}</p>
                    </div>
                    <div>
                        <label for="manager-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            id="manager-email"
                            v-model="managerForm.email"
                            type="email"
                            class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="name@example.com"
                        >
                        <p v-if="managerForm.errors.email" class="mt-1 text-sm text-red-600">{{ managerForm.errors.email }}</p>
                    </div>
                    <div>
                        <label for="manager-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input
                            id="manager-password"
                            v-model="managerForm.password"
                            type="password"
                            class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Password"
                        >
                        <p v-if="managerForm.errors.password" class="mt-1 text-sm text-red-600">{{ managerForm.errors.password }}</p>
                    </div>
                    <div>
                        <label for="manager-password-confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input
                            id="manager-password-confirmation"
                            v-model="managerForm.password_confirmation"
                            type="password"
                            class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Confirm password"
                        >
                    </div>
                </form>
            </template>
            <template #footer>
                <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                        @click="closeManagerModal"
                        class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors duration-200"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        form="manager-form"
                        :disabled="managerForm.processing"
                        class="px-5 py-2 bg-emerald-600 text-white font-semibold rounded-lg shadow-md hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed transition-colors duration-200"
                    >
                        {{ managerForm.processing ? 'Saving...' : managerSubmitLabel }}
                    </button>
                </div>
            </template>
        </SleekModal>
        <Teleport to="body">
            <div v-if="showConsentPrint" class="consent-print-shell">
                <ConsentForm
                    v-for="member in consentFormMembers"
                    :key="member.id"
                    :student-name="member.studentName"
                    :grade-section="member.gradeSection"
                    :activity-title="member.activityTitle"
                    :venue="member.venue"
                    :schedule="member.schedule"
                    :club-name="member.clubName"
                    :school-year="member.schoolYear"
                    :adviser-name="member.adviserName"
                    :consent-date="member.consentDate"
                />
            </div>
        </Teleport>
    </MainLayout>
    </template>

    <script lang="ts" setup>
    import { exportToCSV, fullDate, middleInitials, ucWords,fullDateTime } from '@/composables/utilities'
    import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
    import MainLayout from '@/Layouts/MainLayout.vue'
    import { useForm, usePage, router } from '@inertiajs/vue3'
    import SleekModal from '@/Components/SleekModal.vue'
    import ConsentForm from '@/Components/club/forms/ConsentForm.vue'
    import { toast } from 'vue3-toastify'
    import 'vue3-toastify/dist/index.css'
    import axios from 'axios'

    const page = usePage()
    const selectedClub = ref(null)
    const loading = ref(false)
    const props = defineProps({
        club: Object,
        registered_clubs: Array,
        user: Object,
        current_club: Number,
        current_manager: Object,
        previous_manager: Object,
        has_manager: Boolean,
        current_school_year: Object,
    })
    const searchInput = ref('')
    const showModal = ref(false)
    const showManagerModal = ref(false)
    const showGenerateMenu = ref(false)
    const showConsentPrint = ref(false)
    const sortBy = ref('name')
    const managerForm = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    })
    const clubMembers = computed(() => {
        return props.club.learners ?? props.club.club?.learners ?? []
    })
    const managerButtonLabel = computed(() => {
        return props.previous_manager ? '+ Renew Manager' : '+ Add Manager'
    })
    const managerModalTitle = computed(() => {
        return props.previous_manager ? 'Renew Club Manager' : 'Add Club Manager'
    })
    const managerModalDescription = computed(() => {
        return props.previous_manager
            ? 'Renew the existing club manager account for the current school year.'
            : 'Create a user and assign them to this club for the current school year.'
    })
    const managerSubmitLabel = computed(() => {
        return props.previous_manager ? 'Renew Manager' : 'Save Manager'
    })
    const schoolYearLabel = computed(() => {
        const schoolYear = props.current_school_year ?? props.club?.schoolYear

        if (!schoolYear) {
            return ''
        }

        return `${schoolYear.year_start}-${schoolYear.year_end}`
    })
    const consentDate = computed(() => fullDate(new Date().toISOString()))
    const defaultManagerName = computed(() => {
        return `${props.club?.club?.name ?? ''} Manager`.trim()
    })
    const defaultManagerEmail = computed(() => {
        const clubName = props.club?.club?.name ?? ''

        return `${clubName.toLowerCase().replace(/[^a-z0-9]+/g, '')}manager@alp.com`
    })
    const hasClub = (learner: any) => {
        return (learner.learner.current_club?.length ?? 0) > 0
    }

    const joinedClubs = (clubs: any[]) => {
        if (!clubs.length) {
            return 'No clubs yet'
        }
        return clubs.map((c: { name: any }) => c.name).join(', ')
    }

    const toggleGenerateMenu = () => {
        showGenerateMenu.value = !showGenerateMenu.value
    }

    const formatMemberName = (learner: any) => {
        const firstName = ucWords(learner?.first_name ?? '')
        const middleName = middleInitials(learner?.middle_name ?? '')
        const lastName = ucWords(learner?.last_name ?? '')

        return [firstName, middleName, lastName].filter(Boolean).join(' ')
    }

    const switchClub = () => {
        router.visit(route('admin.club.show', selectedClub.value), {

        })
    }

    const enlist = (learner: any) => {
        const data = {
            learner_id: learner.learner.id,
            club_id: props.club?.club.id,
            club_reg_id: props.current_club
        }
        router.post(route('club.register'), data, {
            onSuccess: () => {
            },
            onError: () => {
                toast.error('Failed to register member.', {
                    autoClose: 2000,
                    position: toast.POSITION.TOP_RIGHT,
                })
            },
            onFinish: () => {
                showModal.value = false
                searchInput.value = ''
            }
        })
    }

    const unlist = (learner: any) => {
        if (!confirm('Are you sure you want to unregister this member?')) {
            return
        }
        const data = {
            learner_id: learner.id,
            club_id: props.club?.club.id,
            club_reg_id: props.current_club
        }
        router.post(route('admin.club.unregister'), data, {
            onSuccess: () => {
            },
            onError: () => {
                toast.error('Failed to register member.', {
                    autoClose: 2000,
                    position: toast.POSITION.TOP_RIGHT,
                })
            },
            onFinish: () => {

            }
        })
    }

    let timeout = null
    const searchResults = ref([])
    watch(searchInput, (newValue) => {
        if(newValue.length > 1) {
            loading.value = true
            clearTimeout(timeout)
            timeout = setTimeout(() => {
                if (newValue.trim()) {
                    axios.get(`${route('student.search', { search: newValue })}`)
                        .then(response => {
                            searchResults.value = response.data
                            loading.value = false
                        })
                        .catch(error => {
                            console.error(error)
                        })
                } else {
                    searchResults.value = []
                }
                loading.value = false
            }, 1000) // 1-second debounce
        }
        if(newValue.length === 0) {
            searchResults.value = []
            loading.value = false
        }
    })
    const sortedClubMembers = computed(() => {
        return [...clubMembers.value].sort((a, b) =>
            a.last_name.localeCompare(b.last_name)
        )
    })
    const csvFormat = computed(() => {
        let data = []
        sortedClubMembers.value.map((learner: any) => {
            data.push({
                'Last Name': learner.last_name,
                'First Name': learner.first_name,
                'Middle Name': learner.middle_name ?? '',
                'Gender': learner.gender,
                'Grade Level': learner.current_enrollment?.section?.grade_level?.grade_level,
                'Section': learner.current_enrollment?.section?.section_name,
                'Club': props.club?.club?.name,
            })
        })
        return data
    })

    const downloadCSV = () => {
        showGenerateMenu.value = false
        exportToCSV(csvFormat.value, `${props.club?.club?.name} members.csv`)
    }

    const consentFormMembers = computed(() => {
        return sortedClubMembers.value.map((learner: any) => {
            const gradeLevel = learner?.current_enrollment?.section?.grade_level?.grade_level ?? ''
            const sectionName = learner?.current_enrollment?.section?.section_name ?? ''

            return {
                id: learner.id,
                studentName: formatMemberName(learner),
                gradeSection: [gradeLevel, sectionName].filter(Boolean).join(' - '),
                activityTitle: `${props.club?.club?.name ?? ''} Activities`,
                venue: 'Philippine Science High School - Caraga Region Campus in Butuan City',
                schedule: schoolYearLabel.value,
                clubName: props.club?.club?.name ?? '',
                schoolYear: schoolYearLabel.value,
                adviserName: props.club?.user?.name ?? '',
                consentDate: consentDate.value,
            }
        })
    })

    const generateConsentForms = async () => {
        showGenerateMenu.value = false
        if (!consentFormMembers.value.length) {
            toast.info('No club members available for consent form generation.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
            return
        }
        showConsentPrint.value = true
        await nextTick()
        window.print()
    }

    const openManagerModal = () => {
        const previous = props.previous_manager?.user

        managerForm.name = previous?.name ?? defaultManagerName.value
        managerForm.email = previous?.email ?? defaultManagerEmail.value
        managerForm.password = ''
        managerForm.password_confirmation = ''
        showManagerModal.value = true
    }

    const closeManagerModal = () => {
        showManagerModal.value = false
        managerForm.reset('password', 'password_confirmation')
    }

    const submitManager = () => {
        managerForm.post(route('admin.club.manager.store', { club: props.current_club }), {
            preserveScroll: true,
            onSuccess: () => {
                managerForm.reset()
                showManagerModal.value = false
            },
            onError: () => {
                toast.error(props.previous_manager ? 'Failed to renew club manager.' : 'Failed to add club manager.', {
                    autoClose: 2000,
                    position: toast.POSITION.TOP_RIGHT,
                })
            },
        })
    }

    const closeConsentPrint = () => {
        showConsentPrint.value = false
    }

    const handleAfterPrint = () => {
        closeConsentPrint()
    }

    // const sortedClubs = computed(() => {
    //     if (sortBy.value === 'name') {
    //         return [...props.registered_clubs].sort((a, b) =>
    //           a.club.name.localeCompare(b.club.name)
    //         )
    //       }

    //       if (sortBy.value === 'members') {
    //         return [...props.registered_clubs].sort(
    //           (a, b) => b.total_members - a.total_members
    //         )
    //       }

    //       return props.registered_clubs
    // })

    onMounted(() => {
        selectedClub.value = props.current_club
        window.addEventListener('afterprint', handleAfterPrint)
        // console.log(unlisted.value)
    })

    onUnmounted(() => {
        window.removeEventListener('afterprint', handleAfterPrint)
    })
    </script>

<style scoped>
.consent-print-shell {
    display: none;
}

@media print {
    :global(body > *:not(.consent-print-shell)) {
        display: none !important;
    }

    .consent-print-shell {
        display: block;
    }
}
</style>
