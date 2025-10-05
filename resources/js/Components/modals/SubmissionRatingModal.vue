<template>
    <SleekModal :is-visible="props.show" @close="$emit('close')" size="7xl" style="z-index: 99999">
        <template #header>
            <h3 class="text-lg font-medium text-gray-900">Target 10 Details</h3>
        </template>
        <template #body>
            <table class="min-w-full border border-gray-200 bg-white">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                    <tr>
                    <th rowspan="2" class="px-4 py-3 text-left text-sm font-semibold border-r border-indigo-400">
                        Target
                    </th>
                    <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                        1st Quarter
                    </th>
                    <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                        2nd Quarter
                    </th>
                    </tr>
                    <tr class="bg-indigo-100 text-indigo-900">
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">T</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                            10. 100% of the Quarterly Accomplishment Report prepared according to the standards set and submitted to ALP coordinator as scheduled.
                        </td>
                        <td class="px-2 py-2 text-center text-sm border-r">{{ props.accomplishment ? parseFloat("5").toFixed(1) : '-' }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r">{{ props.accomplishment ? parseFloat("5").toFixed(1) : '-' }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r">{{ props.accomplishment ? parseFloat("5").toFixed(1) : '-' }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r">-</td>
                        <td class="px-2 py-2 text-center text-sm border-r">-</td>
                        <td class="px-2 py-2 text-center text-sm border-r">-</td>
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
    club: Object,
    accomplishment: Object,
    show: Boolean,
})
</script>

<style>

</style>
