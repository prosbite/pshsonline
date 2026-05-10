<template>
  <div class="w-full flex justify-center bg-gray-200 py-6 print:bg-white print:py-0">
    <!-- Printable Paper -->
    <div
      class="bg-white text-black shadow-md print:shadow-none"
      style="width: 8.5in; min-height: 11in;"
    >
      <div class="px-16 pt-16 pb-10 font-[Times_New_Roman] text-[15px] leading-tight">
        <!-- Header -->
        <div class="text-center leading-tight">
          <p class="font-bold uppercase">
            PHILIPPINE SCIENCE HIGH SCHOOL SYSTEM
          </p>

          <div class="mt-4">
            <span class="font-bold uppercase">CAMPUS:</span>
            <span class="inline-block border-b border-black w-64 align-middle"></span>
          </div>

          <p class="uppercase mt-1">
            ALTERNATIVE LEARNING PROGRAM (ALP)
          </p>

          <p class="uppercase">OFFICIAL CLASS LIST</p>

          <div class="mt-1">
            <span>S.Y.</span>
            <span class="inline-block border-b border-black w-20 align-middle"></span>
          </div>
        </div>

        <!-- ALP Name -->
        <div class="mt-16">
          <span class="font-bold text-[16px]">
            Alternative Learning Program:
          </span>
          <span class="inline-block border-b border-black w-64 align-middle"></span>
        </div>

        <!-- Table -->
        <table class="w-full border border-black border-collapse mt-4 text-[15px]">
          <thead>
            <tr>
              <th class="border border-black font-normal py-1 w-[50%]">
                Name/s
              </th>
              <th class="border border-black font-normal py-1 w-[12%]">
                Sex
              </th>
              <th class="border border-black font-normal py-1 w-[22%]">
                Grade and Section
              </th>
              <th class="border border-black font-normal py-1 w-[16%]">
                Signature/s
              </th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="n in 5"
              :key="n"
              class="h-12"
            >
              <td class="border border-black px-4 align-middle">
                {{ n }}.
              </td>

              <td class="border border-black"></td>

              <td class="border border-black"></td>

              <td class="border border-black"></td>
            </tr>
          </tbody>
        </table>

        <!-- Signature Section -->
        <div class="mt-16 grid grid-cols-2 gap-20">
          <!-- Left Column -->
          <div>
            <p>Prepared by:</p>

            <div class="mt-10">
              <p class="font-bold uppercase">
                FULL NAME AND SIGNATURE
              </p>
              <p>ALP Adviser</p>
            </div>

            <div class="mt-10">
              <p>Recommended by:</p>

              <div class="mt-10">
                <p class="font-bold uppercase">
                  FULL NAME AND SIGNATURE
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

            <div class="mt-10">
              <p class="font-bold uppercase">
                FULL NAME AND SIGNATURE
              </p>
              <p>ALP Coordinator</p>
            </div>

            <div class="mt-10">
              <p>Approved by:</p>

              <div class="mt-10">
                <p class="font-bold uppercase">
                  FULL NAME AND SIGNATURE
                </p>
                <p>Campus Director</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="mt-20 text-[12px] text-gray-700">
          PSHS-00-F-DSA-29-Ver02-Rev0 05/08/2026
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Vue 3 Component
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
