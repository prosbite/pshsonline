<template>
  <div class="w-full flex justify-center bg-gray-200 py-6 print:bg-white print:py-0">
    <!-- Printable Paper -->
    <div
      class="bg-white text-black shadow-md print:shadow-none"
      style="width: 8.5in; min-height: 11in;"
    >
      <div class="px-14 pt-14 pb-10 font-[Times_New_Roman] text-[15px] leading-tight">
        <!-- Header -->
        <div class="text-center leading-tight">
          <p class="font-bold uppercase">
            {{ props.headerTitle }}
          </p>

          <div class="">
            <span class="font-bold uppercase">CAMPUS:</span>
            <span class="inline-block border-b border-black w-64 align-middle">
              {{ props.campusName }}
            </span>
          </div>

          <p class="uppercase mt-3">
            {{ props.programName }}
          </p>

          <p class="uppercase">
            {{ props.documentTitle }}
          </p>

          <div class="mt-1">
            <span>S.Y.</span>
            <span class="inline-block border-b border-black w-20 align-middle">
              {{ schoolYearLabel }}
            </span>
          </div>
        </div>

        <!-- Intro Paragraph -->
        <div class="mt-14 text-justify leading-relaxed">
          <p>
            {{ props.introText }}
            <span class="inline-block border-b border-black min-w-14 align-middle">
              {{ props.clubName }}
            </span>.
          </p>
        </div>

        <!-- Table -->
        <table class="w-full border border-black border-collapse mt-10 text-[14px]">
          <thead>
            <tr>
              <th class="border border-black py-2 font-bold w-[20%]">
                Name/s
              </th>

              <th class="border border-black py-2 font-bold w-[20%]">
                Male/Female
              </th>

              <th class="border border-black py-2 font-bold w-[20%]">
                Grade &amp; Section
              </th>

              <th class="border border-black py-2 font-bold w-[20%]">
                ALP Position
              </th>

              <th class="border border-black py-2 font-bold w-[20%]">
                Remarks
              </th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(officer, index) in printableRows"
              :key="officer.id ?? index"
              class="h-8 !text-xs"
            >
              <td class="border border-black px-2 py-1 min-w-56 align-middle">
                {{ officer.studentName }}
              </td>
              <td class="border border-black px-2 py-1 text-center">
                {{ officer.sex }}
              </td>
              <td class="border border-black px-2 py-1">
                {{ officer.gradeSection }}
              </td>
              <td class="border border-black px-2 py-1 text-center">
                {{ officer.position }}
              </td>
              <td class="border border-black px-2 py-1">
                {{ officer.remarks }}
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Extra Line -->
        <div class="mt-10">
          <span class="inline-block border-b border-black w-2"></span>
        </div>

        <!-- Signature -->
        <div class="mt-8">
          <p>Signed by:</p>

          <div class="mt-12">
            <p class="font-bold uppercase border-b border-black w-max">
              {{ props.signatoryName }}
            </p>

            <p>{{ props.signatoryTitle }}</p>

            <div class="mt-1">
              <span>Date:</span>
              <span class="inline-block border-b border-black w-24 align-bottom"></span>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="mt-72 text-[12px] text-gray-700">
          {{ props.footerCode }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { middleInitials, ucWords } from '@/composables/utilities'

const props = defineProps({
  officers: {
    type: Array,
    default: () => [],
  },
  clubName: {
    type: String,
    default: '',
  },
  schoolYear: {
    type: [Object, String],
    default: null,
  },
  campusName: {
    type: String,
    default: '',
  },
  headerTitle: {
    type: String,
    default: 'PHILIPPINE SCIENCE HIGH SCHOOL SYSTEM',
  },
  programName: {
    type: String,
    default: 'ALTERNATIVE LEARNING PROGRAM (ALP)',
  },
  documentTitle: {
    type: String,
    default: 'CERTIFICATION OF STUDENT RECORD',
  },
  introText: {
    type: String,
    default: 'This is to certify that the following ALP student officers are in good academic standing and fit to lead the Alternative Learning Program ',
  },
  signatoryName: {
    type: String,
    default: 'FULL NAME & SIGNATURE',
  },
  signatoryTitle: {
    type: String,
    default: 'Campus Registrar',
  },
  footerCode: {
    type: String,
    default: 'PSHS-00-F-DSA-28-Ver02-Rev0 05/08/2026',
  },
  minimumRows: {
    type: Number,
    default: 2,
  },
})

const schoolYearLabel = computed(() => {
  if (typeof props.schoolYear === 'string') {
    return props.schoolYear
  }

  if (props.schoolYear?.year_start) {
    return `${props.schoolYear.year_start}-${props.schoolYear.year_end}`
  }

  return ''
})

const formatMemberName = (learner) => {
  const firstName = ucWords(learner?.first_name ?? '')
  const middleName = middleInitials(learner?.middle_name ?? '')
  const lastName = ucWords(learner?.last_name ?? '')

  return [lastName + ', ' + firstName, middleName].filter(Boolean).join(' ')
}

const printableRows = computed(() => {
  const rows = (props.officers ?? []).map((officer) => {
    const gradeLevel = officer?.current_enrollment?.section?.grade_level?.grade_level
      ?? (officer?.current_enrollment?.section?.grade_level_id ? Number(officer.current_enrollment.section.grade_level_id) + 6 : '')
    const sectionName = officer?.current_enrollment?.section?.section_name ?? ''

    return {
      id: officer.id,
      studentName: formatMemberName(officer),
      sex: ucWords(officer?.gender ?? ''),
      gradeSection: [gradeLevel, sectionName].filter(Boolean).join(' - '),
      position: officer?.position ?? '',
      remarks: officer?.remarks ?? '',
    }
  })

  while (rows.length < props.minimumRows) {
    rows.push({
      id: `blank-${rows.length + 1}`,
      studentName: '',
      sex: '',
      gradeSection: '',
      position: '',
      remarks: '',
    })
  }

  return rows
})
</script>

<style scoped>
@media print {
  @page {
    margin: 0;
  }

  body {
    margin: 0;
    padding: 0;
  }
}
</style>
