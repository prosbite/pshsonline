<template>
    <div class="flex min-h-screen font-sans">
      <Sidebar v-if="user.club_registers?.length > 0 || user.role === 'admin'" />
      <div v-if="user.club_registers?.length > 0 || user.role === 'admin'" class="flex-grow p-8 overflow-y-auto bg-gray-100">
        <slot />
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
