<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { fullDateTime, ucWords } from '@/composables/utilities'

defineOptions({
    layout: MainLayout,
})

const props = defineProps<{
    logs: Array<any>
}>()

const search = ref('')
const typeFilter = ref<'all' | 'login' | 'logout'>('all')

const normalizedLogs = computed(() => {
    return [...(props.logs ?? [])].map((log) => ({
        ...log,
        user_name: ucWords(log.user?.name ?? 'Unknown User'),
        log_type_label: log.log_type === 'login' ? 'Login' : log.log_type === 'logout' ? 'Logout' : ucWords(log.log_type ?? 'Unknown'),
    }))
})

const filteredLogs = computed(() => {
    const query = search.value.trim().toLowerCase()

    return normalizedLogs.value.filter((log) => {
        const matchesType = typeFilter.value === 'all' || log.log_type === typeFilter.value
        const matchesQuery =
            !query ||
            log.user_name.toLowerCase().includes(query) ||
            log.log_type_label.toLowerCase().includes(query) ||
            fullDateTime(log.created_at).toLowerCase().includes(query)

        return matchesType && matchesQuery
    })
})

const totalLogs = computed(() => normalizedLogs.value.length)
const loginLogs = computed(() => normalizedLogs.value.filter((log) => log.log_type === 'login').length)
const logoutLogs = computed(() => normalizedLogs.value.filter((log) => log.log_type === 'logout').length)
const latestLog = computed(() => normalizedLogs.value[0] ?? null)

const badgeClasses = (type: string) => {
    if (type === 'login') {
        return 'bg-emerald-100 text-emerald-800 ring-emerald-200'
    }

    if (type === 'logout') {
        return 'bg-rose-100 text-rose-800 ring-rose-200'
    }

    return 'bg-slate-100 text-slate-700 ring-slate-200'
}
</script>

<template>
    <Head title="Log Records" />

    <div class="space-y-6">
        <section class="rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-violet-900 p-8 text-white shadow-2xl ring-1 ring-white/10">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-200">
                Admin Audit Trail
            </p>
            <div class="mt-3 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight">
                        Log Records
                    </h1>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-indigo-100/85">
                        Review login and logout activity across the system.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-200">
                            Total
                        </p>
                        <p class="mt-1 text-2xl font-bold">
                            {{ totalLogs }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-200">
                            Logins
                        </p>
                        <p class="mt-1 text-2xl font-bold">
                            {{ loginLogs }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-200">
                            Logouts
                        </p>
                        <p class="mt-1 text-2xl font-bold">
                            {{ logoutLogs }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-indigo-200">
                            Latest
                        </p>
                        <p class="mt-1 text-xs font-medium leading-5 text-white/90">
                            {{ latestLog ? fullDateTime(latestLog.created_at) : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Activity Feed
                    </h2>
                    <p class="text-sm text-gray-500">
                        Filter by name, time, or record type.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search logs..."
                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 sm:w-72"
                    >

                    <select
                        v-model="typeFilter"
                        class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
                    >
                        <option value="all">All Types</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
                                    #
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
                                    Name
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
                                    Log Type
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
                                    Log Date
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(log, index) in filteredLogs" :key="log.id" class="transition hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm font-semibold text-gray-500">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                    {{ log.user_name }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1" :class="badgeClasses(log.log_type)">
                                        {{ log.log_type_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    {{ fullDateTime(log.created_at) }}
                                </td>
                            </tr>

                            <tr v-if="filteredLogs.length === 0">
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                                    No log records found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
