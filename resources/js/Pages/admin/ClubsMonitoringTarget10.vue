<template>
    <div class="min-h-screen">
         <h2 class="text-lg font-semibold text-gray-800 mb-2">
             Target 10: 100% of the Quarterly Accomplishment Report prepared according to the standards set and submitted to ALP coordinator as scheduled
             <span class="italic text-gray-600">(Printed copies submitted at the end of the quarter)</span>
         </h2>

         <div class="overflow-x-auto rounded-lg shadow">
             <table class="min-w-full border border-gray-200 bg-white">
             <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                 <tr>
                 <th rowspan="2" class="px-4 py-3 text-left text-sm font-semibold border-r border-indigo-400">
                     Adviser’s Name
                 </th>
                 <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     1st Quarter
                 </th>
                 <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     2nd Quarter
                 </th>
                 <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold">
                     Average
                 </th>
                 </tr>
                 <tr class="bg-indigo-100 text-indigo-900">

                 <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                 <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                 <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">T</th>

                 <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                 <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                 <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">T</th>

                 <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                 <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                 <th class="px-2 py-2 text-sm font-medium">T</th>
                 </tr>
             </thead>
             <tbody class="divide-y divide-gray-200">
                <tr v-for="adviser,index in advisers">
                    <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200">
                        {{ adviser }}
                    </td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasAccomplishementReport(adviser) ? '5' : '0' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasAccomplishementReport(adviser) ? '5' : '0' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasAccomplishementReport(adviser) ? '5' : '0' }}</td>

                    <td class="px-2 py-2 text-center text-sm border-r"></td>
                    <td class="px-2 py-2 text-center text-sm border-r"></td>
                    <td class="px-2 py-2 text-center text-sm border-r"></td>

                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasAccomplishementReport(adviser) ? '5' : '0' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasAccomplishementReport(adviser) ? '5' : '0' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasAccomplishementReport(adviser) ? '5' : '0' }}</td>
                </tr>
                 <!-- <tr v-if="sortedData.length === 0">
                     <td colspan="12" class="text-center py-4">
                         No Data
                     </td>
                 </tr> -->
             </tbody>
             </table>
         </div>

         <div class="mt-4 text-sm text-gray-600 space-y-1">
             <div>
                 <h2 class="font-bold">
                     Quality:
                 </h2>
                 <div class="flex flex-col pl-4">
                     <p>
                         <b>5-</b> Well organized; no lapse in grammar or error in content
                     </p>
                     <p>
                         <b>4-</b> one or two minor errors
                     </p>
                     <p>
                         <b>3-</b> More than two minor errors
                     </p>
                     <p>
                         <b>2-</b> one or two major errors; major revision needed
                     </p>
                     <p>
                         <b>1-</b> Needs total revision
                     </p>
                 </div>
             </div>
             <div>
                 <h2 class="font-bold">
                     Efficiency:
                 </h2>
                 <div class="flex flex-col pl-4">
                     <p>
                         <b>5-</b> Met targets for accomplishments requiring 100% of the targets
                     </p>
                     <p>
                         <b>2-</b> Fell short of the targets for accomplishments requiring 100% of the targets
                     </p>
                 </div>
             </div>
             <div>
                 <h2 class="font-bold">
                     Timeliness:
                 </h2>
                 <div class="flex flex-col pl-4">
                     <p>
                         <b>Scheduled date: </b> Within the day (via Portal)
                     </p>
                     <p>
                         <b>Printed copies: </b> 3rd WD of the following quarter
                     </p>
                     <p>
                         <b>5-</b> on schedule
                     </p>
                     <p>
                         <b>1-</b> beyond schedule
                     </p>
                 </div>
             </div>
         </div>
     </div>
 </template>

 <script lang="ts" setup>
 import { computed } from 'vue'
 import { fullDate } from '@/composables/utilities';

 const props = defineProps({
     advisers: Array,
     accomplishment_reports: Array,
 })
 const hasAccomplishementReport = (name:string) => {
    return props.accomplishment_reports.some((report) => report.user.name === name)
 }
 </script>

 <style>

 </style>
