<template>
    <Head title="External Links" />

    <MainLayout>
        <div class="space-y-6">
            <section class="rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-800 p-8 text-white shadow-xl">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-200">
                            Admin Tools
                        </p>
                        <h1 class="mt-2 text-4xl font-extrabold tracking-tight">
                            External Links
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm text-indigo-100/90">
                            Manage club external links for SY {{ currentSchoolYearLabel }}.
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur">
                        <p class="text-xs uppercase tracking-widest text-indigo-200">Links</p>
                        <p class="text-2xl font-bold">{{ externalLinks.length }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">
                            {{ isEditing ? 'Edit External Link' : 'Add External Link' }}
                        </h2>
                        <p class="text-sm text-gray-600">
                            {{ isEditing ? 'Update the selected club link.' : 'Create a new link for a club register.' }}
                        </p>
                    </div>
                    <button
                        v-if="isEditing"
                        type="button"
                        @click="resetForm"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel Edit
                    </button>
                </div>

                <form class="grid gap-4 lg:grid-cols-2" @submit.prevent="submit">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Club</label>
                        <select
                            v-model="form.club_register_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="" disabled>Select club</option>
                            <option
                                v-for="clubRegister in clubRegisters"
                                :key="clubRegister.id"
                                :value="clubRegister.id"
                            >
                                {{ clubRegister.club?.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.club_register_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.club_register_id }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                        <input
                            v-model="form.name"
                            list="external-link-names"
                            type="text"
                            placeholder="Select or type a name"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <datalist id="external-link-names">
                            <option
                                v-for="name in externalLinkNames"
                                :key="name"
                                :value="name"
                            />
                        </datalist>
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            Pick an existing name or type a new one.
                        </p>
                    </div>

                    <div class="lg:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Link</label>
                        <input
                            v-model="form.link"
                            type="url"
                            placeholder="https://..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <p v-if="form.errors.link" class="mt-1 text-sm text-red-600">
                            {{ form.errors.link }}
                        </p>
                    </div>

                    <div class="lg:col-span-2 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="resetForm"
                            class="rounded-xl border border-gray-300 px-5 py-2.5 font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Clear
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-xl bg-indigo-600 px-5 py-2.5 font-semibold text-white shadow-sm transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {{ form.processing ? 'Saving...' : (isEditing ? 'Update Link' : 'Create Link') }}
                        </button>
                    </div>
                </form>
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-md">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">Current School Year Links</h2>
                    <p class="text-sm text-gray-600">
                        Showing links saved under SY {{ currentSchoolYearLabel }}.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Club</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Link</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="externalLink in externalLinks" :key="externalLink.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ externalLink.club_register?.club?.name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ externalLink.name ?? 'External Link' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <a :href="externalLink.link" target="_blank" class="break-all text-indigo-600 hover:underline">
                                        {{ externalLink.link }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            @click="editLink(externalLink)"
                                            class="font-semibold text-emerald-600 hover:text-emerald-700"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            type="button"
                                            @click="deleteLink(externalLink)"
                                            class="font-semibold text-red-600 hover:text-red-700"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="externalLinks.length === 0">
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No external links found for this school year.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    external_links: Array,
    external_link_names: Array,
    club_registers: Array,
    current_school_year: Object,
})

const externalLinks = computed(() => props.external_links ?? [])
const externalLinkNames = computed(() => props.external_link_names ?? [])
const clubRegisters = computed(() => props.club_registers ?? [])
const currentSchoolYearLabel = computed(() => {
    const sy = props.current_school_year
    if (!sy) {
        return 'N/A'
    }

    return `${sy.year_start} - ${sy.year_end}`
})

const form = useForm({
    id: null,
    club_register_id: '',
    name: '',
    link: '',
})

const isEditing = computed(() => !!form.id)

const resetForm = () => {
    form.clearErrors()
    form.reset()
    form.id = null
    form.club_register_id = ''
}

const editLink = (externalLink) => {
    form.id = externalLink.id
    form.club_register_id = externalLink.club_register_id
    form.name = externalLink.name ?? ''
    form.link = externalLink.link ?? ''
}

const submit = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            resetForm()
        },
    }

    if (isEditing.value) {
        form.put(route('admin.external-links.update', { externalLink: form.id }), options)
        return
    }

    form.post(route('admin.external-links.store'), options)
}

const deleteLink = (externalLink) => {
    if (!confirm('Delete this external link?')) {
        return
    }

    router.delete(route('admin.external-links.destroy', { externalLink: externalLink.id }), {
        preserveScroll: true,
    })
}
</script>
