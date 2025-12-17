<template>
    <div class="tab-content">
        <div class="flex items-center justify-between mb-6">
            <div class="flex flex-col gap-2">
                <h3 class="text-2xl font-semibold text-gray-800">Attendance Summary</h3>
                <p class="text-gray-600">{{ props.club?.name }}</p>
            </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th v-for="status in attendanceStatus()" :key="status.value" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ status.abv }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total UA and CC</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody v-if="props.attendance.length > 0" class="bg-white divide-y divide-gray-200">
                    <tr v-for="attendance,index in props.attendance" :key="attendance.id">
                        <td class="px-6 py-4 max-w-[20px] text-center text-sm text-gray-500 w-10">{{ index + 1 }}</td>
                        <td class="px-6 py-4 max-w-[200px] text-left text-sm text-gray-500">{{ attendance.last_name + ', ' + attendance.first_name + ' ' + middleInitials(attendance.middle_name) }}</td>
                        <td class="px-6 py-4 max-w-[20px] text-center text-sm text-indigo-500">{{ totalPresent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-500">{{ totalExcusedAbsent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-500">{{ totalUnexcusedAbsent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-yellow-500">{{ totalTardy(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ totalCuttingClasses(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600">{{ total(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-black">{{ total(attendance) > 4 ? 'For Community Service' : 'Good Standing' }}</td>
                    </tr>

                </tbody>
                <tbody v-else class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No attendance records yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex justify-end">
             <button @click.prevent="printSummary" class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-indigo-50 font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">
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

        <Teleport to="body">
            <table class="to-print w-full">
                <thead>
                    <tr>
                        <th id="header" class="flex items-center justify-between mb-6">
                            <img src="/img/pisay_header_left.png" class="h-[70px]" alt="Pisay Logo">
                            <img src="/img/bplogo.png" class="h-[70px]" alt="Pisay Logo">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div id="printContent" class="w-full flex flex-col bg-white">
                                <div class="flex-col w-full font-bold text-lg mb-6 leading-tight">
                                    <span class="text-center block uppercase">
                                        {{ club?.club?.name }}
                                    </span>
                                    <span class="text-center block uppercase mb-4">

                                    </span>
                                    <span class="text-center block uppercase leading-tight">
                                        Attendance Summary
                                    </span>
                                    <span class="text-center block w-full text-sm leading-tight">1st Semester, S.Y. {{ page.props.sy.year_start }} - {{ page.props.sy.year_end }}</span>
                                </div>
                                <span class="font-bold mb-2">
                                    Club: {{ page.props.auth.user.club_registers?.[0]?.club?.name }}
                                </span>
                                <table class="table-auto border-collapse w-full border border-black text-xs">
                                    <thead>
                                    <tr>
                                        <th class="border border-black px-2 py-1 w-10">NO.</th>
                                        <th class="border border-black px-4 py-1">Student Name<br><span class="text-[10px]"></span></th>
                                        <th class="border border-black px-2 py-1 w-16">Present</th>
                                        <th class="border border-black px-4 py-1">EA</th>
                                        <th class="border border-black px-2 py-1 w-16">UA</th>
                                        <th class="border border-black px-4 py-1">Tardy</th>
                                        <th class="border border-black px-2 py-1 w-16">CC</th>
                                        <th class="border border-black px-4 py-1">Total UA and CC</th>
                                        <th class="border border-black px-4 py-1">Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                         <tr v-for="attendance,index in props.attendance" :key="attendance.id">
                                            <td class="border border-black px-2 py-1 text-center">{{ index + 1 }}</td>
                                            <td class="border border-black px-2 py-1 text-left text-sm text-gray-500">{{ attendance.last_name + ', ' + attendance.first_name + ' ' + middleInitials(attendance.middle_name) }}</td>
                                            <td class="border border-black px-2 py-1 text-center text-sm text-indigo-500">{{ totalPresent(attendance) }}</td>
                                            <td class="border border-black px-2 py-1 whitespace-nowrap text-center text-sm text-green-500">{{ totalExcusedAbsent(attendance) }}</td>
                                            <td class="border border-black px-2 py-1 whitespace-nowrap text-center text-sm text-red-500">{{ totalUnexcusedAbsent(attendance) }}</td>
                                            <td class="border border-black px-2 py-1 whitespace-nowrap text-center text-sm text-yellow-500">{{ totalTardy(attendance) }}</td>
                                            <td class="border border-black px-2 py-1 text-center">{{ totalCuttingClasses(attendance) }}</td>
                                            <td class="border border-black px-2 py-1 text-center">{{ total(attendance) }}</td>
                                            <td class="border border-black px-2 py-1 text-center text-xs">{{ total(attendance) > 4 ? 'For Community Service' : 'Good Standing' }}</td>
                                        </tr>

                                    <!-- Example row -->
                                        <tr v-for="(attendance, index) in consolidatedAttendance" :key="index">
                                            <td class="border border-black px-2 py-1 text-center">{{ index + 1 }}</td>
                                            <td class="border border-black px-2 py-1">{{ attendance[1]?? '' }}</td>
                                            <td class="border border-black px-2 py-1 text-center">{{ attendance[2]?? '' }}</td>
                                            <td class="border border-black px-2 py-1 text-center">{{ attendance[3]?? '' }}</td>

                                            <!-- Meeting cells -->
                                            <template v-for="(meeting, mIndex) in (attendance.length - 4) / 2" :key="mIndex">
                                                <td class="border border-black px-2 py-1 text-center font-bold" :class="statusColor[attendance[4 + (mIndex * 2)] ?? '']" colspan="1">
                                                    {{ status[attendance[4 + (mIndex * 2)] ?? ''] }}
                                                </td>
                                                <td class="border border-black px-2 py-1 text-gray-500 text-center max-w-[100px]" colspan="4">
                                                    {{ attendance[5 + (mIndex * 2)] ?? '-' }}
                                                </td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-2 text-xs mb-4">
                                    <strong>Legend:</strong> T - Tardy, CC - Cutting Class, EA - Excused Absence, UA - Unexcused Absence
                                </div>
                                <div class="flex flex-col gap-6 mt-6" :class="{ 'page-break-before': breakpoint }">
                                    <div class="flex justify-start">
                                        <div class="flex flex-col gap-4 flex-1">
                                            <div class="flex flex-col flex-1">
                                                <span class="mb-6 text-sm">Checked by:</span>
                                                <span class="font-bold underline text-md uppercase">
                                                    {{ page.props.auth.user.name }}
                                                </span>
                                                <span class="text-md">
                                                    {{ page.props?.auth.user?.club_registers?.[0]?.club?.name }} Adviser
                                                </span>
                                                <span class="text-sm">Date Printed: <span id="printed-date">{{ new Date().toLocaleString() }}</span></span>
                                            </div>

                                            <div class="flex flex-col flex-1">
                                                <span class="mb-6 text-sm">Noted by:</span>
                                                <span class="font-bold underline text-md uppercase">
                                                    John Ridan D. Dechusa
                                                </span>
                                                <span class="text-md">
                                                    Asst. CID Chief for Student Affairs
                                                </span>
                                            </div>

                                        </div>

                                        <div class="flex flex-col flex-1">
                                            <span class="mb-6 text-sm">Monitored by:</span>
                                            <span class="font-bold underline text-md">
                                                GRETCHEN MAE B. EMPUESTO, PhD
                                            </span>
                                            <span class="text-md">
                                                ALP Coordinator
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
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { fullDate, attendanceStatus, formatDate, middleInitials, formatMonth } from '@/composables/utilities'

defineOptions({
  layout: MainLayout
})
const page = usePage()
const props = defineProps({
    club: Object,
    attendance: Array,
})
const totalPresent = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => (learner.pivot.status === 'present' || learner.pivot.status === 'tardy') && learner.club_register_id === props.club.id).length
}
const totalUnexcusedAbsent = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'unexcused_absence' && learner.club_register_id === props.club.id ).length
}
const totalExcusedAbsent = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'excused_absence' && learner.club_register_id === props.club.id).length
}
const totalTardy = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'tardy' && learner.club_register_id === props.club.id).length
}
const totalCuttingClasses = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'cutting_classes' && learner.club_register_id === props.club.id).length
}
const total = (attendance: any) => {
    // return parseInt(totalPresent(attendance)) + parseInt(totalUnexcusedAbsent(attendance)) + parseInt(totalExcusedAbsent(attendance)) + parseInt(totalTardy(attendance)) + parseInt(totalCuttingClasses(attendance))
    return parseInt(totalUnexcusedAbsent(attendance)) + parseInt(totalCuttingClasses(attendance))
}
const printSummary = () => {
    window.print()
}
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
