<template>
    <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 space-y-4 md:space-y-0">
            <div >
                <h3 class="text-2xl font-semibold text-gray-800">Monthly Attendance Report</h3>
                <p class="text-gray-600 text-sm mb-4"></p>
            </div>
        </div>

        <table class="table-auto border-collapse w-full border border-black text-xs">
            <thead>
            <tr>
                <th rowspan="2" class="border border-black px-2 py-1 w-10">#</th>
                <th rowspan="2" class="border border-black px-4 py-1">OFFICERS / MEMBERS<br><span class="text-[10px]">(Alphabetical Order; Last Name, First Name)</span></th>
                <th rowspan="2" class="border border-black px-2 py-1 w-16">GRADE<br>LEVEL</th>
                <th rowspan="2" class="border border-black px-4 py-1">SECTION</th>

                <!-- Repeat for number of meetings -->
                <th colspan="5" class="border border-black px-2 py-1">
                <div class="text-center font-bold">MEETING #</div>
                <div class="flex justify-between text-[10px]">
                    <span>Date: ________</span>
                    <span>NATURE: ________</span>
                </div>
                <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                </th>

                <th colspan="5" class="border border-black px-2 py-1">
                <div class="text-center font-bold">MEETING #</div>
                <div class="flex justify-between text-[10px]">
                    <span>Date: ________</span>
                    <span>NATURE: ________</span>
                </div>
                <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                </th>

                <th colspan="5" class="border border-black px-2 py-1">
                <div class="text-center font-bold">MEETING #</div>
                <div class="flex justify-between text-[10px]">
                    <span>Date: ________</span>
                    <span>NATURE: ________</span>
                </div>
                <div class="text-[9px] text-center">Write # of minutes if T/CC; Check if EA/UA</div>
                </th>
            </tr>
            <tr>
                <!-- Repeat headers for each meeting -->
                <th class="border border-black px-2 py-1">P</th>
                <th class="border border-black px-2 py-1">T</th>
                <th class="border border-black px-2 py-1">CC</th>
                <th class="border border-black px-2 py-1">EA</th>
                <th class="border border-black px-2 py-1">UA</th>

                <th class="border border-black px-2 py-1">P</th>
                <th class="border border-black px-2 py-1">T</th>
                <th class="border border-black px-2 py-1">CC</th>
                <th class="border border-black px-2 py-1">EA</th>
                <th class="border border-black px-2 py-1">UA</th>

                <th class="border border-black px-2 py-1">P</th>
                <th class="border border-black px-2 py-1">T</th>
                <th class="border border-black px-2 py-1">CC</th>
                <th class="border border-black px-2 py-1">EA</th>
                <th class="border border-black px-2 py-1">UA</th>
            </tr>
            </thead>
            <tbody>
            <!-- Example row -->
            <tr>
                <td class="border border-black px-2 py-1 text-center">1</td>
                <td class="border border-black px-2 py-1">ACDAL, JZHYWLL D.</td>
                <td class="border border-black px-2 py-1 text-center">8</td>
                <td class="border border-black px-2 py-1 text-center">CARNATION</td>

                <!-- Meeting cells -->
                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>

                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>

                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>
                <td class="border border-black px-2 py-1"></td>
            </tr>
            </tbody>
        </table>

        <div class="mt-2 text-xs">
            <strong>Legend:</strong> T - Tardy, CC - Cutting Class, EA - Excused Absence, UA - Unexcused Absence
        </div>
    </div>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue';
defineOptions({
    layout: MainLayout
})

const props = defineProps({
    attendance: {
        type: Array,
        required: true
    }
})
</script>

<style>

</style>
