<template>
    <div class="p-6 tab-content">
        <div class="flex items-center justify-between mb-6">
            <div class="flex flex-col gap-2">
                <h3 class="text-2xl font-semibold text-gray-800">Club Attendance Records</h3>
                <p class="text-gray-600">{{ props.club?.name }}</p>
            </div>
            <button @click="makeAttendance" class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">
                Record New Attendance
            </button>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th v-for="status in attendanceStatus()" :key="status.value" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ status.abv }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody v-if="props.attendance.length > 0" class="bg-white divide-y divide-gray-200">
                    <tr v-for="(attendance, index) in props.attendance" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <span class="mr-2">
                                {{ fullDate(attendance.date) }}
                            </span>
                            <span v-if="attendance.date === new Date().toISOString().split('T')[0]">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Today
                                </span>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ attendance.activity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-indigo-500">{{ totalPresent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-500">{{ totalExcusedAbsent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-500">{{ totalUnexcusedAbsent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-yellow-500">{{ totalTardy(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ totalCuttingClasses(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ totalMembers(attendance) }}</td>
                        <td class="flex items-center px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <Link :href="route('club.attendance.show', { attendance_id: attendance.id })" class="text-indigo-600 mr-3 hover:text-indigo-900">View</Link>
                            <Link v-if="attendance.date === new Date().toISOString().split('T')[0]" :href="route('club.attendance.edit', { attendance_id: attendance.id })" class="text-green-600 hover:text-green-900">Edit</Link>
                            <svg v-else class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.5a4.5 4.5 0 00-9 0v3M5.25 10.5h13.5a.75.75 0 01.75.75v7.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18.75v-7.5a.75.75 0 01.75-.75z" />
                            </svg>
                            <!-- <Link :href="route('club.attendance.edit', { attendance_id: attendance.id })" class="text-green-600 hover:text-green-900">Edit</Link> -->
                        </td>
                    </tr>
                </tbody>
                <tbody v-else class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No attendance records yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { fullDate, attendanceStatus } from '@/composables/utilities'

defineOptions({
  layout: MainLayout
})
const page = usePage()
const props = defineProps({
    attendance: Array,
    delinquents: Array
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
const makeAttendance = () => {
    if(props.attendance?.[0]?.date === new Date().toISOString().split('T')[0]) {
        if(confirm('Today\'s attendance has already been recorded. Would you like to proceed?')) {
            router.visit(route('club.attendance.create', { club_register_id: page.props?.auth.user?.club_registers?.[0].id }))
        }
    } else {
        router.visit(route('club.attendance.create', { club_register_id: page.props?.auth.user?.club_registers?.[0].id }))
    }
}

</script>

<style>

</style>
