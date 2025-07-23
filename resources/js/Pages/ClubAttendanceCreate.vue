<template>
    <div class="page">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">
            Attendance Recording
        </h1>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                Create New Session
            </h2>
            <form @submit.prevent="submitAttendance" class="space-y-6">
                <div>
                    <label
                        for="session-club"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Club:</label
                    >
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ club?.club?.name }}</span>
                    <!-- <select
                        v-if="club?.club"
                        id="session-club"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option
                            v-for="clb in club?.club"
                            :key="clb?.id"
                            :value="clb?.id"
                        >
                            {{ clb?.name }}
                        </option>
                    </select> -->
                </div>
                <div>
                    <label
                        for="session-date"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Date:</label
                    >
                    <input
                        required
                        v-model="clubAttendance.date"
                        type="date"
                        :max="new Date().toISOString().split('T')[0]"
                        id="session-date"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
                <div>
                    <label
                        for="session-activity"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Activity:</label
                    >
                    <input
                        required
                        v-model="clubAttendance.activity"
                        type="text"
                        id="session-activity"
                        placeholder="e.g., Weekly Meeting, Field Trip"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

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
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Present
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Absent
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Late
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
                            <tr v-for="(member, index) in sortedMembers" :key="index">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ index + 1 }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ member?.learner_name }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <input
                                        v-model="member.status"
                                        type="radio"
                                        :name="`member-${member.learner_id}-status`"
                                        value="present"
                                        checked
                                        class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                    />
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <input
                                        v-model="member.status"
                                        type="radio"
                                        :name="`member-${member.learner_id}-status`"
                                        value="absent"
                                        class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out"
                                    />
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <input
                                        v-model="member.status"
                                        type="radio"
                                        :name="`member-${member.learner_id}-status`"
                                        value="late"
                                        class="form-radio h-4 w-4 text-yellow-600 transition duration-150 ease-in-out"
                                    />
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <input
                                        v-model="member.remarks"
                                        type="text"
                                        placeholder="Optional remarks"
                                        class="p-2 border border-gray-300 rounded-md text-sm w-full"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-6">
                    <button
                        type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Submit Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { middleInitials } from '@/composables/utilities';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

defineOptions({
    layout: MainLayout
})
const page = usePage()
const props = defineProps({
    club: {
        type: Object,
        required: true,
    },
})
let clubAttendance = useForm({
    club_register_id: null,
    date: '',
    activity: '',
    members: []
})
const setClubAttendance = () => {
    clubAttendance.club_register_id = props.club?.id
    clubAttendance.date = new Date().toISOString().split('T')[0]
    clubAttendance.activity = 'Weekly Meeting'
    clubAttendance.members = []
    props.club?.club?.learners.forEach((learner: any) => {
        clubAttendance.members.push({
            learner_id: learner.id,
            learner_name: learner.last_name + ', ' + learner.first_name + ' ' + middleInitials(learner.middle_name ?? ''),
            status: 'present',
            remarks: '',
        })
    })
}
const sortedMembers = computed(() => {
    return [...clubAttendance.members].sort((a, b) => {
        return a.learner_name.localeCompare(b.learner_name)
    })
})
const submitAttendance = () => {
    clubAttendance.post(route('club.attendance.store'), {
        onSuccess: () => {
            toast.success('Attendance submitted successfully')
        },
        onError: () => {
            toast.error('Failed to submit attendance')
        }
    })
}
onMounted(() => {
    setClubAttendance()
})
</script>

<style></style>
