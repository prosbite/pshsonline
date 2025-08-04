<template>
    <div class="page">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">
            Attendance Record
        </h1>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <form class="space-y-6">
                <div>
                    <label
                        for="session-club"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Club:</label
                    >
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        {{ props?.attendance?.club_register?.club?.name }}
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
                            {{ fullDate(props?.attendance?.date) }}
                        </span>
                        <span v-if="props?.attendance?.date === new Date().toISOString().split('T')[0]">
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
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        {{ props?.attendance?.activity }}
                    </span>
                </div>
                <div v-if="props?.attendance?.remarks">
                    <label
                        for="session-activity"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Remarks:</label
                    >
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        {{ props?.attendance?.remarks }}
                    </span>
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

                <div class="flex flex-col">
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
                                <tr v-for="(member, index) in sortedMembers" :key="index">
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
                                            disabled
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
                                            disabled
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
                </div>

                <div class="flex justify-end mt-6">
                    <Link
                        :href="route('club.attendance', { club_register_id: props.attendance.club_register_id })"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Go Back
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { fullDate, middleInitials, attendanceStatus, ucWords, removeUnderScore } from '@/composables/utilities';
import MainLayout from '@/Layouts/MainLayout.vue';
import { usePage, Link } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

defineOptions({
    layout: MainLayout
})
const page = usePage()
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
const sortedMembers = computed(() => {
    return [...props.attendance.club_attendance_learner].sort((a, b) => {
        return a.last_name.localeCompare(b.last_name)
    })
})
</script>

<style></style>
