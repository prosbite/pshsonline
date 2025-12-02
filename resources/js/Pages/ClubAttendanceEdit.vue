<template>
    <div class="page">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">
            Edit Attendance Record
        </h1>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 border-t-8 border-t-green-500">
            <form @submit.prevent="updateAttendance" class="space-y-6">
                <div>
                    <label
                        for="session-club"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Club:</label
                    >
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        {{ attendanceForm.attendance?.club_register?.club?.name }}
                    </span>
                </div>
                <div>
                    <label
                        for="session-club"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Date:</label
                    >
                    <div class="flex items-center w-full gap-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <span>
                            {{ fullDate(attendanceForm.attendance?.date) }}
                        </span>
                        <span v-if="attendanceForm.attendance?.date === new Date().toISOString().split('T')[0]">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Today
                            </span>
                        </span>
                    </div>
                </div>
                <div>
                    <label
                        for="session-activity"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Activity:</label
                    >
                    <input type="text" v-model="attendanceForm.attendance.activity" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div v-if="attendanceForm.attendance.remarks">
                    <label
                        for="session-activity"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Remarks:</label
                    >
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        {{ attendanceForm.attendance.remarks }}
                    </span>
                </div>


                <PreviousDelinquents :delinquents="props.delinquents" />

                <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-4">
                    Member Attendance
                </h3>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    #
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Name
                                </th>
                                <th
                                    v-for="status in attendanceStatus()" :key="status.value"
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ status.abv }}
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Remarks
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(member, index) in attendanceForm.attendance?.club_attendance_learner" :key="index">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ index + 1 }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ member?.last_name }}, {{ member?.first_name }} {{ middleInitials(member?.middle_name ?? '') }}
                                </td>
                                <td
                                    v-for="status in attendanceStatus()" :key="status.value"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <input
                                        v-model="member.pivot.status"
                                        type="radio"
                                        :name="`member-${member.id}-status`"
                                        :value="status.value"
                                        :checked="member.pivot.status === status.value"
                                        class="form-radio h-4 w-4 transition duration-150 ease-in-out"
                                        :class="status.color"
                                    />
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <input

                                        v-model="member.pivot.remarks"
                                        type="text"
                                        placeholder="Optional remarks"
                                        class="p-2 border border-gray-300 rounded-md text-sm w-full"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end gap-4 py-6 mt-10">
                    <Link
                        v-if="page.props.auth.user?.role === 'admin'"
                        :href="route('admin.attendance')"
                        class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Cancel
                    </Link>
                    <Link
                        v-else
                        :href="route('club.attendance', { club_register_id: props.attendance.club_register_id })"
                        class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { fullDate, middleInitials, attendanceStatus, removeUnderScore, ucWords, replicate, formatDate } from '@/composables/utilities';
import MainLayout from '@/Layouts/MainLayout.vue';
import { usePage, Link, useForm, router } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import PreviousDelinquents from '@/Components/delinquents/PreviousDelinquents.vue'

defineOptions({
    layout: MainLayout
})
const page = usePage()
const attendanceForm = useForm({
    attendance: {},
    delinquents: [],
})
const props = defineProps({
    attendance: {
        type: Object,
        required: true,
    },
    delinquents: {
        type: Array,
        required: true,
    },
})

const updateAttendance = () => {
    attendanceForm.attendance.date = formatDate(attendanceForm.attendance.date)
    attendanceForm.delinquents = replicate(sortedDelinquents.value)
    attendanceForm.put(route('club.attendance.update'), {
        onSuccess: () => {
            toast.success('Attendance updated successfully', {
                autoClose: 2000,
            })
        },
        onError: () => {
            toast.error('Failed to update attendance', {
                autoClose: 2000,
            })
        }
    })
}
const sortedMembers = computed(() => {
    const learners = replicate(props.attendance.club_attendance_learner)
    return [...learners].sort((a, b) => {
        return a.last_name.localeCompare(b.last_name)
    })
})
const resolveAttendance = (delinquent: any) => {
    router.put(route('club.attendance.resolve', { id: delinquent.id }),{},{
        onSuccess: () => {
            toast.success('Admission slip submitted.', {
                autoClose: 1000,
            })
            delinquent.resolved = true
        },
        onError: () => {
            toast.error('Failed to resolve attendance', {
                autoClose: 1000,
            })
        }
    })
}

// const sortedDelinquents = computed(() => {
//     const delinquents = []
//     attendanceForm.attendance.club_attendance_learner.forEach((learner: any) => {
//         if (learner.pivot.status === 'unexcused_absence' || learner.pivot.status === 'cutting_classes') {
//             delinquents.push(learner)
//             let count = 0
//             props.delinquents.forEach((delinquent: any) => {
//                 if (learner.pivot.id === delinquent.club_attendance_learner_id) {
//                     count++
//                     delinquents.push(delinquent)
//                 }
//             })
//             if (count === 0) {
//                 delinquents.push({
//                     club_attendance_learner_id: learner.pivot.id,
//                     resolved: false,
//                     link: null,
//                     resolved_by: null,
//                     remarks: null,
//                 })
//             }
//         }
//     })
//     return delinquents
// })
const sortedDelinquents = computed(() => {
    const delinquents = []
    attendanceForm.attendance.club_attendance_learner.forEach((learner: any) => {
        if (
            learner.pivot.status !== 'present'
        ) {
            const existing = props.attendance.delinquents.find(
                (delinquent: any) =>
                    learner.pivot.id === delinquent.club_attendance_learner_id
            )

            if (existing) {
                delinquents.push(existing)
            } else {
                delinquents.push({
                    club_attendance_id: attendanceForm.attendance.id,
                    club_attendance_learner_id: learner.pivot.id,
                    resolved: false,
                    link: null,
                    resolved_by: null,
                    remarks: null,
                })
            }
        }
    })
    return delinquents
})


onMounted(() => {
    attendanceForm.attendance = props.attendance
    attendanceForm.attendance.club_attendance_learner = sortedMembers.value
})
</script>

<style></style>
