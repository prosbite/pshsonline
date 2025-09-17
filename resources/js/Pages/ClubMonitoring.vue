<template>
    <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-8 my-8">
            <!-- Left: Title -->
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">Club Monitoring</h3>
                <p class="text-gray-600 text-sm">{{ props.club.club.name }}</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg">
            <div class="min-h-screen">
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full border border-gray-200 bg-white">
                        <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-r border-indigo-400">
                                    Targets
                                </th>
                                <th class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                                    Quality (Q)
                                </th>
                                <th class="px-4 py-2 text-center text-sm font-semibold">
                                    Efficiency (E)
                                </th>
                                <th class="px-4 py-2 text-center text-sm font-semibold">
                                    Timeliness (T)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                1. ALP/Student Organization Adviser performance demonstrated satisfactorily as indicated in the Performance Evaluation by Students for the semester
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                2. 100% of the required documents for accreditation of newly created clubs or reaccreditation of existing clubs for SY 2026-2027 are reviewed and submitted to the ALP Coordinator as scheduled
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                3. One (1) (f) major activity conducted as indicated in the calendar of activities with activity proposal and activity report in compliance with post-activity requirements
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                4. One (1) (f) community-based activity sponsored/conducted as indicated in the calendar of activities with activity proposal and activity report in compliance with post-activity requirements
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                <div class="flex flex-col justify-start items-start gap-2">
                                    5. One (1) (f) attendance of club members checked and submitted to ALP coordinator without error every meeting (Printed copies submitted at the end of the quarter)
                                    <button @click="showMonitoringModal = true" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 hover:bg-opacity-90 transition-colors duration-300 text-xs self-start">View Details</button>
                                </div>
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">{{ (sortedData?.[0]?.totalQ / attendanceCount).toFixed(1) }}</td>
                            <td class="px-2 py-2 text-center text-sm border-r">{{ (sortedData?.[0]?.totalE / attendanceCount).toFixed(1) }}</td>
                            <td class="px-2 py-2 text-center text-sm">{{ (sortedData?.[0]?.totalT / attendanceCount).toFixed(1) }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                6. 100% of the completers in the ALP classes issued with certificate of completion/accomplishment at the end of the school year during the End Blast
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                7. 100% of the students who incurred five (5) counts/period of cutting classes or unexcused absences filed with an anecdotal report as scheduled
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                8. 100% of the students with two (2) counts of anecdotal reports in one (1) semester required to render an alternative or community work as scheduled
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                9. 100% of the remaining balance of ALP student-led income generating project turned over and sealed to the ALP Coordinator as scheduled
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                <div class="flex flex-col justify-start items-start gap-2">
                                    10. 100% of the Quarterly Accomplishment Report prepared according to the standards set and submitted to ALP coordinator as scheduled
                                    <button @click="detailsModal = true" class="bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 hover:bg-opacity-90 transition-colors duration-300 text-xs self-start">View Details</button>
                                </div>
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200 max-w-72">
                                11. One (1) (f) attendance summary report submitted as scheduled
                            </td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm border-r">-</td>
                            <td class="px-2 py-2 text-center text-sm">-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <AttendanceMonitoringModal :show="showMonitoringModal" @close="showMonitoringModal = false" :sortedData="sortedData" :attendances="attendances" :advisers="advisers" :club="club" :submission="submission" :attendanceCount="attendanceCount" />
    </div>
</template>

<script lang="ts" setup>
import SleekModal from '@/Components/SleekModal.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { fullDate } from '@/composables/utilities';
import { router } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import AttendanceMonitoringModal from '@/Components/modals/AttendanceMonitoringModal.vue';
defineOptions({
    layout: MainLayout
})
const props = defineProps({
    attendances: Object,
    advisers: Array,
    club: Object,
    submission: Object,
})
const clubType = ref('club')
const showMonitoringModal = ref(false)
const selectClubType = () => {
    router.visit(route('admin.clubs.monitoring', { club_type: clubType.value }), { preserveState: true, preserveScroll: true })
}
const sortedData = computed(() => {
    let advisers = props.advisers;
    const attendances = props.attendances;
    let finalData = []
    advisers.forEach((adviser: any, index: number) => {
        let adviserData = {
            adviser: adviser,
            attendances: {},
            totalQ: 0,
            totalE: 0,
            totalT: 0,
        }
        for (let i in attendances) {
            let count = 0
            attendances[i].forEach((a: any, j: number) => {
                if (a.adviser === adviser) {
                    adviserData.attendances[i] = a
                    adviserData.totalQ += a.q
                    adviserData.totalE += a.e
                    adviserData.totalT += a.t
                    count++
                }
            });
            if(count === 0){
                adviserData.attendances[i] = {
                    adviser: adviser,
                    club: null,
                    q: 0,
                    e: 0,
                    t: 0,
                }
                adviserData.totalQ += 0
                adviserData.totalE += 0
                adviserData.totalT += 0
            }
        }
        finalData.push(adviserData)
    });
    return finalData
});
const attendanceCount = computed(() => {
    let count = 0
    for (let i in props.attendances) {
        count++
    }
    return count
})
</script>

<style>

</style>
