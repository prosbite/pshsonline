<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <!-- Left: Title -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Registered Clubs</h3>
                    <p class="text-gray-600 text-sm">Available clubs this school year {{ page.props.sy.year_start }} - {{ page.props.sy.year_end }}</p>
                </div>

                <!-- Right: Statistics -->
                <div class="flex space-x-4">
                    <a href="#" class="bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow-sm hover:bg-green-200 transition-colors duration-200 cursor-pointer">
                        <div class="text-sm font-medium">Enlisted</div>
                        <div class="text-xl font-bold">{{ enlisted }}</div>
                    </a>
                    <a href="#" class="bg-red-100 text-red-800 px-4 py-3 rounded-lg shadow-sm hover:bg-red-200 transition-colors duration-200 cursor-pointer">
                        <div class="text-sm font-medium">Not Enlisted</div>
                        <div class="text-xl font-bold">{{ 0 }}</div>
                    </a>
                </div>
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6 hover:text-blue-500 cursor-pointer" @click="sortBy = 'name'">Club Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'members'">Members</th>
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
                        {{ club.total_members }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ club.female_members }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ club.male_members }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                        <button @click="showClubDetails(club.club.id)" class="px-3 mr-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-blue-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 hover:text-white text-blue-100 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <button v-if="page.props.auth.user?.role === 'admin'" class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-green-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
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
                <tr v-if="props.registered_clubs.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No clubs found.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </MainLayout>

    </template>

    <script lang="ts" setup>
    import { ucWords } from '@/composables/utilities';
    import { computed, onMounted, ref } from 'vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Link, usePage } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    const page = usePage();
    const props = defineProps({
        registered_clubs: Array,
        sy: Object,
        club_student_count: Number,
        unlisted_learners: Array
    })
    const sortBy = ref('name');

    const sortedClubs = computed(() => {
        if (sortBy.value === 'name') {
            return [...props.registered_clubs].sort((a, b) =>
              a.club.name.localeCompare(b.club.name)
            );
          }

          if (sortBy.value === 'members') {
            return [...props.registered_clubs].sort(
              (a, b) => b.total_members - a.total_members
            );
          }

          return props.registered_clubs;
    })

    const enlisted = computed(() => {
        let ecount = 0
        props.registered_clubs.forEach((club: any) => {
            if(club.club?.nature.slice(0, 3).toLowerCase() === 'alp') {
                ecount += parseInt(club.total_members)
            }
        })
        return ecount
    })

    const unlisted = computed(() => {
        return props.club_student_count - enlisted.value
    })

    const showClubDetails = (club_id: number) => {
        router.visit(route('admin.club.show', club_id))
    }

    onMounted(() => {
        // console.log(props.unlisted_learners)
        // console.log(unlisted.value)
    })
    </script>
