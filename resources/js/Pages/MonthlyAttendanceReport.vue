<template>
    <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
            <div >
                <h3 class="text-2xl font-semibold text-gray-800">Monthly Attendance Report</h3>
                <p class="text-gray-600 text-sm mb-4"></p>
            </div>
        </div>

        <table class="table-auto border-collapse w-full border border-black text-xs">
            <thead>
            <tr>
                <th rowspan="2" class="border border-black px-2 py-1 w-10">#</th>
                <th rowspan="2" class="border border-black px-4 py-1">OFFICERS / MEMBERS<br><span class="text-[10px]">(Alphabetical Order; Last Name, First Name)</span></th>
                <th rowspan="2" class="border border-black px-2 py-1 w-16">GRADE<br>LEVEL</th>
                <th rowspan="2" class="border border-black px-4 py-1">SECTION</th>

                <!-- Repeat for number of meetings -->
                <th v-for="att in props.attendance" colspan="5" class="border border-black px-2 py-1">
                    <div class="text-center font-bold">{{ fullDate(att.date) }}</div>
                    <!-- <div class="flex justify-between text-[10px]">
                        <span>Date: ________</span>
                        <span>NATURE: ________</span>
                    </div> -->
                    <!-- <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div> -->
                </th>

                <!-- <th colspan="5" class="border border-black px-2 py-1">
                    <div class="text-center font-bold">MEETING #</div>
                    <div class="flex justify-between text-[10px]">
                        <span>Date: ________</span>
                        <span>NATURE: ________</span>
                    </div>
                    <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                </th>

                <th colspan="5" class="border border-black px-2 py-1">
                    <div class="text-center font-bold">MEETING #</div>
                    <div class="flex justify-between text-[10px]">
                        <span>Date: ________</span>
                        <span>NATURE: ________</span>
                    </div>
                    <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                </th> -->
            </tr>
            <tr>
                <!-- Repeat headers for each meeting -->
                <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                <th class="border border-black px-2 py-1" colspan="4">Remarks</th>

                <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                <th class="border border-black px-2 py-1" colspan="4">Remarks</th>

                <th class="border border-black px-2 py-1 w-[56px]" colspan="1">Status</th>
                <th class="border border-black px-2 py-1" colspan="4">Remarks</th>
            </tr>
            </thead>
            <tbody>
            <!-- Example row -->
                <tr v-for="(attendance, index) in consolidatedAttendance" :key="index">
                    <td class="border border-black px-2 py-1 text-center">{{ index + 1 }}</td>
                    <td class="border border-black px-2 py-1">{{ attendance[1]?? '' }}</td>
                    <td class="border border-black px-2 py-1 text-center">{{ attendance[2]?? '' }}</td>
                    <td class="border border-black px-2 py-1 text-center">{{ attendance[3]?? '' }}</td>

                    <!-- Meeting cells -->
                    <template v-for="(meeting, mIndex) in (attendance.length - 4) / 2" :key="mIndex">
                        <td class="border border-black px-2 py-1 text-center font-bold" :class="statusColor[attendance[4 + (mIndex * 2)] ?? '']" colspan="1">
                            {{ status[attendance[4 + (mIndex * 2)] ?? ''] }}
                        </td>
                        <td class="border border-black px-2 py-1 text-gray-500 text-center" colspan="4">
                            {{ attendance[5 + (mIndex * 2)] ?? 'none' }}
                        </td>
        </template>
                </tr>
            </tbody>
        </table>

        <div class="mt-2 text-xs">
            <strong>Legend:</strong> T - Tardy, CC - Cutting Class, EA - Excused Absence, UA - Unexcused Absence
        </div>
    </div>
</template>

<script lang="ts" setup>
import { fullDate, ucWords, middleInitials } from '@/composables/utilities';
import MainLayout from '@/Layouts/MainLayout.vue';
import { computed, onMounted } from 'vue';
defineOptions({
    layout: MainLayout
})

const props = defineProps({
    attendance: {
        type: Array,
        required: true
    }
})
const sortLearners = (learners: any) => {
    return [...learners].sort((a: any, b: any) => {
        return a.last_name.localeCompare(b.last_name)
    })
}
const status = {
    'present': 'P',
    'tardy': 'T',
    'excused_absence': 'EA',
    'unexcused_absence': 'UA',
    'cutting_classes': 'CC',
}
const statusColor = {
    'present': 'text-indigo-500',
    'tardy': 'text-yellow-500',
    'excused_absence': 'text-green-500',
    'unexcused_absence': 'text-red-500',
    'cutting_classes': 'text-orange-500',
}
const consolidatedAttendance = computed(() => {
    const finalData = []
    props.attendance.forEach((attendance: any, i: number) => {
        let $learners = sortLearners(attendance.club_attendance_learner)
        $learners.forEach((learner, index: number) => {
            if(i == 0) {
                finalData[index] = []
                finalData[index].push(learner.id)
                finalData[index].push(ucWords(learner.last_name + ', ' + learner.first_name + ' ' + middleInitials(learner.middle_name ?? '')))
                finalData[index].push(learner.current_enrollment.section.grade_level_id + 6)
                finalData[index].push(learner.current_enrollment.section.section_name)
                finalData[index].push(learner.pivot.status)
                finalData[index].push(learner.pivot.remarks)
            } else {
                finalData.forEach((data: any) => {
                    if(data[0] == learner.id) {
                        data.push(learner.pivot.status)
                        data.push(learner.pivot.remarks)
                    }
                })
            }
        })
    })
    return finalData
})

</script>

<style>

</style>
