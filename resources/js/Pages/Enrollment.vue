<script setup>
import { onMounted, ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import EnrollmentForm from '@/Components/enrollment/EnrollmentForm.vue';
import EnrolledStudents from '@/Components/enrollment/EnrolledStudents.vue';
import EnrollBulk from '@/Components/enrollment/EnrollBulk.vue';

const props = defineProps({
    gradeLevels: Array,
    learners: Array,
    sections: Array,
});
const bulkStudents = ref([])
const setBulkStudents = (students) => {
    bulkStudents.value = students
}
onMounted(() => {
    // console.log(props.learners);
})
</script>
<template>
    <Head title="Dashboard" />

    <MainLayout>
        <div class="page">
            <!-- <h1 class="text-4xl font-extrabold text-gray-900 mb-8 w-full">Manage Enrollment</h1> -->
            <EnrollBulk @bulk-students="setBulkStudents" v-if="bulkStudents.length > 0" :learners="bulkStudents" :gradeLevels="props.gradeLevels" :sections="props.sections" class="w-full" />
            <div v-else class="flex gap-4 w-full">
                <!-- <EnrollmentForm @bulk-students="setBulkStudents" :gradeLevels="props.gradeLevels" class="flex-1 hidden lg:block" /> -->
                <EnrolledStudents :learners="props.learners" :sections="props.sections" class="flex-2" />
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
    .flex-2 {
        flex: 2;
    }
</style>
