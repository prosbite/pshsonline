<template>
  <div class="w-full flex flex-col items-center bg-gray-200 py-6 print:bg-white print:py-0">
    <div
      class="bg-white text-black shadow-md print:shadow-none mb-6 print:mb-0 print:break-after-page"
      style="width: 8.5in; min-height: 11in;"
    >
      <div class="recognition-sheet px-12 pt-10 pb-6 font-[Times_New_Roman] text-[14px] leading-snug">
        <div class="text-center leading-tight">
          <p class="font-bold uppercase">
            {{ headerTitle }}
          </p>

          <p class="uppercase">
            CAMPUS: {{ campusName }}
          </p>

          <p class="uppercase">
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
          <p>Date: {{ recognitionDate }}</p>
        </div>

        <div class="mt-5 leading-tight">
          <p class="font-bold uppercase">
            {{ recipientName }}
          </p>

          <p>{{ recipientTitle }}</p>
        </div>

        <div class="mt-8">
          <p>Greetings of integrity, excellence, and service!</p>
        </div>

        <div class="mt-6 space-y-6 text-justify leading-tight">
          <p>
            The
            <span class="font-bold">{{ clubName }}</span>
            is an Alternative Learning Program dedicated to
            <span class="underline">{{ clubPurpose }}</span>.
            Established to provide students with opportunities for leadership,
            collaboration, service, and personal growth, the club strives to
            contribute to the holistic development of learners and to support
            the goals and programs of the institution.
          </p>

          <p>
            In line with this, I respectfully seek the official recognition/
            accreditation of the
            <span class="font-bold">{{ clubName }}</span>
            for the Academic Year {{ schoolYearLabel }}. Through this recognition, the
            organization aims to formally operate under the guidelines of the
            Alternative Learning Program while continuing to provide meaningful
            activities and opportunities for its members. Recognition will also
            enable the organization to actively participate in institutional
            programs and strengthen its commitment to student development and
            service.
          </p>

          <p>
            Currently, the organization is composed of
            <span class="font-bold">{{ totalMembers }}</span>
            members, consisting of
            <span class="font-bold">{{ maleMembers }}</span>
            male members and
            <span class="font-bold">{{ femaleMembers }}</span>
            female members from various grade levels. The organization is led
            by its set of elected officers, headed by the President, together
            with the other members of the leadership structure who oversee the
            implementation of the organization’s activities and programs.
          </p>

          <p>
            The club assures its full cooperation and commitment to comply with
            the policies, rules, regulations, and requirements set by the
            institution. The officers and members likewise commit themselves to
            responsibly carrying out the organization’s plans, programs, and
            activities in accordance with the standards of the Alternative
            Learning Program.
          </p>

          <p>
            In this regard, we sincerely hope for your favorable consideration
            of this request for recognition/accreditation. Should you require
            further clarification or additional information, we would be more than willing to meet with you at your convenience.
          </p>

          <p>
            Thank you very much for your time and consideration.
          </p>
        </div>

        <div class="mt-8">
          <p>Respectfully,</p>

          <div class="mt-6">
            <p class="font-bold uppercase border-b border-black !w-fit">
              {{ presidentName }}
            </p>

            <p>{{ presidentTitle }}</p>
          </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-7">
          <div>
            <p>Prepared by:</p>

            <div class="mt-5">
              <p class="font-bold uppercase border-b border-black !w-fit">
                {{ adviserName }}
              </p>

              <p>{{ adviserTitle }}</p>
            </div>
          </div>

          <div>
            <p>Noted by:</p>

            <div class="mt-5">
              <p class="font-bold border-b border-black !w-fit">
                {{ coordinatorName }}
              </p>

              <p>{{ coordinatorTitle }}</p>
            </div>
          </div>

          <div>
            <p>Recommended by:</p>

            <div class="mt-5">
              <p class="font-bold uppercase border-b border-black !w-fit">
                {{ recommendedByName }}
              </p>

              <p>
                {{ recommendedByTitle }}
              </p>
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

        <div class="mt-auto pt-10">
          <div class="pt-2 border-t border-black text-[12px] text-gray-700">
            {{ footerCode }}
          </div>
        </div>
      </div>
    </div>

    <div
      class="bg-white text-black shadow-md print:shadow-none"
      style="width: 8.5in; min-height: 3in;"
    >
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
  recognitionDate: {
    type: String,
    default: '',
  },
  recipientName: {
    type: String,
    default: 'GRETCHEN MAE B. EMPUESTO, PhD',
  },
  recipientTitle: {
    type: String,
    default: 'ALP Coordinator',
  },
  contactDetails: {
    type: String,
    default: '',
  },
  totalMembers: {
    type: [Number, String],
    default: 0,
  },
  maleMembers: {
    type: [Number, String],
    default: 0,
  },
  femaleMembers: {
    type: [Number, String],
    default: 0,
  },
  presidentName: {
    type: String,
    default: 'FULL NAME AND SIGNATURE',
  },
  presidentTitle: {
    type: String,
    default: 'ALP President',
  },
  adviserName: {
    type: String,
    default: 'FULL NAME AND SIGNATURE',
  },
  adviserTitle: {
    type: String,
    default: 'ALP Adviser',
  },
  coordinatorName: {
    type: String,
    default: 'GRETCHEN MAE B. EMPUESTO, PhD',
  },
  coordinatorTitle: {
    type: String,
    default: 'ALP Coordinator',
  },
  recommendedByName: {
    type: String,
    default: 'JOHN RIDAN D. DECHUSA',
  },
  recommendedByTitle: {
    type: String,
    default: 'Assistant CID Chief for Student Affairs/DSA Chief',
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
    default: 'APPLICATION LETTER FOR RECOGNITION',
  },
  footerCode: {
    type: String,
    default: 'PSHS-00-F-DSA-24-Ver02-Rev0 05/08/2026',
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

const recognitionDate = computed(() => {
  if (props.recognitionDate) {
    return props.recognitionDate
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

const contactDetails = computed(() => {
  if (props.contactDetails?.trim()) {
    return props.contactDetails
  }

  return props.adviserName || props.recipientName
})

const presidentName = computed(() => {
  return ucWords(props.presidentName || '')
})

const adviserName = computed(() => {
  return ucWords(props.adviserName || '')
})
</script>

<style scoped>
.recognition-sheet {
  min-height: 11in;
  /* box-sizing: border-box; */
  display: flex;
  flex-direction: column;
}

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
