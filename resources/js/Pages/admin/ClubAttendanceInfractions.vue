<template>
    <MainLayout class="no-print">
        <div class="no-print w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-8 my-8">
                <!-- Left: Title -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Attendance Infractions Summary</h3>
                    <p class="text-gray-600 text-sm">List of students who are delinquent in their club attendance</p>
                </div>
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Infractions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="(infraction, index) in sortedInfractions" :key="infraction.id"
                  class="hover:bg-gray-100 cursor-pointer"
                >
                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                        {{ index + 1 }}
                    </td>
                    <td class="px-4 py-4 text-sm whitespace-nowrap font-medium text-gray-900">
                        {{ ucWords(infraction?.last_name ?? '') }}, {{ ucWords(infraction?.first_name ?? '') }} {{ middleInitials(infraction?.middle_name ?? '') }}
                    </td>
                    <td class="px-4 py-4 text-sm whitespace-nowrap font-medium text-gray-900">
                        {{ ucWords(infraction?.club_attendance?.[0]?.club_register?.club?.name ?? '') }}
                    </td>
                    <td class="px-4 py-4 text-sm whitespace-nowrap font-medium text-gray-900">
                        <div class="flex flex-col gap-1">
                            <span v-for="(attendance, index) in infraction?.club_attendance" :key="index">{{fullDate(attendance.date) + ' - ' + ucWords(removeUnderScore(attendance?.pivot?.status)) }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm whitespace-nowrap font-medium text-gray-900">
                        <span :class="{ 'text-red-500': infraction?.club_attendance?.length >= 5, 'text-yellow-500': infraction?.club_attendance?.length === 4 }">{{ infraction?.club_attendance?.length }}</span>
                    </td>
                </tr>
                <tr v-if="props.infractions.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No infractions found.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
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
    infractions: Array,
})
const sortedInfractions = computed(() => {
    return props.infractions.sort((a, b) => {
        return b.club_attendance.length - a.club_attendance.length;
    });
});
</script>
