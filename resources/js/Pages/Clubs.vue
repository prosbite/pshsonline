<script setup>
import { onMounted, ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import ClubForm from '@/Components/club/ClubForm.vue';
import Clubs from '@/Components/club/Clubs.vue';

const props = defineProps({
    clubs: Array,
    users: Array,
});
onMounted(() => {
    // console.log(props.clubs);
})
</script>
<template>
    <Head title="Dashboard" />

    <MainLayout>
        <div class="page">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8 w-full">Clubs List</h1>
            <div class="flex gap-4 w-full">
                <ClubForm :users="props.users" class="flex-1 w-full hidden" />
                <Clubs :clubs="props.clubs" class="flex-2" />
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
    .flex-2 {
        flex: 2;
    }
</style>
