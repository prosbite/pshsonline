<template>
  <div class="w-full flex justify-center bg-gray-200 py-6 print:bg-white print:py-0">
    <div class="bg-white text-black shadow-md print:shadow-none" style="width: 8.5in; min-height: 11in;">
      <div class="px-10 pt-10 pb-6 font-[Times_New_Roman] text-[14px] leading-snug">
        <div class="text-center leading-tight">
          <p class="font-bold uppercase">
            {{ headerTitle }}
          </p>

          <p class="uppercase">
            CAMPUS:
            <span class="underline">
              {{ campusName }}
            </span>
          </p>

          <p class="uppercase mt-1">
            {{ programName }}
          </p>

          <p class="uppercase">
            {{ documentTitle }}
          </p>

          <p class="mt-1">
            S.Y.
            <span class="underline">{{ schoolYearLabel }}</span>
          </p>
        </div>

        <div class="mt-8">
          <p>
            Date:
            <span class="underline">{{ advisershipDate }}</span>
          </p>
        </div>

        <div class="mt-6 leading-tight">
          <p class="font-bold uppercase !underline">
            {{ intendedAdviserName }}
          </p>
          <p class="font-bold">
            {{ intendedAdviserTitle }}
          </p>
        </div>

        <div class="mt-8">
          <p>Dear Sir/Ma'am,</p>
        </div>

        <div class="mt-6 space-y-6 text-justify leading-relaxed">
          <p>
            Greetings of integrity, excellence, and service!
          </p>

          <p>
            The
            <span class="font-bold">{{ clubName }}</span>
            is an Alternative Learning Program organization committed to
            <span class="underline">{{ clubPurpose }}</span>.
            Established to provide students with opportunities for leadership,
            collaboration, service, and personal growth, the club strives to
            contribute to the holistic development of learners and to support
            the goals and programs of the institution.
          </p>

          <p>
            In line with this, I respectfully request your acceptance as the
            official adviser of the
            <span class="font-bold">{{ clubName }}</span>
            for School Year {{ schoolYearLabel }}. As an adviser, your guidance,
            expertise, and support will play a significant role in helping the
            club achieve its objectives, strengthen its programs, and ensure the
            proper implementation of its activities and initiatives.
          </p>

          <p>
            We believe that your experience will greatly contribute to the
            direction and growth of the club, as well as inspire the members to
            become more responsible, active, and service-oriented students.
          </p>

          <p>
            I sincerely hope for your favorable consideration of this request.
            Should you wish to discuss the matter further or require additional
            clarification, we would be more than willing to meet with you at
            your convenience.
          </p>

          <p>
            Thank you very much for your time, support, and consideration.
          </p>
        </div>

        <div class="mt-10">
          <p>Respectfully,</p>

          <div class="mt-6">
            <p class="font-bold uppercase border-b border-black !w-fit">
              {{ requesterName }}
            </p>

            <p>{{ requesterTitle }}</p>
          </div>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-16">
          <div class="space-y-10">
            <div>
              <p>Accepted by:</p>

              <div class="mt-5">
                <p class="font-bold uppercase border-b border-black !w-fit">
                  {{ acceptedByName }}
                </p>

                <p>{{ acceptedByTitle }}</p>
              </div>
            </div>

            <div>
              <p>Recommended by:</p>

              <div class="mt-5">
                <p class="font-bold uppercase border-b !w-fit border-black">
                  {{ recommendedByName }}
                </p>

                <p class="text-[13px]">
                  {{ recommendedByTitle }}
                </p>
              </div>
            </div>
          </div>

          <div class="space-y-10">
            <div>
              <p>Noted by:</p>

              <div class="mt-5">
                <p class="font-bold border-b border-black !w-fit">
                  {{ notedByName }}
                </p>

                <p>{{ notedByTitle }}</p>
              </div>
            </div>

            <div>
              <p>Approved by:</p>

              <div class="mt-5">
                <p class="font-bold border-b border-black !w-fit">
                  {{ approvedByName }}
                </p>

                <p>{{ approvedByTitle }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-10 pt-2 border-t border-black text-[12px] text-gray-700">
          {{ footerCode }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { fullDate, ucWords } from '@/composables/utilities'

const props = defineProps({
  clubName: {
    type: String,
    default: '',
  },
  clubPurpose: {
    type: String,
    default: '',
  },
  schoolYear: {
    type: [Object, String],
    default: null,
  },
  advisershipDate: {
    type: String,
    default: '',
  },
  intendedAdviserName: {
    type: String,
    default: '',
  },
  intendedAdviserTitle: {
    type: String,
    default: 'Special Science Teacher',
  },
  requesterName: {
    type: String,
    default: 'FULL NAME AND SIGNATURE',
  },
  requesterTitle: {
    type: String,
    default: 'ALP President',
  },
  acceptedByName: {
    type: String,
    default: '',
  },
  acceptedByTitle: {
    type: String,
    default: 'ALP Adviser',
  },
  recommendedByName: {
    type: String,
    default: 'JOHN RIDAN D. DECHUSA',
  },
  recommendedByTitle: {
    type: String,
    default: 'Assistant CID Chief for Student Affairs/DSA Chief',
  },
  notedByName: {
    type: String,
    default: 'GRETCHEN MAE B. EMPUESTO, PhD',
  },
  notedByTitle: {
    type: String,
    default: 'ALP Coordinator',
  },
  approvedByName: {
    type: String,
    default: 'MELBA C. PATACSIL, PhD',
  },
  approvedByTitle: {
    type: String,
    default: 'Campus Director',
  },
  campusName: {
    type: String,
    default: 'Caraga Region Campus in Butuan City',
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
    default: 'ACCEPTANCE LETTER OF ADVISERSHIP',
  },
  footerCode: {
    type: String,
    default: 'PSHS-00-F-DSA-26-Ver02-Rev0 05/08/2026',
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

const advisershipDate = computed(() => {
  if (props.advisershipDate) {
    return props.advisershipDate
  }

  return fullDate(new Date().toISOString())
})

const clubPurpose = computed(() => {
  const purpose = props.clubPurpose?.trim()
  if (purpose) {
    return purpose
  }

  return 'promoting leadership, service, and holistic student development'
})

const acceptedByName = computed(() => {
  return props.acceptedByName || props.intendedAdviserName
})

const requesterName = computed(() => {
  return ucWords(props.requesterName || '')
})
</script>

<style scoped>
@media print {
  @page {
    size: letter;
    margin: 0;
  }

  body {
    margin: 0;
    padding: 0;
  }
}
</style>
