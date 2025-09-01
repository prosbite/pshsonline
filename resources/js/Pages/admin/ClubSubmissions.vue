<template>
    <div>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <div >
                    <h3 class="text-2xl font-semibold text-gray-800">Submissions</h3>
                    <p class="text-gray-600 text-md mb-4">Submission History and Status</p>
                </div>
            </div>

          <div class="rounded-lg border border-gray-200 min-h-[calc(100vh-200px)] mb-48">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'name'">Name of Submission</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'created_at'">Date Submitted</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Club</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'status'">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Link</th>
                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="submission, index in sortedSubmissions" :key="index">
                    <td class="px-4 py-3 text-md text-gray-500">{{ index + 1 }}</td>
                    <td class="px-4 py-3 text-md text-gray-500">
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-slate-600">{{ ucWords(removeUnderScore(submission.name)) }}</span>
                            <span class="text-sm text-slate-600">{{ ucWords(submission.club_register?.user?.name ?? '') }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-md text-gray-500">{{ fullDateTime(submission.created_at) }}</td>
                    <td class="px-4 py-3 text-md text-gray-500">{{ ucWords(submission.club_register?.club?.name ?? '') }}</td>
                    <td class="px-4 py-3 text-md text-green-600 cursor-pointer">
                        <div class="group relative" :style="{'z-index': 999 - index}">
                            <span class="text-sm font-medium text-blue-600">{{ ucWords(removeUnderScore(submission.status)) }}</span>
                            <SubmissionTracker :data="submission.submission_trackers" class="min-w-64" />
                        </div>
                    </td>
                    <td class="px-4 py-3 text-md text-gray-500">
                        <a :href="submission.url" target="_blank" class="text-indigo-400 hover:text-indigo-600">Visit</a>
                    </td>
                    <td class="px-4 py-3 text-md h-full text-gray-500">
                        <div class="flex flex-col items-start justify-center gap-2">
                            <div class="flex gap-2">
                                <button
                                    @click="preUpdateSubmission(status, submission)"
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
                                <button @click="deleteSubmission(submission)" class="text-red-400 hover:text-red-600">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="props.submissions.length === 0" >
                    <td colspan="6" class="px-6 py-4 text-md text-gray-500">
                        No submissions yet.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <SleekModal :is-visible="showModal" @close="showModal = false" size="2xl" class="z-[99999]">
            <template #header>
                <h3 class="text-lg font-medium text-gray-900">Input Remarks</h3>
            </template>
            <template #body>
                <textarea v-model="currentRemarks" class="w-full border border-gray-300 rounded h-48 resize-none" placeholder="What's on your mind?"></textarea>
            </template>
            <template #footer>
                <button @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                <button @click="updateSubmissionStatus(currentStatus, currentSubmission)" class="bg-indigo-500 text-white px-4 py-2 rounded">Update</button>
            </template>
        </SleekModal>
    </div>
    </template>

    <script lang="ts" setup>
    import SleekModal from '@/Components/SleekModal.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { submissionType, removeUnderScore, ucWords, fullDateTime } from '@/composables/utilities';
    import { ref, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import SubmissionTracker from '@/Components/common/SubmissionTracker.vue';

    defineOptions({
        layout: MainLayout,
    });
    const sortBy = ref('created_at')
    const currentStatus = ref({})
    const currentSubmission = ref({})
    const currentRemarks = ref('')
    const showModal = ref(false)
    const props = defineProps({
        submissions: Array,
    });
    const submissionName = (submission: any) => {
        return submissionType().filter((type: any) => type.value === submission)?.[0]?.label ?? ''
    }
    const proceedSubmission = (status: string) => {
        if(status.toLocaleLowerCase() === 'pending' || status.toLocaleLowerCase() === 'revised') {
            return [{
                label: 'Ongoing Review',
                value: 'ongoing_review',
            }]
        }
        if(status.toLocaleLowerCase() === 'ongoing_review') {
            return [
                {
                    label: 'For Revision',
                    value: 'for_revision',
                },
                {
                    label: 'For Printing',
                    value: 'for_printing',
                },
                {
                    label: 'Completed',
                    value: 'completed',
                }
            ]
        }
        if(status.toLocaleLowerCase() === 'for_printing') {
            return [
                {
                    label: 'Completed',
                    value: 'completed',
                }
            ]
        }
        return []
    }
    const preUpdateSubmission = (status: any, submission: any) => {
        if(status.value === 'completed') {
            currentStatus.value = status
            currentSubmission.value = submission
            showModal.value = true
        } else {
            updateSubmissionStatus(status, submission)
        }
    }
    const updateSubmissionStatus = (status: any, submission: any) => {
        const form = useForm({
            status: status.value,
            submission_id: submission.id,
            remarks: currentRemarks.value,
        })
        form.put(route('admin.club.submissions.update', submission.id), {
            onSuccess: () => {
                showModal.value = false
                currentRemarks.value = ''
                currentStatus.value = {}
                currentSubmission.value = {}
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

    const deleteSubmission = (submission: any) => {
        const form = useForm({
            submission_id: submission.id,
        })
        if(confirm('Are you sure you want to delete this submission?')) {
            form.delete(route('admin.club.submissions.delete', submission.id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Submission deleted successfully', {
                        autoClose: 1000,
                    });
                },
                onError: () => {
                    toast.error('Failed to delete submission', {
                        autoClose: 1000,
                    });
                }
            })
        }
    }

    const sortedSubmissions = computed(() => {
        let up = -1
        let down = 1
        if(sortBy.value === 'created_at') {
            up = 1
            down = -1
        }
        return props.submissions.sort((a: any, b: any) => {
            if (a[sortBy.value] < b[sortBy.value]) return up;
            if (a[sortBy.value] > b[sortBy.value]) return down;
            return 0;
        });
    });
    </script>
