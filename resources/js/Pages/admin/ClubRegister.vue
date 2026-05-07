<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Club Registration</h3>
                    <p class="text-gray-600 text-sm">Register clubs for school year {{ currentSchoolYear.year_start }} - {{ currentSchoolYear.year_end }}</p>
                </div>
                <Link :href="route('admin.club.list')" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Back to Clubs List
                </Link>
            </div>

            <!-- Add New Club Form -->
            <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Add New Club</h4>
                <form @submit.prevent="storeClub" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Club Name</label>
                        <input
                            v-model="clubForm.name"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter club name"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select
                            v-model="clubForm.type"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Select type</option>
                            <option value="club">Club</option>
                            <option value="student_organization">Student Organization</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Adviser</label>
                        <select
                            v-model="clubForm.user_id"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Select an adviser</option>
                            <option v-for="user in usersWithoutClubs" :key="user.id" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea
                            v-model="clubForm.description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter club description"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select
                            v-model="clubForm.status"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Select status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button
                            type="submit"
                            :disabled="processing"
                            class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors disabled:opacity-50"
                        >
                            {{ processing ? 'Creating...' : 'Create Club' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Clubs List -->
            <div>
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Existing Clubs</h4>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club Name</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Status</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(club, index) in clubs" :key="club.id" class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ club.name }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ club.type }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span :class="club.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                          class="px-2 py-1 text-xs rounded-full">
                                        {{ club.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ club.description || '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span v-if="isClubRegistered(club.id)" class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Registered
                                    </span>
                                    <span v-else class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        Not Registered
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button
                                        v-if="!isClubRegistered(club.id)"
                                        @click="openRegistrationModal(club)"
                                        class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-green-600 transition-colors"
                                    >
                                        Register
                                    </button>
                                    <span v-else class="text-gray-400 text-xs">Already Registered</span>
                                </td>
                            </tr>
                            <tr v-if="clubs.length === 0">
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No clubs found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Registration Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Register Club</h3>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2"><strong>Club:</strong> {{ selectedClub?.name }}</p>
                    <p class="text-sm text-gray-600 mb-4"><strong>Type:</strong> {{ selectedClub?.type }}</p>
                </div>

                <form @submit.prevent="registerClub">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Adviser</label>
                        <select
                            v-model="registrationForm.user_id"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Select an adviser</option>
                            <option v-for="user in usersWithoutClubs" :key="user.id" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                        <p v-if="usersWithoutClubs.length === 0" class="text-red-500 text-sm mt-2">
                            No available teachers without clubs for this school year.
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="processing || usersWithoutClubs.length === 0"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors disabled:opacity-50"
                        >
                            {{ processing ? 'Registering...' : 'Register Club' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notification -->
        <div v-if="showToast" :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'"
             class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300">
            {{ toastMessage }}
        </div>
    </MainLayout>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    clubs: Array,
    registeredClubIds: Array,
    usersWithoutClubs: Array,
    currentSchoolYear: Object,
    flash: Object
});

const processing = ref(false);
const showModal = ref(false);
const selectedClub = ref(null);
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');

    const clubForm = ref({
        name: '',
        description: '',
        type: '',
        status: 'active',
        user_id: ''
    });

const registrationForm = ref({
    club_id: '',
    user_id: ''
});

const isClubRegistered = (clubId) => {
    return props.registeredClubIds.includes(clubId);
};

const openRegistrationModal = (club) => {
    selectedClub.value = club;
    registrationForm.value.club_id = club.id;
    registrationForm.value.user_id = '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedClub.value = null;
    registrationForm.value = {
        club_id: '',
        user_id: ''
    };
};

const showToastMessage = (message, type = 'success') => {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;

    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

const storeClub = () => {
    processing.value = true;

    router.post(route('admin.club.store'), clubForm.value, {
        onSuccess: () => {
            clubForm.value = {
                name: '',
                description: '',
                type: '',
                status: 'active',
                user_id: ''
            };
            processing.value = false;
        },
        onError: () => {
            showToastMessage('Error creating club. Please try again.', 'error');
            processing.value = false;
        }
    });
};

const registerClub = () => {
    processing.value = true;

    router.post(route('admin.club.register'), registrationForm.value, {
        onSuccess: () => {
            closeModal();
            processing.value = false;
        },
        onError: () => {
            showToastMessage('Error registering club. Please try again.', 'error');
            processing.value = false;
        }
    });
};

// Show flash messages if they exist
if (props.flash?.success) {
    showToastMessage(props.flash.success);
}
if (props.flash?.error) {
    showToastMessage(props.flash.error, 'error');
}
</script>
