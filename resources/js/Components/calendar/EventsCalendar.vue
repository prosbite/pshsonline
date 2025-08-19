<template>
    <div class="bg-white rounded-lg p-6">
      <!-- Calendar -->
      <h1 class="text-2xl font-semibold mb-4">Calendar of Activities</h1>
      <FullCalendar :options="calendarOptions" />

      <!-- Event Modal -->
      <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
          <h2 class="text-lg font-semibold mb-4">{{ editMode ? 'Edit' : 'Add' }} Event</h2>

          <form @submit.prevent="saveEvent">
            <!-- Title -->
            <div class="mb-3">
              <label class="block text-sm font-medium">Title</label>
              <input v-model="form.title" type="text" class="w-full border p-2 rounded" required />
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label class="block text-sm font-medium">Description</label>
              <textarea v-model="form.description" class="w-full border p-2 rounded"></textarea>
            </div>

            <!-- Location -->
            <div class="mb-3">
              <label class="block text-sm font-medium">Location</label>
              <input v-model="form.location" type="text" class="w-full border p-2 rounded" />
            </div>

            <!-- Date & Time -->
            <div class="mb-3">
              <label class="block text-sm font-medium">Start</label>
              <input v-model="form.start" type="datetime-local" class="w-full border p-2 rounded" required />
            </div>
            <div class="mb-3">
              <label class="block text-sm font-medium">End</label>
              <input v-model="form.end" type="datetime-local" class="w-full border p-2 rounded" />
            </div>

            <!-- All Day -->
            <div class="mb-3 flex items-center space-x-2">
              <input v-model="form.all_day" type="checkbox" />
              <label class="text-sm">All Day Event</label>
            </div>

            <!-- Color -->
            <div class="mb-3">
              <label class="block text-sm font-medium">Color</label>
              <input v-model="form.color" type="color" class="w-16 h-10 p-1 rounded cursor-pointer" />
            </div>

            <!-- Actions -->
             <div class="flex space-x-2 mt-4" :class="editMode ? 'justify-between' : 'justify-end'">
                <button v-if="editMode" @click.prevent="deleteEvent" type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="closeModal" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
                    <button v-if="!editMode" type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Save</button>
                    <button v-else @click.prevent="updateEvent" class="px-3 py-1 bg-blue-600 text-white rounded">Update</button>
                </div>
             </div>
          </form>
        </div>
      </div>
        <div
            v-if="hoverEvent"
            class="absolute bg-white border shadow-lg p-4 rounded text-sm z-50 min-w-[200px]"
            :style="{ top: `${hoverPos.y}px`, left: `${hoverPos.x}px` }"
            >
            <div class="font-semibold mb-[4px]">{{ hoverEvent.title }}</div>
            <div class="flex flex-col">
                <div class="text-[10px] text-blue-600 flex gap-1 items-center leading-tight">
                    <svg class="w-3 h-3 text-white-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[10px]">{{ shortDate(hoverEvent.startStr) }} - {{ shortDate(hoverEvent.endStr) }}</span>
                    <span class="text-[10px]">{{ justTime(hoverEvent.startStr) }} - {{ justTime(hoverEvent.endStr) }}</span>
                </div>
                <div v-if="hoverEvent.extendedProps.location" class="text-[10px] text-blue-600 flex gap-1 items-center mb-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="14" height="14"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        role="img"
                        aria-label="Location"
                        >
                        <title>Location</title>

                        <!-- Pin outline -->
                        <path d="M12 21s-6.5-5.2-8.2-8.1C2.8 10.6 5.4 6 12 6s9.2 4.6 8.2 6.9C18.5 15.8 12 21 12 21z"/>

                        <!-- Inner circle -->
                        <circle cx="12" cy="10" r="2.5" />
                    </svg>
                    <span class="text-[10px]">{{ hoverEvent.extendedProps.location }}</span>
                </div>
                <div class="text-xs text-gray-500">{{ hoverEvent.extendedProps.description }}</div>
            </div>
        </div>
    </div>
  </template>

<script setup>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { useForm } from '@inertiajs/vue3'
import { ref, onMounted, watch } from 'vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { shortDate, justTime } from '@/composables/utilities'

const props = defineProps({
    events: Array,
})
const hoverEvent = ref(null)
const hoverPos = ref({ x: 0, y: 0 })
const editMode = ref(false)
const showModal = ref(false)
const form = useForm({
  title: '',
  description: '',
  location: '',
  start: '',
  end: '',
  all_day: false,
  color: '#3788d8'
})

// Example school years
const schoolYears = ref([
  { id: 1, name: '2024-2025' },
  { id: 2, name: '2025-2026' }
])

// 👇 Reactive events array
const events = ref([])

// format helper
const formatDateTimeLocal = (dateStr) => {
  if (dateStr.length === 10) {
    return `${dateStr}T00:00`
  }
  return dateStr.slice(0, 16)
}

const deleteEvent = () => {
    form.delete(route('admin.event.delete', form.id), {
        onSuccess: () => {
            form.reset()
            editMode.value = false
            toast.success('Event deleted successfully', {
                autoClose: 1000,
            })
        },
        onError: () => {
            toast.error('Failed to delete event', {
                autoClose: 1000,
            })
        }
    })
    closeModal()
}

const updateEvent = () => {
    form.put(route('admin.event.update', form.id), {
        onSuccess: () => {
            form.reset()
            editMode.value = false
            toast.success('Event updated successfully', {
                autoClose: 1000,
            })
        },
        onError: () => {
            toast.error('Failed to update event', {
                autoClose: 1000,
            })
        }
    })
    closeModal()
}

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  selectable: true,
  height: '600px',
  select: (info) => {
    form.reset()
    editMode.value = false
    form.start = formatDateTimeLocal(info.startStr)
    form.end = formatDateTimeLocal(info.endStr)
    showModal.value = true
  },
  eventClick: (info) => {
    const event = info.event
    editMode.value = true
    // populate form with clicked event
    form.id = event.id
    form.title = event.title
    form.description = event.extendedProps.description || ''
    form.school_year_id = event.extendedProps.school_year_id || ''
    form.location = event.extendedProps.location || ''
    form.start = formatDateForInput(event.startStr)
    form.end = formatDateForInput(event.endStr)
    form.all_day = event.allDay
    form.color = event.backgroundColor
    editMode.value = true
    showModal.value = true
  },
  eventMouseEnter(info) {
    hoverEvent.value = info.event
    hoverPos.value = { x: info.jsEvent.pageX + 10, y: info.jsEvent.pageY + 10 }
  },
  eventMouseLeave() {
    hoverEvent.value = null
  },

  events: events.value // 👈 bind the reactive array
})

const closeModal = () => {
  showModal.value = false
}

function formatDateForInput(date) {
  if (!date) return ''
  const d = new Date(date)
  const year = d.getFullYear()
  const month = String(d.getMonth() + 1).padStart(2, '0')
  const day = String(d.getDate()).padStart(2, '0')
  const hours = String(d.getHours()).padStart(2, '0')
  const minutes = String(d.getMinutes()).padStart(2, '0')
  return `${year}-${month}-${day}T${hours}:${minutes}`
}

const saveEvent = () => {
 form.post(route('admin.event.store'), {
    onSuccess: () => {
      form.reset()
      toast.success('Event created successfully', {
        autoClose: 1000,
      })
    },
    onError: () => {
      toast.error('Failed to create event', {
        autoClose: 1000,
      })
    }
  })
  closeModal()
}

const loadEvents = (evs) => {
    evs.forEach(event => {
        events.value.push({
            id: event.id,
            title: event.title,
            start: event.start,
            end: event.end,
            allDay: event.all_day,
            backgroundColor: event.color,
            extendedProps: {
                description: event.description,
                school_year_id: event.school_year_id,
                location: event.location
            }
        })
    })
}

watch(() => props.events, (newEvents) => {
  // wipe out old data
  events.value.splice(0, events.value.length)
  // push again
  loadEvents(newEvents)
})

onMounted(() => {
    loadEvents(props.events)
})
</script>

<style>
.fc-today-button {
  text-transform: uppercase !important;
}
.fc-toolbar-title {
    font-size: 1.3rem !important;
    font-weight: bold !important;
    color: rgb(32, 96, 193) !important;
}
</style>
