<template>
    <div class="flex flex-col w-full">
            <div class="bg-white shadow-md w-full fixed top-0 z-50">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-end">
                    <!-- Logo or Title -->
                    <!-- <div class="text-lg font-semibold text-gray-800">My App</div> -->

                    <!-- Right Side: Notifications & User -->
                    <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <div class="relative group">
                    <!-- Invisible hover bridge -->
                    <div class="absolute -top-2 -right-4 w-48 h-48 z-10"></div>

                    <!-- Notification Icon -->
                    <button class="relative z-20">
                        <svg class="w-6 h-6 text-gray-600 hover:text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14 10V6a4 4 0 10-8 0v4a4 4 0 008 0zM3 20h18M3 20a9 9 0 0118 0" />
                        </svg>
                        <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
                    </button>

                    <!-- Dropdown -->
                    <div
                        class="absolute right-0 mt-2 w-64 bg-white border rounded-lg shadow-lg z-30 opacity-0 group-hover:opacity-100 invisible group-hover:visible transition duration-150">
                        <div class="p-2 text-sm text-gray-700 font-medium border-b">Notifications</div>
                        <ul class="divide-y">
                        <li class="p-2 hover:bg-gray-100 cursor-pointer">📣 New announcement posted</li>
                        <li class="p-2 hover:bg-gray-100 cursor-pointer">📝 You have 2 tasks due</li>
                        <li class="p-2 hover:bg-gray-100 cursor-pointer">👤 New student registered</li>
                        </ul>
                    </div>
                    </div>


                    <!-- User -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff"
                            class="w-8 h-8 rounded-full border border-gray-300" />
                        <span class="text-gray-700 text-sm">John Doe</span>
                        </button>
                        <!-- Dropdown -->
                        <div
                        class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                        <ul class="text-sm text-gray-700">
                            <li class="p-2 hover:bg-gray-100 cursor-pointer">🔧 Settings</li>
                            <li class="p-2 hover:bg-gray-100 cursor-pointer">📄 Profile</li>
                            <li class="p-2 hover:bg-gray-100 cursor-pointer">🚪 Logout</li>
                        </ul>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
        </div>
</template>

<script lang="ts" setup>

</script>

<style>

</style>
