<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <div >
                    <h3 class="text-2xl font-semibold text-gray-800">Submissions</h3>
                    <p class="text-gray-600 text-sm mb-4">Submission History and Status</p>
                </div>
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'name'">Name of Submission</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'members'">Date Submitted</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="submission, index in props.submissions" :key="index">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-slate-600">{{ ucWords(submissionName(submission.name)) }}</span>
                            <span class="text-xs text-slate-600">{{ ucWords(submission.club_register?.user?.name ?? '') }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ fullDate(submission.created_at) }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ucWords(submission.club_register?.club?.name ?? '') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ucWords(submission.status ?? '') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <a :href="submission.url" target="_blank" class="text-indigo-400 hover:text-indigo-600">Visit</a>
                    </td>
                </tr>
                <tr v-if="props.submissions.length === 0" >
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No submissions yet.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    </MainLayout>
    </template>

    <script lang="ts" setup>
    import SleekModal from '@/Components/SleekModal.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { fullDate, submissionType, removeUnderScore, ucWords } from '@/composables/utilities';
    import { onMounted, ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    const props = defineProps({
        submissions: Array,
    });
    const submissionName = (submission: any) => {
        return submissionType().filter((type: any) => type.value === submission)?.[0]?.label ?? ''
    }
    </script>
