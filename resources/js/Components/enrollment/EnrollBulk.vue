<template>
    <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Bulk Enrollment</h3>
        <p class="text-gray-600 mb-4">Uploaded students from a csv file.</p>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Middle Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade Level</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th> -->
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="learner,index in props.learners" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ ucWords(learner.last_name)}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucWords(learner.first_name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucWords(learner.middle_name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucWords(learner.gender) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucWords(learner.grade_level) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucWords(learner.section) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ learner.email }}</td>
                        <!-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex w-full justify-end mt-4">
            <button @click="submit" class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Enroll Students</button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed }  from 'vue';
import { ucWords } from '@/composables/utilities';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

const props = defineProps({
    learners: Array,
    gradeLevels: Array,
    sections: Array,
})

const emit = defineEmits(['bulkStudents'])

const submit = () => {
    router.post(route('enrollment.bulk'), {learners: processedData.value}, {
        onSuccess: () => {
            toast.success('Students enrolled successfully.', {
                autoClose: 5000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onFinish: () => {
            emit('bulkStudents', [])
        },
        onError: () => {
            toast.error('Failed to enroll student.', {
                autoClose: 5000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
    })
}

const processedData = computed(() => {
    let learners = ref([])
    props.learners.map((learner) => {
        learners.value.push({
            last_name: ucWords(learner.last_name),
            first_name: ucWords(learner.first_name),
            middle_name: ucWords(learner.middle_name),
            gender: learner.gender.toLowerCase(),
            grade_level_id: findGradeLevel(learner.grade_level),
            section_id: findSection(learner.section),
            email: learner.email,
        })
    })
    return learners.value
})

const findGradeLevel = (grade_level:string) => {
    return props.gradeLevels.find((grade) => parseInt(grade.id) + 6 === parseInt(grade_level))?.id
}

const findSection = (section:string) => {
    return props.sections.find((sec) => sec.section_name.toLowerCase() === section.toLowerCase())?.id
}
onMounted(() => {
    // console.log(processedData())
})
</script>
