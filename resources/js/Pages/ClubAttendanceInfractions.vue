<template>
    <div class="tab-content">
        <div class="flex items-center justify-between mb-6">
            <div class="flex flex-col gap-2">
                <h3 class="text-2xl font-semibold text-gray-800">Attendance Infractions Summary</h3>
                <p class="text-gray-600">{{ props.club?.club?.name }}</p>
            </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th v-for="status in attendanceStatus()" :key="status.value" scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ status.abv }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UA/CC</th>
                    </tr>
                </thead>
                <tbody v-if="sortedInfractions.length > 0" class="bg-white divide-y divide-gray-200">
                    <tr v-for="(sortedInfractions, index) in sortedInfractions" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <span class="mr-2">
                                {{ sortedInfractions.last_name }}, {{ sortedInfractions.first_name }} {{ sortedInfractions.middle_name?.slice(0, 1) }}.
                            </span>
                        </td>
                        <td class="text-center">
                            {{ sortedInfractions.attendance.present }}
                        </td>
                        <td class="text-center">
                            {{ sortedInfractions.attendance.excused_absence }}
                        </td>
                        <td class="text-center">
                            {{ sortedInfractions.attendance.unexcused_absence }}
                        </td>
                        <td class="text-center">
                            {{ sortedInfractions.attendance.tardy }}
                        </td>
                        <td class="text-center">
                            {{ sortedInfractions.attendance.cutting_classes }}
                        </td>
                        <td class="text-center">
                            {{ sortedInfractions.attendance.total }}
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
import { onMounted, ref, computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { fullDate, attendanceStatus, formatDate } from '@/composables/utilities'

defineOptions({
  layout: MainLayout
})
const page = usePage()
const props = defineProps({
    club: Object,
    attendance: {
        type: Array,
        default: () => []
    }
})
const learners = ref([])
const infractions = computed(() => {
    let infra = JSON.parse(JSON.stringify(learners.value))
    infra.forEach((learner: any) => {
        learner.attendance = {
            present: 0,
            excused_absence: 0,
            unexcused_absence: 0,
            tardy: 0,
            cutting_classes: 0,
            total: 0
        }
        props.club.club_attendances.forEach((attendance: any) => {
            attendance.club_attendance_learner.forEach((ln: any) => {
                if (learner.id === ln.id) {
                    if(ln.pivot.status === 'present') {
                        learner.attendance.present++
                    }
                    if(ln.pivot.status === 'excused_absence') {
                        learner.attendance.excused_absence++
                    }
                    if(ln.pivot.status === 'unexcused_absence') {
                        learner.attendance.unexcused_absence++
                        learner.attendance.total++
                    }
                    if(ln.pivot.status === 'tardy') {
                        learner.attendance.tardy++
                    }
                    if(ln.pivot.status === 'cutting_classes') {
                        learner.attendance.cutting_classes++
                        learner.attendance.total++
                    }
                }
            })
        })
    })
    return infra
})
const sortedInfractions = computed(() => {
  return [...infractions.value].sort((a, b) => a.last_name.localeCompare(b.last_name))
})
onMounted(() => {
    learners.value = props.club.club_attendances[0].club_attendance_learner ?? []
})
</script>

<style>

</style>
