<template>
    <div class="tab-content">
        <div class="flex items-center justify-between mb-6">
            <div class="flex flex-col gap-2">
                <h3 class="text-2xl font-semibold text-gray-800">Advisers Attendance Records</h3>
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
                        <th v-for="status in advisersAttendanceStatus()" :key="status.value" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ status.abv }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody v-if="props.attendances.length > 0" class="bg-white divide-y divide-gray-200">
                    <tr v-for="(attendance, index) in props.attendances" :key="index">
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
                        <td class="px-6 py-4 max-w-[200px] text-center text-sm text-indigo-500">{{ totalPresent(attendance) }}</td>
                        <td class="px-6 py-4 max-w-[200px] text-center text-sm text-red-500">{{ totalAbsent(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-yellow-500">{{ totalTardy(attendance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ totalMembers(attendance) }}</td>
                        <td class="flex items-center px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <Link :href="route('admin.advisers.attendance.show', { id: attendance.id })" class="text-indigo-600 mr-3 hover:text-indigo-900">View</Link>
                            <!-- <Link :href="route('club.attendance.edit', { attendance_id: attendance.id })" class="text-green-600 hover:text-green-900">Edit</Link> -->
                        </td>
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
import { ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { fullDate, advisersAttendanceStatus } from '@/composables/utilities'

defineOptions({
  layout: MainLayout
})
const page = usePage()
const props = defineProps({
    attendances: Array,
})
const totalPresent = (attendance: any) => {
    return attendance.users.filter((adviser: any) => adviser.pivot.status === 'present').length
}
const totalMembers = (attendance: any) => {
    return attendance.users.length
}
const totalAbsent = (attendance: any) => {
    return attendance.users.filter((adviser: any) => adviser.pivot.status === 'absent').length
}
const totalTardy = (attendance: any) => {
    return attendance.users.filter((adviser: any) => adviser.pivot.status === 'tardy').length
}
const makeAttendance = () => {
    router.visit(route('admin.advisers.attendance.create'))
}

</script>

<style>

</style>
