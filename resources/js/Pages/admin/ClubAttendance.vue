<template>
    <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
            <!-- Left: Title -->
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">Club Attendances</h3>
                <p class="text-gray-600 text-sm">Attendance of each club</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th v-for="status in attendanceStatus()" :key="status.value" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ status.abv }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr v-for="(attendance, index) in props.attendance" :key="index" class="hover:bg-gray-100 cursor-pointer">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                        <td class="flex flex-col gap-0 px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="font-semibold text-black text-lg">{{ attendance.club_register?.club?.name }}</span>
                            <span class="text-blue-600 text-sm">{{ fullDateTime(attendance.created_at) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ attendance.activity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-500 text-center">{{ totalPresent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-500 text-center">{{ totalExcusedAbsent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500 text-center">{{ totalUnexcusedAbsent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-500 text-center">{{ totalTardy(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ totalCuttingClasses(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black text-center">{{ totalMembers(attendance) }}</td>

                    </tr>
                <!-- <tr v-if="props.registered_clubs.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No clubs found.
                    </td>
                </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { attendanceStatus, fullDateTime } from '@/composables/utilities';

defineOptions({
    layout: MainLayout
})
const props = defineProps({
    attendance: {
        type: Array,
        required: true
    }
})
const totalPresent = (attendance: any) => {
    return attendance.club_attendance_learner.filter((learner: any) => learner.pivot.status === 'present').length
}
const totalMembers = (attendance: any) => {
    return attendance.club_attendance_learner.length
}
const totalUnexcusedAbsent = (attendance: any) => {
    return attendance.club_attendance_learner.filter((learner: any) => learner.pivot.status === 'unexcused_absence').length
}
const totalExcusedAbsent = (attendance: any) => {
    return attendance.club_attendance_learner.filter((learner: any) => learner.pivot.status === 'excused_absence').length
}
const totalTardy = (attendance: any) => {
    return attendance.club_attendance_learner.filter((learner: any) => learner.pivot.status === 'tardy').length
}
const totalCuttingClasses = (attendance: any) => {
    return attendance.club_attendance_learner.filter((learner: any) => learner.pivot.status === 'cutting_classes').length
}
const totalLate = (attendance: any) => {
    return attendance.club_attendance_learner.filter((learner: any) => learner.pivot.status === 'late').length
}
</script>

<style>

</style>
