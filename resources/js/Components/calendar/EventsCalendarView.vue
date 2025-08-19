<template>
    <div class="w-full bg-white rounded-lg p-6">
      <!-- Calendar -->
       <h1 class="text-2xl font-semibold mb-4">Calendar of Activities</h1>
      <FullCalendar :options="calendarOptions" />

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
                    <span class="text-[10px]">{{ justTime(hoverEvent.startStr) }}</span>
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
import { ref, onMounted, watch } from 'vue'
import { shortDate, justTime } from '@/composables/utilities'

const props = defineProps({
    events: Array,
})
const hoverEvent = ref(null)
const hoverPos = ref({ x: 0, y: 0 })
// 👇 Reactive events array
const events = ref([])

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  selectable: true,
  height: '600px',
  eventMouseEnter(info) {
    hoverEvent.value = info.event
    hoverPos.value = { x: info.jsEvent.pageX + 10, y: info.jsEvent.pageY + 10 }
  },
  eventMouseLeave() {
    hoverEvent.value = null
  },
  events: events.value // 👈 bind the reactive array
})

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
