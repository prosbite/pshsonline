<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <!-- Left: Title -->
                <div class="flex justify-between w-full items-center pb-6 pt-6">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ club.club.name }}</h3>
                        <p class="text-gray-600 text-sm">Adviser: {{ club.user.name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
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
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                            <span class="font-semibold text-sm">{{ ucWords(learner.last_name ?? '') + ', ' + ucWords(learner.first_name ?? '')}}</span>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                        <button @click="unlist(learner)" class="bg-red-100 hover:bg-red-200 text-red-100 hover:text-red-600 px-4 py-2 rounded-lg transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-3-3v3m-7 0h14" />
                            </svg>

                        </button>
                    </td>
                </tr>
                <tr v-if="props.club?.club?.learners.length === 0">
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
                                <td class="py-2">{{learner.learner.last_name}}, {{learner.learner.first_name}}</td>
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
    </MainLayout>

    </template>

    <script lang="ts" setup>
    import { ucWords } from '@/composables/utilities'
    import { computed, onMounted, ref, watch } from 'vue'
    import MainLayout from '@/Layouts/MainLayout.vue'
    import { usePage, router } from '@inertiajs/vue3'
    import SleekModal from '@/Components/SleekModal.vue'
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
    })
    const searchInput = ref('')
    const showModal = ref(false)
    const sortBy = ref('name')
    const clubMembers = computed(() => {
        return props.club.club.learners
    })

    const hasClub = (learner: any) => {
        if(learner.learner.current_club?.filter((club: any) => club.nature.slice(0, 3).toLowerCase() === 'alp').length > 0) {
            return true
        }
        return false
    }

    const joinedClubs = (clubs: any[]) => {
        if (!clubs.length) {
            return 'No clubs yet'
        }
        return clubs.map((c: { name: any }) => c.name).join(', ')
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
                toast.success('Member registered successfully.', {
                    autoClose: 2000,
                    position: toast.POSITION.TOP_RIGHT,
                })
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
                toast.success('Member registered successfully.', {
                    autoClose: 2000,
                    position: toast.POSITION.TOP_RIGHT,
                })
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
        // console.log(unlisted.value)
    })
    </script>
