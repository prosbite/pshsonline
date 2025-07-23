<template>
  <nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-8">
        <div class="flex justify-between h-16">
        <!-- Logo or App Name -->
        <div class="flex items-center">
            <Link :href="route('dashboard')" class="text-xl font-semibold text-indigo-600">CID Online</Link>
        </div>
        <!-- Desktop Navigation -->
        <div class="flex space-x-8 items-center">
            <Link v-if="user?.role === 'club adviser'" :href="route('club.attendance', { club_register_id: user?.club_registers?.[0]?.id })" :class="{'!text-black': route().current('club.attendance')}" class="text-gray-500 hover:text-indigo-600 font-medium">Attendance</Link>
            <Link v-if="user?.role === 'admin'" :href="route('admin.club.list')" :class="{'!text-black': route().current('admin.club.list')}" class="text-gray-500 hover:text-indigo-600 font-medium">Club</Link>
            <Link v-if="user?.role === 'club adviser'" :href="route('club.members')" :class="{'!text-black': route().current('club.members')}" class="text-gray-500 hover:text-indigo-600 font-medium">Club</Link>
            <Link v-if="user?.role === 'admin'" :href="route('enrollment')" :class="{'!text-black': route().current('enrollment')}" class="text-gray-500 hover:text-indigo-600 font-medium">Enrollment</Link>
        </div>

        </div>
    </div>
</nav>

</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
const page = usePage()
const user = computed(() => page.props.auth.user)
</script>

<style>

</style>
