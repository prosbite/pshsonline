<template>
  <!-- Sidebar Navigation -->
  <aside class="w-64 bg-gray-800 text-white p-6 flex flex-col shadow-lg rounded-r-xl">
    <div class="text-2xl font-bold mb-8 text-indigo-300">ALP Online</div>
    <nav class="space-y-4 flex-grow">
        <Link :href="route('dashboard')" :class="{'bg-gray-700': route().current('dashboard')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
            <svg class="w-6 h-6 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h3v-3m-3 3h3v-3m-3 0V9a1 1 0 011-1h2a1 1 0 011 1v10m-6 0h6"></path></svg>
            <span class="text-lg">Dashboard</span>
        </Link>

        <!-- Club Management Group Header (now a toggle) -->
        <div v-if="user.role === 'admin'" class="relative">
            <a href="#" @click.prevent="isEnrollmentGroupOpen = !isEnrollmentGroupOpen"
                :class="{'bg-gray-700': route().current('club-management')}"
                class="flex items-center justify-between space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="text-lg">Enrollment</span>
                </div>
                <!-- Arrow icon for expand/collapse -->
                <svg :class="{'rotate-90': isEnrollmentGroupOpen}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <!-- Sub-items -->
            <div v-if="isEnrollmentGroupOpen" class="pl-8 space-y-2 mt-2">
                <!-- New "Manage" link for the main Club Management page -->
                <Link :href="route('enrollment')" :class="{'bg-gray-700': route().current('enrollment')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-md">Manage</span>
                </Link>
                <!-- <a href="#" :class="{'bg-gray-700': route().current('member-list')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2v12m0 0l-1.588 1.588M17 20H9.588l-1.588 1.588M9.588 20H7.588m0 0H5.588M5.588 20H3.588M12 10a4 4 0 11-8 0 4 4 0 018 0zM12 10V4"></path></svg>
                    <span class="text-md">Member List</span>
                </a>
                <a href="#" :class="{'bg-gray-700': route().current('attendance')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="text-md">Attendance</span>
                </a>
                <a href="#" :class="{'bg-gray-700': route().current('reports')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m2 2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2v-2m-6 0H9"></path></svg>
                    <span class="text-md">Reports</span>
                </a> -->
            </div>
        </div>

        <div v-if="user.role === 'admin'" class="relative">
            <a href="#" @click.prevent="isClubManagementGroupOpen = !isClubManagementGroupOpen"
                :class="{'bg-gray-700': route().current('club-management')}"
                class="flex items-center justify-between space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="text-lg">ALP</span>
                </div>
                <!-- Arrow icon for expand/collapse -->
                <svg :class="{'rotate-90': isClubManagementGroupOpen}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <!-- Sub-items -->
            <div v-if="isClubManagementGroupOpen" class="pl-8 space-y-2 mt-2">
                <!-- New "Manage" link for the main Club Management page -->
                <Link v-if="user.role === 'club adviser'" :href="route('club.members')" :class="{'bg-gray-700': route().current('club.members')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2v12m0 0l-1.588 1.588M17 20H9.588l-1.588 1.588M9.588 20H7.588m0 0H5.588M5.588 20H3.588M12 10a4 4 0 11-8 0 4 4 0 018 0zM12 10V4"></path></svg>
                    <span class="text-md">Members</span>
                </Link>
                <Link v-if="user.role === 'admin'" :href="route('admin.club.list')" :class="{'bg-gray-700': route().current('admin.club.list')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14 0v-2a4 4 0 00-3-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0zM20 8a2 2 0 11-4 0 2 2 0 014 0zM20 20v-1.5a2.5 2.5 0 00-1.5-2.3" /></svg>
                    <span class="text-md">Clubs List</span>
                </Link>
                <Link v-if="user.role === 'admin'" :href="route('admin.attendance')" :class="{'bg-gray-700': route().current('admin.attendance')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-6 h-6 text-white-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-md">Attendance</span>
                </Link>
                <Link v-if="user.role === 'admin'" :href="route('admin.attendance.delinquents')" :class="{'bg-gray-700': route().current('admin.attendance.delinquents')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a1.5 1.5 0 001.29 2.25h18.78a1.5 1.5 0 001.29-2.25L13.71 3.86a1.5 1.5 0 00-2.42 0z"/>
                    </svg>
                    <span class="text-md">Delinquents</span>
                </Link>
                <Link v-if="user.role === 'admin'" :href="route('admin.advisers.attendance')" :class="{'bg-gray-700': route().current('admin.advisers.attendance')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-labelledby="title desc"
                        role="img"
                        width="24" height="24"
                        >
                        <title id="title">Attendance</title>
                        <desc id="desc">Calendar with a user and a check mark</desc>

                        <!-- Calendar -->
                        <rect x="2.5" y="4.5" width="13" height="13" rx="2.5"/>
                        <path d="M2.5 8.5h13"/>
                        <path d="M6.5 3.5v3 M11.5 3.5v3"/>

                        <!-- User (head + shoulders) -->
                        <circle cx="18.5" cy="9.5" r="2"/>
                        <path d="M15.8 14.2c.7-1.3 2-2.2 3.7-2.2s3 .9 3.7 2.2"/>

                        <!-- Check badge -->
                        <circle cx="18.5" cy="18.5" r="3.5"/>
                        <path d="M17.2 18.5l1 1 2-2"/>
                    </svg>

                    <span class="text-md">Advisers Attendance</span>
                </Link>
                <Link v-if="user.role === 'admin'" :href="route('admin.club.submissions')" :class="{'bg-gray-700': route().current('admin.club.submissions')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9h-4V3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V9zm-6 9h-2v-4H8l4-4 4 4h-3v4z"/>
                    </svg>
                    <span class="text-md">Submissions</span>
                </Link>
                <!-- <a href="#" :class="{'bg-gray-700': route().current('attendance')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="text-md">Attendance</span>
                </a>
                <a href="#" :class="{'bg-gray-700': route().current('reports')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m2 2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2v-2m-6 0H9"></path></svg>
                    <span class="text-md">Reports</span>
                </a> -->
            </div>
        </div>

        <!-- Club Management Group Header (now a toggle) -->

        <Link v-if="user.role === 'club adviser'" :href="route('club.members')" :class="{'bg-gray-700': route().current('club.members')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
            <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2v12m0 0l-1.588 1.588M17 20H9.588l-1.588 1.588M9.588 20H7.588m0 0H5.588M5.588 20H3.588M12 10a4 4 0 11-8 0 4 4 0 018 0zM12 10V4"></path></svg>
            <span class="text-md">Members</span>
        </Link>

        <div v-if="user.role === 'club adviser'" class="relative">
            <a href="#" @click.prevent="isClubManagementGroupOpen = !isClubManagementGroupOpen"
                :class="{'bg-gray-700': route().current('club-management')}"
                class="flex items-center justify-between space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-white-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-lg">Attendance</span>
                </div>
                <!-- Arrow icon for expand/collapse -->
                <svg :class="{'rotate-90': isClubManagementGroupOpen}" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            <!-- Sub-items -->
            <div v-if="isClubManagementGroupOpen" class="pl-8 space-y-2 mt-2">
                <Link :href="route('club.attendance.create', { club_register_id: clubRegisterId })" :class="{'bg-gray-700': route().current('club.attendance.create')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <span class="text-md">Record Attendance</span>
                </Link>
            </div>
            <div v-if="isClubManagementGroupOpen" class="pl-8 space-y-2 mt-2">
                <Link :href="route('club.attendance', { club_register_id: clubRegisterId })" :class="{'bg-gray-700': route().current('club.attendance')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <span class="text-md">Attendance List</span>
                </Link>
            </div>
            <div v-if="isClubManagementGroupOpen" class="pl-8 space-y-2 mt-2">
                <Link :href="route('club.attendance.monthly', { club_id: clubRegisterId })" :class="{'bg-gray-700': route().current('club.attendance.monthly')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
                    <span class="text-md">Monthly Summary</span>
                </Link>
            </div>
        </div>
        <Link v-if="user.role === 'club adviser'" :href="route('club.submissions', { club_register_id: clubRegisterId })" :class="{'bg-gray-700': route().current('club.submissions')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 9h-4V3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V9zm-6 9h-2v-4H8l4-4 4 4h-3v4z"/>
            </svg>
            <span class="text-md">Submissions</span>
        </Link>
        <!-- <Link v-if="user.role === 'admin'" :href="route('admin.club.list')" :class="{'bg-gray-700': route().current('admin.club.list')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
            <svg class="w-5 h-5 text-indigo-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14 0v-2a4 4 0 00-3-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0zM20 8a2 2 0 11-4 0 2 2 0 014 0zM20 20v-1.5a2.5 2.5 0 00-1.5-2.3" /></svg>
            <span class="text-md">Clubs List</span>
        </Link> -->

        <Link v-if="user.role === 'admin'" :href="route('users')" :class="{'bg-gray-700': route().current('users')}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6 5.87v-2a4 4 0 00-3-3.87m6 5.87v-2a4 4 0 013-3.87M15 11a3 3 0 100-6 3 3 0 000 6zM9 11a3 3 0 100-6 3 3 0 000 6z" />
            </svg>
            <span class="text-lg">Users</span>
        </Link>
    </nav>
    <!-- User Profile/Settings at the bottom -->
    <div class="mt-auto pt-6 border-t border-gray-700">
        <a href="#" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 group">
            <img src="https://placehold.co/40x40/6366f1/ffffff?text=U" alt="User Avatar" class="w-10 h-10 rounded-full border-2 border-indigo-400">
            <div class="flex flex-col">
                <span class="text-md font-medium">{{ user.name }}</span>
                <span class="text-sm text-gray-400 mb-4">{{ ucWords(user.role) }}</span>
                <Link :href="route('change-password')" class="text-sm text-left text-gray-400 hover:text-white">Change Password</Link>
                <button @click="logout" class="text-sm text-left text-gray-400 hover:text-white">Logout</button>
            </div>
        </a>
    </div>
</aside>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { ucWords } from '@/composables/utilities'
import { router } from '@inertiajs/vue3'

const page = usePage()
const isClubManagementGroupOpen = ref(false)
const isEnrollmentGroupOpen = ref(false)
const activePage = ref('dashboard');
const user = computed(() => page.props.auth.user)
const navTitle = computed(() => {
if (page.props.auth.user?.role === 'admin') {
        return 'Admin'
    } else if (page.props.auth.user?.role === 'club adviser') {
        return 'ALP Management'
    }
})
const clubRegisterId = computed(() => user.value?.club_registers?.[0].id ?? 1)
const logout = () => {
    router.post(route('logout'))
}
onMounted(() => {
    if (route().current('club.attendance')
        || route().current('club.reports')
        || route().current('club.submissions')
        || route().current('admin.club.submissions')
        || route().current('admin.attendance')
        || route().current('club.attendance.create')
        || route().current('club.attendance.monthly')
        )
    {
        isClubManagementGroupOpen.value = true
    }
    if (route().current('enrollment')) {
        isEnrollmentGroupOpen.value = true
    }
    if (route().current('admin.club.list')) {
        isClubManagementGroupOpen.value = true
    }
    if (route().current('admin.attendance.delinquents') ||
        route().current('admin.advisers.attendance') ||
        route().current('admin.advisers.attendance.create')) {
        isClubManagementGroupOpen.value = true
    }
})
</script>

<style>

</style>
