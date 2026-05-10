<template>
  <div class="consent-page w-full flex justify-center bg-gray-200 py-6 print:bg-white print:py-0">
    <!-- Printable Paper -->
    <div
      class="consent-paper bg-white text-black shadow-md print:shadow-none"
    >
      <div class="consent-sheet px-16 pt-8 pb-8 text-[15px] leading-tight font-[Times_New_Roman]">
        <!-- Header -->
        <div class="text-center leading-tight">
          <p class="font-bold uppercase">PHILIPPINE SCIENCE HIGH SCHOOL SYSTEM</p>
          <p class="uppercase">CAMPUS: Caraga Region Campus in Butuan City</p>
          <p class="uppercase">ALTERNATIVE LEARNING PROGRAM (ALP)</p>
          <p class="uppercase">PARENT CONSENT FORM</p>
        </div>

        <div class="consent-body flex flex-col">
        <!-- Details -->
        <div class="mt-8 space-y-1">
          <div class="flex items-end gap-2">
            <span>DATE:</span>
            <span class="inline-block min-w-36 border-b border-black px-2 text-center align-middle">{{ consentDate }}</span>
          </div>

          <div class="flex items-end gap-2">
            <span>NAME OF STUDENT:</span>
            <span class="inline-block min-w-44 border-b border-black px-2 text-center align-middle">{{ studentName }}</span>
          </div>

          <div class="flex items-end gap-2">
            <span>GRADE AND SECTION:</span>
            <span class="inline-block min-w-40 border-b border-black px-2 text-center align-middle">Grade {{ gradeSection }}</span>
          </div>

          <div class="flex items-end gap-2">
            <span>TITLE OF ACTIVITY:</span>
            <span class="inline-block min-w-40 border-b border-black px-2 text-center align-middle">{{ activityTitle }}</span>
          </div>

          <div class="flex items-end gap-2">
            <span>VENUE:</span>
            <span class="inline-block min-w-48 border-b border-black px-2 text-center align-middle">{{ venue }}</span>
          </div>

          <div class="flex items-end gap-2">
            <span>SCHEDULE:</span>
            <span class="inline-block min-w-44 border-b border-black px-2 text-center align-middle">{{ schedule }}</span>
          </div>
        </div>

        <!-- Body -->
        <div class="mt-10 text-justify indent-10 leading-relaxed">
          <p>
            This is to attest that I voluntarily allow my child to participate in all
            activities, meetings, and learning experiences under
            <span class="font-bold underline">{{ clubName }}</span>
            for School Year
            <span class="inline-block min-w-24 border-b border-black px-2 text-center align-middle">{{ schoolYear }}</span>.
          </p>

          <p class="mt-8">
            I acknowledge that each Alternative Learning Program supports different
            learner needs and involves varied learning strategies such as life-skills,
            project and community-based learning, supervised instructional and
            developmental activities.
          </p>

          <p class="mt-8">
            I authorize the school to manage, monitor, and communicate with me
            regarding any concerns or adjustments needed in relation to the program. I
            affirm that I am informed about the nature of the ALP and understand its
            purpose and objective.
          </p>

          <p class="mt-8">
                I also understand that participation in ALP may include activities inside
                or outside the classroom/school under the supervision of an authorized
                school personnel.
          </p>
        </div>

          <!-- Signature Blocks -->
          <div class="mt-auto pt-8">
            <!-- Parent Signature -->
            <div class="mt-8">
              <p class="font-bold uppercase leading-tight border-t pr-2 border-black mb-2 w-fit">
                FULL NAME &amp; SIGNATURE OF <br />
                PARENT/GUARDIAN
              </p>

              <div class="mt-2">
                <span>Name of Child:</span>
                <span class="inline-block min-w-44 border-b border-black px-2 text-center align-middle">{{ studentName }}</span>
              </div>

              <div class="mt-1">
                <span>Date:</span>
                <span class="inline-block min-w-24 border-b border-black px-2 text-center align-middle"></span>
              </div>
            </div>

            <!-- Adviser -->
            <div class="mt-10">
              <p>Submitted to:</p>

              <div class="mt-6">
                <p class="font-bold uppercase underline">{{ adviserName }}</p>
                <p>ALP Adviser</p>

                <div class="mt-1">
                  <span>Date:</span>
                  <span class="inline-block min-w-24 border-b border-black px-2 text-center align-middle"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer Code -->
          <div class="mt-8 text-[12px] text-gray-700">
            PSHS-00-F-DSA-31-Ver02-Rev0 05/08/2026
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

const props = defineProps({
  studentName: {
    type: String,
    required: true,
  },
  gradeSection: {
    type: String,
    required: true,
  },
  activityTitle: {
    type: String,
    required: true,
  },
  venue: {
    type: String,
    required: true,
  },
  schedule: {
    type: String,
    required: true,
  },
  clubName: {
    type: String,
    required: true,
  },
  schoolYear: {
    type: String,
    required: true,
  },
  adviserName: {
    type: String,
    required: true,
  },
  consentDate: {
    type: String,
    required: true,
  },
})
</script>

<style scoped>
.consent-page {
  page-break-after: always;
  break-after: page;
}

.consent-paper {
  width: 8.5in;
  min-height: 11in;
  box-sizing: border-box;
  overflow: hidden;
}

.consent-sheet {
  min-height: 11in;
  box-sizing: border-box;
}

.consent-body {
  min-height: calc(11in - 1.5in);
}

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
