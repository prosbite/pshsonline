<template>
    <Head title="Club Manager Renewal Required" />

    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-2xl rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
            <div class="space-y-6">
                <div>
                    <p class="text-emerald-300 uppercase tracking-[0.25em] text-xs font-semibold mb-3">Club Manager Access</p>
                    <h1 class="text-4xl font-extrabold tracking-tight">Renewal required</h1>
                    <p class="mt-3 text-slate-200 leading-relaxed">
                        Your club manager record has not yet been renewed for
                        <span class="font-semibold text-white">{{ schoolYearLabel }}</span>.
                        Please contact the administrator to renew your club registration for this school year.
                    </p>
                </div>

                <div class="rounded-2xl border border-amber-300/30 bg-amber-400/10 px-5 py-4 text-amber-100">
                    Once the club is re-registered for the current school year, your dashboard will become available here again.
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-lg transition hover:bg-slate-100"
                    >
                        Log Out
                    </Link>
                    <span class="text-sm text-slate-300">
                        If you believe this is a mistake, ask an administrator to verify your current `club_manager` record.
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    school_year: {
        type: Object,
        default: null,
    },
})

const schoolYearLabel = computed(() => {
    const year = props.school_year

    if (!year) {
        return 'the current school year'
    }

    return `SY ${year.year_start}-${year.year_end}`
})
</script>
