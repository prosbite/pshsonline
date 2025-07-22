<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 space-y-24 md:space-y-0">
                <!-- Left: Title -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Assessment Mixmatch</h3>
                    <p class="text-gray-600 text-sm">List of students who are not matched in their assessment</p>
                </div>
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Last Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Code</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="(assessment, index) in finalAssessments" :key="assessment.id"
                  class="hover:bg-gray-100 cursor-pointer"
                >
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ ucWords(assessment?.lastName ?? '') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ assessment?.firstName }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ assessment?.entryCode }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button @click="findMatch(assessment)" class="px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">+ Match</button>
                    </td>
                </tr>
                <tr v-if="props.assessments.length === 0">
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        No assessment mixmatch found.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <SleekModal :is-visible="showModal" @close="showModal = false" size="7xl">
            <template #header>
                <div class="flex justify-between w-full flex-1 pr-8 gap-4">
                    <div class="flex-1">
                        <h3 class="text-2xl font-semibold text-gray-800">Student Enlistment</h3>
                        <p class="text-gray-600 text-sm">Search new students to enlist.</p>
                    </div>
                    <div class="flex flex-col gap-2 flex-1">
                        <input v-model="searchInput" type="text" placeholder="Search by name..." class="flex-grow p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <!-- <span class="text-sm text-gray-400 ita">Note: Use @gender, @section or @grade to filter by gender, section or grade level.</span> -->
                    </div>
                </div>
            </template>
            <template #body>
                <div v-if="searchResults.length > 0" class="flex flex-col gap-2 py-4">
                    <table class="w-full">
                        <tbody>
                            <tr v-for="(learner,index) in searchResults" :key="index" class="border-b hover:bg-gray-100">
                                <td class="py-2">{{learner.learner.last_name}}, {{learner.learner.first_name}}</td>
                                <td class="py-2">{{learner.section.grade_level_id + 6 + ' - ' + learner.section.section_name}}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="{'bg-green-100 text-green-800': learner.learner.gender === 'male', 'bg-red-100 text-red-800': learner.learner.gender === 'female'}"
                                    >{{ ucWords(learner.learner.gender) }}</span>
                                </td>
                                <td>
                                    <button @click="matchAssessment(learner)" class="px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">+ Match</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="searchResults.length === 0 && !loading" class="flex justify-center items-center w-full h-full bg-gray-100">
                    <span class="text-gray-500 text-lg">
                        No results yet.
                    </span>
                </div>
                <div v-if="loading" class="flex flex-col justify-center items-center w-full h-full bg-gray-100">
                    <img src="/img/ellipses_loader.gif" alt="" class="w-14 h-14">
                    <span class="text-gray-500 text-lg">Searching...</span>
                </div>
                <!-- <Enlisted :enlisted="enlisted" :currentSection="selectedSection" /> -->
            </template>

        </SleekModal>
    </MainLayout>

    </template>

    <script lang="ts" setup>
    import { ucWords, middleInitials } from '@/composables/utilities';
    import { onMounted, ref, watch } from 'vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Link, usePage } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    import SleekModal from '@/Components/SleekModal.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    const page = usePage();
    const searchInput = ref('');
    const searchResults = ref([]);
    const loading = ref(false);
    const props = defineProps({
        assessments: Array,
    })
    const currentAssessment = ref(null);
    const finalAssessments = ref([]);
    const showModal = ref(false);
    const filteredAssessments = () => {
        return props.assessments.filter((assessment) => {
            return assessment.entryCode !== 'trial-2025';
        });
    };
    const matchAssessment = (learner: any) => {
        let data = {
            assessment_id: currentAssessment.value.id,
            enrollment_id: learner.id
        }
        router.post(route('admin.assessment.match'), data, {
            onSuccess: () => {
                toast.success('Assessment matched successfully.', {
                    autoClose: 2000,
                    position: toast.POSITION.TOP_RIGHT,
                })
            },
            onError: () => {
                toast.error('Failed to match assessment.', {
                    autoClose: 2000,
                    position: toast.POSITION.TOP_RIGHT,
                })
            },
            onFinish: () => {
                showModal.value = false
                searchInput.value = ''
            }
        })
    };
    const findMatch = (assessment: any) => {
        showModal.value = true;
        currentAssessment.value = assessment;
        // console.log(currentAssessment.value);
    };

    let timeout = null
    watch(searchInput, (newValue) => {
        if(newValue.length > 1) {
            loading.value = true
            clearTimeout(timeout)
            timeout = setTimeout(() => {
                if (newValue.trim()) {
                    axios.get(`${route('student.search', { search: newValue })}`)
                        .then(response => {
                            searchResults.value = response.data
                            loading.value = false
                        })
                        .catch(error => {
                            console.error(error)
                        })
                } else {
                    searchResults.value = []
                }
                loading.value = false
            }, 1000) // 1-second debounce
        }
        if(newValue.length === 0) {
            searchResults.value = []
            loading.value = false
        }
    })

    onMounted(() => {
        finalAssessments.value = filteredAssessments();
    })
    </script>
