<template>
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Feedback Form</p>
                <h1 class="mt-2 text-3xl font-bold text-gray-900">ALP Management Portal User Feedback</h1>
                <p class="mt-2 text-gray-600">
                    Please complete this form before accessing your club certificate page.
                </p>
                <div class="mt-4 grid gap-3 text-sm text-gray-600 sm:grid-cols-2">
                    <div>
                        <span class="font-semibold text-gray-900">Club:</span>
                        {{ props.club?.club?.name }}
                    </div>
                    <div>
                        <span class="font-semibold text-gray-900">School Year:</span>
                        S.Y. {{ props.schoolYear?.year_start }} - {{ props.schoolYear?.year_end }}
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">1. Attachment of Photo in Attendance Submission</h2>
                    <p class="mt-1 text-sm text-gray-500">Have you used the feature?</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <label v-for="option in yesNoOptions" :key="option.value" class="cursor-pointer">
                            <input v-model="form.feedback.photo_attachment_used" type="radio" :value="option.value" class="sr-only" />
                            <span class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition"
                                :class="form.feedback.photo_attachment_used === option.value ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'">
                                {{ option.label }}
                            </span>
                        </label>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-700">If YES, how would you rate its functionality and usefulness?</p>
                        <p class="mt-1 text-xs text-gray-500">If you answered No, the form will mark this as Not applicable.</p>
                        <div class="mt-3 grid gap-3">
                            <label v-for="option in ratingOptions" :key="option.value" class="flex cursor-pointer items-center gap-3 rounded-2xl border p-4 transition"
                                :class="form.feedback.photo_attachment_rating === option.value ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white hover:border-gray-300'">
                                <input v-model="form.feedback.photo_attachment_rating" type="radio" :value="option.value" class="h-4 w-4 text-indigo-600" />
                                <span class="text-sm text-gray-700">{{ option.label }}</span>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">2. Inclusion of Documentation in the Generated Accomplishment Report</h2>
                    <p class="mt-1 text-sm text-gray-500">Have you used the feature?</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <label v-for="option in yesNoOptions" :key="option.value" class="cursor-pointer">
                            <input v-model="form.feedback.documentation_used" type="radio" :value="option.value" class="sr-only" />
                            <span class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition"
                                :class="form.feedback.documentation_used === option.value ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'">
                                {{ option.label }}
                            </span>
                        </label>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-700">If YES, how would you rate its functionality and usefulness?</p>
                        <p class="mt-1 text-xs text-gray-500">If you answered No, the form will mark this as Not applicable.</p>
                        <div class="mt-3 grid gap-3">
                            <label v-for="option in ratingOptions" :key="option.value" class="flex cursor-pointer items-center gap-3 rounded-2xl border p-4 transition"
                                :class="form.feedback.documentation_rating === option.value ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white hover:border-gray-300'">
                                <input v-model="form.feedback.documentation_rating" type="radio" :value="option.value" class="h-4 w-4 text-indigo-600" />
                                <span class="text-sm text-gray-700">{{ option.label }}</span>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">3. Overall, how would you rate the improvements in the system?</h2>
                    <div class="mt-4 grid gap-3">
                        <label v-for="option in overallOptions" :key="option.value" class="flex cursor-pointer items-center gap-3 rounded-2xl border p-4 transition"
                            :class="form.feedback.overall_system_improvement === option.value ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 bg-white hover:border-gray-300'">
                            <input v-model="form.feedback.overall_system_improvement" type="radio" :value="option.value" class="h-4 w-4 text-indigo-600" />
                            <span class="text-sm text-gray-700">{{ option.label }}</span>
                        </label>
                    </div>
                </section>

                <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">4. Would you recommend the continued use of this system for the succeeding school years?</h2>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <label v-for="option in recommendOptions" :key="option.value" class="cursor-pointer">
                            <input v-model="form.feedback.recommend_continued_use" type="radio" :value="option.value" class="sr-only" />
                            <span class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition"
                                :class="form.feedback.recommend_continued_use === option.value ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'">
                                {{ option.label }}
                            </span>
                        </label>
                    </div>
                </section>

                <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">5. Please explain your answer in Question 4.</h2>
                    <textarea
                        v-model="form.feedback.recommendation_explanation"
                        rows="5"
                        class="mt-4 w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Share your thoughts here..."
                    />
                </section>

                <div class="flex items-center justify-end gap-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        Submit Feedback
                    </button>
                </div>
            </form>
    </div>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    club: Object,
    schoolYear: Object,
});

const yesNoOptions = [
    { label: 'Yes', value: 'yes' },
    { label: 'No', value: 'no' },
];

const ratingOptions = [
    { label: 'Very helpful and works properly', value: 'very_helpful_and_works_properly' },
    { label: 'Helpful but needs minor improvement', value: 'helpful_but_needs_minor_improvement' },
    { label: 'Not helpful / has issues', value: 'not_helpful_or_has_issues' },
    { label: 'Not applicable (have not used)', value: 'not_applicable_have_not_used' },
];

const overallOptions = [
    { label: 'Very effective', value: 'very_effective' },
    { label: 'Effective', value: 'effective' },
    { label: 'Slightly effective', value: 'slightly_effective' },
    { label: 'Not effective', value: 'not_effective' },
];

const recommendOptions = [
    { label: 'Yes', value: 'yes' },
    { label: 'No', value: 'no' },
    { label: 'Not sure', value: 'not_sure' },
];

const form = useForm({
    feedback: {
        photo_attachment_used: '',
        photo_attachment_rating: '',
        documentation_used: '',
        documentation_rating: '',
        overall_system_improvement: '',
        recommend_continued_use: '',
        recommendation_explanation: '',
    },
});

watch(
    () => form.feedback.photo_attachment_used,
    (value) => {
        if (value === 'no') {
            form.feedback.photo_attachment_rating = 'not_applicable_have_not_used';
        }
        if (value === 'yes' && form.feedback.photo_attachment_rating === 'not_applicable_have_not_used') {
            form.feedback.photo_attachment_rating = '';
        }
    }
);

watch(
    () => form.feedback.documentation_used,
    (value) => {
        if (value === 'no') {
            form.feedback.documentation_rating = 'not_applicable_have_not_used';
        }
        if (value === 'yes' && form.feedback.documentation_rating === 'not_applicable_have_not_used') {
            form.feedback.documentation_rating = '';
        }
    }
);

const submit = () => {
    form.post(route('club.feedback.store', { club_id: props.club?.id }));
};
</script>
