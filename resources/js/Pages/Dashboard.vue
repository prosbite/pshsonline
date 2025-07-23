<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const page = usePage()

const props = defineProps({
    clubs: Array,
    todayEvents: Array,
    recentAttendance: Array,
});
const externalink = computed(() => page.props?.auth?.user.club_registers?.[0]?.externalinks?.[0]?.link ?? '#')
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
                <!-- <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Links</h2>
                    <div class="flex flex-wrap gap-4">
                        <Link :href="route('attendance.create')" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Add Attendance
                        </Link>
                        <button class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            View Members
                        </button>
                        <button class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            Generate Reports
                        </button>
                    </div>
                </div> -->

                <!-- External Resources -->
                <section class="bg-white dark:bg-gray-900 rounded-2xl shadow-md p-6 mt-6 mb-12">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">External Resources</h2>
                    <ul class="grid gap-4 md:grid-cols-3">

                        <!-- <li>
                        <a :href="externalink ?? '#'"
                            target="_blank"
                            class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                            <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m2-4h-4l-1-2H9l-1 2H4v14h16V6z" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">Reaccreditation</span>
                            </div>
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        </li> -->

                        <li>
                        <a :href="externalink ?? '#'"
                            target="_blank"
                            class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                            <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">Accreditation and Reaccreditation</span>
                            </div>
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        </li>

                        <li>
                        <a href="https://www.canva.com/design/DAGtyO5Eqaw/dfo5TzluL8Y0f3mqRJlKqg/edit?utm_content=DAGtyO5Eqaw&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton"
                            target="_blank"
                            class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                            <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v9a1 1 0 01-1 1h-6l2 5H9l2-5H4a1 1 0 01-1-1V4z" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">Presentation</span>
                            </div>
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        </li>

                    </ul>
                </section>
            </div>
    </MainLayout>
</template>
