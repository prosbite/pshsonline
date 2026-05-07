<script setup lang="ts">
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import { fullDate } from '@/composables/utilities'

defineOptions({
    layout: MainLayout,
})

const props = defineProps<{
    clubs: Array<any>
    schoolYear: any
}>()

const clubsWithPhotos = computed(() => {
    return props.clubs.map((register) => ({
        ...register,
        attendances: register.club_attendances ?? [],
    }))
})
</script>

<template>
    <Head title="Accomplishment Summary" />

    <div class="space-y-8">
        <section class="rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-violet-900 p-8 text-white shadow-2xl ring-1 ring-white/10">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-200">
                Admin Report
            </p>
            <h1 class="mt-3 text-4xl font-extrabold">
                Accomplishment Summary
            </h1>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-indigo-100/90">
                Club attendance documentation with photos, grouped by club and ordered by date.
            </p>
            <p class="mt-4 text-sm text-indigo-100/80">
                School Year: {{ props.schoolYear?.year_start }} - {{ props.schoolYear?.year_end }}
            </p>
        </section>

        <section
            v-for="club in clubsWithPhotos"
            :key="club.id"
            class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm"
        >
            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ club.club?.name ?? 'Unnamed Club' }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ club.attendances.length }} photo{{ club.attendances.length === 1 ? '' : 's' }} found
                    </p>
                </div>
            </div>

            <div
                v-if="club.attendances.length"
                class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3"
            >
                <article
                    v-for="attendance in club.attendances"
                    :key="attendance.id"
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
                >
                    <div class="aspect-[4/3] bg-gray-100">
                        <img
                            :src="`/storage/${attendance.image}`"
                            :alt="attendance.activity || 'Attendance photo'"
                            class="h-full w-full object-cover"
                        />
                    </div>
                    <div class="space-y-1 p-4">
                        <h3 class="text-base font-semibold text-gray-900">
                            {{ attendance.activity }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ fullDate(attendance.date) }}
                        </p>
                    </div>
                </article>
            </div>

            <div
                v-else
                class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500"
            >
                No attendance photos yet for this club.
            </div>
        </section>
    </div>
</template>
