<template>
    <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-8 my-8">
            <!-- Left: Title -->
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">Clubs Monitoring</h3>
                <p class="text-gray-600 text-sm">Monitoring of each club</p>
            </div>
            <div class="flex gap-4 bg-gray-50">
                <select @change="selectClubType" v-model="clubType" required class="border flex-1 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select ALP Type</option>
                    <option value="club">Club</option>
                    <option value="school_organization">School Organization</option>
                    <option value="student_organization">Student Organization</option>
                </select>
                <select @change="selectTargetType" v-model="targetType" required class="border flex-1 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select ALP Type</option>
                    <option value="5">Target 5</option>
                    <option value="10">Target 10</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg">
            <ClubsMonitoringTarget5 v-if="targetType === 5" :attendances="props.attendances" :advisers="props.advisers" />
            <ClubsMonitoringTarget10 v-if="targetType === 10" :advisers="props.advisers" :accomplishment_reports="props.accomplishment_reports"/>
        </div>
    </div>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import ClubsMonitoringTarget5 from '@/Pages/admin/ClubsMonitoringTarget5.vue'
import ClubsMonitoringTarget10 from '@/Pages/admin/ClubsMonitoringTarget10.vue';
import { router } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
defineOptions({
    layout: MainLayout
})
const props = defineProps({
    attendances: Object,
    advisers: Array,
    accomplishment_reports: Array,
})
const clubType = ref('club')
const targetType = ref(5)
const selectClubType = () => {
    router.visit(route('admin.clubs.monitoring', { club_type: clubType.value, target_type: targetType.value }))
}
const selectTargetType = () => {
    router.visit(route('admin.clubs.monitoring', { club_type: clubType.value, target_type: targetType.value }))
}
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search)
    targetType.value = parseInt(urlParams.get('target_type')) ?? 5
    clubType.value = urlParams.get('club_type') ?? 'club'
})
</script>

<style>

</style>
