<template>
    <SleekModal :is-visible="props.show" @close="$emit('close')" size="7xl" style="z-index: 99999">
        <template #header>
            <h3 class="text-lg font-medium text-gray-900">Target 5 Details</h3>
        </template>
        <template #body>
            <table class="min-w-full border border-gray-200 bg-white">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                    <tr>
                    <th rowspan="2" class="px-4 py-3 text-left text-sm font-semibold border-r border-indigo-400">
                        Target
                    </th>
                    <th v-for="attendance,index in attendances" colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                        {{ fullDate(index) }}
                    </th>
                    <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                        Printed (1st Quarter)
                    </th>
                    <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold">
                        Average
                    </th>
                    </tr>
                    <tr class="bg-indigo-100 text-indigo-900">
                    <template v-for="attendance,index in attendances">
                        <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                        <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                        <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">T</th>
                    </template>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">T</th>

                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-medium">T</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="adviser,index in sortedData" class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                            Target 5: 5. One (1) (f) attendance of club members checked and submitted to ALP coordinator without error every meeting (Printed copies submitted at the end of the quarter)
                        </td>
                        <template v-for="attendance,index in adviser.attendances">
                            <td class="px-2 py-2 text-center text-sm border-r"> {{ attendance.q }}</td>
                            <td class="px-2 py-2 text-center text-sm border-r"> {{ attendance.e }}</td>
                            <td class="px-2 py-2 text-center text-sm border-r"> {{ attendance.t }}</td>
                        </template>
                        <td class="px-2 py-2 text-center text-sm border-r">{{ props.submission ? 5 : '-' }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r">{{ props.submission ? 5 : '-' }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r">{{ props.submission ? 5 : '-' }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r font-bold"> {{ (adviser.totalQ / attendanceCount).toFixed(1) }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r font-bold"> {{ (adviser.totalE / attendanceCount).toFixed(1) }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r font-bold"> {{ (adviser.totalT / attendanceCount).toFixed(1) }}</td>
                    </tr>
                </tbody>
            </table>
        </template>
    </SleekModal>
</template>

<script lang="ts" setup>
import { fullDate, removeUnderScore, ucWords } from '@/composables/utilities';
import SleekModal from '../SleekModal.vue';
import { ref } from 'vue';

const props = defineProps({
    attendances: Object,
    sortedData: Array,
    advisers: Array,
    club: Object,
    submission: Object,
    show: Boolean,
    attendanceCount: Number,
})
</script>

<style>

</style>
