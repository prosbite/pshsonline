<script setup>
import { onMounted, ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const props = defineProps({
    users: Array
})
const edit = ref(false);
const user = ref({
    id: '',
    name: '',
    email: '',
    role: '',
})
const editUser = (user) => {
    edit.value = true;
    user.value = {
        id: user.id,
        name: user.name,
        email: user.email,
        role: user.role,
    };
    console.log(user)
}
const updateUser = async () => {
    router.post(route('user.update'), user.value, {
        onSuccess: () => {
            toast.success('User updated successfully.', {
                autoClose: 5000,
                position: toast.POSITION.TOP_RIGHT,
            })
            user.value = {
                id: '',
                name: '',
                email: '',
                role: '',
            }
        },
        onError: () => {
            toast.error('Failed to update user.', {
                autoClose: 5000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
    })
}
onMounted(() => {
    console.log(props.users);
})
</script>
<template>
    <Head title="Dashboard" />

    <MainLayout>
        <div class="page">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8 flex-1">Users List</h1>
            <div class="grid grid-cols-3 gap-4">
                <div v-if="edit" class="w-full h-fit bg-white p-8 rounded-xl shadow-md border border-gray-200 relative col-span-3">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit User</h2>
                    <form @submit.prevent="updateUser" class="space-y-4">
                        <!-- Last Name -->
                        <div>
                            <label for="name" class="block text-gray-700 text-md font-medium mb-1">Name:</label>
                            <input v-model="user.name" type="text" id="name" required
                                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="email" class="block text-gray-700 text-md font-medium mb-1">Email:</label>
                            <input v-model="user.email" type="email" id="email" required
                                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Club Adviser -->
                        <div>
                            <label for="role" class="block text-gray-700 text-md font-medium mb-1">Role:</label>
                            <select v-model="user.role" required
                                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="student">Student</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="submit"
                                    class="px-4 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 col-span-3">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                        v-for="(user, index) in props.users" :key="user.id"
                        class="hover:bg-gray-100 cursor-pointer"
                        >
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ index + 1 }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ user.name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ user.role }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ user.teacher?.gender }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ user.email }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                <button @click="editUser(user)" class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-green-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <svg width="24" height="24" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="6" y="6" width="30" height="36" rx="2" ry="2" stroke="black" stroke-width="2" fill="white"/>
                                        <line x1="10" y1="14" x2="30" y2="14" stroke="black" stroke-width="2"/>
                                        <line x1="10" y1="20" x2="30" y2="20" stroke="black" stroke-width="2"/>
                                        <line x1="10" y1="26" x2="24" y2="26" stroke="black" stroke-width="2"/>
                                        <path d="M32 30l6 6-10 10H22v-6l10-10z" stroke="black" stroke-width="2" fill="none"/>
                                        <line x1="30" y1="32" x2="36" y2="38" stroke="black" stroke-width="2"/>
                                    </svg>

                                </button>
                            </td>
                        </tr>
                        <tr v-if="props.users.length === 0">
                            <td colspan="6" class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
    .flex-2 {
        flex: 2;
    }
</style>
