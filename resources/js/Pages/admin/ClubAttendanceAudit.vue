<template>
    <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-6">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">Club Attendance Audit</h3>
                <p class="text-gray-600 text-sm">
                    Wednesday dates are the source of truth. Blue means the submission date matches the Wednesday, red means it was submitted on another day in the same week.
                </p>
            </div>

            <Link
                :href="route('admin.attendance')"
                class="inline-flex items-center justify-center px-5 py-2 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200"
            >
                Open Attendance List
            </Link>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="sticky top-0 z-10 bg-gray-50">
                    <tr>
                        <th class="sticky left-0 z-20 bg-gray-50 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 min-w-[180px]">
                            Wednesday
                        </th>
                        <th
                            v-for="club in props.clubs"
                            :key="club.id"
                            class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 min-w-[160px]"
                        >
                            <div class="flex flex-col items-center gap-1">
                                <span class="leading-tight">{{ club.club?.name ?? 'Club' }}</span>
                                <span class="text-[10px] font-normal text-gray-400">
                                    {{ club.user?.name ?? '' }}
                                </span>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="row in props.attendance" :key="row.date" class="hover:bg-gray-50">
                        <td class="sticky left-0 z-10 bg-white px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                            {{ row.label }}
                        </td>

                        <td
                            v-for="(cell, index) in row.cells"
                            :key="`${row.date}-${index}`"
                            class="px-3 py-3 text-center border-r border-gray-100 align-middle"
                        >
                            <Link
                                v-if="cell.attendance"
                                :href="cell.edit_url"
                                class="inline-flex flex-col items-center justify-center rounded-md px-3 py-2 transition-colors duration-150"
                                :class="cell.is_exact ? 'text-blue-600 hover:bg-blue-50' : 'text-red-600 hover:bg-red-50'"
                            >
                                <span class="font-semibold">
                                    {{ cell.label }}
                                </span>
                                <span class="text-[10px] text-gray-400">
                                    Edit
                                </span>
                            </Link>

                            <span v-else class="text-sm text-gray-400">-</span>
                        </td>
                    </tr>

                    <tr v-if="props.attendance.length === 0">
                        <td :colspan="props.clubs.length + 1" class="px-6 py-8 text-center text-sm text-gray-500">
                            No attendance records found for the current school year.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
    layout: MainLayout
})

const props = defineProps({
    attendance: {
        type: Array,
        required: true,
    },
    clubs: {
        type: Array,
        required: true,
    },
    wednesdays: {
        type: Array,
        required: true,
    },
})
</script>

<style>
</style>
