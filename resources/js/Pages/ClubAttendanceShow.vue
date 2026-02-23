<template>
    <div class="page">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">
            Attendance Record
        </h1>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <form class="space-y-6">
                <div>
                    <label
                        for="session-club"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Club:</label
                    >
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        {{ props?.attendance?.club_register?.club?.name }}
                    </span>
                </div>
                <div>
                    <label
                        for="session-club"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Date:</label
                    >
                    <div class="flex items-center w-full gap-2 p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <span>
                            {{ fullDate(props?.attendance?.date) }}
                        </span>
                        <span v-if="props?.attendance?.date === new Date().toISOString().split('T')[0]">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Today
                            </span>
                        </span>
                    </div>
                </div>
                <div>
                    <label
                        for="session-activity"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Activity:</label
                    >
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        {{ props?.attendance?.activity }}
                    </span>
                </div>
                <div v-if="props?.attendance?.remarks">
                    <label
                        for="session-activity"
                        class="block text-gray-700 text-md font-medium mb-1"
                        >Remarks:</label
                    >
                    <span class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        {{ props?.attendance?.remarks }}
                    </span>
                </div>
                <div v-if="props?.attendance?.image" class="flex flex-col mt-6 gap-4 bg-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Uploaded Image
                    </h3>
                    <img :src="`/storage/${props?.attendance?.image}`" alt="Uploaded Image" class="w-full h-auto rounded-lg">
                </div>
                <div v-else class="flex flex-col mt-6 gap-4 bg-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Upload Image
                    </h3>
                    <ImageUpload @update:files="updateImageFiles" @update:portrait="imgNotPortrait = false" :portrait="imgNotPortrait" />
                    <button
                        v-if="clubAttendance.images.length > 0"
                        type="button"
                        @click.prevent="saveImage"
                        class="inline-flex items-center px-4 py-2 w-[80px] justify-center bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Save
                    </button>
                </div>

                <PreviousDelinquents :delinquents="props.delinquents" />

                <div class="flex flex-col">
                    <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-4">
                        Member Attendance
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        #
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Name
                                    </th>
                                    <th
                                        v-for="status in attendanceStatus()" :key="status.value"
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ status.abv }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Remarks
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(member, index) in sortedMembers" :key="index">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        {{ index + 1 }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        {{ member?.last_name }}, {{ member?.first_name }} {{ middleInitials(member?.middle_name ?? '') }}
                                    </td>
                                    <td
                                        v-for="status in attendanceStatus()" :key="status.value"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        <input
                                            disabled
                                            v-model="member.pivot.status"
                                            type="radio"
                                            :name="`member-${member.id}-status`"
                                            :value="status.value"
                                            :checked="member.pivot.status === status.value"
                                            class="form-radio h-4 w-4 transition duration-150 ease-in-out"
                                            :class="status.color"
                                        />
                                    </td>

                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        <input
                                            disabled
                                            v-model="member.pivot.remarks"
                                            type="text"
                                            placeholder="Optional remarks"
                                            class="p-2 border border-gray-300 rounded-md text-sm w-full"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <Link
                        :href="route('club.attendance', { club_register_id: props.attendance.club_register_id })"
                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Go Back
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { fullDate, middleInitials, attendanceStatus, ucWords, removeUnderScore } from '@/composables/utilities';
import MainLayout from '@/Layouts/MainLayout.vue';
import { usePage, Link } from '@inertiajs/vue3';
import { onMounted, computed, ref } from 'vue';
import PreviousDelinquents from '@/Components/delinquents/PreviousDelinquents.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { useForm } from '@inertiajs/vue3'
import ImageUpload from '@/Components/ImageUpload.vue'

defineOptions({
    layout: MainLayout
})
const page = usePage()
const props = defineProps({
    attendance: {
        type: Object,
        required: true,
    },
    delinquents: {
        type: Array,
        required: true,
    },
})
const imgNotPortrait = ref(false)
const sortedMembers = computed(() => {
    return [...props.attendance.club_attendance_learner].sort((a, b) => {
        return a.last_name.localeCompare(b.last_name)
    })
})
let clubAttendance = useForm({
    id: props.attendance.id,
    images: [],
})
const updateImageFiles = async (files: any) => {
    if (files.length > 0) {
        // Check if any file is not in portrait mode (width >= height)
        const notPortrait = await isNotPortrait(files[0])
        if (!notPortrait) {
            imgNotPortrait.value = true
            toast.error('Please upload image in landscape mode.', {
                autoClose: 3000,
            });
            return;
        } else {
            clubAttendance.images = files
        }
    }
}
async function isNotPortrait(file) {
    return new Promise((resolve) => {
        // Only process image files
        if (!file.type.startsWith('image/')) {
            resolve(false);
            return;
        }

        const img = new Image();
        const objectUrl = URL.createObjectURL(file);

        img.onload = function() {
            URL.revokeObjectURL(objectUrl); // Free up memory immediately

            // Logic: Is Width >= Height?
            const isValid = img.width >= img.height;
            resolve(isValid);
        };

        img.onerror = () => resolve(false);
        img.src = objectUrl;
    });
}
const saveImage = () => {
    clubAttendance.post(route('club.attendance.update-image', props.attendance.id))
}
onMounted(() => {
    // console.log(props.attendance)
})
</script>

<style></style>
