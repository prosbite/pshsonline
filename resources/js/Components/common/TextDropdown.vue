<template>
    <div class="relative inline-block text-left">
        <button type="button" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600 focus:outline-none" onclick="document.getElementById('dropdown-menu').classList.toggle('hidden')">
            Download
            <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="dropdown-menu" class="absolute right-0 z-10 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
            <div class="py-1 text-sm text-gray-700">
            <a href="#" class="block px-4 py-2 hover:bg-gray-100">CSV</a>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Excel</a>
            <a href="#" class="block px-4 py-2 hover:bg-gray-100">PDF</a>
            </div>
        </div>
    </div>

</template>

<script lang="ts" setup>

</script>

<style>

</style>
