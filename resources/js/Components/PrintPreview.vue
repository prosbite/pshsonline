<template>
    <!-- Trigger Button (optional slot) -->
    <button @click="handlePrint" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
      <slot name="trigger">Print</slot>
    </button>

    <!-- Teleport printable content to body -->
    <Teleport to="body">
      <div v-if="visible" ref="printArea" class="print-area">
        <slot />
      </div>
    </Teleport>
  </template>

  <script setup>
  import { ref, nextTick } from 'vue'

  const visible = ref(false)
  const printArea = ref(null)

  const handlePrint = async () => {
    visible.value = true
    await nextTick()

    const content = printArea.value.innerHTML
    const printWindow = window.open('', '', 'width=800,height=600')

    printWindow.document.write(`
      <html>
        <head>
          <title>Print</title>
          <style>
            body { font-family: sans-serif; padding: 2rem; color: #000; }
            @media print {
              body { -webkit-print-color-adjust: exact; color-adjust: exact; }
            }
          </style>
        </head>
        <body>${content}</body>
      </html>
    `)

    printWindow.document.close()
    printWindow.focus()
    printWindow.print()
    printWindow.close()

    visible.value = false
  }
  </script>

  <style>
  .print-area {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }
  </style>
