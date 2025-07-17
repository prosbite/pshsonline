<script setup>
import { onMounted, ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import ClubForm from '@/Components/club/ClubForm.vue';
import Clubs from '@/Components/club/Clubs.vue';

const props = defineProps({
    clubs: Array,
    users: Array,
    entrants: Array,
    sections: Array
});
const editClub = ref(false)
const selectedClub = ref(null)
const editClubDetails = (club) => {
    editClub.value = true
    selectedClub.value = club
    console.log(selectedClub.value)
}
onMounted(() => {
    // console.log(props.clubs);
})
</script>
<template>
    <Head title="Dashboard" />

    <MainLayout>
        <div class="page">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8 flex-1">Clubs List</h1>
            <div class="grid grid-cols-3 gap-4">
                <ClubForm @updated="editClub = false" :users="props.users" :club="selectedClub" class="col-span-1" v-if="editClub" />
                <Clubs @edit="editClubDetails" class="col-span-2" :class="!editClub ? 'col-span-3' : 'col-span-2'" :clubs="props.clubs" :entrants="props.entrants" :sections="props.sections" />
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
    .flex-2 {
        flex: 2;
    }
</style>
