<template>
    <div class="w-full">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
            <!-- Left: Title -->
        </div>

      <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Learner</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="(learner, index) in props.club.club.learners" :key="index"
            >
                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ ucWords(learner?.last_name) + ', ' + ucWords(learner?.first_name) + ' ' + middleInitials(learner?.middle_name?? '') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ parseInt(learner.current_enrollment?.section?.grade_level_id) + 6 + ' - ' + learner?.current_enrollment?.section?.section_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ ucWords(learner?.gender) }}
                </td>
            </tr>
            <tr v-if="props.club.club.learners.length === 0">
                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    No members yet.
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    </template>

    <script lang="ts" setup>
    import { ucWords, middleInitials } from '@/composables/utilities';
    import { computed, onMounted, ref } from 'vue';

    const props = defineProps({
        club: Object,
    })

    onMounted(() => {
        // console.log(props.club)
    })
    </script>
