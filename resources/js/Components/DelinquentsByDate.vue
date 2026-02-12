<template>
  <div class="collapsible">
    <!-- Header -->
    <button class="header" @click="toggle">
      <span class="date">{{ fullDate(date) }}</span>
      <span class="icon">
        {{ isOpen ? '▲' : '▼' }}
      </span>
    </button>

    <!-- Content -->
    <transition name="collapse">
      <div v-if="isOpen" class="content">
        <table class="attendance-table">
          <thead>
            <tr>
              <th>Learner Name</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="learner in learners" :key="learner.id">
              <td class="w-1/2">{{ learner.club_attendance_learner.learner.last_name }}, {{ learner.club_attendance_learner.learner.first_name }} {{ learner.club_attendance_learner.learner.middle_name }}</td>
              <td class="w-1/4">
                <span :class="['status', learner.club_attendance_learner.status]">
                  {{ learner.club_attendance_learner.status.toUpperCase().replace('_', ' ') }}
                </span>
              </td>
              <td class="w-1/4">
                <button class="edit-btn" @click="$emit('edit', learner)">
                  Edit
                </button>
              </td>
            </tr>

            <tr v-if="learners.length === 0">
              <td colspan="3" class="empty">
                No attendance records
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { fullDate } from '@/composables/utilities'
import { onMounted, ref } from 'vue'

const props = defineProps({
  date: {
    type: String,
    required: true
  },
  learners: {
    type: Array,
    required: true
  },
  isOpen: {
    type: Boolean,
    default: false
  }
})

const isOpen = ref(false)

const toggle = () => {
  isOpen.value = !isOpen.value
}
onMounted(() => {
  isOpen.value = props.isOpen
})
</script>

<style scoped>
.collapsible {
  border: 1px solid #ddd;
  border-radius: 6px;
  margin-bottom: 12px;
  overflow: hidden;
}

.header {
  width: 100%;
  background: #f7f7f7;
  border: none;
  padding: 12px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  font-weight: 600;
}

.content {
  padding: 12px 16px;
  background: #fff;
}

.attendance-table {
  width: 100%;
  border-collapse: collapse;
}

.attendance-table th,
.attendance-table td {
  padding: 8px;
  border-bottom: 1px solid #eee;
  text-align: left;
}

.status {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.85rem;
  text-transform: capitalize;
}

.status.excused_absence {
  background: #e6f7ec;
  color: #2e7d32;
}

.status.unexcused_absence {
  background: #fdecea;
  color: #c62828;
}

.status.tardy {
  background: #fff4e5;
  color: #ef6c00;
}

.status.cutting_classes {
  background: #eee;
  color: #555;
}

.edit-btn {
  padding: 4px 8px;
  font-size: 0.85rem;
  cursor: pointer;
}

.empty {
  text-align: center;
  color: #999;
}

/* Collapse animation */
.collapse-enter-active,
.collapse-leave-active {
  transition: all 0.25s ease;
}

.collapse-enter-from,
.collapse-leave-to {
  opacity: 0;
  max-height: 0;
}
</style>
