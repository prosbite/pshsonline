<template>
    <MainLayout class="no-print">
        <div class="no-print w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-8 my-8">
                <!-- Left: Title -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Attendance Infractions</h3>
                    <p class="text-gray-600 text-sm">List of students who are delinquent in their club attendance</p>
                </div>
                <select @change="getAttendance($event)" id="attendanceDate" v-model="selectedDate" required class="border w-1/5 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select Date</option>
                    <option v-for="(date,index) in props.attendanceDates" :key="index" :value="formatDateLocal(date)">{{ fullDate(date) }}</option>
                </select>
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions Taken</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="(delinquent, index) in sortedDelinquents" :key="delinquent.id"
                  class="hover:bg-gray-100 cursor-pointer"
                >
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <div class="flex flex-col gap-1">
                            <span>{{ ucWords(delinquent?.club_attendance_learner?.learner?.last_name ?? '') }}, {{ ucWords(delinquent?.club_attendance_learner?.learner?.first_name ?? '') }} {{ middleInitials(delinquent?.club_attendance_learner?.learner?.middle_name ?? '') }}</span>
                            <span class="text-sm text-gray-500">{{ delinquent?.club_attendance_learner?.learner?.current_enrollment?.section?.grade_level?.grade_level }} - {{ delinquent?.club_attendance_learner?.learner?.current_enrollment?.section?.section_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ delinquent?.club_attendance?.club_register?.club?.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{  ucWords(removeUnderScore(delinquent?.club_attendance_learner?.status)) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-[150px]">
                        {{  delinquent?.club_attendance_learner?.remarks ? ucWords(delinquent?.club_attendance_learner?.remarks) : 'None' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-[150px]">
                        {{  delinquent?.resolved ? 'Resolved - ' + ucWords(removeUnderScore(delinquent?.actions_taken)) : 'Unresolved' }}
                    </td>
                </tr>
                <tr v-if="props.delinquents.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No delinquent learners found.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="no-print flex justify-end items-center p-4 py-8 gap-4">
                <button v-if="page?.props?.auth?.user?.role === 'admin'" @click.prevent="downloadCSV" class="flex items-center gap-2 px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white hover:text-green-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <!-- File icon with folded corner -->
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <!-- Download arrow -->
                        <path d="M12 11v6"/>
                        <path d="M9 14l3 3 3-3"/>
                    </svg>

                    Download CSV
                </button>
                <button v-if="page?.props?.auth?.user?.role === 'admin'" @click.prevent="printDelinquents" class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-indigo-50 font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">
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
                                    <span class="text-center block uppercase leading-tight text-2xl">
                                        Attendance Infractions
                                    </span>
                                    <span class="text-center block uppercase leading-tight">
                                        {{ fullDate(selectedDate) }}
                                    </span>
                                    <span class="text-center block w-full text-sm leading-tight">S.Y. {{ page.props.sy.year_start }} - {{ page.props.sy.year_end }}</span>
                                </div>

                                <table class="w-full border border-black border-collapse mb-10 text-sm">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border border-black px-1 py-1" colspan="2">
                                                Name
                                            </th>
                                            <th class="border border-black px-1 py-1">
                                                Grade/Section
                                            </th>
                                            <th class="border border-black px-1 py-1">
                                                Club
                                            </th>
                                            <th class="border border-black px-1 py-1">
                                                Status
                                            </th>
                                            <th class="border border-black px-1 py-1">
                                                Remarks
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="learner,index in sortedDelinquents">
                                            <td class="border border-black p-1 text-center">
                                                {{ index + 1 }}
                                            </td>
                                            <td class="border border-black px-1 py-1 text-left">
                                                {{ ucWords(learner?.club_attendance_learner?.learner?.last_name) + ', ' + ucWords(learner?.club_attendance_learner?.learner?.first_name) + ' ' + middleInitials(learner?.club_attendance_learner?.learner?.middle_name?? '') }}
                                            </td>
                                            <td class="border border-black px-1 py-1 text-center">
                                                {{ learner.club_attendance_learner?.learner?.current_enrollment?.section?.grade_level?.grade_level }}-
                                                {{ learner.club_attendance_learner?.learner?.current_enrollment?.section?.section_name }}
                                            </td>
                                            <td class="border border-black px-1 py-1 text-center">
                                                {{ learner.club_attendance?.club_register?.club?.name }}
                                            </td>
                                            <td class="border border-black px-1 py-1 text-center">
                                                {{ ucWords(removeUnderScore(learner.club_attendance_learner?.status)) }}
                                            </td>
                                            <td class="border border-black px-1 py-1 text-center">
                                                {{  learner?.club_attendance_learner?.remarks ? ucWords(learner?.club_attendance_learner?.remarks) : '-' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="flex flex-col gap-24 mt-2">
                                    <div class="flex gap-16 justify-start">
                                        <div class="flex flex-col ">
                                            <span class="mb-6 text-sm">Prepared by:</span>
                                            <span class="font-bold underline text-md">
                                                GRETCHEN MAE B. EMPUESTO, PhD
                                            </span>
                                            <span class="text-md">
                                                ALP Coordinator
                                            </span>
                                        </div>

                                        <div class="flex flex-col">
                                            <span class="mb-6 text-sm">Noted by:</span>
                                            <span class="font-bold underline text-md uppercase">
                                                JOHN RIDAN D. DECHUSA
                                            </span>
                                            <span class="text-md">
                                                Assistant CID Chief for Student Affairs
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
    </MainLayout>

    </template>

<script lang="ts" setup>
import { ucWords, middleInitials, fullDate, removeUnderScore, exportToCSV, formatDateLocal } from '@/composables/utilities';
import { computed, onMounted, ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
const page = usePage();
const props = defineProps({
    delinquents: Array,
    attendanceDates: Array,
    date: String
})
const selectedDate = ref(props.date)
const getAttendance = (event: any) => {
    router.get(route('admin.attendance.delinquents', { date: event.target.value }))
}
const csvFormat = computed(() => {
    let data = []
    sortedDelinquents.value.map((learner: any) => {
        data.push({
            'Last Name': learner.club_attendance_learner?.learner?.last_name,
            'First Name': learner.club_attendance_learner?.learner?.first_name,
            'Middle Name': learner.club_attendance_learner?.learner?.middle_name ?? '',
            'Grade Level': learner.club_attendance_learner?.learner?.current_enrollment?.section?.grade_level?.grade_level,
            'Section': learner.club_attendance_learner?.learner?.current_enrollment?.section?.section_name,
            'Club': learner.club_attendance?.club_register?.club?.name,
            'Status': ucWords(removeUnderScore(learner.club_attendance_learner?.status)),
            "HR Adviser's Remarks": ''
        })
    })
    return data
})
const downloadCSV = () => {
    exportToCSV(csvFormat.value, `delinquent_students_${selectedDate.value}.csv`)
}
const sortedDelinquents = computed(() => {
    return props.delinquents.sort((a:any, b:any) => {
        // first compare gradeLevel
        if (a?.club_attendance_learner?.learner?.current_enrollment?.section?.grade_level?.grade_level !== b?.club_attendance_learner?.learner?.current_enrollment?.section?.grade_level?.grade_level) {
            return a?.club_attendance_learner?.learner?.current_enrollment?.section?.grade_level?.grade_level - b?.club_attendance_learner?.learner?.current_enrollment?.section?.grade_level?.grade_level; // ascending
        }
        // if gradeLevel is the same, compare section
        return a?.club_attendance_learner?.learner?.current_enrollment?.section?.section_name.localeCompare(b?.club_attendance_learner?.learner?.current_enrollment?.section?.section_name);
    })
})
const printDelinquents = () => {
    window.print();
}
onMounted(() => {
    selectedDate.value = formatDateLocal(props.date)
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
        .to-print {
            display: block;
        }
    }
    </style>
