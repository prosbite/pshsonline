<template>
    <div class="w-full bg-white rounded-lg p-6">
      <!-- Calendar -->
       <h1 class="text-2xl font-semibold mb-4">Calendar of Activities</h1>
      <FullCalendar :options="calendarOptions" />
    </div>
  </template>

<script setup>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
    events: Array,
})
// 👇 Reactive events array
const events = ref([])

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  selectable: true,
  height: '600px',
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
