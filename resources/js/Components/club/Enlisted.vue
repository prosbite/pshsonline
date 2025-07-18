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
              v-for="(learner, index) in filteredStudents" :key="index"
            >
                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ ucWords(learner?.learner?.last_name) + ', ' + ucWords(learner?.learner?.first_name) + ' ' + middleInitials(learner?.learner?.middle_name?? '') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ parseInt(learner?.section?.grade_level_id) + 6 + ' - ' + learner?.section?.section_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ ucWords(learner?.learner?.gender) }}
                </td>
            </tr>
            <tr v-if="props.enlisted?.length === 0">
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
    import { computed, onMounted, ref } from 'vue';

    const props = defineProps({
        enlisted: Array,
        currentSection: Number
    })
    const filteredStudents = computed(() => {
        if (!props.currentSection) {
            return props.enlisted
        }
        return props.enlisted.filter((learner) => learner.section_id === props.currentSection)
    })
    onMounted(() => {
        // console.log(props.enlisted)
    })
    </script>
