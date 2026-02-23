<template>
    <div>
        <div class="no-print w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-8 my-8">
                <!-- Left: Title -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Log Records</h3>
                    <p class="text-gray-600 text-sm">Login & Logout Records</p>
                </div>
                <!-- <select class="border w-1/5 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select Date</option>
                    <option v-for="(date,index) in props.attendanceDates" :key="index" :value="formatDateLocal(date)">{{ fullDate(date) }}</option>
                </select> -->
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Log Type</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Log Date</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="(log, index) in props.logs" :key="log.id"
                  class="hover:bg-gray-100 cursor-pointer"
                >
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <div class="flex flex-col gap-1">
                            <span>{{ ucWords(log.user?.name ?? '') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ log.log_type }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ fullDateTime(log.created_at) }}
                    </td>
                </tr>
                <tr v-if="props.logs.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No log records found.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </div>
    </template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { ucWords, fullDateTime } from '@/composables/utilities';
defineOptions({
    layout: MainLayout,
});
const props = defineProps({
    logs: {
        type: Array,
        required: true,
    },
});
</script>
