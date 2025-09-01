<template>
  <!-- Status Cell -->
    <div class="absolute hidden top-0 right-0 group-hover:flex flex-col gap-2 bg-white shadow-xl border border-gray-200 rounded-xl p-4 w-fit -translate-x-1/2 transition-all duration-200">
        <h4 class="text-sm font-semibold text-gray-700 mb-1">Status Timeline</h4>
        <ul class="relative pl-4 border-l-2 border-gray-200">
        <li v-for="(item, index) in props.data" :key="index" class="mb-2 last:mb-0">
            <div class="absolute w-2 h-2 bg-green-500 rounded-full -left-1"></div>
            <p class="text-sm text-gray-800 font-semibold">{{ ucWords(removeUnderScore(item.status)) }}</p>
            <p class="text-xs text-gray-500">{{ fullDateTime(item.updated_at) }} - by {{ item.updated_by.name }}</p>
            <p v-if="item.remarks" class="text-[10px] text-indigo-600 bg-indigo-100 px-1 rounded py-0.5">{{(item.remarks ?? '') }}</p>
        </li>
        <!-- <li class="mb-2 last:mb-0">
            <div class="absolute w-2 h-2 bg-yellow-400 rounded-full -left-1"></div>
            <p class="text-sm text-gray-800 font-semibold">Submitted</p>
            <p class="text-xs text-gray-500">Aug 1, 2025 - by Student</p>
        </li>
        <li>
            <div class="absolute w-2 h-2 bg-gray-400 rounded-full -left-1"></div>
            <p class="text-sm text-gray-800 font-semibold">Pending</p>
            <p class="text-xs text-gray-500">July 31, 2025 - System</p>
        </li> -->
        </ul>
    </div>

</template>

<script lang="ts" setup>
import { fullDateTime, removeUnderScore, ucWords } from '@/composables/utilities';
const props = defineProps({
    data: Array,
})
</script>

<style>

</style>
