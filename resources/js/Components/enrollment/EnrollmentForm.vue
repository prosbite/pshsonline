<template>
<div class="w-full h-fit md:w-1/2 bg-white p-8 rounded-xl shadow-md border border-gray-200 relative">
    <div v-if="loading" class="absolute top-0 right-0 w-full h-full bg-black opacity-50 z-10 flex items-center justify-center">
        <img src="/img/ellipses_loader.gif" alt="Loading..." class="w-20 h-20">
    </div>
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">New Enrollee</h2>
    <form @submit.prevent="enrollStudent" class="space-y-4">
        <!-- Last Name -->
        <div>
            <label for="lastName" class="block text-gray-700 text-md font-medium mb-1">Last Name:</label>
            <input type="text" id="lastName" v-model="student.last_name" required
                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Middle Name -->
        <div>
            <label for="middleName" class="block text-gray-700 text-md font-medium mb-1">Middle Name:</label>
            <input type="text" id="middleName" v-model="student.middle_name"
                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- First Name -->
        <div>
            <label for="firstName" class="block text-gray-700 text-md font-medium mb-1">First Name:</label>
            <input type="text" id="firstName" v-model="student.first_name" required
                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-gray-700 text-md font-medium mb-1">Email:</label>
            <input type="email" id="email" v-model="student.email" required
                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>


        <!-- Gender Dropdown -->
        <div>
            <label for="gender" class="block text-gray-700 text-md font-medium mb-1">Gender:</label>
            <select id="gender" v-model="student.gender" required
                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="" disabled>Select Gender</option>
                <option v-for="option in useConstants().genders" :key="option" :value="option">{{ ucWords(option) }}</option>
            </select>
        </div>

        <!-- Grade Level Dropdown -->
        <div>
            <label for="gradeLevel" class="block text-gray-700 text-md font-medium mb-1">Grade Level:</label>
            <select id="gradeLevel" v-model="student.grade_level_id" required
                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="" disabled>Select Grade Level</option>
                <option v-for="grade in gradeLevels" :key="grade.id" :value="grade.id">{{ grade.grade_level }}</option>
            </select>
        </div>

        <!-- Section Dropdown -->
        <div>
            <label for="section" class="block text-gray-700 text-md font-medium mb-1">Section:</label>
            <select id="section" v-model="student.section_id" required
                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="" disabled>Select Section</option>
                <option v-for="sec in sections" :key="sec.id" :value="sec.id">{{ sec.section_name }}</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4 pt-4">
            <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Enroll Student
            </button>
            <button @click="bulkEnroll" type="button"
                    class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                Bulk Enroll
            </button>
            <input type="file" id="fileInput" @change="handleFileChange" accept=".csv" class="hidden" />
        </div>
    </form>
</div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useConstants, ucWords } from '@/composables/utilities'
import { router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
const student = ref({
    last_name: '',
    middle_name: '',
    first_name: '',
    gender: '',
    grade_level_id: '',
    section_id: '',
    email: '',
})
const loading = ref(false)
const props = defineProps({
    gradeLevels: Array,
})
const emit = defineEmits(['bulkStudents'])
const enrollStudent = async () => {
    loading.value = true
    router.post(route('enrollment.store'), student.value, {
        onSuccess: () => {
            toast.success('Student enrolled successfully.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
            student.value = {
                last_name: '',
                middle_name: '',
                first_name: '',
                gender: '',
                grade_level_id: '',
                section_id: '',
                email: '',
            }
        },
        onError: () => {
            toast.error('Failed to enroll student.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
    })
    loading.value = false
}
const bulkStudents = ref([])
const bulkEnroll = () => {
    document.getElementById('fileInput')?.click()
}
const handleFileChange = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0]

    if (file) {
        const reader = new FileReader()

        reader.onload = (e) => {
            const data = e.target?.result as string
            const lines = data.split('\n')

            const students = lines
                .map(line => line.trim())
                .filter((line, index) => line && index !== 0) // skip empty lines and the header
                .map((line) => {
                    const [last_name, first_name, middle_name, gender, grade_level, section, email] = line.split(',').map(field => field?.trim())

                    return {
                        last_name,
                        first_name,
                        middle_name,
                        gender,
                        grade_level,
                        section,
                        email,
                    }
                })

            bulkStudents.value = students
            alert('Bulk students set')
            emit('bulkStudents', bulkStudents.value)
        }

        reader.readAsText(file)
    }
}

const sections = computed(() => {
    if (!student.value.grade_level_id) return [];
    return props.gradeLevels.filter((grade: any) => grade.id === student.value.grade_level_id)[0].sections
})
</script>

<style>

</style>
