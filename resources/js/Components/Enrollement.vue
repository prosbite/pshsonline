<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <MainLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Enrollment
            </h2>
        </template>

        <div class="page">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Enrollment</h1>
            <div class="flex gap-4 justify-end">
                <EnrollmentForm class="w-1/3" />
            </div>
        </div>
    </MainLayout>
</template>
