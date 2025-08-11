<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <div >
                    <h3 class="text-2xl font-semibold text-gray-800">Submissions</h3>
                    <p class="text-gray-600 text-sm mb-4">Submission History and Status</p>
                </div>
                <button @click="showModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">+ New Submission</button>
            </div>

          <div class="rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'name'">Name of Submission</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer" @click="sortBy = 'members'">Date Submitted</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="submission, index in props.submissions" :key="index">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ucWords(removeUnderScore(submission.name ?? '')) }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ fullDate(submission.created_at) }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <div class="group relative" :style="{'z-index': 999 - index}">
                            <span class="text-sm font-medium text-blue-600">{{ ucWords(removeUnderScore(submission.status)) }}</span>
                            <SubmissionTracker :data="submission.submission_trackers" />
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        <a :href="submission.url" target="_blank" class="text-indigo-600 hover:text-indigo-900">Visit</a>
                    </td>
                    <td v-if="submission.status === 'for_revision'">
                        <button
                            @click="showRevisionModal(submission)"
                            class="text-indigo-400 flex items-center gap-1 w-fit bg-indigo-50 text-[10px] hover:bg-indigo-600 px-2 py-1 rounded hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
                            </svg>
                            Submit Revision
                        </button>
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

          <SleekModal :is-visible="showModal" @close="showModal = false" size="2xl" style="z-index: 99999">
            <template #header>
                <h3 class="text-lg font-medium text-gray-900">Create Submission</h3>
            </template>
            <template #body>
                <form action="" @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label for="club" class="block text-sm font-medium text-gray-700">Club</label>
                        <input type="text" id="club" name="club" disabled :value="props.clubRegister.club.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name of Submission</label>
                        <select required id="name" name="name" v-model="submissionName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="" disabled selected>Select Type</option>
                            <option v-for="type in submissionType()" :key="type.value" :value="type.value">{{ type.label }}</option>
                        </select>
                    </div>
                    <div class="mb-4" v-if="submissionName === 'others'">
                        <label for="otherSubmissionName" class="block text-sm font-medium text-red-400">Specify Name of Submission</label>
                        <input type="text" id="otherSubmissionName" name="otherSubmissionName" v-model="otherSubmissionName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="url" class="block text-sm font-medium text-gray-700">Link (If applicable)</label>
                        <input type="text" id="url" name="url" v-model="form.url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-8">
                        <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea id="remarks" name="remarks" v-model="form.remarks" class="resize-none mt-1 block w-full h-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">Submit</button>
                    </div>
            </form>
            </template>
          </SleekModal>

          <SleekModal :is-visible="revisionModal" @close="revisionModal = false" size="2xl" style="z-index: 99999">
            <template #header>
                <h3 class="text-lg font-medium text-gray-900">Revision of Submission</h3>
            </template>
            <template #body>
                <form action="" @submit.prevent="submitRevision">
                    <div class="mb-4">
                        <label for="club" class="block text-sm font-medium text-gray-700">Club</label>
                        <input type="text" id="club" name="club" disabled :value="props.clubRegister.club.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name of Submission</label>
                        <input type="text" id="name" name="name" disabled :value="ucWords(removeUnderScore(revisionForm.name))" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <!-- <select required id="name" name="name" disabled v-model="revisionForm.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="" disabled selected>Select Type</option>
                            <option v-for="type in submissionType()" :key="type.value" :value="type.value">{{ type.label }}</option>
                        </select> -->
                    </div>
                    <div class="mb-4">
                        <label for="url" class="block text-sm font-medium text-gray-700">Link (If applicable)</label>
                        <input type="text" id="url" name="url" v-model="revisionForm.url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-8">
                        <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                        <textarea id="remarks" name="remarks" v-model="revisionForm.remarks" class="resize-none mt-1 block w-full h-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">Submit</button>
                    </div>
            </form>
            </template>
          </SleekModal>
        </div>
    </MainLayout>
    </template>

    <script lang="ts" setup>
    import SleekModal from '@/Components/SleekModal.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { fullDate, submissionType, removeUnderScore, ucWords } from '@/composables/utilities';
    import { onMounted, ref, computed } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import SubmissionTracker from '@/Components/common/SubmissionTracker.vue';
    const form = useForm({
        name: '',
        club_register_id: '',
        url: '',
        remarks: '',
        user_id: '',
    });
    const props = defineProps({
        submissions: Array,
        clubRegister: Object,
    });
    const otherSubmissionName = ref('')
    const submissionName = ref('')
    const showModal = ref(false);
    const submitForm = () => {
        if(submissionName.value === 'others' && otherSubmissionName.value.trim().length === 0) {
            toast.error('Please specify the name for the submission')
            return
        }
        form.name = submissionName.value
        if(submissionName.value === 'others') {
            form.name = otherSubmissionName.value
        }
        form.post(route('club.submissions.store'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Submission created successfully', {
                    autoClose: 2000,
                });
                showModal.value = false;
                form.reset();
            },
            onError: () => {
                toast.error('Failed to create submission', {
                    autoClose: 2000,
                });
                showModal.value = true;
            }
        });
    }
    const showRevisionModal = (submission: any) => {
        revisionModal.value = true;
        revisionForm.id = submission.id;
        revisionForm.name = submission.name;
        revisionForm.status = "revised";
        revisionForm.url = submission.url;
    }
    const revisionModal = ref(false);
    const revisionForm = useForm({
        id: '',
        name: '',
        url: '',
        status: '',
        remarks: '',
    });
    const validatedRevisionForm = computed(() => {
        if (revisionForm.name === 'others') {
            return otherSubmissionName.value.trim().length > 0
        } else {
            return true
        }
    })
    const submitRevision = () => {
        revisionForm.put(route('club.submissions.update', revisionForm.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Submission updated successfully');
                revisionModal.value = false;
                revisionForm.reset();
            },
            onError: () => {
                toast.error('Failed to update submission');
                revisionModal.value = true;
            }
        });
    }
    onMounted(() => {
        form.club_register_id = props.clubRegister.id;
        form.user_id = props.clubRegister.user_id;
    })
    </script>
