<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    clubs: Array,
    todayEvents: Array,
    recentAttendance: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <MainLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="page">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Dashboard</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Card: Clubs You Manage -->
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Clubs You Manage</h2>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li v-for="club in clubs" :key="club.id">
                                {{ club.name }} <span class="text-sm text-gray-500">({{ club.members }} members)</span>
                            </li>
                        </ul>
                        <a href="#" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">View All Clubs &rarr;</a>
                    </div>

                    <!-- Card: Today's Events/Meetings -->
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Today's Events/Meetings</h2>
                        <ul class="text-gray-600 space-y-2">
                            <li v-for="(event, index) in todayEvents" :key="index" class="flex items-center space-x-2">
                                <span :class="`text-${event.color}-500`">&#9679;</span>
                                <span>{{ event.time }} - {{ event.activity }}</span>
                            </li>
                        </ul>
                        <a href="#" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">View Calendar &rarr;</a>
                    </div>

                    <!-- Card: Recent Attendance Submissions -->
                    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Attendance Submissions</h2>
                        <ul class="text-gray-600 space-y-2">
                            <li v-for="(submission, index) in recentAttendance" :key="index">
                                {{ submission.club }} - {{ submission.date }} ({{ submission.present }}/{{ submission.total }} present)
                            </li>
                        </ul>
                        <a href="#" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">View All Submissions &rarr;</a>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Links</h2>
                    <div class="flex flex-wrap gap-4">
                        <button class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Add Attendance
                        </button>
                        <button class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            View Members
                        </button>
                        <button class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            Generate Reports
                        </button>
                    </div>
                </div>
            </div>
    </MainLayout>
</template>
