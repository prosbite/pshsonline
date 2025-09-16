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
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-indigo-600">{{ total(attendance) }}</td>
                    </tr>

                </tbody>
                <tbody v-else class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No attendance records yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { fullDate, attendanceStatus, formatDate, middleInitials } from '@/composables/utilities'

defineOptions({
  layout: MainLayout
})
const page = usePage()
const props = defineProps({
    club: Object,
    attendance: Array,
})
const totalPresent = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'present').length
}
const totalUnexcusedAbsent = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'unexcused_absence').length
}
const totalExcusedAbsent = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'excused_absence').length
}
const totalTardy = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'tardy').length
}
const totalCuttingClasses = (attendance: any) => {
    return attendance.club_attendance.filter((learner: any) => learner.pivot.status === 'cutting_classes').length
}
const total = (attendance: any) => {
    return parseInt(totalPresent(attendance)) + parseInt(totalUnexcusedAbsent(attendance)) + parseInt(totalExcusedAbsent(attendance)) + parseInt(totalTardy(attendance)) + parseInt(totalCuttingClasses(attendance))
}
</script>

<style>

</style>
