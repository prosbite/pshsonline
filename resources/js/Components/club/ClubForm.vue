<template>
    <div class="w-full h-fit bg-white p-8 rounded-xl shadow-md border border-gray-200 relative">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">New Club</h2>
        <form @submit.prevent="enrollStudent" class="space-y-4">
            <!-- Last Name -->
            <div>
                <label for="lastName" class="block text-gray-700 text-md font-medium mb-1">Club Name:</label>
                <input type="text" id="lastName" v-model="club.club_name" required
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

             <!-- Club Adviser -->
             <div>
                <label for="lastName" class="block text-gray-700 text-md font-medium mb-1">Club Adviser:</label>
                <select id="lastName" v-model="club.adviser" required
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select Adviser</option>
                    <option v-for="user in props.users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
            </div>

            <!-- Description -->
            <div>
                <label for="lastName" class="block text-gray-700 text-md font-medium mb-1">Description:</label>
                <textarea id="lastName" v-model="club.description" required
                        class="block w-full p-3 border h-24 min-h-24 resize-none border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-4">
                <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Create Club
                </button>
            </div>
        </form>
    </div>
    </template>

    <script lang="ts" setup>
    import { ref } from 'vue'
    import { router } from '@inertiajs/vue3'
    import { toast } from 'vue3-toastify'
    import 'vue3-toastify/dist/index.css'

    const props = defineProps({
        users: Array,
    })
    const club = ref({
        club_name: '',
        adviser: '',
        description: '',
    })
    const enrollStudent = async () => {
        router.post(route('club.store'), club.value, {
            onSuccess: () => {
                toast.success('Club created successfully.', {
                    autoClose: 5000,
                    position: toast.POSITION.TOP_RIGHT,
                })
                club.value = {
                    club_name: '',
                    adviser: '',
                    description: '',
                }
            },
            onError: () => {
                toast.error('Failed to create club.', {
                    autoClose: 5000,
                    position: toast.POSITION.TOP_RIGHT,
                })
            },
        })
    }
    </script>

    <style>

    </style>
