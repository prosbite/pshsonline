<template>
    <div class="w-fullmx-auto bg-white p-10 rounded-2xl shadow-lg border">
        <div class="flex justify-end items-center">
            <select @change="getQuarterActivities" v-model="selectedQuarter" id="attendanceDate" required class="border w-1/5 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="" disabled selected>Select Quarter</option>
            <option v-for="(quarter,index) in props.quarters" :key="index" :value="quarter.id">{{ quarter.quarter }}{{ quarterAbv[quarter.quarter - 1] }} Quarter</option>
            </select>
        </div>
        <form action="" @submit.prevent="saveReport">
            <div>
                    <!-- Header -->
                <div class="text-center border-b pb-4 mb-6">
                <h1 class="text-xl font-bold text-gray-800">Accomplishment Report</h1>
                <p class="text-gray-600">{{ props.quarter?.quarter }}<sup>{{ quarterAbv[props.quarter?.quarter - 1] }}</sup> Quarter S.Y. {{ props.schoolYear?.year_start }}-{{ props.schoolYear?.year_end }}</p>
                </div>

                <!-- Info -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block font-semibold text-gray-700">ALP:</label>
                    <input :value="page.props?.auth?.user?.club_registers?.[0]?.club?.name" type="text" disabled class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700">Adviser:</label>
                    <input :value="page.props?.auth?.user?.name" type="text" disabled class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                </div>

                <!-- Activities -->
                <div class="mb-8">
                <h2 class="font-semibold text-gray-800 mb-2">List of activities conducted: <span class="text-sm italic text-red-500">(Generated from weekly activities)</span></h2>
                <ol class="list-decimal pl-6 space-y-2 text-gray-700">
                    <li v-for="activity in props.activities" :key="activity.id">
                        <input :value="activity.activity" type="text" class="w-full border rounded-lg px-3 py-2" disabled>
                    </li>
                </ol>
                </div>

                <!-- Other Accomplishments -->
                <div class="mb-8">
                    <h2 class="font-semibold text-gray-800 mb-2">Other Accomplishments: <span class="text-sm italic text-red-500">(Input participation in competitions, winnings, major and community-based activities conducted, activities not conducted during ALP time.)</span></h2>
                    <ol class="list-decimal pl-6 space-y-2 text-gray-700 mb-2">
                        <li v-for="(acc,index) in report?.report?.other_accomplishments" :key="index">
                            <input v-model="report.report.other_accomplishments[index]" type="text" class="w-full border rounded-lg px-3 py-2">
                        </li>
                    </ol>
                    <div class="flex justify-end items-center">
                        <button @click="report.report.other_accomplishments.push('')" class="px-3 py-1 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">+Add</button>
                    </div>
                </div>

                <!-- Separate Sections Instead of Table -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Strengths</h3>
                    <ul class="space-y-2">
                    <li v-for="(strength, index) in report?.report?.strengths" :key="index">
                        <input :required="!hasStrengths" v-model="report.report.strengths[index]" type="text" class="w-full border rounded px-3 py-2">
                    </li>
                    </ul>
                    <div class="flex justify-between mt-2">
                        <div>
                            <span v-if="!hasStrengths" class="text-sm italic text-red-500">Provide at least 1 strength.</span>
                        </div>
                        <button @click="report.report.strengths.push('')" class="px-3 py-1 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">+Add</button>
                    </div>
                </div>
                <div class="bg-gray-50 border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Weaknesses</h3>
                    <ul class="space-y-2">
                    <li v-for="(weakness, index) in report?.report?.weaknesses" :key="index">
                        <input :required="!hasWeaknesses" v-model="report.report.weaknesses[index]" type="text" class="w-full border rounded px-3 py-2">
                    </li>
                    </ul>
                    <div class="flex justify-between mt-2">
                        <div>
                            <span v-if="!hasWeaknesses" class="text-sm italic text-red-500">Provide at least 1 weakness.</span>
                        </div>
                        <button @click="report.report.weaknesses.push('')" class="px-3 py-1 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">+Add</button>
                    </div>
                </div>
                <div class="bg-gray-50 border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Gaps</h3>
                    <ul class="space-y-2">
                    <li v-for="(gap, index) in report?.report?.gaps" :key="index">
                        <input :required="!hasGaps" v-model="report.report.gaps[index]" type="text" class="w-full border rounded px-3 py-2">
                    </li>
                    </ul>
                    <div class="flex justify-between mt-2">
                        <div>
                            <span v-if="!hasGaps" class="text-sm italic text-red-500">Provide at least 1 gap.</span>
                        </div>
                        <button @click="report.report.gaps.push('')" class="px-3 py-1 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">+Add</button>
                    </div>
                </div>
                <div class="bg-gray-50 border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Recommendations</h3>
                    <ul class="space-y-2">
                    <li v-for="(recommendation, index) in report?.report?.recommendations" :key="index">
                        <input :required="!hasRecommendations" v-model="report.report.recommendations[index]" type="text" class="w-full border rounded px-3 py-2">
                    </li>
                    </ul>
                    <div class="flex justify-between mt-2">
                        <div>
                            <span v-if="!hasRecommendations" class="text-sm italic text-red-500">Provide at least 1 recommendation.</span>
                        </div>
                        <button @click="report.report.recommendations.push('')" class="px-3 py-1 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">+Add</button>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-12 gap-4">
                <button v-if="props?.report?.id" @click.prevent="print" class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-indigo-50 font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-5 h-5 text-white dark:text-white"
                    >
                        <!-- Printer body -->
                        <path d="M6 9V2h12v7" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect x="6" y="14" width="12" height="8" rx="2" ry="2" />
                        <!-- Paper coming out -->
                        <line x1="8" y1="18" x2="8" y2="22" />
                        <line x1="16" y1="18" x2="16" y2="22" />
                    </svg>
                    Print
                </button>
                <button type="submit" class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Save Report</button>
            </div>
            </div>
        </form>
    <!-- Footer -->
    <!-- <div class="mt-10">
      <p class="font-semibold">Prepared by:</p>
      <div class="mt-8">
        <p class="border-t border-gray-400 w-64 text-center pt-2">Name and signature of ALA Adviser</p>
      </div>
    </div>

    <p class="text-xs text-gray-500 mt-10 text-center">Form created by: Jocelyn H. Terania, PSHS-WVC</p> -->
    <Teleport to="body">
        <table class="to-print w-full">
            <thead>
                <tr>
                    <th id="header" class="flex items-center justify-between mb-6">
                        <div class="flex gap-2 items-center">
                            <img src="/img/pisaylogo.png" class="h-[70px]" alt="Pisay Logo">
                            <div class="flex flex-col gap-0">
                                <span class="text-left">
                                    Republic of the Philippines
                                </span>
                                <span class="text-left text-xs leading-normal">
                                    DEPARTMENT OF SCIENCE AND TECHNOLOGY
                                </span>
                                <span class="text-left font-bold text-xs leading-normal">
                                    PHILIPPINE SCIENCE HIGH SCHOOL CARAGA-REGION CAMPUS
                                </span>
                                <span class="text-left text-xs leading-normal">
                                    CURRICULUM AND INSTRUCTION DIVISION
                                </span>
                            </div>
                        </div>
                        <img src="/img/bplogo.png" class="h-[70px]" alt="Pisay Logo">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div id="printContent" class="w-full flex flex-col bg-white">
                            <!-- Title -->
                            <div class="text-center mb-8">
                                <h1 class="font-bold">Accomplishment Report</h1>
                                <p class="text-gray-600">{{ props.quarter?.quarter }}<sup>{{ quarterAbv[props.quarter?.quarter - 1] }}</sup> Quarter S.Y. {{ props.schoolYear?.year_start }}-{{ props.schoolYear?.year_end }}</p>
                            </div>

                            <!-- ALP & Adviser -->
                            <div class="mb-6">
                                <p class="mb-2">ALP: <span class="border-b border-black inline-block w-48">{{ page.props?.auth?.user?.club_registers?.[0]?.club?.name }}</span></p>
                                <p>Adviser: <span class="border-b border-black inline-block w-64">{{ page.props?.auth?.user?.name }}</span></p>
                            </div>

                            <!-- List of Activities -->
                            <div class="mb-6">
                                <p class="font-semibold">List of activities conducted:
                                </p>
                                <ol class="list-decimal list-outside pl-5">
                                <li v-for="activity in props.activities" :key="activity.id">{{ activity.activity }}</li>
                                </ol>
                            </div>

                            <!-- Other Activities -->
                            <div class="mb-6">
                                <p class="font-semibold">Other Accomplishments:
                                </p>
                                <span class="text-gray-600 italic" v-if="removeEmpty(report?.report?.other_accomplishments)?.length === 0">No other accomplishments</span>
                                <ol class="list-decimal list-outside pl-5">
                                    <li v-for="(acc,index) in removeEmpty(report?.report?.other_accomplishments)" :key="index">
                                        <span v-if="acc">{{ acc }}</span>
                                    </li>
                                </ol>
                            </div>

                            <!-- Table -->
                            <div class="mb-8">
                                <table class="table-auto border border-black w-full text-sm text-center">
                                <thead class="bg-gray-100">
                                    <tr>
                                    <th class="border border-black px-4 py-2">Strength</th>
                                    <th class="border border-black px-4 py-2">Weakness</th>
                                    <th class="border border-black px-4 py-2">Gap</th>
                                    <th class="border border-black px-4 py-2">Recommendation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td class="border border-black h-10 text-left align-top">
                                        <ol class="list-disc list-outside pl-5">
                                            <li v-for="(strength,index) in removeEmpty(report?.report?.strengths)" :key="index">
                                                <span v-if="strength">{{ strength }}</span>
                                            </li>
                                        </ol>
                                    </td>
                                    <td class="border border-black text-left align-top">
                                        <ol class="list-disc list-outside pl-5">
                                            <li v-for="(weakness,index) in removeEmpty(report?.report?.weaknesses)" :key="index">
                                                <span v-if="weakness">{{ weakness }}</span>
                                            </li>
                                        </ol>
                                    </td>
                                    <td class="border border-black text-left align-top">
                                        <ol class="list-disc list-outside pl-5">
                                            <li v-for="(gap,index) in removeEmpty(report?.report?.gaps)" :key="index">
                                                <span v-if="gap">{{ gap }}</span>
                                            </li>
                                        </ol>
                                    </td>
                                    <td class="border border-black text-left align-top">
                                        <ol class="list-disc list-outside pl-5">
                                            <li v-for="(recommendation,index) in removeEmpty(report?.report?.recommendations)" :key="index">
                                                <span v-if="recommendation">{{ recommendation }}</span>
                                            </li>
                                        </ol>
                                    </td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>

                            <div class="flex flex-col gap-6 mt-6" :class="{ 'page-break-before': breakpoint }">
                                    <div class="flex justify-start">
                                        <div class="flex flex-col gap-4 flex-1">
                                            <div class="flex flex-col flex-1">
                                                <span class="mb-6 text-sm">Prepared by:</span>
                                                <span class="font-bold underline text-md uppercase">
                                                    {{ page.props.auth.user.name }}
                                                </span>
                                                <span class="text-md">
                                                    {{ page.props?.auth.user?.club_registers?.[0]?.club?.name }} Adviser
                                                </span>
                                                <span class="text-sm">Date Printed: <span id="printed-date">{{ new Date().toLocaleString() }}</span></span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-6 flex-1">
                                            <div class="flex flex-col flex-1">
                                                <span class="mb-6 text-sm">Noted by:</span>
                                                <span class="font-bold underline text-md">
                                                    GRETCHEN MAE B. EMPUESTO, PhD
                                                </span>
                                                <span class="text-md">
                                                    ALP Coordinator
                                                </span>
                                            </div>
                                            <div class="flex flex-col flex-1">
                                                <span class="font-bold underline text-md uppercase">
                                                    John Ridan D. Dechusa
                                                </span>
                                                <span class="text-md">
                                                    Asst. CID Chief for Student Affairs
                                                </span>
                                            </div>
                                        </div>
                                    </div>
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
import MainLayout from '@/Layouts/MainLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

defineOptions({
    layout: MainLayout
})
const quarterAbv = ref(['st', 'nd', 'rd', 'th'])
const selectedQuarter = ref('')
const page = usePage()
const props = defineProps<{
    report: any,
    quarter: any,
    schoolYear: any,
    activities: any[],
    quarters: any[],
}>()
const report = ref(null)
const getQuarterActivities = () => {
    router.get(route('club.accomplishment.quarterly', {club_id: page.props?.auth.user?.club_registers?.[0]?.id, quarter: selectedQuarter.value}))
}
const saveReport = () => {
    const form = useForm(report.value)
    form.post(route('club.accomplishment.quarterly.store'), {
        onSuccess: () => {
            toast.success('Report saved successfully')
        }
    })
}
const print = () => {
    window.print()
}
const removeEmpty = (arr: string[]) => {
    return arr?.filter((item) => item !== '' && item !== null && item !== undefined)
}
const checkRequiredFields = (field:string) => {
    let isValid = false
    report?.value?.report[field]?.forEach((item:string) => {
        if (item !== '') {
            isValid = true
        }
    })
    return isValid
}
const hasStrengths = computed(() => {
    return checkRequiredFields('strengths')
})
const hasWeaknesses = computed(() => {
    return checkRequiredFields('weaknesses')
})
const hasGaps = computed(() => {
    return checkRequiredFields('gaps')
})
const hasRecommendations = computed(() => {
    return checkRequiredFields('recommendations')
})
onMounted(() => {
    selectedQuarter.value = props.quarter.id
    report.value = props.report ?? {
        id: null,
        quarter_id: props.quarter.id,
        school_year_id: props.schoolYear.id,
        club_register_id: page.props?.auth?.user?.club_registers?.[0]?.id,
        status: 'pending',
        remarks: null,
        report: {
            other_accomplishments: ['', '', ''],
            strengths: [''],
            weaknesses: [''],
            gaps: [''],
            recommendations: [''],
        },
    }
    if (props.report) {
        report.value.report = JSON.parse(props.report.report)
    }
})
</script>

<style>
.to-print {
    display: none;
}
@media print {
    .no-print {
        display: none;
    }
    /* body {
        transform: scale(0.75);
        transform-origin: top left;
        width: 133.33%;
    } */
    .to-print {
        display: table;
    }
    .page-break-before {
        break-before: page; /* Standard property */
        page-break-before: always; /* Older property for broader compatibility */
    }
}
</style>
