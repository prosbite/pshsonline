<template>
    <Head title="Club Dashboard" />
    <MainLayout>
        <div class="space-y-6">
            <section class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-900 text-white p-8 shadow-xl border border-white/10">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <div>
                        <p class="text-emerald-300 uppercase tracking-[0.2em] text-xs font-semibold mb-3">Club Manager Dashboard</p>
                        <h1 class="text-4xl font-extrabold tracking-tight">
                            {{ clubRegister?.club?.name ?? 'Club Dashboard' }}
                        </h1>
                        <p class="mt-3 text-slate-200 text-sm max-w-2xl">
                            Manage your club members, search students, and enlist new members for the current school year.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full lg:w-auto">
                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 backdrop-blur col-span-2">
                            <p class="text-xs uppercase tracking-widest text-slate-300">Adviser</p>
                            <p class="text-lg font-semibold">{{ clubRegister?.user?.name ?? 'N/A' }}</p>
                        </div>
                        <!-- <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 backdrop-blur">
                            <p class="text-xs uppercase tracking-widest text-slate-300">Manager</p>
                            <p class="text-lg font-semibold">{{ clubManager?.user?.name ?? 'N/A' }}</p>
                        </div> -->
                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 backdrop-blur">
                            <p class="text-xs uppercase tracking-widest text-slate-300">Members</p>
                            <p class="text-lg font-semibold">{{ clubMembers.length }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-md border border-gray-200">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Enlist New Members</h2>
                        <p class="text-gray-600 text-sm">Look up students and add them to this club.</p>
                    </div>
                    <div class="w-full lg:w-[30rem]">
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Search by name..."
                            :disabled="isClubFull"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            :class="isClubFull ? 'cursor-not-allowed bg-gray-100 text-gray-400' : ''"
                        >
                    </div>
                </div>

                <div v-if="isClubFull" class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">
                    This club has reached the maximum of 26 members.
                </div>

                <div v-if="searchResults.length > 0" class="mt-6 overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Grade/Section</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Club(s)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Gender</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(result, index) in searchResults" :key="index" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ ucWords(result.learner.last_name ?? '') }}, {{ ucWords(result.learner.first_name ?? '') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ result.section.grade_level_id + 6 }} - {{ result.section.section_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ joinedClubs(result.learner.current_club) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ ucWords(result.learner.gender) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        v-if="!hasClub(result) && !isClubFull"
                                        @click="enlist(result)"
                                        class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700 transition-colors duration-200"
                                    >
                                        + Add
                                    </button>
                                    <span v-else-if="hasClub(result)" class="text-xs font-semibold text-emerald-700">Already added</span>
                                    <span v-else class="text-xs font-semibold text-amber-700">Club full</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="searchInput.length > 1 && searchResults.length === 0 && !loading" class="mt-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-10 text-center text-gray-500">
                    No results found.
                </div>

                <div v-if="loading" class="mt-6 flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-gray-50 px-4 py-10">
                    <img src="/img/ellipses_loader.gif" alt="" class="h-14 w-14">
                    <p class="mt-2 text-gray-500">Searching...</p>
                </div>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-md border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Club Members</h2>
                        <p class="text-gray-600 text-sm">Current members enrolled in this club.</p>
                    </div>
                    <div class="rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                        {{ sortedClubMembers.length }} members
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 w-[40px]">#</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 cursor-pointer hover:text-emerald-600"
                                    @click="setSort('name')"
                                >
                                    Name
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 cursor-pointer hover:text-emerald-600"
                                    @click="setSort('grade_section')"
                                >
                                    Grade/Section
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 cursor-pointer hover:text-emerald-600"
                                    @click="setSort('gender')"
                                >
                                    Gender
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(learner, index) in sortedClubMembers" :key="learner.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ index + 1 + '.' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ ucWords(learner.last_name ?? '') }}, {{ ucWords(learner.first_name ?? '') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ learner.current_enrollment.section.grade_level.grade_level }} - {{ learner.current_enrollment.section.section_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ ucWords(learner.gender) }}
                                </td>
                            </tr>
                            <tr v-if="clubMembers.length === 0">
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No members yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </MainLayout>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import axios from 'axios'
import { ucWords } from '@/composables/utilities'
import { useLearnerSorting, sortLearners } from '@/composables/learnerSorting'

const props = defineProps({
    club_manager: Object,
})

const clubManager = computed(() => props.club_manager)
const clubRegister = computed(() => clubManager.value?.clubRegister ?? clubManager.value?.club_register ?? null)
const clubMembers = computed(() => clubRegister.value?.club?.learners ?? [])
const isClubFull = computed(() => clubMembers.value.length >= 26)
const { setSort, sortItems } = useLearnerSorting('name')
const sortedClubMembers = computed(() => sortItems(clubMembers.value))
const searchInput = ref('')
const loading = ref(false)
const searchResults = ref<any[]>([])
const enlistForm = useForm({
    learner_id: 0,
    club_id: 0,
    club_reg_id: 0,
})
let timeout: ReturnType<typeof setTimeout> | null = null

const hasClub = (learner: any) => {
    if (
        learner.learner.current_club?.filter((club: any) => club.nature.slice(0, 3).toLowerCase() === 'alp').length > 0 &&
        clubRegister.value?.club?.nature.slice(0, 3).toLowerCase() === 'alp'
    ) {
        return true
    }
    return false
}

const joinedClubs = (clubs: any[]) => {
    if (!clubs?.length) {
        return 'No clubs yet'
    }
    return clubs.map((club: { name: string }) => club.name).join(', ')
}

const enlist = (learner: any) => {
    enlistForm.learner_id = learner.learner.id
    enlistForm.club_id = clubRegister.value?.club?.id
    enlistForm.club_reg_id = clubRegister.value?.id

    enlistForm.post(route('club.manager.register'), {
        preserveScroll: true,
        onSuccess: () => {
            enlistForm.learner_id = 0
            enlistForm.club_id = 0
            enlistForm.club_reg_id = 0
            searchInput.value = ''
            searchResults.value = []
        },
        onError: () => {
            toast.error('Failed to register member.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
    })
}

watch(searchInput, (newValue) => {
    if (timeout) {
        clearTimeout(timeout)
    }

    if (isClubFull.value) {
        searchResults.value = []
        loading.value = false
        return
    }

    if (newValue.length === 0) {
        searchResults.value = []
        loading.value = false
        return
    }

    if (newValue.length <= 1) {
        return
    }

    loading.value = true
    timeout = setTimeout(() => {
        const query = newValue.trim()
        if (!query) {
            searchResults.value = []
            loading.value = false
            return
        }

        axios.get(route('student.search', { search: query }))
            .then((response) => {
                searchResults.value = sortLearners(response.data)
            })
            .catch((error) => {
                console.error(error)
            })
            .finally(() => {
                loading.value = false
            })
    }, 700)
})
</script>
