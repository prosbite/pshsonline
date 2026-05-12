<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { fullDateTime, ucWords } from '@/composables/utilities'

defineOptions({
    layout: MainLayout,
})

const props = defineProps<{
    feedbacks: Array<any>
}>()

const search = ref('')

const normalizeFeedback = (feedback: any) => {
    const data = feedback ?? {}

    return {
        photoAttachmentUsed: data.photo_attachment_used ?? 'N/A',
        photoAttachmentRating: data.photo_attachment_rating ?? 'N/A',
        documentationUsed: data.documentation_used ?? 'N/A',
        documentationRating: data.documentation_rating ?? 'N/A',
        overallSystemImprovement: data.overall_system_improvement ?? 'N/A',
        recommendContinuedUse: data.recommend_continued_use ?? 'N/A',
        recommendationExplanation: data.recommendation_explanation ?? 'N/A',
    }
}

const labelize = (value: string) => {
    const labels: Record<string, string> = {
        yes: 'Yes',
        no: 'No',
        very_helpful_and_works_properly: 'Very helpful and works properly',
        helpful_but_needs_minor_improvement: 'Helpful but needs minor improvement',
        not_helpful_or_has_issues: 'Not helpful / has issues',
        not_applicable_have_not_used: 'Not applicable (have not used)',
        very_effective: 'Very effective',
        effective: 'Effective',
        slightly_effective: 'Slightly effective',
        not_effective: 'Not effective',
        not_sure: 'Not sure',
    }

    return labels[value] ?? ucWords(value ?? '')
}

const normalizedFeedbacks = computed(() => {
    return (props.feedbacks ?? []).map((feedback) => ({
        id: feedback.id,
        club_name: feedback.clubRegister?.club?.name ?? 'Unnamed Club',
        adviser_name: feedback.user?.name ?? 'Unknown User',
        school_year: feedback.schoolYear?.school_year ?? 'N/A',
        created_at: feedback.created_at,
        feedback: normalizeFeedback(feedback.feedback),
    }))
})

const filteredFeedbacks = computed(() => {
    const query = search.value.trim().toLowerCase()

    if (!query) {
        return normalizedFeedbacks.value
    }

    return normalizedFeedbacks.value.filter((row) => {
        const haystack = [
            row.club_name,
            row.adviser_name,
            row.school_year,
            row.feedback.photoAttachmentUsed,
            row.feedback.photoAttachmentRating,
            row.feedback.documentationUsed,
            row.feedback.documentationRating,
            row.feedback.overallSystemImprovement,
            row.feedback.recommendContinuedUse,
            row.feedback.recommendationExplanation,
            fullDateTime(row.created_at),
        ]
            .join(' ')
            .toLowerCase()

        return haystack.includes(query)
    })
})

const totalFeedbacks = computed(() => normalizedFeedbacks.value.length)
const yesRecommendations = computed(() => normalizedFeedbacks.value.filter((row) => row.feedback.recommendContinuedUse === 'yes').length)
const noRecommendations = computed(() => normalizedFeedbacks.value.filter((row) => row.feedback.recommendContinuedUse === 'no').length)
const latestFeedback = computed(() => normalizedFeedbacks.value[0] ?? null)
</script>

<template>
    <Head title="Feedback Results" />

    <div class="space-y-6">
        <section class="rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-violet-900 p-8 text-white shadow-2xl ring-1 ring-white/10">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-200">
                Admin Report
            </p>
            <div class="mt-3 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight">
                        Feedback Results
                    </h1>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-indigo-100/85">
                        View all submitted user feedback, including ratings and written comments.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-200">
                            Total
                        </p>
                        <p class="mt-1 text-2xl font-bold">
                            {{ totalFeedbacks }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-200">
                            Yes
                        </p>
                        <p class="mt-1 text-2xl font-bold">
                            {{ yesRecommendations }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-200">
                            No
                        </p>
                        <p class="mt-1 text-2xl font-bold">
                            {{ noRecommendations }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-200">
                            Latest
                        </p>
                        <p class="mt-1 text-xs font-medium leading-5 text-white/90">
                            {{ latestFeedback ? fullDateTime(latestFeedback.created_at) : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Feedback Entries
                    </h2>
                    <p class="text-sm text-gray-500">
                        Search by club, adviser, answer, or date.
                    </p>
                </div>

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search feedback..."
                    class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 lg:w-96"
                >
            </div>

            <div class="mt-6 space-y-4">
                <article
                    v-for="row in filteredFeedbacks"
                    :key="row.id"
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50"
                >
                    <div class="border-b border-gray-200 bg-white px-4 py-3">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ row.club_name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ row.adviser_name }} • S.Y. {{ row.school_year }}
                                </p>
                            </div>
                            <p class="text-xs font-medium text-gray-500">
                                {{ fullDateTime(row.created_at) }}
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 p-4 lg:grid-cols-2">
                        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Photo Attachment</p>
                            <p class="mt-2 text-sm text-gray-700">
                                Used: <span class="font-semibold">{{ labelize(row.feedback.photoAttachmentUsed) }}</span>
                            </p>
                            <p class="mt-1 text-sm text-gray-700">
                                Rating: <span class="font-semibold">{{ labelize(row.feedback.photoAttachmentRating) }}</span>
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Documentation</p>
                            <p class="mt-2 text-sm text-gray-700">
                                Used: <span class="font-semibold">{{ labelize(row.feedback.documentationUsed) }}</span>
                            </p>
                            <p class="mt-1 text-sm text-gray-700">
                                Rating: <span class="font-semibold">{{ labelize(row.feedback.documentationRating) }}</span>
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">System Improvement</p>
                            <p class="mt-2 text-sm text-gray-700">
                                {{ labelize(row.feedback.overallSystemImprovement) }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Recommendation</p>
                            <p class="mt-2 text-sm text-gray-700">
                                Continue use: <span class="font-semibold">{{ labelize(row.feedback.recommendContinuedUse) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 bg-white px-4 py-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
                            Explanation
                        </p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                            {{ row.feedback.recommendationExplanation }}
                        </p>
                    </div>
                </article>

                <div
                    v-if="filteredFeedbacks.length === 0"
                    class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-4 py-10 text-center text-sm text-gray-500"
                >
                    No feedback results found.
                </div>
            </div>
        </section>
    </div>
</template>
