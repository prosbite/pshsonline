<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-8 my-8">
                <!-- Left: Title -->
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Delinquent Students</h3>
                    <p class="text-gray-600 text-sm">List of students who are delinquent in their club attendance</p>
                </div>
                <select @change="getAttendance($event)" id="attendanceDate" v-model="selectedDate" required class="border w-1/5 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select Date</option>
                    <option v-for="(date,index) in props.attendanceDates" :key="index" :value="date">{{ fullDate(date) }}</option>
                </select>
            </div>

          <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Name</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions Taken</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="(delinquent, index) in sortedDelinquents" :key="delinquent.id"
                  class="hover:bg-gray-100 cursor-pointer"
                >
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <div class="flex flex-col gap-1">
                            <span>{{ ucWords(delinquent?.club_attendance_learner?.learner?.last_name ?? '') }}, {{ ucWords(delinquent?.club_attendance_learner?.learner?.first_name ?? '') }} {{ middleInitials(delinquent?.club_attendance_learner?.learner?.middle_name ?? '') }}</span>
                            <span class="text-sm text-gray-500">{{ delinquent?.club_attendance_learner?.learner?.current_enrollment?.section?.grade_level?.grade_level }} - {{ delinquent?.club_attendance_learner?.learner?.current_enrollment?.section?.section_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ delinquent?.club_attendance?.club_register?.club?.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ ucWords(delinquent?.club_attendance_learner?.learner?.gender ?? '') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{  ucWords(removeUnderScore(delinquent?.club_attendance_learner?.status)) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-[150px]">
                        {{  delinquent?.remarks ? ucWords(delinquent?.remarks) : 'None' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-[150px]">
                        {{  delinquent?.resolved ? ucWords(removeUnderScore(delinquent?.actions_taken)) : 'Unresolved' }}
                    </td>
                </tr>
                <tr v-if="props.delinquents.length === 0">
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
    import { ucWords, middleInitials, fullDate, removeUnderScore } from '@/composables/utilities';
    import { computed, onMounted, ref } from 'vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Link, usePage } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    const page = usePage();
    const props = defineProps({
        delinquents: Array,
        attendanceDates: Array,
        date: String
    })
    const selectedDate = ref(props.date)
    const getAttendance = (event: any) => {
        router.get(route('admin.attendance.delinquents', { date: event.target.value }))
    }
    // const combinedDelinquents = computed(() => {
    //     let delinquents = []
    //     console.log(props.attendance)
    //     for (let index = 0; index < props.attendance.length; index++) {
    //         const attendance = props.attendance[index];
    //         delinquents.push(...attendance.delinquents)
    //     }
    //     return delinquents
    // })
    const sortedDelinquents = computed(() => {
        return props.delinquents.sort((a: any, b: any) => {
            return a.club_attendance.club_register.club.name.localeCompare(b.club_attendance.club_register.club.name)
        })
    })
    onMounted(() => {
        selectedDate.value = props.date
    })
    </script>
