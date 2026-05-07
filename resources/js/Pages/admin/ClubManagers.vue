<template>
    <MainLayout>
        <div class="w-full bg-white p-8 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800">Club Managers</h3>
                    <p class="text-gray-600 text-sm">
                        All club managers across school years
                    </p>
                </div>
                <div class="rounded-xl bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-700">
                    Total Managers: {{ props.club_managers.length }}
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(manager, index) in props.club_managers" :key="manager.id" class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    <span class="font-semibold">{{ manager.user?.name }}</span>
                                    <span class="text-xs text-gray-500">Status: {{ manager.status }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ manager.user?.email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ manager.club_register?.club?.name }}
                            </td>
                        </tr>
                        <tr v-if="props.club_managers.length === 0">
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                No club managers found for this school year.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </MainLayout>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'

const props = defineProps({
    club_managers: Array,
    schoolYear: Object,
})
</script>
