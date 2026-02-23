<template>
    <div class="no-print w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12 space-y-4 md:space-y-0">
            <div >
                <h3 class="text-2xl font-semibold text-gray-800">Attendance Infractions</h3>
            </div>
            <select id="attendanceDate" required class="border w-1/5 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="" disabled selected>Select Quarter</option>
                <option v-for="(quarter,index) in props.quarters" :key="index" :value="quarter.id">Quarter {{ quarter.quarter }}</option>
            </select>
        </div>
        <DelinquentsByDate v-for="(att, index) in attendances" :key="index" :date="att.date" :learners="att.delinquents" :is-open="index === 0" />
    </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import DelinquentsByDate from '@/Components/DelinquentsByDate.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
defineOptions({
    layout: MainLayout
})
const props = defineProps<{
    currentQuarter: Object;
    quarters: Object[];
    club: Object;
}>();
const attendances = computed(() => {
    return props.club.club_attendances.filter((att) => att.delinquents.length > 0);
});

const editAttendance = (learner) => {
  console.log('Edit', learner)
}
</script>
