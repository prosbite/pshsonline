<template>
    <div class="no-print flex min-h-screen font-sans relative">
      <Sidebar v-if="user.club_registers?.length > 0 || user.role === 'admin' || user.role === 'supervisor' || user.role === 'club manager'" class="hidden lg:block" />
        <Sidebar v-if="user.club_registers?.length > 0 || user.role === 'admin' || user.role === 'supervisor' || user.role === 'club manager'" class="lg:hidden" :class="{ 'hidden': collapse, 'block': !collapse }" />
      <div v-if="user.club_registers?.length > 0 || user.role === 'admin' || user.role === 'supervisor' || user.role === 'club manager'" class="flex flex-col flex-1 flex-grow overflow-y-auto bg-gray-100">
        <!-- <TopNav class="lg:hidden" /> -->
        <div class="flex flex-col md:p-8 w-full">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="m-4 lg:hidden"
                @click="collapse = !collapse"
                >
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
            <slot />
        </div>
      </div>
      <div v-else class="flex-grow p-8 overflow-y-auto bg-gray-100">
        <p class="text-center text-gray-600">You are not enrolled in any club yet.</p>
      </div>
    </div>
  </template>

  <script setup>
  import Sidebar from '@/Components/Sidebar.vue'
  import { toast } from 'vue3-toastify'
  import 'vue3-toastify/dist/index.css'
  import { usePage } from '@inertiajs/vue3'
  import { onMounted, computed, ref } from 'vue'
  import TopNav from '@/Components/TopNav.vue'

  const page = usePage()
  const collapse = ref(true)
  const props = defineProps({
    success: {
        type: String,
        default: null,
    },
    error: {
        type: String,
        default: null,
    },
    watch: {
        success: {
            handler(value) {
                if (value) {
                    toast.success(value, {
                        autoClose: 2000,
                        position: toast.POSITION.TOP_RIGHT,
                    })
                }
            },
            immediate: true,
        },
        error: {
            handler(value) {
                if (value) {
                    toast.error(value);
                }
            },
            immediate: true,
        },
    },
  })
  const user = computed(() => page.props.auth.user)
  onMounted(() => {
    // console.log(user.value)
  })
  </script>

  <style>
  @media print {
    .no-print {
      display: none;
    }
  }
  </style>
