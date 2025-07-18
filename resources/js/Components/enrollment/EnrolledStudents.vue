<template>
    <div class="w-full md:w-4/5 bg-white p-8 rounded-xl shadow-md border border-gray-200">
  <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4 space-y-4 md:space-y-0">
    <div>
      <h3 class="text-2xl font-semibold text-gray-800">Enrolled Students</h3>
      <p class="text-gray-600 text-sm">Filter and view enrolled students by section.</p>
    </div>
    <div>
      <label for="sectionFilter" class="sr-only">Filter by section</label>
      <select
        id="sectionFilter"
        v-model="selectedSection"
        class="block w-full md:w-64 px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
      >
        <option :value="null" disabled>Select Section</option>
        <option
          v-for="section in props.sections"
          :key="section.id"
          :value="section.id"
        >
          {{ parseInt(section.grade_level_id) + 6 }} - {{ section.section_name }}
        </option>
      </select>
    </div>
  </div>

  <div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr
          v-for="(learner, index) in filteredLearners"
          :key="learner.id"
        >
            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ index + 1 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ ucWords(learner?.learner?.last_name) + ', ' + ucWords(learner?.learner?.first_name) + ' ' + middleInitials(learner?.learner?.middle_name?? '') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ parseInt(learner?.section?.grade_level_id) + 6 }} - {{ learner?.section?.section_name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ ucWords(learner?.learner?.gender) }}
          </td>
        </tr>
        <tr v-if="filteredLearners.length === 0">
            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                No learners found.
            </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</template>

<script lang="ts" setup>
import { ref, computed }  from 'vue';
import { ucWords, middleInitials } from '@/composables/utilities';
const selectedSection = ref(1)
const filteredLearners = computed(() => {
    if (!selectedSection.value) {
        return props.learners
    }
    return props.learners.filter((learner) => learner.section_id === selectedSection.value)
})
const props = defineProps({
    learners: Array,
    sections: Array,
})
</script>
