<template>
    <MainLayout>
        <div class="page">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{club?.name}} Enlistment</h1>

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <input v-model="searchInput" type="text" placeholder="Search by Name" class="flex-grow p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <!-- <select class="p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Filter by Grade</option>
                        <option value="10">Grade 10</option>
                        <option value="11">Grade 11</option>
                        <option value="12">Grade 12</option>
                    </select>
                    <select class="p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Filter by Role</option>
                        <option value="leader">Leader</option>
                        <option value="member">Member</option>
                    </select>
                    <select class="p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Filter by Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select> -->
                </div>
                <div v-if="searchResults.length > 0" class="flex flex-col gap-2 py-4">
                    <table class="w-full">
                        <tbody>
                            <tr v-for="(learner,index) in searchResults" :key="index" class="border-b hover:bg-gray-100">
                                <!-- <td class="py-2">
                                    <input v-model="learner.checked" type="checkbox">
                                </td> -->
                                <td class="py-2">{{learner.last_name}}, {{learner.first_name}}</td>
                                <td class="py-2">{{learner.current_enrollment.section.grade_level.grade_level + ' - ' + learner.current_enrollment.section.section_name}}</td>
                                <td class="py-2 text-gray-500">{{ learner.current_club?.length > 0 ? learner.current_club?.[0].name : 'No club yet' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="{'bg-green-100 text-green-800': learner.gender === 'male', 'bg-red-100 text-red-800': learner.gender === 'female'}"
                                    >{{ ucWords(learner.gender) }}</span>
                                    <!-- <span :class="{'bg-green-100 text-green-800': member.status === 'Active', 'bg-red-100 text-red-800': member.status === 'Inactive'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">{{ member.status }}</span> -->
                                </td>
                                <td>
                                    <div class="flex w-full justify-end">
                                        <button v-if="!hasClub(learner)" @click="addNewMember(learner)" class="px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">+ Add</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="searchResults.length === 0 && searchInput.length > 1" class="py-4">
                    <span class="text-left text-gray-500">No results found.</span>
                </div>
                <div class="flex flex-wrap gap-4">
                    <!-- <button @click="addNewMember" v-if="checkedLearners.length > 0" class="px-5 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200">Add New Member</button> -->
                    <!-- <button v-else class="px-5 py-2 bg-gray-300 text-white font-semibold rounded-lg shadow-md">Add New Member</button> -->
                    <!-- <button class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200">Bulk Import</button> -->
                    <!-- <button class="px-5 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-colors duration-200">Assign from School DB</button> -->
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade/Section</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="member,index in clubMembers" :key="index">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ member.last_name }}, {{ member.first_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ member.grade_section.grade_level.grade_level + ' - ' + member.grade_section.section_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Member</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="{'bg-green-100 text-green-800': member.gender === 'male', 'bg-red-100 text-red-800': member.gender === 'female'}"
                                >{{ ucWords(member.gender) }}</span>
                                <!-- <span :class="{'bg-green-100 text-green-800': member.status === 'Active', 'bg-red-100 text-red-800': member.status === 'Inactive'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">{{ member.status }}</span> -->
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <!-- <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a> -->
                                <a @click.prevent="unregisterMember(member)" href="#" class="text-red-600 hover:text-red-900">Remove</a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-left px-6 py-4 text-gray-500">
                                <span class="mr-4">
                                    Total members: {{ clubMembers.length }}
                                </span>
                                <span class="mr-4">
                                    Male: {{ clubMembers.filter((member: any) => member.gender === 'male').length }}
                                </span>
                                <span>
                                    Female: {{ clubMembers.filter((member: any) => member.gender === 'female').length }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="club.learners.length === 0">
                            <td colspan="5" class="text-left px-6 py-4 text-gray-500">No members yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </MainLayout>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { computed, ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { ucWords } from '@/composables/utilities'

const page = usePage()
const props = defineProps({
    learners: {
        type: Array,
        required: true,
        default: () => []
    },
})
const hasClub = (learner: any) => {
    return learner.current_club?.length > 0
}

const addNewMember = (learner: any) => {
    const data = {
        learner_id: learner.id,
        club_id: club.value.id,
    }
    console.log(data)
    router.post(route('club.register'), data, {
        onSuccess: () => {
            toast.success('Member registered successfully.', {
                autoClose: 3000,
                position: toast.POSITION.TOP_RIGHT,
            })
            searchInput.value = ''
        },
        onError: () => {
            toast.error('Failed to register member.', {
                autoClose: 3000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onFinish: () => {

        }
    })
}

const clubMembers = computed(() => {
    let members = JSON.parse(JSON.stringify(club.value.learners))
    members.map((member: any) => {
        props.learners.map((learner: any) => {
            if (learner.id === member.id) {
                member.grade_section = learner.current_enrollment.section
            }
        })
    })
    return members
})

const unregisterMember = (learner: any) => {
    if (!confirm('Are you sure you want to unregister this member?')) {
        return
    }
    const data = {
        learner_id: learner.id,
        club_id: club.value.id,
    }
    console.log(data)
    router.post(route('club.unregister'), data, {
        onSuccess: () => {
            toast.success('Member unregistered successfully.', {
                autoClose: 3000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onError: () => {
            toast.error('Failed to unregister member.', {
                autoClose: 3000,
                position: toast.POSITION.TOP_RIGHT,
            })
        },
        onFinish: () => {

        }
    })
}

const searchInput = ref('')
const searchResults = computed(() => {
    if (searchInput.value.length < 2) {
        return []
    }
    return props.learners.filter((learner: any) => {
        return learner.last_name.toLowerCase().includes(searchInput.value.toLowerCase()) ||
        learner.first_name.toLowerCase().includes(searchInput.value.toLowerCase())
    })
})

const club = computed(() => {
    return page.props.auth.user?.club_registers?.[0]?.club
})
</script>
