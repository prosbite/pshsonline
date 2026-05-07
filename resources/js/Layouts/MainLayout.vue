<template>
    <div class="no-print relative flex min-h-screen bg-gray-100 font-sans">
        <Sidebar
            v-if="showSidebar"
            class="hidden lg:block"
        />
        <Sidebar
            v-if="showSidebar"
            class="lg:hidden"
            :class="{ hidden: collapse, block: !collapse }"
        />

        <div
            v-if="showSidebar"
            class="flex min-w-0 flex-1 flex-col overflow-y-auto"
        >
            <header class="sticky top-0 z-20 border-b border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-end gap-4 px-4 py-3 md:px-8">
                    <button
                        type="button"
                        class="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 text-gray-600 transition hover:bg-gray-50 hover:text-gray-900"
                        aria-label="Toggle sidebar"
                        @click="collapse = !collapse"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="22"
                            height="22"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>

                    <div v-if="page.props.auth.user.role === 'admin' || page.props.auth.user.role === 'club adviser'" class="flex min-w-0 flex-1 items-center gap-3">
                        <div class="flex-1 flex items-center gap-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">SY</p>
                            <select
                                v-model="selectedSchoolYearId"
                                id="syDD"
                                class="mt-1 flex-6 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 disabled:cursor-not-allowed disabled:bg-gray-100"
                                :disabled="!schoolYears.length || switchingSchoolYear"
                                @change="switchSchoolYear"
                            >
                                <option
                                    v-for="schoolYear in schoolYears"
                                    :key="schoolYear.id"
                                    :value="schoolYear.id"
                                >
                                    {{ schoolYear.year_start }} - {{ schoolYear.year_end }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <Dropdown align="right" width="48" contentClasses="py-2 bg-white">
                        <template #trigger>
                            <button
                                type="button"
                                class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-2 text-left shadow-sm transition hover:bg-gray-50"
                            >
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-sm font-semibold text-white">
                                    {{ userInitials }}
                                </div>
                                <div class="hidden text-left sm:block">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ user?.name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ user?.role }}
                                    </p>
                                </div>
                                <svg
                                    class="h-4 w-4 text-gray-500"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('change-password')">
                                Change Password
                            </DropdownLink>
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto md:p-8">
                <slot />
            </main>
        </div>

        <div
            v-else
            class="flex-grow overflow-y-auto bg-gray-100 p-8"
        >
            <p class="text-center text-gray-600">You are not enrolled in any club yet.</p>
        </div>
    </div>
</template>

<script setup>
import Sidebar from '@/Components/Sidebar.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const page = usePage()
const collapse = ref(true)
const switchingSchoolYear = ref(false)

const user = computed(() => page.props.auth.user)
const showSidebar = computed(() => !!(user.value?.club_registers?.length > 0 || user.value?.role === 'admin' || user.value?.role === 'supervisor' || user.value?.role === 'club manager'))
const schoolYears = computed(() => page.props.schoolYears ?? [])
const currentSchoolYear = computed(() => page.props.currentSchoolYear ?? page.props.sy ?? null)
const selectedSchoolYearId = ref(currentSchoolYear.value?.id ?? schoolYears.value[0]?.id ?? null)
const userInitials = computed(() => {
    const name = user.value?.name ?? ''

    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0]?.toUpperCase())
        .join('') || 'U'
})

watch(
    currentSchoolYear,
    (value) => {
        selectedSchoolYearId.value = value?.id ?? schoolYears.value[0]?.id ?? null
    },
    { immediate: true },
)

watch(
    () => page.props.flash?.success,
    (value) => {
        if (value) {
            toast.success(value, {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        }
    },
    { immediate: true },
)

watch(
    () => page.props.flash?.error,
    (value) => {
        if (value) {
            toast.error(value)
        }
    },
    { immediate: true },
)

const switchSchoolYear = () => {
    if (!selectedSchoolYearId.value || selectedSchoolYearId.value === currentSchoolYear.value?.id) {
        return
    }

    switchingSchoolYear.value = true

    router.post(
        route('school-years.switch'),
        {
            school_year_id: selectedSchoolYearId.value,
        },
        {
            preserveScroll: true,
            preserveState: false,
            onFinish: () => {
                switchingSchoolYear.value = false
            },
        },
    )
}
</script>

<style>
@media print {
    .no-print {
        display: none;
    }
    #syDD {
        width:300px!important;
    }
    .flex-6 {
        flex: 5;
    }
}
</style>
