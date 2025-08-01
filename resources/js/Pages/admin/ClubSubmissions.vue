<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <div >
                    <h3 class="text-2xl font-semibold text-gray-800">Submissions</h3>
                    <p class="text-gray-600 text-md mb-4">Submission History and Status</p>
                </div>
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'name'">Name of Submission</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'members'">Date Submitted</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Club</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Link</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="submission, index in props.submissions" :key="index">
                    <td class="px-4 py-3 whitespace-nowrap text-md text-gray-500">{{ index + 1 }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-md text-gray-500">
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-slate-600">{{ ucWords(submissionName(submission.name)) }}</span>
                            <span class="text-sm text-slate-600">{{ ucWords(submission.club_register?.user?.name ?? '') }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-md text-gray-500">{{ fullDateTime(submission.created_at) }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-md text-gray-500">{{ ucWords(submission.club_register?.club?.name ?? '') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-md text-green-600">{{ ucWords(removeUnderScore(submission.status ?? '')) }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-md text-gray-500">
                        <a :href="submission.url" target="_blank" class="text-indigo-400 hover:text-indigo-600">Visit</a>
                    </td>
                    <td class="flex flex-col gap-1 px-4 py-3 whitespace-nowrap text-md text-gray-500">
                        <button
                            @click="updateSubmissionStatus(status, submission)"
                            v-for="status in proceedSubmission(submission?.status)"
                            :key="status.value"
                            class="text-indigo-400 flex items-center gap-1 w-fit bg-indigo-50 text-[10px] hover:bg-indigo-600 px-2 py-1 rounded hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
                            </svg>
                            {{ status.label }}
                        </button>
                        <span v-if="proceedSubmission(submission?.status).length === 0" class="text-sm text-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </td>
                </tr>
                <tr v-if="props.submissions.length === 0" >
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-md text-gray-500">
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
    import { fullDate, submissionType, removeUnderScore, ucWords, fullDateTime } from '@/composables/utilities';
    import { onMounted, ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const currentStatus = ref({})
    const props = defineProps({
        submissions: Array,
    });
    const submissionName = (submission: any) => {
        return submissionType().filter((type: any) => type.value === submission)?.[0]?.label ?? ''
    }
    const proceedSubmission = (status: string) => {
        currentStatus.value = status
        if(status.toLocaleLowerCase() === 'pending') {
            return [{
                label: 'Ongoing Review',
                value: 'ongoing_review',
            }]
        }
        if(status.toLocaleLowerCase() === 'ongoing_review') {
            return [
                {
                label: 'Completed (For Revision)',
                    value: 'completed_for_revision',
                },
                {
                    label: 'Completed (For Printing)',
                    value: 'completed_for_printing',
                }
            ]
        }
        return []
    }
    const updateSubmissionStatus = (status: any, submission: any) => {
        const form = useForm({
            status: status.value,
            submission_id: submission.id,
        })
        form.put(route('admin.club.submissions.update', submission.id), {
            onSuccess: () => {
                toast.success('Submission status updated successfully', {
                    autoClose: 1000,
                });
            },
            onError: () => {
                toast.error('Failed to update submission status', {
                    autoClose: 1000,
                });
            }
        })
    }

    </script>
