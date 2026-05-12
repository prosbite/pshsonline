<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'
import AccomplishmentReportTable from '@/Components/reports/AccomplishmentReport.vue'

defineOptions({
    layout: MainLayout,
})

defineProps<{
    reports: Array<any>
}>()
</script>

<template>
    <Head title="Accomplishment Report" />

    <AccomplishmentReportTable :reports="reports" />
</template>
