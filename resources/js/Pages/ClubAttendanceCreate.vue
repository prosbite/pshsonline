<template>
    <div class="page relative">
        <div v-if="loading" class="fixed flex items-center justify-center top-0 left-0 right-0 w-full h-screen z-50 bg-black opacity-50">
            <img src="/img/intertwind_loader.gif" class="w-24 h-24" alt="">
        </div>
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
                        disabled
                        required
                        v-model="clubAttendance.date"
                        type="date"
                        :max="new Date().toISOString().split('T')[0]"
                        id="session-date"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
                <div class="mb-6">
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

                <div v-if="delinquents.length > 0" class="overflow-x-auto py-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Attendance Concerns to Resolve
                    </h2>
                    <table class="min-w-full divide-y divide-gray-200 shadow-md rounded-lg">
                        <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Student Name</th>
                            <th class="px-4 py-3 text-left">Attendance Status</th>
                            <th class="px-4 py-3 text-left">Admission Slip</th>
                            <th class="px-4 py-3 text-left">Action</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                        <!-- Example Row -->
                        <tr v-for="delinquent in delinquents" :key="delinquent.id" class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ ucWords(delinquent.learner_last_name) }}, {{ ucWords(delinquent.learner_first_name) }} {{ middleInitials(delinquent.learner_middle_name ?? '') }}</td>
                            <td class="px-4 py-3 text-red-600 font-semibold">{{ ucWords(removeUnderScore(delinquent.attendance_status)) }}</td>
                            <td class="px-4 py-3">
                            <span class="text-gray-500 italic">{{ delinquent.resolved ? 'Submitted' : 'Not Submitted' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <button v-if="!delinquent.resolved" @click.prevent="resolveAttendance(delinquent.id)" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-md">
                                    Resolve
                                </button>
                                <span v-else class="text-green-500 italic">Resolved</span>
                            </td>
                        </tr>

                        <!-- Repeat for more rows... -->
                        </tbody>
                    </table>
                </div>


                <div class="flex justify-between items-center mt-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Member Attendance
                    </h3>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                        <span><span class="font-semibold text-indigo-600">P</span> - Present</span>
                        <span><span class="font-semibold text-green-500">EA</span> - Excused Absence</span>
                        <span><span class="font-semibold text-red-500">UA</span> - Unexcused Absence</span>
                        <span><span class="font-semibold text-yellow-500">T</span> - Tardy</span>
                        <span><span class="font-semibold text-gray-600">CC</span> - Cutting Classes</span>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 table-auto">
                        <thead class="bg-gray-50 sticky top-0 z-10 shadow">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
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
                                    v-for="status in attendanceStatus()"
                                    :key="status.value"
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    {{ status.abv }}
                                </th>
                                <!-- <th
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
                                </th> -->
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Remarks
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                            <tr v-for="(member, index) in sortedMembers" :key="index" class="hover:bg-gray-50">
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
                                    v-for="status in attendanceStatus()"
                                    :key="status.value"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <input
                                        v-model="member.status"
                                        type="radio"
                                        :name="`member-${member.learner_id}-status`"
                                        :value="status.value"
                                        class="form-radio h-4 w-4 transition duration-150 ease-in-out"
                                        :class="status.color"
                                    />
                                </td>
                                <!-- <td
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
                                </td> -->
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
import { attendanceStatus, middleInitials, removeUnderScore, ucWords } from '@/composables/utilities';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, computed, ref } from 'vue';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

defineOptions({
    layout: MainLayout
})
const loading = ref(false)
const page = usePage()
const props = defineProps({
    club: {
        type: Object,
        required: true,
    },
    delinquents: {
        type: Array,
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
const resolveAttendance = (id: number) => {
    loading.value = true
    axios.put(route('club.attendance.resolve', { id }))
        .then(() => {
            toast.success('Admission slip submitted.', {
                autoClose: 1000,
            })
            loading.value = false
            router.visit(route('club.attendance.create', { club_register_id: props.club?.id }))
        })
        .catch(() => {
            toast.error('Failed to resolve attendance', {
                autoClose: 1000,
            })
            loading.value = false
        })
}
onMounted(() => {
    setClubAttendance()
})
</script>

<style scoped>
.scrollbar-hide {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;     /* Firefox */
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;             /* Chrome, Safari, Opera */
}
</style>
