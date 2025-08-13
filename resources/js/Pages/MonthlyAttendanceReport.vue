<template>
    <div class="no-print w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
            <div >
                <h3 class="text-2xl font-semibold text-gray-800">Monthly Attendance Report</h3>
                <p class="text-gray-600 text-sm mb-4">For the month of {{ formatMonth(month) }}</p>
            </div>
            <select @change="getMonthlyAttendance($event)" id="attendanceDate" v-model="selectedMonth" required class="border w-1/5 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="" disabled selected>Select Month</option>
                <option v-for="(month,index) in props.months" :key="index" :value="month">Month of {{ formatMonth(month) }}</option>
            </select>
        </div>

        <table class="table-auto border-collapse w-full border border-black text-xs">
            <thead>
            <tr>
                <th rowspan="2" class="border border-black px-2 py-1 w-10">#</th>
                <th rowspan="2" class="border border-black px-4 py-1">OFFICERS / MEMBERS<br><span class="text-[10px]">(Alphabetical Order; Last Name, First Name)</span></th>
                <th rowspan="2" class="border border-black px-2 py-1 w-16">GRADE<br>LEVEL</th>
                <th rowspan="2" class="border border-black px-4 py-1">SECTION</th>

                <!-- Repeat for number of meetings -->
                <th v-for="att in props.attendance" colspan="5" class="border border-black px-2 py-1">
                    <div class="text-center font-bold">{{ fullDate(att.date) }}</div>
                    <!-- <div class="flex justify-between text-[10px]">
                        <span>Date: ________</span>
                        <span>NATURE: ________</span>
                    </div> -->
                    <!-- <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div> -->
                </th>

                <!-- <th colspan="5" class="border border-black px-2 py-1">
                    <div class="text-center font-bold">MEETING #</div>
                    <div class="flex justify-between text-[10px]">
                        <span>Date: ________</span>
                        <span>NATURE: ________</span>
                    </div>
                    <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                </th>

                <th colspan="5" class="border border-black px-2 py-1">
                    <div class="text-center font-bold">MEETING #</div>
                    <div class="flex justify-between text-[10px]">
                        <span>Date: ________</span>
                        <span>NATURE: ________</span>
                    </div>
                    <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                </th> -->
            </tr>
            <tr>
                <!-- Repeat headers for each meeting -->
                 <template v-for="att in props.attendance" :key="att.id">
                     <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                     <th class="border border-black px-2 py-1" colspan="4">Remarks</th>
                 </template>

                <!-- <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                <th class="border border-black px-2 py-1" colspan="4">Remarks</th> -->

                <!-- <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                <th class="border border-black px-2 py-1" colspan="4">Remarks</th> -->
            </tr>
            </thead>
            <tbody>
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
                        <td class="border border-black px-2 py-1 text-gray-500 text-center" colspan="4">
                            {{ attendance[5 + (mIndex * 2)] ?? 'none' }}
                        </td>
                    </template>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-between items-center pt-6 no-print">
            <div class="mt-2 text-xs">
                <strong>Legend:</strong> T - Tardy, CC - Cutting Class, EA - Excused Absence, UA - Unexcused Absence
            </div>
            <button @click.prevent="printMonthlyAttendanceReport" class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-indigo-50 font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">
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
                            <div class="flex gap-2 items-center">
                                <img src="/img/pisaylogo.png" class="h-[70px]" alt="Pisay Logo">
                                <div class="flex flex-col gap-0">
                                    <span class="text-left">
                                        Republic of the Philippines
                                    </span>
                                    <span class="text-left text-xs leading-normal">
                                        DEPARTMENT OF SCIENCE AND TECHNOLOGY
                                    </span>
                                    <span class="text-left font-bold text-xs leading-normal">
                                        PHILIPPINE SCIENCE HIGH SCHOOL CARAGA-REGION CAMPUS
                                    </span>
                                    <span class="text-left text-xs leading-normal">
                                        CURRICULUM AND INSTRUCTION DIVISION
                                    </span>
                                </div>
                            </div>
                            <img src="/img/bplogo.png" class="h-[70px]" alt="Pisay Logo">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div id="printContent" class="w-full flex flex-col bg-white">
                                <div class="flex-col w-full font-bold text-lg mb-6">
                                    <span class="text-center block uppercase">
                                        {{ club?.club?.name }}
                                    </span>
                                    <span class="text-center block uppercase mb-4">

                                    </span>
                                    <span class="text-center block uppercase">
                                        Monthly Attendance Report
                                    </span>
                                    <span class="text-center block text-sm uppercase mb-4">
                                        For the month of {{ formatMonth(selectedMonth) }}
                                    </span>
                                </div>

                                <table class="table-auto border-collapse w-full border border-black text-xs">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="border border-black px-2 py-1 w-10">#</th>
                                        <th rowspan="2" class="border border-black px-4 py-1">OFFICERS / MEMBERS<br><span class="text-[10px]">(Alphabetical Order; Last Name, First Name)</span></th>
                                        <th rowspan="2" class="border border-black px-2 py-1 w-16">GRADE<br>LEVEL</th>
                                        <th rowspan="2" class="border border-black px-4 py-1">SECTION</th>

                                        <!-- Repeat for number of meetings -->
                                        <th v-for="att in props.attendance" colspan="5" class="border border-black px-2 py-1">
                                            <div class="text-center font-bold">{{ fullDate(att.date) }}</div>
                                            <!-- <div class="flex justify-between text-[10px]">
                                                <span>Date: ________</span>
                                                <span>NATURE: ________</span>
                                            </div> -->
                                            <!-- <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div> -->
                                        </th>

                                        <!-- <th colspan="5" class="border border-black px-2 py-1">
                                            <div class="text-center font-bold">MEETING #</div>
                                            <div class="flex justify-between text-[10px]">
                                                <span>Date: ________</span>
                                                <span>NATURE: ________</span>
                                            </div>
                                            <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                                        </th>

                                        <th colspan="5" class="border border-black px-2 py-1">
                                            <div class="text-center font-bold">MEETING #</div>
                                            <div class="flex justify-between text-[10px]">
                                                <span>Date: ________</span>
                                                <span>NATURE: ________</span>
                                            </div>
                                            <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                                        </th> -->
                                    </tr>
                                    <tr>
                                        <!-- Repeat headers for each meeting -->
                                        <template v-for="att in props.attendance" :key="att.id">
                                            <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                                            <th class="border border-black px-2 py-1" colspan="4">Remarks</th>
                                        </template>

                                        <!-- <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                                        <th class="border border-black px-2 py-1" colspan="4">Remarks</th> -->

                                        <!-- <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                                        <th class="border border-black px-2 py-1" colspan="4">Remarks</th> -->
                                    </tr>
                                    </thead>
                                    <tbody>
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
                                                <td class="border border-black px-2 py-1 text-gray-500 text-center" colspan="4">
                                                    {{ attendance[5 + (mIndex * 2)] ?? '-' }}
                                                </td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="flex flex-col gap-6 mt-6" :class="{ 'page-break-before': breakpoint }">
                                    <div class="flex justify-start">
                                        <div class="flex flex-col flex-1">
                                            <span class="mb-6 text-sm">Checked By:</span>
                                            <span class="font-bold underline text-md uppercase">
                                                {{ page.props.auth.user.name }}
                                            </span>
                                            <span class="text-md">
                                                {{ page.props?.auth.user?.club_registers?.[0]?.club?.name }} Adviser
                                            </span>
                                            <span class="text-sm">Date Printed: <span id="printed-date">{{ new Date().toLocaleString() }}</span></span>
                                        </div>

                                        <div class="flex flex-col flex-1">
                                            <span class="mb-6 text-sm">Reviewed by:</span>
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
import { fullDate, ucWords, middleInitials, formatMonth } from '@/composables/utilities';
import MainLayout from '@/Layouts/MainLayout.vue';
import { computed, onMounted, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3'
defineOptions({
    layout: MainLayout
})
const page = usePage()
const props = defineProps({
    attendance: {
        type: Array,
        required: true
    },
    months: {
        type: Array,
        required: true
    },
    month: {
        type: String,
        required: true
    }
})
const selectedMonth = ref('')
const getMonthlyAttendance = (event: any) => {
    selectedMonth.value = event.target.value
    router.get(route('club.attendance.monthly', {club_id: page.props?.auth.user?.club_registers?.[0]?.id, month: selectedMonth.value}))
}
const sortLearners = (learners: any) => {
    if(!learners) return []
    return [...learners].sort((a: any, b: any) => {
        return a.learner.last_name.localeCompare(b.learner.last_name)
    })
}
const status = {
    'present': 'P',
    'tardy': 'T',
    'excused_absence': 'EA',
    'unexcused_absence': 'UA',
    'cutting_classes': 'CC',
}
const statusColor = {
    'present': 'text-indigo-500',
    'tardy': 'text-yellow-500',
    'excused_absence': 'text-green-500',
    'unexcused_absence': 'text-red-500',
    'cutting_classes': 'text-orange-500',
}
const printMonthlyAttendanceReport = () => {
    window.print()
}
const consolidatedAttendance = computed(() => {
    const finalData = []
    props.attendance.forEach((attendance: any, i: number) => {
        let $learners = sortLearners(attendance.club_attendance_learners)
        $learners.forEach((learner, index: number) => {
            if(i == 0) {
                finalData[index] = []
                finalData[index].push(learner.learner.id)
                finalData[index].push(ucWords(learner.learner.last_name + ', ' + learner.learner.first_name + ' ' + middleInitials(learner.learner.middle_name ?? '')))
                finalData[index].push(learner.learner.current_enrollment.section.grade_level_id + 6)
                finalData[index].push(learner.learner.current_enrollment.section.section_name)
                finalData[index].push(learner.status)
                finalData[index].push(learner.delinquent?.remarks ?? '-')
            } else {
                finalData.forEach((data: any) => {
                    if(data[0] == learner.learner.id) {
                        data.push(learner.status)
                        data.push(learner.delinquent?.remarks ?? '-')
                    }
                })
            }
        })
    })
    return finalData
})
onMounted(() => {
    selectedMonth.value = props.month ?? ''
})
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
