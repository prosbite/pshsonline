<template>
    <div class="flex min-h-screen font-sans relative">
      <Sidebar v-if="user.club_registers?.length > 0 || user.role === 'admin'" class="ml-[-16rem] lg:ml-0" />
      <div v-if="user.club_registers?.length > 0 || user.role === 'admin'" class="flex flex-col flex-1 flex-grow overflow-y-auto bg-gray-100">
        <TopNav class="lg:hidden" />
        <div class="md:px-8 px-2 py-8">
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
  import { onMounted, computed } from 'vue'
  import TopNav from '@/Components/TopNav.vue'

  const page = usePage()
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
                        autoClose: 5000,
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
