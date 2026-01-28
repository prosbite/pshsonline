<template>
  <div class="space-y-4">
    <!-- File input -->
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      @change="onFileChange"
    />

    <!-- Preview container -->
    <div
      v-if="previews.length"
      class="flex justify-start gap-3 flex-wrap"
    >
      <div
        v-for="(src, index) in previews"
        :key="src"
        class="relative"
      >
        <!-- Remove button -->
        <button
          type="button"
          @click="removeImage(index)"
          class="absolute -top-2 -right-2 z-10
                 h-6 w-6 rounded-full bg-black/70 text-white
                 flex items-center justify-center
                 hover:bg-black"
        >
          ×
        </button>

        <img
          :src="src"
          class="h-24 w-auto rounded shadow"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue'

/* emit */
const emit = defineEmits(['update:files'])

const fileInput = ref(null)
const files = ref([])
const previews = ref([])

function onFileChange(event) {
  const selected = Array.from(event.target.files)

  // Replace existing files
  files.value = selected

  rebuildPreviews()
  syncInput()
  emitFiles()
}

function removeImage(index) {
  URL.revokeObjectURL(previews.value[index])

  files.value.splice(index, 1)
  previews.value.splice(index, 1)

  syncInput()
  emitFiles()
}

function rebuildPreviews() {
  previews.value.forEach(url => URL.revokeObjectURL(url))
  previews.value = files.value.map(file =>
    URL.createObjectURL(file)
  )
}

function syncInput() {
  const dt = new DataTransfer()
  files.value.forEach(file => dt.items.add(file))
  fileInput.value.files = dt.files
}

function emitFiles() {
  emit('update:files', files.value)
}

onBeforeUnmount(() => {
  previews.value.forEach(url => URL.revokeObjectURL(url))
})
</script>
