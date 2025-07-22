<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 space-y-24 md:space-y-0">
                <!-- Left: Title -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Delinquent Students</h3>
                    <p class="text-gray-600 text-sm">List of students who are delinquent in their club attendance</p>
                </div>
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="(learner, index) in props.learners" :key="learner.id"
                  class="hover:bg-gray-100 cursor-pointer"
                >
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ ucWords(learner?.last_name ?? '') }}, {{ ucWords(learner?.first_name ?? '') }} {{ middleInitials(learner?.middle_name ?? '') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ learner?.grade }} - {{ learner?.section }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ learner?.club_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ ucWords(learner?.gender ?? '') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{  ucWords(learner?.status) }}
                    </td>
                </tr>
                <tr v-if="props.learners.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No delinquent learners found.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </MainLayout>

    </template>

    <script lang="ts" setup>
    import { ucWords, middleInitials } from '@/composables/utilities';
    import { computed, onMounted, ref } from 'vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Link, usePage } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    const page = usePage();
    const props = defineProps({
        learners: Array,
    })

    onMounted(() => {

    })
    </script>
