<template>
   <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
                <div >
                    <h3 class="text-2xl font-semibold text-gray-800">Certificates</h3>
                    <p class="text-gray-600 text-sm mb-4">Generated Certificates</p>
                </div>
            </div>

          <div class="rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer">Students</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer">Section</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-blue-500 cursor-pointer"></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="student, index in props.club.club.learners" :key="index">
                    <td class="px-4 py-3 text-sm text-gray-500">{{ index + 1 }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        <div class="flex flex-col gap-1">
                            <span class="font-medium text-gray-800">
                                {{ student.last_name }}, {{ student.first_name + " " + middleInitials(student.middle_name) }}
                            </span>
                        </div>
                    </td>
                    <td>
                        {{ student.current_enrollment.section.section_name }}
                    </td>
                    <td>
                        <button
                            @click="generateCertificate(student.last_name, student.first_name, middleInitials(student.middle_name))"
                            class="text-indigo-400 flex items-center gap-1 w-fit bg-indigo-50 text-[10px] hover:bg-indigo-600 px-2 py-1 rounded hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
                            </svg>
                            Generate Certificate
                        </button>
                    </td>
                </tr>
                <tr v-if="props.club.club.learners.length === 0" >
                    <td colspan="6" class="px-6 py-4 text-sm text-gray-500">
                        No members yet.
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
           <SleekModal :is-visible="showModal" @close="showModal = false" size="7xl" style="z-index: 99999">
            <template #header>
                <button
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm"
                    >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.89l-2.1 2.1c-.19.19-.32.44-.37.71l-.86 4.71c-.02.11.01.22.07.31.05.09.13.15.22.18.04.01.07.01.1.01.08 0 .15-.02.22-.06l4.47-2.48c.23-.13.43-.31.57-.54l2.11-3.41m-5.38-1.54L15 4.12m-9.42 8.23c.18-.18.41-.28.65-.28H15m0 0l2.25 2.25M15 4.12l5.03 5.03m-5.03-5.03c.18-.18.41-.28.65-.28H15.75l5.03 5.03c0 .24-.1.47-.28.65l-2.25 2.25" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9V5.25a2.25 2.25 0 012.25-2.25h6a2.25 2.25 0 012.25 2.25V9m-11.25 3h13.5A2.25 2.25 0 0121 14.25v4.5A2.25 2.25 0 0118.75 21h-13.5A2.25 2.25 0 013 18.75v-4.5A2.25 2.25 0 015.25 12zm3 4.5h7.5" />
                    </svg>

                    Print
                </button>
            </template>
            <template #body>
               <div class="flex justify-center">
                    <div class="relative w-full h-full">
                        <span id="cert-name" class="absolute font-yesteryear text-center text-blue-900 font-bold">
                            {{ decapitalizeText(studentName) }}
                        </span>
                        <p v-if="parseInt(studentClubId) === 1" id="cert-message" class="absolute px-32 leading-7">
                            for their dedicated service as a member of the Sangguniang Iskolar  for the Academic Year 2025–2026.
                            Their efforts have significantly contributed to the success of student-led initiatives and the strengthening of student representation within the institution.
                            <br />
                            <br />
                            Given this 11th day of May, 2026, at PSHS-CRC in Butuan City.
                        </p>
                        <p v-else id="cert-message" class="absolute px-32 leading-7">
                            has successfully completed the {{ studentClub }} under the Alternative Learning Program (ALP) during the Academic Year 2025-2026. This recognition is given in appreciation of the student’s valuable contribution, dedication, and active participation in the club’s activities.

                            <br />
                            <br />Given this 11th day of May, 2026, at the Philippine Science High School - Caraga Region Campus in Butuan City.
                        </p>
                        <img :src="'/storage/certificates/' + studentClubId + '.jpg'" class="w-full h-full" alt="">
                    </div>
               </div>
            </template>
          </SleekModal>

          <Teleport to="body">
        <table class="to-print w-full certificate-page to-print">
            <tbody>
                <tr>
                    <td>
                       <div class="flex justify-center">
                    <div class="relative w-full h-full">
                        <span id="cert-name-print" class="absolute font-yesteryear text-center text-blue-900 font-bold">
                            {{ decapitalizeText(studentName) }}
                        </span>
                        <p v-if="parseInt(studentClubId) === 1" id="cert-message-print" class="absolute px-32 leading-7">
                            for their dedicated service as a member of the Sangguniang Iskolar  for the Academic Year 2025–2026.
                            Their efforts have significantly contributed to the success of student-led initiatives and the strengthening of student representation within the institution.
                            <br />
                            <br />
                            Given this 11th day of May, 2026, at PSHS-CRC in Butuan City.
                        </p>
                        <p id="cert-message-print" class="absolute px-32 leading-7">
                            has successfully completed the {{ studentClub }} under the Alternative Learning Program (ALP) during the Academic Year 2025-2026. This recognition is given in appreciation of the student’s valuable contribution, dedication, and active participation in the club’s activities.

                            <br />
                            <br />Given this 6th day of May, 2026, at the Philippine Science High School - Caraga Region Campus in Butuan City.
                        </p>
                        <img :src="'/storage/certificates/' + studentClubId + '.jpg'" class="w-full h-full" alt="">
                    </div>
               </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </Teleport>
        </div>
    </template>

    <script lang="ts" setup>
    import SleekModal from '@/Components/SleekModal.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { fullDate, submissionType, removeUnderScore, ucWords, middleInitials, decapitalizeText } from '@/composables/utilities';
    import { onMounted, ref, computed } from 'vue';
    import 'vue3-toastify/dist/index.css';
    import SubmissionTracker from '@/Components/common/SubmissionTracker.vue';
    import { usePage } from '@inertiajs/vue3';

    defineOptions({
        layout: MainLayout,
    })
    const page = usePage()
    const props = defineProps({
        club: Object,
    })
    const studentName = ref('')
    const studentClub = ref('')
    const studentClubId = ref('')
    const showModal = ref(false);

    const generateCertificate = (lastName: any, firstName: any, middleName: any) => {
        studentName.value = lastName + ', ' + firstName + ' ' + middleName
        studentClub.value = props.club?.club.name
        studentClubId.value = props.club?.club.id
        showModal.value = true
    }
    onMounted(() => {
        console.log(page.props.sy)
    })
    </script>

<style lang="css" scoped>
#cert-name {
    top: 270px;
    left: 310px;
    width: 100%;
    text-align: left;
    font-size: 54px;
    font-weight: bold;
}
#cert-message {
    top: 360px;
    left: 180px;
    width: 80%;
    text-align: left;
    font-size: 16px;
    font-weight: normal;
}

#cert-name-print {
    top: 230px;
    left: 265px;
    width: 100%;
    text-align: left;
    font-size: 54px;
    font-weight: bold;
}
#cert-message-print {
    top: 310px;
    left: 140px;
    width: 80%;
    text-align: left;
    font-size: 14px;
    font-weight: normal;
}
.to-print {
    display:none;
}

@media print {
  @page {
    size: letter landscape;
    margin: 0; /* Let the CSS container handle margins */
  }
  .to-print {
    display: block;
  }
  body {
    margin: 0;
    padding: 0;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  .certificate-page {
    /* Critical: Lock to physical Letter dimensions */
    width: 11in;
    height: 8.5in;
    position: relative;
    overflow: hidden;
    page-break-after: always;
  }

  .background-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1; /* Keep it behind text */
    object-fit: cover;
  }
}
</style>
