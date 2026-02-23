<template>
  <div class="space-y-4">
    <!-- File input -->
    <input
      ref="fileInput"
      type="file"
      accept="image/*"
      required
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
import { ref, onBeforeUnmount, watch } from 'vue'

const props = defineProps({
    portrait: {
        type: Boolean,
        default: false,
    },
})
/* emit */
const emit = defineEmits(['update:files', 'update:portrait'])

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

watch(() => props.portrait, (value) => {
    if (value) {
        // Check if images are portrait (height > width)
        files.value.forEach((file, i) => {
            const img = new Image();
            img.onload = function() {
                removeImage(i);
            };
            img.src = URL.createObjectURL(file);
        });
        emit('update:portrait', false);
    }
})

onBeforeUnmount(() => {
  previews.value.forEach(url => URL.revokeObjectURL(url))
})
</script>
