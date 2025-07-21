<template>
    <div class="p-6 tab-content">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Club Attendance Records</h3>
        <p class="text-gray-600 mb-4">View past attendance sessions for this club.</p>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody v-if="props.attendance.length > 0" class="bg-white divide-y divide-gray-200">
                    <tr v-for="(attendance, index) in props.attendance" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ attendance.date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Weekly Meeting</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ attendance.present }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ attendance.total }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">View</a>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No attendance records yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="mt-6 px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Record New Attendance</button>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';

defineOptions({
  layout: MainLayout
})
const props = defineProps({
    attendance: Array
})
const recentAttendance = ref([
    { date: '2024-10-25', present: 20, total: 25 },
    { date: '2024-10-24', present: 28, total: 30 },
    { date: '2024-10-23', present: 15, total: 18 },
]);

</script>

<style>

</style>
