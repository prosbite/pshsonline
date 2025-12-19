<template>
    <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-8 my-8">
            <!-- Left: Title -->
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">Clubs Monitoring</h3>
                <p class="text-gray-600 text-sm">Monitoring of each club</p>
            </div>
            <div class="flex gap-4 bg-gray-50">
                <button v-if="!editMode" @click.prevent="editMode = true" class="flex items-center gap-2 px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        >
                        <!-- Pencil body -->
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                    </svg>

                    Edit On
                </button>
                <button v-else @click.prevent="editMode = false" class="flex items-center gap-2 px-5 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition-colors duration-200">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        >
                        <!-- Pencil body -->
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                    </svg>

                    Edit Off
                </button>
                <select @change="selectClubType" v-model="clubType" required class="border flex-1 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select ALP Type</option>
                    <option value="club">Club</option>
                    <option value="school_organization">School Organization</option>
                    <option value="student_organization">Student Organization</option>
                </select>
                <select @change="selectSemester" v-model="semesterType" required class="border flex-1 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select Semester</option>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                </select>
                <!-- <select @change="selectTargetType" v-model="targetType" required class="border flex-1 !mt-0 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select Target</option>
                    <option value="5">Target 5</option>
                    <option value="10">Target 10</option>
                    <option value="11">Target 11</option>
                </select> -->
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full border border-gray-200 bg-white">
             <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                 <tr>
                 <th rowspan="2" class="px-4 py-3 text-left text-sm font-semibold border-r border-indigo-400 min-w-[250px]">
                     Adviser’s Name
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 1
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 2
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 3
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 4
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 5
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 6
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 7
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 8
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 9
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 10
                 </th>
                 <th colspan="5" class="px-4 py-2 text-center text-sm font-semibold border-r border-indigo-400">
                     Target 11
                 </th>
                 </tr>
                 <tr class="bg-indigo-100 text-indigo-900">
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>

                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">Q</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">E</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">T</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">AVG</th>
                    <th class="px-2 py-2 text-sm font-bold border-r border-gray-300">REMARKS</th>
                 </tr>
             </thead>
             <tbody class="divide-y divide-gray-200">
                <tr v-for="sem,index in semMonitoring" :key="sem.id" class="odd:bg-gray-100">
                    <td class="px-4 py-3 text-sm text-gray-700 font-medium border-r border-gray-400">
                        {{ sem.adviser }}
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_1.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_1.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_1.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_1.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_1.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_1.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_1.q,sem.monitoring.target_1.e,sem.monitoring.target_1.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_1.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_1.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_2.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_2.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_2.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_2.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_2.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_2.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_2.q,sem.monitoring.target_2.e,sem.monitoring.target_2.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_2.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_2.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_3.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_3.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_3.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_3.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_3.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_3.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_3.q,sem.monitoring.target_3.e,sem.monitoring.target_3.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_3.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_3.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_4.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_4.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_4.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_4.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_4.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_4.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_4.q,sem.monitoring.target_4.e,sem.monitoring.target_4.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_4.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_4.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_5.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_5.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_5.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_5.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_5.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_5.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_5.q,sem.monitoring.target_5.e,sem.monitoring.target_5.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_5.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_5.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_6.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_6.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_6.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_6.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_6.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_6.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_6.q,sem.monitoring.target_6.e,sem.monitoring.target_6.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_6.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_6.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_7.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_7.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_7.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_7.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_7.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_7.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_7.q,sem.monitoring.target_7.e,sem.monitoring.target_7.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_7.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_7.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_8.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_8.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_8.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_8.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_8.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_8.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_8.q,sem.monitoring.target_8.e,sem.monitoring.target_8.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_8.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_8.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_9.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_9.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_9.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_9.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_9.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_9.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_9.q,sem.monitoring.target_9.e,sem.monitoring.target_9.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_9.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_9.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_10.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_10.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_10.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_10.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_10.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_10.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_10.q,sem.monitoring.target_10.e,sem.monitoring.target_10.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_10.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_10.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_11.q) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_11.q" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_11.e) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_11.e" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ ratingValue(sem.monitoring.target_11.t) }}</span>
                        <input v-else type="number" v-model="sem.monitoring.target_11.t" class="border border-gray-200 w-[40px] p-0">
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r">
                        <span v-if="!editMode">{{ avgValue(sem.monitoring.target_11.q,sem.monitoring.target_11.e,sem.monitoring.target_11.t) }}</span>
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500 text-sm border-r border-gray-400">
                        <span v-if="!editMode">{{ sem.monitoring.target_11.remarks }}</span>
                        <input v-else type="text" v-model="sem.monitoring.target_11.remarks" class="border border-gray-200 w-[40px] p-0">
                    </td>

                </tr>
             </tbody>
             </table>

             <div class="flex justify-end mt-4">
                <button v-if="!props.ipcr" @click.prevent="saveMonitoring" class="flex items-center gap-2 px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        >
                        <!-- Floppy disk outline -->
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <!-- Label -->
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <!-- Notch -->
                        <polyline points="7 3 7 8 15 8" />
                    </svg>

                    Save IPCR
                </button>
                <button v-else @click.prevent="updateMonitoring" class="flex items-center gap-2 px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-colors duration-200">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        >
                        <!-- Floppy disk outline -->
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <!-- Label -->
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <!-- Notch -->
                        <polyline points="7 3 7 8 15 8" />
                    </svg>

                    Update IPCR
                </button>
             </div>
            <!-- <ClubsMonitoringTarget5
                v-if="targetType === 5"
                :attendances="props.attendances"
                :advisers="props.advisers"
                :monthly_attendance_reports="props.monthly_attendance_reports"
                :monthly_attendance_reports2="props.monthly_attendance_reports2"
            />
            <ClubsMonitoringTarget10 v-if="targetType === 10" :advisers="props.advisers" :accomplishment_reports="props.accomplishment_reports" :accomplishment_reports2="props.accomplishment_reports2"/>
            <ClubsMonitoringTarget11 v-if="targetType === 11" :advisers="props.advisers" :accomplishment_reports="props.accomplishment_reports"/> -->
        </div>
    </div>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import ClubsMonitoringTarget5 from '@/Pages/admin/ClubsMonitoringTarget5.vue'
import ClubsMonitoringTarget10 from '@/Pages/admin/ClubsMonitoringTarget10.vue';
import ClubsMonitoringTarget11 from '@/Pages/admin/ClubsMonitoringTarget11.vue';
import { router } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { fullDate, formatDateLocal } from '@/composables/utilities';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
defineOptions({
    layout: MainLayout
})
const props = defineProps({
    attendances: Object,
    advisers: Array,
    accomplishment_reports: Array,
    accomplishment_reports2: Array,
    monthly_attendance_reports: Array,
    monthly_attendance_reports2: Array,
    ipcr: Array,
})
const editMode = ref(false)
const clubType = ref('club')
const semesterType = ref(1)
const targetType = ref(5)
const semMonitoring = ref([])
const selectClubType = () => {
    router.visit(route('admin.clubs.monitoring', { club_type: clubType.value, semester: semesterType.value }))
}
const selectTargetType = () => {
    router.visit(route('admin.clubs.monitoring', { club_type: clubType.value, target_type: targetType.value }))
}
const selectSemester = () => {
    router.visit(route('admin.clubs.monitoring', { club_type: clubType.value, semester: semesterType.value }))
}
const initMonitoring = () => {
    props.advisers.forEach(adviser => {
        let adviserMonitoring = {
            adviser: adviser,
            isEdit: false,
            school_year: 1,
            semester: 1,
            monitoring: {
                target_1: {
                    q: '',
                    e:'',
                    t: '',
                    remarks: ''
                },
                target_2: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_3: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_4: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_5: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_6: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_7: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_8: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_9: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_10: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
                target_11: {
                    q: '',
                    e: '',
                    t: '',
                    remarks: ''
                },
            }
        }
        semMonitoring.value.push(adviserMonitoring)
    })
}
const ratingValue = (value) => {
    if(!value || value === '') return '-'
    return parseFloat(value).toFixed(1)
}
const avgValue = (q:any,e:any,t:any) => {
    if(!q || !e || !t || q === '' || e === '' || t === '') return '-'
    return parseFloat((parseFloat(q) + parseFloat(e) + parseFloat(t)) / 3).toFixed(1)
}
const saveMonitoring = () => {
    const form = new FormData()
    form.append('semester', 1)
    form.append('school_year_id', 1)
    form.append('club_type', clubType.value)
    form.append('monitoring', JSON.stringify(semMonitoring.value))
    router.post(route('admin.clubs.monitoring.store'), form, {
        onSuccess: () => {
            // success action
            toast.success('IPCR Saved!', {
                autoClose: 1000,
            })
        },
        onError: (errors) => {
            toast.error('Something went wrong.', {
                autoClose: 1000,
            })
        },
        onFinish: () => {
            // always runs (success or error)
            console.log('Request finished');
        },
    });
}

const updateMonitoring = () => {
    const form = new FormData()
    form.append('id', props.ipcr.id)
    form.append('semester', props.ipcr.semester)
    form.append('school_year_id', props.ipcr.school_year_id)
    form.append('club_type', props.ipcr.club_type)
    form.append('monitoring', JSON.stringify(semMonitoring.value))
    router.post(route('admin.clubs.monitoring.update'), form, {
        onSuccess: () => {
            // success action
            toast.success('IPCR Updated!', {
                autoClose: 1000,
            })
        },
        onError: (errors) => {
            toast.error('Something went wrong.', {
                autoClose: 1000,
            })
        },
        onFinish: () => {
            // always runs (success or error)
            console.log('Request finished');
        },
    });
}

// For Target 5

const hasMonthlyAttendanceReport = (adviser: string) => {
    return props.monthly_attendance_reports.some((report: any) => report.user.name === adviser)
}
const hasMonthlyAttendance2Report = (adviser: string) => {
    return props.monthly_attendance_reports2.some((report: any) => report.user.name === adviser)
}
const isTimelyTarget5 = (adviser: string) => {
    const report = props.monthly_attendance_reports2?.find((report: any) => report.user.name === adviser)
    if(report){
        if (new Date(formatDateLocal(report?.created_at)) <= new Date("2025-12-11")) {
            return true
        }
    }
    return false
}
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search)
    targetType.value = parseInt(urlParams.get('target_type')) ?? 5
    clubType.value = urlParams.get('club_type') ?? 'club'
    if(!props.ipcr){
        initMonitoring()
    }else {
        semMonitoring.value = JSON.parse(props.ipcr.monitoring)
    }
})
</script>

<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
  appearance: textfield;
  text-align: center!important;
}
</style>
