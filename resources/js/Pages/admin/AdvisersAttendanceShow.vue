<template>
    <div class="page relative">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                Attendance Record
            </h2>
            <form @submit.prevent="submitAttendance" class="space-y-6">
                <div>
                    <label
                        for="session-date"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Date:</label
                    >
                    <input
                        disabled
                        required
                        :value="attendance.date"
                        type="date"
                        :max="new Date().toISOString().split('T')[0]"
                        id="session-date"
                        placeholder="Input ALP Class Date"
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
                        disabled
                        :value="attendance.activity"
                        type="text"
                        id="session-activity"
                        placeholder="e.g., Weekly Meeting, Field Trip"
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                <div class="flex justify-between items-center mt-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Advisers Attendance
                    </h3>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                        <span><span class="font-semibold text-indigo-600">P</span> - Present</span>
                        <span><span class="font-semibold text-red-600">A</span> - Absent</span>
                        <span><span class="font-semibold text-yellow-500">T</span> - Tardy</span>
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
                                    v-for="status in advisersAttendanceStatus()"
                                    :key="status.value"
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
                                    <div class="flex flex-col">
                                        <span class="text-md font-semibold">{{ member?.name }}</span>
                                        <span class="text-xs text-gray-500">{{ member?.club_registers?.[0]?.club?.name }}</span>
                                    </div>
                                </td>
                                <td
                                    v-for="status in advisersAttendanceStatus()"
                                    :key="status.value"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    <input
                                        v-model="member.pivot.status"
                                        type="radio"
                                        :name="`member-${member.pivot.user_id}-status`"
                                        :value="status.value"
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

                <div class="flex justify-end mt-6 gap-4">
                    <Link
                        :href="route('admin.advisers.attendance')"
                        class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Return to List
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { advisersAttendanceStatus, middleInitials, removeUnderScore, ucWords } from '@/composables/utilities';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted, computed, ref } from 'vue';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { router, Link } from '@inertiajs/vue3'
import PreviousDelinquents from '@/Components/delinquents/PreviousDelinquents.vue'

defineOptions({
    layout: MainLayout
})
const loading = ref(false)
const page = usePage()
const props = defineProps({
    attendance: {
        type: Object,
        required: true,
    },
})
const sortedMembers = computed(() => {
    return [...props.attendance.users].sort((a, b) => {
        return a.name.localeCompare(b.name)
    })
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
