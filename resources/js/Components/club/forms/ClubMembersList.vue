<template>
  <div class="w-full flex justify-center bg-gray-200 py-6 print:bg-white print:py-0">
    <!-- Printable Paper -->
    <div
      class="bg-white text-black shadow-md print:shadow-none flex flex-col"
      style="width: 8.5in; min-height: 11in;"
    >
      <div class="flex flex-1 flex-col px-16 pt-16 pb-10 font-[Times_New_Roman] text-[15px] leading-tight">
        <!-- Header -->
        <div class="text-center leading-tight">
          <p class="font-bold uppercase">
            {{ props.headerTitle }}
          </p>

          <div class="">
            <span class="font-bold uppercase">CAMPUS:</span>
            <span class="inline-block border-b border-black min-w-64 align-middle text-center">
              {{ props.campusName }}
            </span>
          </div>

          <p class="uppercase mt-2">
            {{ props.programName }}
          </p>

          <p class="uppercase">{{ props.documentTitle }}</p>

          <div class="mt-1">
            <span>S.Y.</span>
            <span class="inline-block border-b border-black min-w-20 align-middle text-center">
              {{ schoolYearLabel }}
            </span>
          </div>
        </div>

        <!-- ALP Name -->
        <div class="mt-16">
          <span class="font-bold text-[16px] mr-2">
            {{ props.programLabel }}:
          </span>
          <span class="inline-block border-b border-black min-w-12 align-middle text-left">
            {{ props.clubName }}
          </span>
        </div>
        <p v-if="props.programLabel==='ALP'" class="mt-2">
            The following is the list of scholars who will serve as the official officers:
        </p>

        <!-- Table -->
        <table class="w-full border border-black border-collapse mt-4 text-[15px]">
          <thead>
            <tr>
              <th class="border border-black font-normal w-[48%]">
                Name/s
              </th>
              <th class="border border-black font-normal w-[12%]">
                Sex
              </th>
              <th class="border border-black font-normal w-[24%]">
                Grade and Section
              </th>
              <th class="border border-black font-normal w-[16%]">
                {{ props.rightColumnLabel }}
              </th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(member, index) in printableRows"
              :key="`${member.id ?? 'blank'}-${index}`"
              class="h-8 !text-sm"
            >
              <td class="border border-black px-4 align-middle">
                <span v-if="props.showRowNumbers">
                  <span v-if="member.isBlank">{{ index + 1 }}.</span>
                  <span v-else class="mr-2">{{ index + 1 }}.</span>
                </span>
                <span>
                    {{ member.studentName ?? '' }}
                </span>
              </td>

              <td class="border border-black px-3 align-middle text-center">
                {{ member.sex ?? '' }}
              </td>

              <td class="border border-black px-3 align-middle">
                {{ member.gradeSection ?? '' }}
              </td>

              <td class="border border-black px-3 align-middle text-center">
                {{ member.rightColumnValue ?? '' }}
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Signature Section -->
        <div class="mt-16 grid grid-cols-2 gap-20">
          <!-- Left Column -->
          <div>
            <p>Prepared by:</p>

            <div class="mt-4">
              <p class="font-bold uppercase underline">
                {{ props.adviserName }}
              </p>
              <p>{{ props.adviserLabel }}</p>
            </div>

            <div class="mt-4">
              <p>Recommended by:</p>

              <div class="mt-4">
                <p class="font-bold underline">
                  JOHN RIDAN D. DECHUSA
                </p>
                <p>
                  Assistant CID Chief for Student Affairs/DSA Chief
                </p>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div>
            <p>Noted by:</p>

            <div class="mt-4">
              <p class="font-bold underline">
                GRETCHEN MAE B. EMPUESTO, PhD
              </p>
              <p>ALP Coordinator</p>
            </div>

            <div class="mt-4">
              <p>Approved by:</p>

              <div class="mt-4">
                <p class="font-bold underline">
                  MELBA C. PATACSIL, PhD
                </p>
                <p>Campus Director</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="mt-auto pt-8 text-[12px] text-gray-700">
          {{ props.footerCode }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { middleInitials, ucWords } from '@/composables/utilities'

const props = defineProps({
  members: {
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
  adviserName: {
    type: String,
    default: '',
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
    default: 'OFFICIAL CLASS LIST',
  },
  programLabel: {
    type: String,
    default: 'Alternative Learning Program',
  },
  adviserLabel: {
    type: String,
    default: 'ALP Adviser',
  },
  footerCode: {
    type: String,
    default: 'PSHS-00-F-DSA-29-Ver02-Rev0 05/08/2026',
  },
  rightColumnLabel: {
    type: String,
    default: 'Signature/s',
  },
  minimumRows: {
    type: Number,
    default: 2,
  },
  showRowNumbers: {
    type: Boolean,
    default: true,
  },
})

const schoolYearLabel = computed(() => {
  if (typeof props.schoolYear === 'string') {
    return props.schoolYear
  }

  if (props.schoolYear?.school_year) {
    return props.schoolYear.school_year
  }

  if (props.schoolYear?.year_start) {
    return `${props.schoolYear.year_start}-${props.schoolYear.year_end}`
  }

  return ''
})

const formatMemberName = (learner: any) => {
  const firstName = ucWords(learner?.first_name ?? '')
  const middleName = middleInitials(learner?.middle_name ?? '')
  const lastName = ucWords(learner?.last_name ?? '')

  return [lastName + ',', firstName, middleName].filter(Boolean).join(' ')
}

const printableRows = computed(() => {
  const rows = (props.members ?? []).map((member: any) => {
    const gradeLevel = member?.current_enrollment?.section?.grade_level?.grade_level
      ?? (member?.current_enrollment?.section?.grade_level_id ? Number(member.current_enrollment.section.grade_level_id) + 6 : '')
    const sectionName = member?.current_enrollment?.section?.section_name ?? ''

    return {
      id: member.id,
      studentName: formatMemberName(member),
      sex: ucWords(member?.gender ?? ''),
      gradeSection: [gradeLevel, sectionName].filter(Boolean).join(' - '),
      rightColumnValue: member?.rightColumnValue ?? '',
    }
  })

  while (rows.length < props.minimumRows) {
      rows.push({
        id: `blank-${rows.length + 1}`,
        isBlank: true,
        studentName: '',
        sex: '',
        gradeSection: '',
        rightColumnValue: '',
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
