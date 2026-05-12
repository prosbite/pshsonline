<template>
    <Head title="Club Dashboard" />
    <MainLayout>
        <div class="space-y-6">
            <section class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-900 text-white p-8 shadow-xl border border-white/10">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <div>
                        <p class="text-emerald-300 uppercase tracking-[0.2em] text-xs font-semibold mb-3">Club Manager Dashboard</p>
                        <h1 class="text-4xl font-extrabold tracking-tight">
                            {{ clubRegister?.club?.name ?? 'Club Dashboard' }}
                        </h1>
                        <!-- <p class="mt-3 text-slate-200 text-sm max-w-2xl">
                            Manage your club members, search students, and enlist new members for the current school year.
                        </p> -->
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 w-full lg:w-auto">
                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 backdrop-blur col-span-2">
                            <p class="text-xs uppercase tracking-widest text-slate-300">Adviser</p>
                            <p class="text-lg font-semibold">{{ clubRegister?.user?.name ?? 'N/A' }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 backdrop-blur">
                            <p class="text-xs uppercase tracking-widest text-slate-300">Officers</p>
                            <p class="text-lg font-semibold">{{ clubOfficers.length }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 border border-white/10 px-4 py-3 backdrop-blur">
                            <p class="text-xs uppercase tracking-widest text-slate-300">Members</p>
                            <p class="text-lg font-semibold">{{ clubMembers.length }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- <section class="rounded-2xl bg-white p-6 shadow-md border border-gray-200">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Enlist New Members</h2>
                        <p class="text-gray-600 text-sm">Look up students and add them to this club.</p>
                    </div>
                    <div class="w-full lg:w-[30rem]">
                        <input
                            v-model="searchInput"
                            type="text"
                            placeholder="Search by name..."
                            :disabled="isClubFull"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            :class="isClubFull ? 'cursor-not-allowed bg-gray-100 text-gray-400' : ''"
                        >
                    </div>
                </div>

                <div v-if="isClubFull" class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">
                    This club has reached the maximum of 26 members.
                </div>

                <div v-if="searchResults.length > 0" class="mt-6 overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Grade/Section</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Club(s)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Gender</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(result, index) in searchResults" :key="index" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ ucWords(result.learner.last_name ?? '') }}, {{ ucWords(result.learner.first_name ?? '') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ result.section.grade_level_id + 6 }} - {{ result.section.section_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ joinedClubs(result.learner.current_club) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ ucWords(result.learner.gender) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        v-if="!hasClub(result) && !isClubFull"
                                        @click="enlist(result)"
                                        class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700 transition-colors duration-200"
                                    >
                                        + Add
                                    </button>
                                    <span v-else-if="hasClub(result)" class="text-xs font-semibold text-emerald-700">Already added</span>
                                    <span v-else class="text-xs font-semibold text-amber-700">Club full</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="searchInput.length > 1 && searchResults.length === 0 && !loading" class="mt-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-10 text-center text-gray-500">
                    No results found.
                </div>

                <div v-if="loading" class="mt-6 flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-gray-50 px-4 py-10">
                    <img src="/img/ellipses_loader.gif" alt="" class="h-14 w-14">
                    <p class="mt-2 text-gray-500">Searching...</p>
                </div>
            </section> -->

            <section v-if="sortedClubOfficers.length > 0" class="rounded-2xl bg-white p-6 shadow-md border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Club Officers</h2>
                        <p class="text-gray-600 text-sm">Assigned club officers for the current school year.</p>
                    </div>
                    <div class="rounded-full bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700">
                        {{ sortedClubOfficers.length }} officers
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <!-- <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 w-[40px]">#</th> -->
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Position</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Grade/Section</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(officer, index) in sortedClubOfficers" :key="officer.id" class="hover:bg-gray-50">
                                <!-- <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ index + 1 + '.' }}</td> -->
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ ucWords(officer.learner?.last_name ?? '') }}, {{ ucWords(officer.learner?.first_name ?? '') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        {{ officer.position }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ officer.learner?.current_enrollment?.section?.grade_level?.grade_level }} - {{ officer.learner?.current_enrollment?.section?.section_name }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        @click="dissolveOfficer(officer)"
                                        class="rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-rose-700 transition-colors duration-200"
                                    >
                                        Revoke
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-md border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Club Members</h2>
                        <p class="text-gray-600 text-sm">Current members enrolled in this club.</p>
                    </div>
                    <div class="rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                        {{ sortedClubMembers.length }} members
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 w-[40px]">#</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 cursor-pointer hover:text-emerald-600"
                                    @click="setSort('name')"
                                >
                                    Name
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 cursor-pointer hover:text-emerald-600"
                                    @click="setSort('grade_section')"
                                >
                                    Grade/Section
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 cursor-pointer hover:text-emerald-600"
                                    @click="setSort('gender')"
                                >
                                    Gender
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Position
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(learner, index) in sortedClubMembers" :key="learner.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ index + 1 + '.' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ ucWords(learner.last_name ?? '') }}, {{ ucWords(learner.first_name ?? '') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ learner.current_enrollment.section.grade_level.grade_level }} - {{ learner.current_enrollment.section.section_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ ucWords(learner.gender) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    Member
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        @click="openOfficerModal(learner)"
                                        class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors duration-200"
                                    >
                                        Assign Position
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="clubMembers.length === 0">
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No members yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 shadow-sm">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-slate-800">Roster Summary</h3>
                        <!-- <p class="text-sm text-slate-500">Quick totals for the current club enlistment.</p> -->
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700">
                        Summary
                    </span>
                </div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Enlisted</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ enrolledTotal }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Male</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ maleTotal }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Female</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ femaleTotal }}</p>
                    </div>
                </div>
            </div>

            <div v-if="isClubFull" class="flex justify-end pb-2">
                <div class="relative">
                    <button
                        type="button"
                        @click="toggleGenerateMenu"
                        class="inline-flex select-none items-center gap-2 rounded-xl border border-emerald-600/30 bg-gradient-to-r from-emerald-600 to-emerald-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/10 transition hover:from-emerald-500 hover:to-emerald-400 hover:shadow-xl hover:shadow-emerald-900/15 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 focus:ring-offset-white"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M6 2.5A1.5 1.5 0 0 0 4.5 4v1.5h11V4A1.5 1.5 0 0 0 14 2.5H6Z" />
                            <path fill-rule="evenodd" d="M4 7a2 2 0 0 0-2 2v3.5A1.5 1.5 0 0 0 3.5 14H4v1.5A1.5 1.5 0 0 0 5.5 17h9a1.5 1.5 0 0 0 1.5-1.5V14h.5A1.5 1.5 0 0 0 18 12.5V9a2 2 0 0 0-2-2H4Zm2 5.5A1.5 1.5 0 1 1 6 9a1.5 1.5 0 0 1 0 3.5Zm1.25-2.25a.75.75 0 0 0 0 1.5h5.5a.75.75 0 0 0 0-1.5h-5.5Z" clip-rule="evenodd" />
                        </svg>
                        Print
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div
                        v-if="showGenerateMenu"
                        class="absolute right-0 bottom-full mb-2 w-64 overflow-hidden rounded-xl border border-emerald-100 bg-white shadow-2xl shadow-emerald-950/10 z-20"
                    >
                        <button
                            type="button"
                            @click="generateConsentForms"
                            class="w-full px-4 py-3 text-left text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150"
                        >
                            Consent Form
                        </button>
                        <button
                            type="button"
                            @click="printClubMembers"
                            class="w-full px-4 py-3 text-left text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150"
                        >
                            Class List
                        </button>
                        <button
                            type="button"
                            @click="printClubOfficers"
                            class="w-full px-4 py-3 text-left text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150"
                        >
                            Officers List
                        </button>
                        <button
                            type="button"
                            @click="printClubCertification"
                            class="w-full px-4 py-3 text-left text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150"
                        >
                            Officers Certification
                        </button>
                        <button
                            type="button"
                            @click="printAdvisershipLetter"
                            class="w-full px-4 py-3 text-left text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150"
                        >
                            Acceptance Letter of Advisership
                        </button>
                        <button
                            type="button"
                            @click="printRecognitionLetter"
                            class="w-full px-4 py-3 text-left text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150"
                        >
                            Application Letter for Recognition
                        </button>
                    </div>
                </div>
            </div>
            <!-- <div v-else class="rounded-2xl border border-dashed border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Print options unlock once the club roster is complete.
            </div> -->

            <SleekModal :is-visible="showOfficerModal" @close="closeOfficerModal" size="lg">
                <template #header>
                    <div class="flex flex-col pr-8">
                        <h3 class="text-2xl font-semibold text-gray-800">Assign Position</h3>
                        <p class="text-gray-600 text-sm">
                            Set an officer position for {{ selectedOfficerName || 'the selected member' }}.
                        </p>
                    </div>
                </template>
                <template #body>
                    <form id="officer-form" @submit.prevent="submitOfficer" class="space-y-4">
                        <div>
                            <label for="officer-position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                            <input
                                id="officer-position"
                                v-model="officerForm.position"
                                list="club-position-suggestions"
                                type="text"
                                class="block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="President, Vice President, Secretary..."
                            >
                            <p v-if="officerForm.errors.position" class="mt-1 text-sm text-red-600">{{ officerForm.errors.position }}</p>
                            <datalist id="club-position-suggestions">
                                <option v-for="position in positionSuggestions" :key="position" :value="position" />
                            </datalist>
                        </div>
                        <div>
                            <label for="officer-order" class="block text-sm font-medium text-gray-700 mb-1">Order No.</label>
                            <input
                                id="officer-order"
                                v-model="officerForm.order_no"
                                type="number"
                                min="1"
                                class="block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="1"
                            >
                            <p v-if="officerForm.errors.order_no" class="mt-1 text-sm text-red-600">{{ officerForm.errors.order_no }}</p>
                        </div>
                        <div v-if="selectedOfficerName" class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">
                            <span class="font-semibold">Member:</span> {{ selectedOfficerName }}
                        </div>
                    </form>
                </template>
                <template #footer>
                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeOfficerModal"
                            class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors duration-200"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            form="officer-form"
                            :disabled="officerForm.processing"
                            class="px-5 py-2 bg-emerald-600 text-white font-semibold rounded-lg shadow-md hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed transition-colors duration-200"
                        >
                            {{ officerForm.processing ? 'Saving...' : 'Assign Position' }}
                        </button>
                    </div>
                </template>
            </SleekModal>
            <SleekModal :is-visible="showAdvisershipPurposeModal" @close="closeAdvisershipPurposeModal" size="2xl">
                <template #header>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800">Advisership Letter Purpose</h3>
                        <p class="text-gray-600 text-sm">Type the club's nature, advocacy, and purpose before printing.</p>
                    </div>
                </template>
                <template #body>
                    <div class="space-y-4">
                        <div>
                            <label for="manager-advisership-purpose" class="block text-sm font-medium text-gray-700 mb-2">
                                Nature, advocacy, and purpose
                            </label>
                            <textarea
                                id="manager-advisership-purpose"
                                v-model="advisershipPurposeDraft"
                                rows="6"
                                class="block w-full rounded-lg border border-gray-300 p-3 text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="Enter a short statement describing what the club is committed to..."
                            />
                        </div>
                        <p class="text-sm text-gray-500">
                            This statement will appear in the sentence that starts with "The club is an Alternative Learning Program organization committed to..."
                        </p>
                    </div>
                </template>
                <template #footer>
                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeAdvisershipPurposeModal"
                            class="px-5 py-2 rounded-lg bg-gray-200 font-semibold text-gray-800 hover:bg-gray-300 transition-colors duration-200"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="confirmAdvisershipLetter"
                            class="px-5 py-2 rounded-lg bg-emerald-600 font-semibold text-white hover:bg-emerald-700 transition-colors duration-200"
                        >
                            Continue to Print
                        </button>
                    </div>
                </template>
            </SleekModal>
            <SleekModal :is-visible="showRecognitionPurposeModal" @close="closeRecognitionPurposeModal" size="2xl">
                <template #header>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800">Recognition Letter Purpose</h3>
                        <p class="text-gray-600 text-sm">Type the club's nature, advocacy, and purpose before printing.</p>
                    </div>
                </template>
                <template #body>
                    <div class="space-y-4">
                        <div>
                            <label for="manager-recognition-purpose" class="block text-sm font-medium text-gray-700 mb-2">
                                Nature, advocacy, and purpose
                            </label>
                            <textarea
                                id="manager-recognition-purpose"
                                v-model="recognitionPurposeDraft"
                                rows="6"
                                class="block w-full rounded-lg border border-gray-300 p-3 text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="Enter a short statement describing what the club is committed to..."
                            />
                        </div>
                        <p class="text-sm text-gray-500">
                            This statement will appear in the sentence that starts with "The club is an Alternative Learning Program dedicated to..."
                        </p>
                    </div>
                </template>
                <template #footer>
                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeRecognitionPurposeModal"
                            class="px-5 py-2 rounded-lg bg-gray-200 font-semibold text-gray-800 hover:bg-gray-300 transition-colors duration-200"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="confirmRecognitionLetter"
                            class="px-5 py-2 rounded-lg bg-emerald-600 font-semibold text-white hover:bg-emerald-700 transition-colors duration-200"
                        >
                            Continue to Print
                        </button>
                    </div>
                </template>
            </SleekModal>
            <Teleport to="body">
                <div v-if="showAdvisershipPrint" class="advisership-print-shell">
                    <AdvisershipLetter
                        :club-name="advisershipLetterData.clubName"
                        :club-purpose="advisershipLetterData.clubPurpose"
                        :school-year="advisershipLetterData.schoolYear"
                        :advisership-date="advisershipLetterData.advisershipDate"
                        :intended-adviser-name="advisershipLetterData.intendedAdviserName"
                        :intended-adviser-title="advisershipLetterData.intendedAdviserTitle"
                        :requester-name="advisershipLetterData.requesterName"
                        :requester-title="advisershipLetterData.requesterTitle"
                        :accepted-by-name="advisershipLetterData.acceptedByName"
                        :accepted-by-title="advisershipLetterData.acceptedByTitle"
                    />
                </div>
            </Teleport>
            <Teleport to="body">
                <div v-if="showRecognitionPrint" class="recognition-print-shell">
                    <ApplicationLetterForRecognition
                        :club-name="recognitionLetterData.clubName"
                        :club-purpose="recognitionLetterData.clubPurpose"
                        :school-year="recognitionLetterData.schoolYear"
                        :recognition-date="recognitionLetterData.recognitionDate"
                        :recipient-name="recognitionLetterData.recipientName"
                        :recipient-title="recognitionLetterData.recipientTitle"
                        :contact-details="recognitionLetterData.contactDetails"
                        :total-members="recognitionLetterData.totalMembers"
                        :male-members="recognitionLetterData.maleMembers"
                        :female-members="recognitionLetterData.femaleMembers"
                        :president-name="recognitionLetterData.presidentName"
                        :president-title="recognitionLetterData.presidentTitle"
                        :adviser-name="recognitionLetterData.adviserName"
                        :adviser-title="recognitionLetterData.adviserTitle"
                    />
                </div>
            </Teleport>
            <Teleport to="body">
                <div v-if="showConsentPrint" class="consent-print-shell">
                    <ConsentForm
                        v-for="member in consentFormMembers"
                        :key="member.id"
                        :student-name="member.studentName"
                        :grade-section="member.gradeSection"
                        :activity-title="member.activityTitle"
                        :venue="member.venue"
                        :schedule="member.schedule"
                        :club-name="member.clubName"
                        :school-year="member.schoolYear"
                        :adviser-name="member.adviserName"
                        :consent-date="member.consentDate"
                    />
                </div>
            </Teleport>
            <Teleport to="body">
                <div v-if="showMembersPrint" class="club-members-print-shell">
                    <ClubMembersList
                        :members="sortedClubMembers"
                        :club-name="clubRegister?.club?.name ?? ''"
                        :school-year="printSchoolYear"
                        :adviser-name="clubRegister?.user?.name ?? ''"
                        :minimum-rows="0"
                        campus-name="Caraga Region Campus in Butuan City"
                    />
                </div>
            </Teleport>
            <Teleport to="body">
                <div v-if="showOfficersPrint" class="officers-print-shell">
                    <ClubMembersList
                        :members="officersPrintRows"
                        :club-name="clubRegister?.club?.name ?? ''"
                        :school-year="printSchoolYear"
                        :adviser-name="clubRegister?.user?.name ?? ''"
                        :minimum-rows="0"
                        campus-name="Caraga Region Campus in Butuan City"
                    />
                </div>
            </Teleport>
            <Teleport to="body">
                <div v-if="showCertificationPrint" class="certification-print-shell">
                    <ClubOfficerStanding
                        :officers="certificationOfficers"
                        :club-name="clubRegister?.club?.name ?? ''"
                        :school-year="printSchoolYear"
                        signatory-name="MARIFE D. MALLONGA"
                        campus-name="Caraga Region Campus in Butuan City"
                    />
                </div>
            </Teleport>
        </div>
    </MainLayout>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import axios from 'axios'
import { clubPositions, fullDate, middleInitials, ucWords } from '@/composables/utilities'
import { useLearnerSorting, sortLearners } from '@/composables/learnerSorting'
import SleekModal from '@/Components/SleekModal.vue'
import ConsentForm from '@/Components/club/forms/ConsentForm.vue'
import ClubMembersList from '@/Components/club/forms/ClubMembersList.vue'
import ClubOfficerStanding from '@/Components/club/forms/ClubOfficerStanding.vue'
import AdvisershipLetter from '@/Components/club/forms/AdvisershipLetter.vue'
import ApplicationLetterForRecognition from '@/Components/club/forms/ApplicationLetterForRecognition.vue'

const props = defineProps({
    club_manager: Object,
})

const page = usePage()

const clubManager = computed(() => props.club_manager)
const clubRegister = computed(() => clubManager.value?.clubRegister ?? clubManager.value?.club_register ?? null)
const allClubMembers = computed(() => clubRegister.value?.learners ?? [])
const clubOfficers = computed(() => clubRegister.value?.club_officers ?? clubRegister.value?.clubOfficers ?? [])
const enrolledTotal = computed(() => allClubMembers.value.length)
const maleTotal = computed(() => allClubMembers.value.filter((member: any) => `${member.gender ?? ''}`.toLowerCase() === 'male').length)
const femaleTotal = computed(() => allClubMembers.value.filter((member: any) => `${member.gender ?? ''}`.toLowerCase() === 'female').length)
const positionSuggestions = computed(() => clubPositions(clubOfficers.value.map((officer: any) => officer.position)))
const officerLearnerIds = computed(() => clubOfficers.value.map((officer: any) => Number(officer.learner_id)))
const clubMembers = computed(() => allClubMembers.value.filter((learner: any) => !officerLearnerIds.value.includes(Number(learner.id))))
const isClubFull = computed(() => enrolledTotal.value >= 26)
const { setSort, sortItems } = useLearnerSorting('name')
const sortedClubMembers = computed(() => sortItems(clubMembers.value))
const showConsentPrint = ref(false)
const showMembersPrint = ref(false)
const showOfficersPrint = ref(false)
const showCertificationPrint = ref(false)
const showGenerateMenu = ref(false)
const showAdvisershipPrint = ref(false)
const showAdvisershipPurposeModal = ref(false)
const showRecognitionPrint = ref(false)
const showRecognitionPurposeModal = ref(false)
const advisershipPurposeDraft = ref('')
const recognitionPurposeDraft = ref('')
const consentDate = computed(() => fullDate(new Date().toISOString()))
const sortedClubOfficers = computed(() => {
    return [...clubOfficers.value].sort((a: any, b: any) => {
        const orderDiff = Number(a.order_no ?? 0) - Number(b.order_no ?? 0)
        if (orderDiff !== 0) {
            return orderDiff
        }

        return `${a.learner?.last_name ?? ''} ${a.learner?.first_name ?? ''}`.localeCompare(
            `${b.learner?.last_name ?? ''} ${b.learner?.first_name ?? ''}`,
        )
    })
})
const clubPresidentOfficer = computed(() => {
    return sortedClubOfficers.value.find((officer: any) =>
        `${officer.position ?? ''}`.toLowerCase().includes('president')
    ) ?? sortedClubOfficers.value[0] ?? null
})
const formatMemberName = (learner: any) => {
    const firstName = ucWords(learner?.first_name ?? '')
    const middleName = middleInitials(learner?.middle_name ?? '')
    const lastName = ucWords(learner?.last_name ?? '')

    return [firstName, middleName, lastName].filter(Boolean).join(' ')
}
const searchInput = ref('')
const loading = ref(false)
const searchResults = ref<any[]>([])
const showOfficerModal = ref(false)
const selectedOfficer = ref<any | null>(null)
const enlistForm = useForm({
    learner_id: 0,
    club_id: 0,
    club_reg_id: 0,
})
const officerForm = useForm({
    learner_id: 0,
    club_reg_id: 0,
    position: '',
    order_no: 1,
})
let timeout: ReturnType<typeof setTimeout> | null = null
const nextOfficerOrder = computed(() => (sortedClubOfficers.value.length ?? 0) + 1)
const normalizeSchoolYear = (schoolYear: any) => {
    if (!schoolYear) {
        return ''
    }

    if (typeof schoolYear === 'string') {
        return schoolYear
    }

    if (schoolYear?.school_year) {
        return schoolYear.school_year
    }

    if (schoolYear?.year_start) {
        return `${schoolYear.year_start}-${schoolYear.year_end}`
    }

    return ''
}
const schoolYearLabel = computed(() => {
    return normalizeSchoolYear(
        page.props.school_year
        ?? page.props.current_school_year
        ?? page.props.sy
        ?? clubRegister.value?.schoolYear
        ?? clubRegister.value?.current_school_year
    )
})
const printSchoolYear = computed(() => schoolYearLabel.value)
const advisershipPurposeDefault = computed(() => {
    return clubRegister.value?.club?.description ?? clubRegister.value?.club?.type ?? 'promoting leadership, service, and holistic student development'
})
const recognitionPurposeDefault = computed(() => {
    return clubRegister.value?.club?.description ?? clubRegister.value?.club?.type ?? 'promoting leadership, service, and holistic student development'
})
const consentFormMembers = computed(() => {
    return sortedClubMembers.value.map((learner: any) => {
        const gradeLevel = learner?.current_enrollment?.section?.grade_level?.grade_level
            ?? (learner?.current_enrollment?.section?.grade_level_id ? Number(learner.current_enrollment.section.grade_level_id) + 6 : '')
        const sectionName = learner?.current_enrollment?.section?.section_name ?? ''

        return {
            id: learner.id,
            studentName: formatMemberName(learner),
            gradeSection: [gradeLevel, sectionName].filter(Boolean).join(' - '),
            activityTitle: `${clubRegister.value?.club?.name ?? ''} Activities`,
            venue: 'Philippine Science High School - Caraga Region Campus in Butuan City',
            schedule: schoolYearLabel.value,
            clubName: clubRegister.value?.club?.name ?? '',
            schoolYear: schoolYearLabel.value,
            adviserName: clubRegister.value?.user?.name ?? '',
            consentDate: consentDate.value,
        }
    })
})
const officersPrintRows = computed(() => {
    return sortedClubOfficers.value.map((officer: any) => ({
        id: officer.id,
        first_name: officer.learner?.first_name ?? '',
        middle_name: officer.learner?.middle_name ?? '',
        last_name: officer.learner?.last_name ?? '',
        gender: officer.learner?.gender ?? '',
        current_enrollment: officer.learner?.current_enrollment ?? null,
        rightColumnValue: officer.position ?? '',
    }))
})
const certificationOfficers = computed(() => {
    return sortedClubOfficers.value.map((officer: any) => ({
        id: officer.id,
        first_name: officer.learner?.first_name ?? '',
        middle_name: officer.learner?.middle_name ?? '',
        last_name: officer.learner?.last_name ?? '',
        gender: officer.learner?.gender ?? '',
        current_enrollment: officer.learner?.current_enrollment ?? null,
        position: officer.position ?? '',
        remarks: officer.remarks ?? '',
    }))
})
const selectedOfficerName = computed(() => {
    if (!selectedOfficer.value) {
        return ''
    }

    return `${ucWords(selectedOfficer.value?.last_name ?? '')}, ${ucWords(selectedOfficer.value?.first_name ?? '')}`.trim()
})

const hasClub = (learner: any) => {
    return (learner.learner.current_club?.length ?? 0) > 0
}

const joinedClubs = (clubs: any[]) => {
    if (!clubs?.length) {
        return 'No clubs yet'
    }
    return clubs.map((club: { name: string }) => club.name).join(', ')
}

const toggleGenerateMenu = () => {
    if (!isClubFull.value) {
        toast.info('Complete the club roster to unlock print options.', {
            autoClose: 2000,
            position: toast.POSITION.TOP_RIGHT,
        })
        return
    }
    showGenerateMenu.value = !showGenerateMenu.value
}

const advisershipLetterData = computed(() => {
    const adviserName = clubRegister.value?.user?.name ?? ''
    const presidentName = clubPresidentOfficer.value?.learner
        ? `${ucWords(clubPresidentOfficer.value.learner?.first_name ?? '')} ${ucWords(clubPresidentOfficer.value.learner?.middle_name ?? '')} ${ucWords(clubPresidentOfficer.value.learner?.last_name ?? '')}`.trim().replace(/\s+/g, ' ')
        : ''

    return {
        clubName: clubRegister.value?.club?.name ?? '',
        clubPurpose: advisershipPurposeDraft.value.trim() || advisershipPurposeDefault.value,
        schoolYear: schoolYearLabel.value,
        advisershipDate: fullDate(new Date().toISOString()),
        intendedAdviserName: adviserName,
        intendedAdviserTitle: 'Special Science Teacher',
        requesterName: presidentName,
        requesterTitle: clubPresidentOfficer.value?.position ?? 'ALP President',
        acceptedByName: adviserName,
        acceptedByTitle: 'ALP Adviser',
    }
})

const recognitionLetterData = computed(() => {
    const adviserName = clubRegister.value?.user?.name ?? ''
    const presidentName = clubPresidentOfficer.value?.learner
        ? `${ucWords(clubPresidentOfficer.value.learner?.first_name ?? '')} ${ucWords(clubPresidentOfficer.value.learner?.middle_name ?? '')} ${ucWords(clubPresidentOfficer.value.learner?.last_name ?? '')}`.trim().replace(/\s+/g, ' ')
        : ''

    return {
        clubName: clubRegister.value?.club?.name ?? '',
        clubPurpose: recognitionPurposeDraft.value.trim() || recognitionPurposeDefault.value,
        schoolYear: schoolYearLabel.value,
        recognitionDate: fullDate(new Date().toISOString()),
        recipientName: 'GRETCHEN MAE B. EMPUESTO, PhD',
        recipientTitle: 'ALP Coordinator',
        contactDetails: adviserName,
        totalMembers: enrolledTotal.value,
        maleMembers: maleTotal.value,
        femaleMembers: femaleTotal.value,
        presidentName,
        presidentTitle: clubPresidentOfficer.value?.position ?? 'ALP President',
        adviserName,
        adviserTitle: 'ALP Adviser',
    }
})

const enlist = (learner: any) => {
    enlistForm.learner_id = learner.learner.id
    enlistForm.club_id = clubRegister.value?.club?.id
    enlistForm.club_reg_id = clubRegister.value?.id

    enlistForm.post(route('club.manager.register'), {
        preserveScroll: true,
        onSuccess: () => {
            enlistForm.learner_id = 0
            enlistForm.club_id = 0
            enlistForm.club_reg_id = 0
            searchInput.value = ''
            searchResults.value = []
        },
        onError: () => {
            toast.error('Failed to register member.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
    })
}

const openOfficerModal = (learner: any) => {
    selectedOfficer.value = learner
    officerForm.learner_id = learner.id
    officerForm.club_reg_id = clubRegister.value?.id ?? 0
    officerForm.position = ''
    officerForm.order_no = nextOfficerOrder.value
    showOfficerModal.value = true
}

const closeOfficerModal = () => {
    showOfficerModal.value = false
    selectedOfficer.value = null
    officerForm.reset('position', 'order_no')
}

const submitOfficer = () => {
    officerForm.post(route('club.officers.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeOfficerModal()
        },
        onError: () => {
            toast.error('Failed to assign position.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
    })
}

const dissolveOfficer = (officer: any) => {
    const confirmed = window.confirm(`Dissolve ${ucWords(officer.learner?.last_name ?? '')}, ${ucWords(officer.learner?.first_name ?? '')}'s position?`)
    if (!confirmed) {
        return
    }

    useForm({}).delete(route('club.officers.destroy', { clubOfficer: officer.id }), {
        preserveScroll: true,
        onError: () => {
            toast.error('Failed to dissolve position.', {
                autoClose: 2000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
    })
}

const printAdvisershipLetter = async () => {
    showGenerateMenu.value = false
    showConsentPrint.value = false
    showMembersPrint.value = false
    showOfficersPrint.value = false
    showCertificationPrint.value = false
    showAdvisershipPrint.value = false
    showRecognitionPrint.value = false
    advisershipPurposeDraft.value = advisershipPurposeDefault.value
    showAdvisershipPurposeModal.value = true
}

const confirmAdvisershipLetter = async () => {
    showAdvisershipPurposeModal.value = false
    showAdvisershipPrint.value = true
    await nextTick()
    document.body.classList.add('advisership-print-mode')
    await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)))
    window.print()
}

const printRecognitionLetter = async () => {
    showGenerateMenu.value = false
    showConsentPrint.value = false
    showMembersPrint.value = false
    showOfficersPrint.value = false
    showCertificationPrint.value = false
    showAdvisershipPrint.value = false
    showRecognitionPrint.value = false
    recognitionPurposeDraft.value = recognitionPurposeDefault.value
    showRecognitionPurposeModal.value = true
}

const printClubMembers = async () => {
    showGenerateMenu.value = false
    showConsentPrint.value = false
    showOfficersPrint.value = false
    showCertificationPrint.value = false
    showAdvisershipPrint.value = false
    showRecognitionPrint.value = false
    showMembersPrint.value = true
    await nextTick()
    document.body.classList.add('club-members-print-mode')
    await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)))
    window.print()
}

const printClubOfficers = async () => {
    showGenerateMenu.value = false
    showConsentPrint.value = false
    showMembersPrint.value = false
    showCertificationPrint.value = false
    showAdvisershipPrint.value = false
    showRecognitionPrint.value = false
    showOfficersPrint.value = true
    await nextTick()
    document.body.classList.add('officers-print-mode')
    await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)))
    window.print()
}

const printClubCertification = async () => {
    showGenerateMenu.value = false
    showConsentPrint.value = false
    showMembersPrint.value = false
    showOfficersPrint.value = false
    showAdvisershipPrint.value = false
    showRecognitionPrint.value = false
    showCertificationPrint.value = true
    await nextTick()
    document.body.classList.add('certification-print-mode')
    await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)))
    window.print()
}

const generateConsentForms = async () => {
    showGenerateMenu.value = false
    showMembersPrint.value = false
    showOfficersPrint.value = false
    showCertificationPrint.value = false
    showAdvisershipPrint.value = false
    showRecognitionPrint.value = false

    if (!consentFormMembers.value.length) {
        toast.info('No club members available for consent form generation.', {
            autoClose: 2000,
            position: toast.POSITION.TOP_RIGHT,
        })
        return
    }

    showConsentPrint.value = true
    await nextTick()
    document.body.classList.add('consent-print-mode')
    await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)))
    window.print()
}

const confirmRecognitionLetter = async () => {
    showRecognitionPurposeModal.value = false
    showRecognitionPrint.value = true
    await nextTick()
    document.body.classList.add('recognition-print-mode')
    await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)))
    window.print()
}

const closeAdvisershipPrint = () => {
    showAdvisershipPrint.value = false
    document.body.classList.remove('advisership-print-mode')
}

const closeRecognitionPrint = () => {
    showRecognitionPrint.value = false
    document.body.classList.remove('recognition-print-mode')
}

const closeConsentPrint = () => {
    showConsentPrint.value = false
    document.body.classList.remove('consent-print-mode')
}

const closeMembersPrint = () => {
    showMembersPrint.value = false
    document.body.classList.remove('club-members-print-mode')
}

const closeOfficersPrint = () => {
    showOfficersPrint.value = false
    document.body.classList.remove('officers-print-mode')
}

const closeCertificationPrint = () => {
    showCertificationPrint.value = false
    document.body.classList.remove('certification-print-mode')
}

const closeAdvisershipPurposeModal = () => {
    showAdvisershipPurposeModal.value = false
}

const closeRecognitionPurposeModal = () => {
    showRecognitionPurposeModal.value = false
}

const handleAfterPrint = () => {
    closeConsentPrint()
    closeMembersPrint()
    closeOfficersPrint()
    closeCertificationPrint()
    closeAdvisershipPrint()
    closeRecognitionPrint()
    closeAdvisershipPurposeModal()
    closeRecognitionPurposeModal()
    showMembersPrint.value = false
    showOfficersPrint.value = false
    showCertificationPrint.value = false
    showGenerateMenu.value = false
}

watch(searchInput, (newValue) => {
    if (timeout) {
        clearTimeout(timeout)
    }

    if (isClubFull.value) {
        searchResults.value = []
        loading.value = false
        return
    }

    if (newValue.length === 0) {
        searchResults.value = []
        loading.value = false
        return
    }

    if (newValue.length <= 1) {
        return
    }

    loading.value = true
    timeout = setTimeout(() => {
        const query = newValue.trim()
        if (!query) {
            searchResults.value = []
            loading.value = false
            return
        }

        axios.get(route('student.search', { search: query }))
            .then((response) => {
                searchResults.value = sortLearners(response.data)
            })
            .catch((error) => {
                console.error(error)
            })
            .finally(() => {
                loading.value = false
            })
    }, 700)
})

onMounted(() => {
    window.addEventListener('afterprint', handleAfterPrint)
})

onUnmounted(() => {
    window.removeEventListener('afterprint', handleAfterPrint)
})
</script>

<style scoped>
.advisership-print-shell {
    display: none;
}

.recognition-print-shell {
    display: none;
}

.consent-print-shell {
    display: none;
}

.club-members-print-shell {
    display: none;
}

.officers-print-shell {
    display: none;
}

.certification-print-shell {
    display: none;
}

@media print {
    :global(body.consent-print-mode > *:not(.consent-print-shell)) {
        display: none !important;
    }

    :global(body.consent-print-mode .consent-print-shell) {
        display: block;
    }

    :global(body.club-members-print-mode > *:not(.club-members-print-shell)) {
        display: none !important;
    }

    :global(body.club-members-print-mode .club-members-print-shell) {
        display: block;
    }

    :global(body.officers-print-mode > *:not(.officers-print-shell)) {
        display: none !important;
    }

    :global(body.officers-print-mode .officers-print-shell) {
        display: block;
    }

    :global(body.certification-print-mode > *:not(.certification-print-shell)) {
        display: none !important;
    }

    :global(body.certification-print-mode .certification-print-shell) {
        display: block;
    }

    :global(body.advisership-print-mode > *:not(.advisership-print-shell)) {
        display: none !important;
    }

    :global(body.advisership-print-mode .advisership-print-shell) {
        display: block;
    }

    :global(body.recognition-print-mode > *:not(.recognition-print-shell)) {
        display: none !important;
    }

    :global(body.recognition-print-mode .recognition-print-shell) {
        display: block;
    }
}
</style>
