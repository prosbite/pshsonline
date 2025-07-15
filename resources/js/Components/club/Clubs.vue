<template>
    <div class="w-full md:w-4/5 bg-white p-8 rounded-xl shadow-md border border-gray-200">
  <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4 space-y-4 md:space-y-0">
    <div>
      <h3 class="text-2xl font-semibold text-gray-800">Clubs</h3>
      <p class="text-gray-600 text-sm">Available clubs this school year.</p>
    </div>
  </div>

  <div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Male</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Female</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr
          v-for="(club, index) in sortedClubs" :key="club.id"
        >
            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ index + 1 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <div class="flex flex-col">
                    <span class="font-semibold text-lg">{{ ucWords(club.club.name ?? '') }}</span>
                    <span class="text-gray-500 text-sm">Adviser: {{ club.user.name }}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ club.club.learners?.length }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ club.club.learners?.filter((learner) => learner.gender === 'male').length }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ club.club.learners?.filter((learner) => learner.gender === 'female').length }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <button class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-red-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Remove
                </button>
            </td>
        </tr>
        <tr v-if="props.clubs.length === 0">
            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                No clubs found.
            </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</template>

<script lang="ts" setup>
import { ucWords, middleInitials } from '@/composables/utilities';
import { computed } from 'vue';
const props = defineProps({
    clubs: Array,
})
const sortedClubs = computed(() => {
  return [...props.clubs].sort((a, b) => b.club.learners.length - a.club.learners.length)
})
</script>
