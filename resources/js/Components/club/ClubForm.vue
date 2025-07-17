<template>
    <div class="w-full h-fit bg-white p-8 rounded-xl shadow-md border border-gray-200 relative">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit Club</h2>
        <form @submit.prevent="updateClub" class="space-y-4">
            <!-- Last Name -->
            <div>
                <label for="lastName" class="block text-gray-700 text-md font-medium mb-1">Club Name:</label>
                <input type="text" id="lastName" v-model="club.name" required
                        class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

             <!-- Club Adviser -->
             <div>
                <label for="lastName" class="block text-gray-700 text-md font-medium mb-1">Club Adviser:</label>
                <select id="lastName" v-model="club.user_id" required
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
                    Update Club
                </button>
            </div>
        </form>
    </div>
    </template>

    <script lang="ts" setup>
    import { ref, onMounted, watch } from 'vue'
    import { router } from '@inertiajs/vue3'
    import { toast } from 'vue3-toastify'
    import 'vue3-toastify/dist/index.css'

    const props = defineProps({
        users: Array,
        club: Object,
    })
    const emit = defineEmits(['updated'])
    const club = ref({
        id: '',
        name: '',
        user_id: '',
        description: '',
    })
    const updateClub = async () => {
        router.post(route('club.update'), club.value, {
            onSuccess: () => {
                toast.success('Club updated successfully.', {
                    autoClose: 5000,
                    position: toast.POSITION.TOP_RIGHT,
                })
                club.value = {
                    id: '',
                    name: '',
                    user_id: '',
                    description: '',
                }
                emit('updated')
            },
            onError: () => {
                toast.error('Failed to create club.', {
                    autoClose: 5000,
                    position: toast.POSITION.TOP_RIGHT,
                })
            },
        })
    }
    watch(() => props.club, () => {
        if(props.club){
            club.value = {
                id: props.club.id,
                name: props.club.club.name,
                user_id: props.club.user_id,
                description: props.club.club.description,
            }
        }
    })
    onMounted(() => {
        if(props.club){
            club.value = {
                id: props.club.id,
                name: props.club.club.name,
                user_id: props.club.user_id,
                description: props.club.club.description,
            }
        }
    })
    </script>

    <style>

    </style>
