<script setup>
import { reactive } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const entrySteps = [
    { title: 'Login Screen', detail: 'User enters email and password.', tone: 'from-sky-500 to-cyan-400' },
    { title: 'Authenticate', detail: 'System verifies credentials.', tone: 'from-cyan-500 to-emerald-400' },
    { title: 'Role Check', detail: 'Access is routed by user role.', tone: 'from-emerald-500 to-lime-400' },
    { title: 'Dashboard', detail: 'User lands on the main portal.', tone: 'from-indigo-500 to-violet-400' },
];

const sharedActions = [
    'View dashboard',
    'Update profile',
    'Change password',
    'Logout',
];

const expandedSections = reactive({
    shared: false,
    admin: false,
    adviser: false,
    core: false,
});

const toggleSection = (key) => {
    expandedSections[key] = !expandedSections[key];
};

const adminWorkflow = [
    {
        title: 'Users',
        items: ['View user list', 'Login as user when allowed', 'Update account details', 'Review role assignments'],
    },
    {
        title: 'Enrollment',
        items: ['Search learner', 'Create enrollment', 'Bulk enrollment', 'Assign section and school year'],
    },
    {
        title: 'Clubs and Attendance',
        items: ['Create or manage clubs', 'Register members', 'Create attendance sessions', 'Mark learner attendance'],
    },
    {
        title: 'Reports and Monitoring',
        items: ['Review infractions', 'Manage submissions', 'Open monitoring records', 'View log records and events'],
    },
];

const adviserWorkflow = [
    {
        title: 'Members',
        items: ['Open club member list', 'Review enrolled learners', 'Manage club roster'],
    },
    {
        title: 'Attendance',
        items: ['Create attendance entry', 'Edit attendance record', 'Upload image evidence', 'Resolve attendance issues'],
    },
    {
        title: 'Reports',
        items: ['View monthly attendance', 'View attendance summary', 'Generate accomplishment report', 'Check certificates'],
    },
    {
        title: 'Monitoring and Submission',
        items: ['Open monitoring page', 'Submit club work', 'Track submission status', 'Review IPCR-related items'],
    },
];

const coreWorkflow = [
    {
        title: 'Academic Records',
        items: ['Manage learners', 'Manage teachers', 'Define sections', 'Maintain school years and quarters'],
    },
    {
        title: 'Club Records',
        items: ['Create clubs', 'Register club advisers', 'Maintain club learners', 'Set club type and status'],
    },
    {
        title: 'Attendance Records',
        items: ['Track club attendance', 'Record learner attendance status', 'Store infractions', 'Store adviser attendance'],
    },
    {
        title: 'Documents and Logs',
        items: ['Handle submissions', 'Track accomplishment reports', 'Store IPCR data', 'Store log records'],
    },
];

const promptText = `Create a modern workflow diagram for the PSHS-CRC ALP Management Portal.
Flow: Login Screen -> Authenticate -> Role Check -> Dashboard.
Branch into Admin/Supervisor tools and Club Adviser tools.
Use a clean school portal design, blue/white/slate palette, clear arrows, and professional spacing.`;
</script>

<template>
    <Head title="Workflow" />

    <div class="relative min-h-screen overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[url('/img/pisaybg.png')] bg-cover bg-center bg-no-repeat"></div>
        <div class="absolute inset-0 bg-slate-950/88"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/92 via-slate-950/80 to-slate-900/70"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(14,165,233,0.10),transparent_40%),radial-gradient(circle_at_bottom_right,rgba(99,102,241,0.08),transparent_35%)]"></div>
        <div class="absolute -top-24 left-1/2 h-80 w-80 -translate-x-1/2 rounded-full bg-cyan-400/15 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-indigo-500/10 blur-3xl"></div>

        <main class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col gap-8 px-4 py-8 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-[2rem] border border-white/10 bg-slate-950/70 p-6 shadow-[0_30px_80px_rgba(2,6,23,0.55)] backdrop-blur-xl sm:p-8 lg:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.45em] text-cyan-200">
                            System Workflow
                        </p>
                        <h1 class="text-3xl font-black uppercase tracking-[0.14em] text-white sm:text-5xl">
                            PSHS-CRC ALP Management Portal
                        </h1>
                        <p class="max-w-2xl text-sm leading-7 text-slate-200 sm:text-base">
                            A role-based school portal that begins at login, checks access permissions,
                            and routes each user to the right dashboard and workflow.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <Link
                            href="/login"
                            class="rounded-full border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20"
                        >
                            Go to Login
                        </Link>
                        <Link
                            href="/dashboard"
                            class="rounded-full bg-gradient-to-r from-sky-500 via-cyan-500 to-emerald-500 px-5 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-950/20 transition hover:brightness-110"
                        >
                            Open Dashboard
                        </Link>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 lg:grid-cols-4">
                <article
                    v-for="(step, index) in entrySteps"
                    :key="step.title"
                    class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-950/70 p-5 shadow-lg shadow-black/30 backdrop-blur"
                >
                    <div :class="['mb-4 h-1.5 w-20 rounded-full bg-gradient-to-r', step.tone]"></div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-lg font-black text-white">
                            0{{ index + 1 }}
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">{{ step.title }}</h2>
                            <p class="text-sm text-slate-300">{{ step.detail }}</p>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-cyan-200">
                        <span>Flow Step</span>
                        <span class="h-px flex-1 bg-white/10"></span>
                    </div>
                </article>
            </section>

            <section class="grid gap-8 lg:grid-cols-2">
                <article class="rounded-[2rem] border border-white/10 bg-slate-950/70 p-6 shadow-xl shadow-black/30 backdrop-blur">
                    <button
                        type="button"
                        class="mb-5 flex w-full items-center justify-between gap-4 text-left"
                        @click="toggleSection('shared')"
                    >
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-200">Shared Access</p>
                            <h2 class="mt-2 text-2xl font-black text-white">Common Actions After Login</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-2xl bg-white/10 px-4 py-2 text-sm font-semibold text-slate-100">
                                Everyone
                            </div>
                            <svg :class="expandedSections.shared ? 'rotate-180' : ''" class="h-5 w-5 transition-transform text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <transition name="fade-slide">
                        <div v-if="expandedSections.shared" class="grid gap-3 sm:grid-cols-2">
                            <div
                                v-for="action in sharedActions"
                                :key="action"
                                class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4 text-sm font-medium text-slate-100"
                            >
                                {{ action }}
                            </div>
                        </div>
                    </transition>
                </article>

                <article class="rounded-[2rem] border border-white/10 bg-slate-950/70 p-6 shadow-xl shadow-black/30 backdrop-blur">
                    <button
                        type="button"
                        class="mb-5 flex w-full items-center justify-between gap-4 text-left"
                        @click="toggleSection('core')"
                    >
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-200">Role Routing</p>
                            <h2 class="mt-2 text-2xl font-black text-white">Dashboard Branching</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-300">
                                The portal separates users into two main operational paths after authentication.
                            </p>
                        </div>
                        <svg :class="expandedSections.core ? 'rotate-180' : ''" class="h-5 w-5 transition-transform text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <transition name="fade-slide">
                        <div v-if="expandedSections.core" class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-3xl border border-cyan-400/20 bg-cyan-400/10 p-5">
                                <h3 class="text-lg font-bold text-white">Admin / Supervisor</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-200">
                                    School-wide management, enrollment, clubs, attendance, reports, and logs.
                                </p>
                            </div>
                            <div class="rounded-3xl border border-emerald-400/20 bg-emerald-400/10 p-5">
                                <h3 class="text-lg font-bold text-white">Club Adviser</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-200">
                                    Club-specific attendance, submissions, reports, monitoring, and certificates.
                                </p>
                            </div>
                        </div>
                    </transition>
                </article>
            </section>

            <section class="grid gap-8 lg:grid-cols-2">
                <article class="rounded-[2rem] border border-cyan-400/15 bg-gradient-to-br from-slate-950/85 to-cyan-950/25 p-6 shadow-xl shadow-cyan-950/30 backdrop-blur">
                    <button
                        type="button"
                        class="mb-5 flex w-full items-center justify-between gap-4 text-left"
                        @click="toggleSection('admin')"
                    >
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-cyan-200">Admin Flow</p>
                            <h2 class="mt-2 text-2xl font-black text-white">Admin / Supervisor Workflow</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold text-white">
                                Portal Control
                            </div>
                            <svg :class="expandedSections.admin ? 'rotate-180' : ''" class="h-5 w-5 transition-transform text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <transition name="fade-slide">
                        <div v-if="expandedSections.admin" class="space-y-4">
                            <div
                                v-for="section in adminWorkflow"
                                :key="section.title"
                                class="rounded-3xl border border-white/10 bg-white/5 p-5"
                            >
                                <h3 class="text-lg font-bold text-white">{{ section.title }}</h3>
                                <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-300">
                                    <li v-for="item in section.items" :key="item" class="flex items-start gap-2">
                                        <span class="mt-2 h-1.5 w-1.5 rounded-full bg-cyan-300"></span>
                                        <span>{{ item }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </transition>
                </article>

                <article class="rounded-[2rem] border border-emerald-400/15 bg-gradient-to-br from-slate-950/85 to-emerald-950/25 p-6 shadow-xl shadow-emerald-950/30 backdrop-blur">
                    <button
                        type="button"
                        class="mb-5 flex w-full items-center justify-between gap-4 text-left"
                        @click="toggleSection('adviser')"
                    >
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-200">Adviser Flow</p>
                            <h2 class="mt-2 text-2xl font-black text-white">Club Adviser Workflow</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold text-white">
                                Club Focused
                            </div>
                            <svg :class="expandedSections.adviser ? 'rotate-180' : ''" class="h-5 w-5 transition-transform text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <transition name="fade-slide">
                        <div v-if="expandedSections.adviser" class="space-y-4">
                            <div
                                v-for="section in adviserWorkflow"
                                :key="section.title"
                                class="rounded-3xl border border-white/10 bg-white/5 p-5"
                            >
                                <h3 class="text-lg font-bold text-white">{{ section.title }}</h3>
                                <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-300">
                                    <li v-for="item in section.items" :key="item" class="flex items-start gap-2">
                                        <span class="mt-2 h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                        <span>{{ item }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </transition>
                </article>
            </section>

            <section class="rounded-[2rem] border border-white/10 bg-slate-950/70 p-6 shadow-xl shadow-black/30 backdrop-blur">
                <button
                    type="button"
                    class="mb-5 flex w-full items-center justify-between gap-4 text-left"
                    @click="toggleSection('core')"
                >
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-violet-200">Core Modules</p>
                        <h2 class="mt-2 text-2xl font-black text-white">Main System Areas</h2>
                    </div>
                    <svg :class="expandedSections.core ? 'rotate-180' : ''" class="h-5 w-5 transition-transform text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <transition name="fade-slide">
                    <div v-if="expandedSections.core" class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div
                            v-for="module in coreWorkflow"
                            :key="module.title"
                            class="rounded-3xl border border-white/10 bg-slate-950/40 p-5"
                        >
                            <h3 class="text-lg font-bold text-white">{{ module.title }}</h3>
                            <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-300">
                                <li v-for="item in module.items" :key="item" class="flex items-start gap-2">
                                    <span class="mt-2 h-1.5 w-1.5 rounded-full bg-violet-300"></span>
                                    <span>{{ item }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </transition>
            </section>

            <section class="grid gap-8 lg:grid-cols-2">
                <article class="rounded-[2rem] border border-white/10 bg-slate-950/70 p-6 shadow-xl shadow-black/30 backdrop-blur">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-cyan-200">Prompt Ready</p>
                    <h2 class="mt-2 text-2xl font-black text-white">Image Generation Prompt</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-300">
                        Use this prompt directly in an image generator if you want a polished visual workflow poster.
                    </p>

                    <pre class="mt-5 overflow-auto rounded-3xl border border-white/10 bg-slate-950/70 p-5 text-sm leading-6 text-slate-100 whitespace-pre-wrap">{{ promptText }}</pre>
                </article>

                <article class="rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-950/80 to-slate-950/95 p-6 shadow-xl shadow-black/30 backdrop-blur">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-200">Quick Summary</p>
                    <h2 class="mt-2 text-2xl font-black text-white">One-Page Workflow</h2>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <h3 class="text-base font-bold text-white">1. Login</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-300">
                                The user opens the login page and submits email and password.
                            </p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <h3 class="text-base font-bold text-white">2. Role Check</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-300">
                                The system checks whether the account is admin, supervisor, or club adviser.
                            </p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <h3 class="text-base font-bold text-white">3. Dashboard Branch</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-300">
                                Users land on the dashboard and see only the tools relevant to their role.
                            </p>
                        </div>
                    </div>
                </article>
            </section>
        </main>
    </div>
</template>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}
</style>
