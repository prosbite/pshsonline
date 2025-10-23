<template>
    <table class="to-print w-full">
        <thead>
            <tr>
                <th id="header" class="flex items-center justify-between mb-6">
                    <!-- <div class="flex gap-2 items-center">
                        <img src="/img/pisaylogo.png" class="h-[70px]" alt="Pisay Logo">
                        <div class="flex flex-col gap-0">
                            <span class="text-left text-xs">
                                Republic of the Philippines
                            </span>
                            <span class="text-left text-sm font-bold leading-normal">
                                PHILIPPINE SCIENCE HIGH SCHOOL
                            </span>
                            <span class="text-left font-bold text-xs leading-normal">
                                CARAGA REGION CAMPUS IN BUTUAN CITY
                            </span>
                            <span class="text-left text-xs leading-normal">
                                DEPARTMENT OF SCIENCE AND TECHNOLOGY
                            </span>
                        </div>
                    </div> -->
                    <img src="/img/pisay_header_left.png" class="h-[70px]" alt="Pisay Logo">
                    <img src="/img/bplogo.png" class="h-[70px]" alt="Pisay Logo">
                </th>
            </tr>
        </thead>
        <tbody>
            <div class="w-full flex flex-col bg-white">
                <div class="flex-col w-full font-bold text-lg mb-6">
                    <span class="text-center block uppercase">
                        {{ club?.name }}
                    </span>
                    <span class="text-center block uppercase mb-4">
                        SY {{ props.sy?.year_start }} - {{ props.sy?.year_end }}
                    </span>
                    <span class="text-center block uppercase">
                        List of Club Members
                    </span>
                </div>

                <table class="w-full border border-black border-collapse">
                    <thead>
                        <tr class="text-center">
                            <th></th>
                            <th class="flex flex-col text-lg" colspan="2">
                                <span clas="block">
                                    Name
                                </span>
                            </th>
                            <th class="border border-black">
                                Grade Level
                            </th>
                            <th class="border border-black">
                                Section
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="learner,index in sortedMembers">
                            <td class="border border-black px-0 py-1 text-center">
                                {{ index + 1 }}
                            </td>
                            <td class="border border-black px-2 py-1 text-left">
                                {{ ucWords(learner?.last_name) + ', ' + ucWords(learner?.first_name) + ' ' + middleInitials(learner?.middle_name?? '') }}
                            </td>
                            <td class="border border-black px-2 py-1 text-center">
                                {{ learner.grade_section?.grade_level.grade_level }}
                            </td>
                            <td class="border border-black px-2 py-1 text-center uppercase">
                                {{ learner.grade_section?.section_name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </tbody>
    </table>
</template>

<script lang="ts" setup>
import { ref, defineProps, onMounted } from 'vue'
import { ucWords, middleInitials } from '@/composables/utilities'

const props = defineProps({
    club: {
        type: Object,
        required: true
    },
    members: {
        type: Array,
        required: true
    },
    sy: {
        type: Object,
        required: true
    }
})

const sortedMembers = ref(props.members.sort((a, b) => {
    const nameA = a.last_name.toLowerCase();
    const nameB = b.last_name.toLowerCase();
    if (nameA < nameB) return -1;
    if (nameA > nameB) return 1;
    return 0;
}))
onMounted(() => {
    // console.log(sortedMembers.value)
})
</script>

<style>

</style>
