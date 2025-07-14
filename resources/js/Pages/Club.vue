<script setup>
import { ref, onMounted } from 'vue';

// Reactive state for the current active page
const activePage = ref('dashboard');
// Reactive state for the active tab within Club Management
const activeClubTab = ref('members-tab');
// Reactive state to control the collapse/expand of the Club Management group
const isClubManagementGroupOpen = ref(false);

// Function to change the active page
const showPage = (pageId, subAction = null) => {
    activePage.value = pageId;

    // Automatically open the Club Management group if navigating to one of its sub-pages
    if (['club-management', 'member-list', 'attendance', 'reports'].includes(pageId)) {
        isClubManagementGroupOpen.value = true;
    } else {
        isClubManagementGroupOpen.value = false; // Collapse if navigating to Dashboard or other top-level
    }

    // Handle specific sub-actions, e.g., pre-filling attendance form
    if (pageId === 'attendance' && subAction === 'new') {
        // Set today's date for the attendance session date input
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('session-date').value = today;
        // Focus on the club select for attendance
        setTimeout(() => {
            document.getElementById('session-club').focus();
        }, 0); // Use setTimeout to ensure element is rendered after DOM update
    }

    // Reset tab selection for Club Management page if navigating away and back to club-management
    if (pageId === 'club-management') {
        showClubTab('members-tab'); // Default to members tab
    }
};

// Function to change the active tab within the Club Management page
const showClubTab = (tabId) => {
    activeClubTab.value = tabId;
};

// Simulate data for demonstration
const clubs = ref([
    { id: 'science', name: 'Science Club', members: 25 },
    { id: 'debate', name: 'Debate Society', members: 18 },
    { id: 'art', name: 'Art Enthusiasts', members: 30 },
]);

const members = ref([
    { id: 1, name: 'Alice Johnson', gradeSection: '10-A', role: 'Member', status: 'Active' },
    { id: 2, name: 'Bob Williams', gradeSection: '11-C', role: 'Leader', status: 'Active' },
    { id: 3, name: 'Charlie Brown', gradeSection: '12-B', role: 'Member', status: 'Inactive' },
    { id: 4, name: 'Diana Prince', gradeSection: '10-B', role: 'Member', status: 'Active' },
    { id: 5, name: 'Ethan Hunt', gradeSection: '11-A', role: 'Member', status: 'Active' },
]);

const recentAttendance = ref([
    { club: 'Science Club', date: '2024-10-25', present: 20, total: 25 },
    { club: 'Art Enthusiasts', date: '2024-10-24', present: 28, total: 30 },
    { club: 'Debate Society', date: '2024-10-23', present: 15, total: 18 },
]);

const todayEvents = ref([
    { time: '10:00 AM', activity: 'Science Club Meeting (Lab 3)', color: 'green' },
    { time: '02:00 PM', activity: 'Debate Practice (Auditorium)', color: 'blue' },
]);

// On component mount, ensure dashboard is shown and default tab is active
onMounted(() => {
    showPage('dashboard');
});
</script>

<template>
    <div class="flex min-h-screen font-sans">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-gray-800 text-white p-6 flex flex-col shadow-lg rounded-r-xl">
            <div class="text-2xl font-bold mb-8 text-indigo-300">Club Manager</div>
            <nav class="space-y-4 flex-grow">
                <a href="#" @click.prevent="showPage('dashboard')" :class="{'bg-gray-700': activePage === 'dashboard'}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-6 h-6 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h3v-3m-3 3h3v-3m-3 0V9a1 1 0 011-1h2a1 1 0 011 1v10m-6 0h6"></path></svg>
                    <span class="text-lg">Dashboard</span>
                </a>

                <!-- Club Management Group Header (now a toggle) -->
                <div class="relative">
                    <a href="#" @click.prevent="isClubManagementGroupOpen = !isClubManagementGroupOpen"
                       :class="{'bg-gray-700': isClubManagementGroupOpen}"
                       class="flex items-center justify-between space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-lg">Club Management</span>
                        </div>
                        <!-- Arrow icon for expand/collapse -->
                        <svg :class="{'rotate-90': isClubManagementGroupOpen}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    <!-- Sub-items -->
                    <div v-if="isClubManagementGroupOpen" class="pl-8 space-y-2 mt-2">
                        <!-- New "Manage" link for the main Club Management page -->
                        <a href="#" @click.prevent="showPage('club-management')" :class="{'bg-gray-700': activePage === 'club-management'}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-md">Manage</span>
                        </a>
                        <a href="#" @click.prevent="showPage('member-list')" :class="{'bg-gray-700': activePage === 'member-list'}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2v12m0 0l-1.588 1.588M17 20H9.588l-1.588 1.588M9.588 20H7.588m0 0H5.588M5.588 20H3.588M12 10a4 4 0 11-8 0 4 4 0 018 0zM12 10V4"></path></svg>
                            <span class="text-md">Member List</span>
                        </a>
                        <a href="#" @click.prevent="showPage('attendance')" :class="{'bg-gray-700': activePage === 'attendance'}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-md">Attendance</span>
                        </a>
                        <a href="#" @click.prevent="showPage('reports')" :class="{'bg-gray-700': activePage === 'reports'}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m2 2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-2m-6 0H9"></path></svg>
                            <span class="text-md">Reports</span>
                        </a>
                    </div>
                </div>
            </nav>
            <!-- User Profile/Settings at the bottom -->
            <div class="mt-auto pt-6 border-t border-gray-700">
                <a href="#" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <img src="https://placehold.co/40x40/6366f1/ffffff?text=U" alt="User Avatar" class="w-10 h-10 rounded-full border-2 border-indigo-400">
                    <div class="flex flex-col">
                        <span class="text-md font-medium">Adviser Name</span>
                        <span class="text-sm text-gray-400">Club Adviser</span>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-grow p-8 overflow-y-auto bg-gray-100">

            <!-- 1. Dashboard Page -->
            <div v-if="activePage === 'dashboard'" class="page">
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
                        <a href="#" @click.prevent="showPage('club-management')" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">View All Clubs &rarr;</a>
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
                        <a href="#" @click.prevent="showPage('attendance')" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">View All Submissions &rarr;</a>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Links</h2>
                    <div class="flex flex-wrap gap-4">
                        <button @click="showPage('attendance', 'new')" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Add Attendance
                        </button>
                        <button @click="showPage('member-list')" class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            View Members
                        </button>
                        <button @click="showPage('reports')" class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            Generate Reports
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. Club Management Page -->
            <div v-if="activePage === 'club-management'" class="page">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Club Management</h1>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
                    <label for="club-select" class="block text-gray-700 text-lg font-medium mb-2">Select Club:</label>
                    <select id="club-select" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-lg">
                        <option v-for="club in clubs" :key="club.id" :value="club.id">{{ club.name }}</option>
                    </select>
                </div>

                <div class="bg-white rounded-xl shadow-md border border-gray-200">
                    <div class="flex border-b border-gray-200">
                        <button @click="showClubTab('members-tab')" :class="{'border-indigo-600 text-indigo-600': activeClubTab === 'members-tab', 'border-transparent text-gray-700': activeClubTab !== 'members-tab'}" class="py-4 px-6 text-lg font-medium hover:text-indigo-600 hover:bg-gray-50 focus:outline-none border-b-2 transition-colors duration-200">Members</button>
                        <button @click="showClubTab('attendance-club-tab')" :class="{'border-indigo-600 text-indigo-600': activeClubTab === 'attendance-club-tab', 'border-transparent text-gray-700': activeClubTab !== 'attendance-club-tab'}" class="py-4 px-6 text-lg font-medium hover:text-indigo-600 hover:bg-gray-50 focus:outline-none border-b-2 transition-colors duration-200">Attendance</button>
                        <button @click="showClubTab('reports-club-tab')" :class="{'border-indigo-600 text-indigo-600': activeClubTab === 'reports-club-tab', 'border-transparent text-gray-700': activeClubTab !== 'reports-club-tab'}" class="py-4 px-6 text-lg font-medium hover:text-indigo-600 hover:bg-gray-50 focus:outline-none border-b-2 transition-colors duration-200">Reports</button>
                        <button @click="showClubTab('settings-club-tab')" :class="{'border-indigo-600 text-indigo-600': activeClubTab === 'settings-club-tab', 'border-transparent text-gray-700': activeClubTab !== 'settings-club-tab'}" class="py-4 px-6 text-lg font-medium hover:text-indigo-600 hover:bg-gray-50 focus:outline-none border-b-2 transition-colors duration-200">Settings</button>
                    </div>

                    <div v-if="activeClubTab === 'members-tab'" class="p-6 tab-content">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Club Members</h3>
                        <p class="text-gray-600 mb-4">Manage members for the selected club. (Pulled from school DB)</p>
                        <div class="flex space-x-4 mb-6">
                            <button class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Add Member</button>
                            <button class="px-5 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition-colors duration-200">Remove Selected</button>
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="member in members" :key="member.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ member.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ member.gradeSection }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ member.role }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="activeClubTab === 'attendance-club-tab'" class="p-6 tab-content">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Club Attendance Records</h3>
                        <p class="text-gray-600 mb-4">View past attendance sessions for this club.</p>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(submission, index) in recentAttendance" :key="index">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ submission.date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Weekly Meeting</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ submission.present }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ submission.total }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button @click="showPage('attendance', 'new')" class="mt-6 px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Record New Attendance</button>
                    </div>

                    <div v-if="activeClubTab === 'reports-club-tab'" class="p-6 tab-content">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Club Reports</h3>
                        <p class="text-gray-600 mb-4">Generate reports specific to this club.</p>
                        <div class="space-y-4">
                            <button class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200">Download Attendance Summary (PDF)</button>
                            <button class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200">Download Member List (Excel)</button>
                        </div>
                    </div>

                    <div v-if="activeClubTab === 'settings-club-tab'" class="p-6 tab-content">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Club Settings</h3>
                        <p class="text-gray-600 mb-4">Manage club specific settings.</p>
                        <div class="space-y-4">
                            <div>
                                <label for="co-adviser" class="block text-gray-700 text-md font-medium mb-1">Assign Co-Adviser:</label>
                                <input type="text" id="co-adviser" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="club-rename" class="block text-gray-700 text-md font-medium mb-1">Rename Club:</label>
                                <input type="text" id="club-rename" value="Science Club" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <button class="px-5 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition-colors duration-200">Archive Club</button>
                            <button class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Save Settings</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Member List Page -->
            <div v-if="activePage === 'member-list'" class="page">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Member List</h1>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
                    <div class="flex flex-col md:flex-row gap-4 mb-4">
                        <input type="text" placeholder="Search by Name, Grade..." class="flex-grow p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <select class="p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Filter by Grade</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
                        <select class="p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Filter by Role</option>
                            <option value="leader">Leader</option>
                            <option value="member">Member</option>
                        </select>
                        <select class="p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Filter by Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <button class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Add New Member</button>
                        <button class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200">Bulk Import</button>
                        <button class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200">Assign from School DB</button>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="member in members" :key="member.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ member.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ member.gradeSection }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ member.role }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="{'bg-green-100 text-green-800': member.status === 'Active', 'bg-red-100 text-red-800': member.status === 'Inactive'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">{{ member.status }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-900">Remove</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. Attendance Page -->
            <div v-if="activePage === 'attendance'" class="page">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Attendance Recording</h1>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create New Session</h2>
                    <form class="space-y-6">
                        <div>
                            <label for="session-club" class="block text-gray-700 text-md font-medium mb-1">Club:</label>
                            <select id="session-club" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option v-for="club in clubs" :key="club.id" :value="club.id">{{ club.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="session-date" class="block text-gray-700 text-md font-medium mb-1">Date:</label>
                            <input type="date" id="session-date" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="session-activity" class="block text-gray-700 text-md font-medium mb-1">Activity:</label>
                            <input type="text" id="session-activity" placeholder="e.g., Weekly Meeting, Field Trip" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-4">Member Attendance</h3>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="member in members" :key="member.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ member.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <input type="radio" :name="`member-${member.id}-status`" value="present" checked class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <input type="radio" :name="`member-${member.id}-status`" value="absent" class="form-radio h-4 w-4 text-red-600 transition duration-150 ease-in-out">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <input type="text" placeholder="Optional remarks" class="p-2 border border-gray-300 rounded-md text-sm w-full">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Submit Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 5. Reports Page -->
            <div v-if="activePage === 'reports'" class="page">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Reports</h1>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Generate Reports</h2>
                    <div class="space-y-6">
                        <!-- Filter by Date Range -->
                        <div>
                            <label class="block text-gray-700 text-md font-medium mb-1">Date Range:</label>
                            <div class="flex space-x-4">
                                <input type="date" class="flex-1 p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <span class="self-center text-gray-600">-</span>
                                <input type="date" class="flex-1 p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <!-- Filter by Club -->
                        <div>
                            <label for="report-club" class="block text-gray-700 text-md font-medium mb-1">Club:</label>
                            <select id="report-club" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Clubs</option>
                                <option v-for="club in clubs" :key="club.id" :value="club.id">{{ club.name }}</option>
                            </select>
                        </div>

                        <!-- Filter by Student -->
                        <div>
                            <label for="report-student" class="block text-gray-700 text-md font-medium mb-1">Student (Optional):</label>
                            <input type="text" id="report-student" placeholder="Enter student name" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="flex flex-wrap gap-4 pt-4 border-t border-gray-200">
                            <button class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Download PDF Report
                            </button>
                            <button class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Download Excel Report
                            </button>
                        </div>

                        <div class="pt-6 border-t border-gray-200 mt-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Submit Report to Admin (Optional)</h3>
                            <textarea placeholder="Add comments for admin..." rows="4" class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-4"></textarea>
                            <button class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Submit Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style>
/* Global styles for body and font */
body {
    font-family: 'Inter', sans-serif;
    background-color: #f3f4f6; /* Light gray background */
}

/* Custom scrollbar for better aesthetics */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #e0e0e0;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
