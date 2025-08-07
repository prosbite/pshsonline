<template>
    <MainLayout>
        <div class="flex w-full h-screen items-center justify-center">
            <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 space-y-6">
            <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800">Change Password</h2>
            <p class="text-sm text-gray-500">Keep your account secure</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="old_password" class="block text-sm font-medium text-gray-700">Old Password</label>
                <input v-model="form.old_password"
                type="password"
                id="old_password"
                name="old_password"
                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required
                />
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input v-model="form.new_password"
                type="password"
                id="new_password"
                name="new_password"
                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required
                />
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input v-model="form.new_password_confirmation"
                type="password"
                id="new_password_confirmation"
                name="new_password_confirmation"
                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                required
                />
            </div>

            <div>
                <button
                type="submit"
                class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-300"
                >
                Update Password
                </button>
            </div>
            </form>
            </div>
        </div>
    </MainLayout>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
const form = useForm({
    old_password: '',
    new_password: '',
    new_password_confirmation: '',
});
const submit = () => {
    form.post(route('change-password.store'), {
        onSuccess: () => {
            toast.success('Password changed successfully.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
            form.reset();
        },
        onError: () => {
            toast.error('Failed to change password.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
            form.reset();
        },
    });
};
</script>

<style>

</style>
