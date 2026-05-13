<template>
   <div class="min-h-screen">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">
            Target 5: One (1) (f) attendance of club members checked and submitted to ALP coordinator without error every meeting
            <span class="italic text-gray-600">(Printed copies submitted at the end of the quarter)</span>
        </h2>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full border border-gray-200 bg-white">
            <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                <tr>
                <th rowspan="2" class="px-4 py-3 text-left text-sm font-semibold border-r border-indigo-400">
                    Adviser’s Name
                </th>
                    <th v-for="date in attendanceDates" :key="date" colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                        {{ fullDate(date) }}
                    </th>
                <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                    Printed Copies (1st Quarter)
                </th>
                <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                    Printed Copies (2nd Quarter)
                </th>
                <th colspan="3" class="px-4 py-2 text-center text-sm font-semibold">
                    Average
                </th>
                </tr>
                <tr class="bg-indigo-100 text-indigo-900">
                <template v-for="date in attendanceDates" :key="date">
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-medium border-r border-gray-300">T</th>
                </template>

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
                <tr v-if="sortedData.length === 0">
                    <td colspan="12" class="text-center py-4">
                        No Data
                    </td>
                </tr>
                <tr v-for="adviser,index in sortedData" class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-200">
                        {{ adviser.adviser }}
                    </td>
                    <template v-for="date in attendanceDates" :key="date">
                        <td class="px-2 py-2 text-center text-sm border-r"> {{ adviser.attendances?.[date] ? adviser.attendances[date].q : '-' }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r"> {{ adviser.attendances?.[date] ? adviser.attendances[date].e : '-' }}</td>
                        <td class="px-2 py-2 text-center text-sm border-r"> {{ adviser.attendances?.[date] ? adviser.attendances[date].t : '-' }}</td>
                    </template>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasMonthlyAttendanceReport(adviser.adviser) ? '5' : '-' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasMonthlyAttendanceReport(adviser.adviser) ? '5' : '-' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasMonthlyAttendanceReport(adviser.adviser) ? '5' : '-' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasMonthlyAttendance2Report(adviser.adviser) ? '5' : '-' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasMonthlyAttendance2Report(adviser.adviser) ? '5' : '-' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ hasMonthlyAttendance2Report(adviser.adviser) ? (isTimely(adviser.adviser) ? '5' : '1') : '-' }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ getAverageScore(adviser, adviser.totalQ, 'q') }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ getAverageScore(adviser, adviser.totalE, 'e') }}</td>
                    <td class="px-2 py-2 text-center text-sm border-r"> {{ getAverageScore(adviser, adviser.totalT, 't') }}</td>
                </tr>
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
import { fullDate, formatDateLocal } from '@/composables/utilities';

const props = defineProps({
    attendances: Object,
    advisers: Array,
    monthly_attendance_reports: Array,
    monthly_attendance_reports2: Array,
})
const validAttendances = computed(() => {
    const filtered: Record<string, any> = {}

    Object.entries(props.attendances ?? {}).forEach(([date, attendances]) => {
        if (!date || date === 'null' || date === 'undefined') {
            return
        }

        const validRows = (attendances as any[]).filter((attendance: any) => attendance?.created_at && attendance?.updated_at)
        if (validRows.length === 0) {
            return
        }

        filtered[date] = validRows
    })

    return filtered
})
const validatedFinalAttendances = computed(() => {
    return Object.fromEntries(
        Object.entries(validAttendances.value).filter(([, attendances]) => {
            return Array.isArray(attendances) && attendances.length > 0
        })
    )
})
const attendanceDates = computed(() => Object.keys(validatedFinalAttendances.value))
const attendanceCount = computed(() => {
    let count = 0
    for (let i in validatedFinalAttendances.value) {
        count++
    }
    return count
})
const hasMonthlyAttendanceReport = (adviser: string) => {
    return props.monthly_attendance_reports.some((report: any) => report.user.name === adviser)
}
const hasMonthlyAttendance2Report = (adviser: string) => {
    return props.monthly_attendance_reports2.some((report: any) => report.user.name === adviser)
}
const getSubmissionCount = (adviser: string) => {
    return [
        hasMonthlyAttendanceReport(adviser),
        hasMonthlyAttendance2Report(adviser),
    ].filter(Boolean).length
}
const getSubmissionScore = (adviser: string, axis: 'q' | 'e' | 't') => {
    const firstReportScore = hasMonthlyAttendanceReport(adviser) ? 5 : 0
    const secondReportScore = hasMonthlyAttendance2Report(adviser)
        ? (axis === 't' ? (isTimely(adviser) ? 5 : 1) : 5)
        : 0

    return firstReportScore + secondReportScore
}
const getAverageScore = (adviser: any, baseTotal: number, axis: 'q' | 'e' | 't') => {
    const totalCount = adviser.realAttendanceCount + getSubmissionCount(adviser.adviser)
    if (totalCount === 0) {
        return '-'
    }

    const totalScore = baseTotal + getSubmissionScore(adviser.adviser, axis)
    return (totalScore / totalCount).toFixed(1)
}
const isTimely = (adviser: string) => {
    const report = props.monthly_attendance_reports2?.find((report: any) => report.user.name === adviser)
    if(report){
        if (new Date(formatDateLocal(report?.created_at)) <= new Date("2025-12-11")) {
            return true
        }
    }
    return false
}
const sortedData = computed(() => {
    let advisers = props.advisers;
    const attendances = validatedFinalAttendances.value;
    const dates = attendanceDates.value;
    let finalData = []
    advisers.forEach((adviser: any, index: number) => {
        let adviserData = {
            adviser: adviser,
            attendances: {},
            totalQ: 0,
            totalE: 0,
            totalT: 0,
            realAttendanceCount: 0,
        }
        dates.forEach((date) => {
            const match = attendances[date]?.find((a: any) => a.adviser === adviser && a.created_at && a.updated_at)
            if (match) {
                adviserData.attendances[date] = match
                adviserData.totalQ += match.q
                adviserData.totalE += match.e
                adviserData.totalT += match.t
                adviserData.realAttendanceCount++
            } else {
                adviserData.attendances[date] = null
            }
        })
        finalData.push(adviserData)
    });
    return finalData
});
</script>

<style>

</style>
