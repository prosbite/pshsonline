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
const externalink = computed(() => {
    if (page.props.auth.user?.role === 'club adviser') {
        return page.props?.auth?.user.club_registers?.[0]?.externalinks?.[0]?.link ?? '#'
    }
    if (page.props.auth.user?.role === 'admin') {
        return 'https://drive.google.com/drive/folders/17CZsXYIKWQSbLXYRRSypnpM954GfnyMB?usp=drive_link'
    }
    return '#'
})
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

                <!-- External Resources -->
                <section class="bg-white dark:bg-gray-900 rounded-2xl shadow-md p-6 mt-6 mb-12">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">External Resources</h2>
                    <ul class="grid gap-4 md:grid-cols-3">

                        <li>
                            <a href="/pdf/alp_classes_venue.pdf"
                                target="_blank"
                                class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                                <div class="flex items-start space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" viewBox="0 0 64 64" fill="none">
                                        <rect width="64" height="64" rx="8" fill="#E53E3E"/>
                                        <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="20" fill="white" font-weight="bold">PDF</text>
                                    </svg>
                                <span class="text-gray-700 dark:text-gray-200 font-medium">ALP Classes Venue</span>
                                </div>
                                <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </li>

                        <!-- <li>
                            <a href="/pdf/student_org_venues.pdf"
                                target="_blank"
                                class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                                <div class="flex items-start space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" viewBox="0 0 64 64" fill="none">
                                        <rect width="64" height="64" rx="8" fill="#E53E3E"/>
                                        <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="20" fill="white" font-weight="bold">PDF</text>
                                    </svg>
                                <span class="text-gray-700 dark:text-gray-200 font-medium">Student Organization Meeting Venue</span>
                                </div>
                                <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </li> -->

                        <!-- <li>
                            <a href="/word/activity_proposal_checklist.docx"
                                target="_blank"
                                class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-indigo-500" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="64" height="64" rx="8" fill="#2B579A"/>
                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="32" font-family="Segoe UI, sans-serif" fill="white" font-weight="bold">W</text>
                                    </svg>
                                <span class="text-gray-700 dark:text-gray-200 font-medium">Activity Proposal Checklist</span>
                                </div>
                                <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </li>

                        <li>
                            <a href="/word/cover_letter_format.docx"
                                target="_blank"
                                class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-indigo-500" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="64" height="64" rx="8" fill="#2B579A"/>
                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="32" font-family="Segoe UI, sans-serif" fill="white" font-weight="bold">W</text>
                                    </svg>
                                <span class="text-gray-700 dark:text-gray-200 font-medium">Cover Letter Format</span>
                                </div>
                                <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </li>

                        <li>
                            <a href="/word/substitution_plan.docx"
                                target="_blank"
                                class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-indigo-500" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="64" height="64" rx="8" fill="#2B579A"/>
                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="32" font-family="Segoe UI, sans-serif" fill="white" font-weight="bold">W</text>
                                    </svg>
                                <span class="text-gray-700 dark:text-gray-200 font-medium">Substitution Plan</span>
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
                            <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">Accreditation & Reaccreditation (2025-2026)</span>
                            </div>
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        </li>

                        <li>
                        <a href="#"
                            target="_blank"
                            class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                            <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v9a1 1 0 01-1 1h-6l2 5H9l2-5H4a1 1 0 01-1-1V4z" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">Accreditation & Reaccreditation (2026-2027)</span>
                            </div>
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        </li>

                        <li>
                        <a href="https://docs.google.com/document/d/1HzSSLS2JbFBFJwGLObMXxqV-tcvsbaKy/edit?usp=drive_link&ouid=103896997284313246840&rtpof=true&sd=true"
                            target="_blank"
                            class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                            <div class="flex items-start space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    <circle cx="12" cy="12" r="6" stroke-width="2" />
                                    <circle cx="12" cy="12" r="2" stroke-width="2" fill="currentColor" />
                                </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">IPCR Targets (July - December 2025)</span>
                            </div>
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        </li>

                        <li>
                        <a href="https://drive.google.com/drive/folders/1u4mYTQFDUPD73lNlefRT-zLcklnHD1v8?usp=sharing"
                            target="_blank"
                            class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                            <div class="flex items-start space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h4l2-4 4 8 2-4h6" />
                                </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">Activity Proposal & Report</span>
                            </div>
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        </li>

                        <!-- <li>
                        <a href="https://docs.google.com/spreadsheets/d/1sL41qnc4QQU9cs5R5b-6XDlQiGR2ov5G/edit?usp=sharing&ouid=103896997284313246840&rtpof=true&sd=true"
                            target="_blank"
                            class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-800 transition">
                            <div class="flex items-start space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5h6M9 3h6a2 2 0 012 2v1h1a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h1V5a2 2 0 012-2zM9 12h6M9 16h3" />
                                </svg>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">Activity Report</span>
                            </div>
                            <svg class="w-5 h-5 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        </li> -->

                    </ul>
                </section>

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
            </div>
    </MainLayout>
</template>
