<template>
    <transition name="modal-fade">
      <div v-if="isVisible" class="fixed inset-0 z-50 min-h-[100vh] flex justify-center items-center p-4 bg-black bg-opacity-50" @click.self="closeModal">
        <div
          :class="modalClasses"
          class="bg-white h-fit dark:bg-gray-800 rounded-xl shadow-2xl transform transition-all duration-300 ease-out flex flex-col overflow-hidden"
          role="dialog"
          aria-modal="true"
          aria-labelledby="modal-header"
          aria-describedby="modal-body"
        >
          <!-- Header Slot -->
          <div v-if="$slots.header" class="px-6 py-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <slot name="header"></slot>
            <button
              @click="closeModal"
              class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200 text-4xl leading-none focus:outline-none"
              aria-label="Close modal"
            >
              &times;
            </button>
          </div>
          <div v-else class="flex justify-end px-6 py-4">
            <button
              @click="closeModal"
              class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200 text-2xl leading-none focus:outline-none"
              aria-label="Close modal"
            >
              &times;
            </button>
          </div>

          <!-- Body Slot -->
          <div v-if="$slots.body" class="p-6 overflow-y-auto flex-grow">
            <slot name="body"></slot>
          </div>

          <!-- Optional Footer Slot (you can uncomment and use this if needed) -->
          <!--
          <div v-if="$slots.footer" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end items-center">
            <slot name="footer"></slot>
          </div>
          -->
        </div>
      </div>
    </transition>
  </template>

  <script>
  export default {
    name: 'ModalComponent',
    props: {
      isVisible: {
        type: Boolean,
        default: false
      },
      position: {
        type: String,
        default: 'center', // 'center', 'top', 'bottom', 'left', 'right'
        validator: (value) => ['center', 'top', 'bottom', 'left', 'right'].includes(value)
      },
      size: {
        type: String,
        default: 'md', // 'sm', 'md', 'lg', 'xl', '2xl'
        validator: (value) => ['sm', 'md', 'lg', 'xl', '2xl','3xl','4xl','5xl','6xl','7xl'].includes(value)
      }
    },
    emits: ['close'],
    computed: {
      modalClasses() {
        switch (this.size) {
          case 'sm':
            return 'max-w-sm w-full h-auto max-h-[90vh]';
          case 'md':
            return 'max-w-md w-full h-auto max-h-[90vh]';
          case 'lg':
            return 'max-w-lg w-full h-auto max-h-[90vh]';
          case 'xl':
            return 'max-w-xl w-full h-auto max-h-[90vh]';
          case '2xl':
            return 'max-w-2xl w-full h-auto max-h-[90vh]';
          case '3xl':
            return 'max-w-3xl w-full h-auto max-h-[90vh]';
          case '4xl':
            return 'max-w-4xl w-full h-auto max-h-[90vh]';
          case '5xl':
            return 'max-w-5xl w-full h-auto max-h-[90vh]';
          case '6xl':
            return 'max-w-6xl w-full h-auto max-h-[90vh]';
          case '7xl':
            return 'max-w-7xl w-full h-auto max-h-[90vh]';
          default:
            return 'max-w-md w-full h-auto max-h-[90vh]';
        }
      }
    },
    methods: {
      closeModal() {
        this.$emit('close');
      },
      handleKeydown(event) {
        if (this.isVisible && event.key === 'Escape') {
          this.closeModal();
        }
      }
    },
    watch: {
      isVisible(newVal) {
        if (newVal) {
          document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
          document.addEventListener('keydown', this.handleKeydown);
        } else {
          document.body.style.overflow = ''; // Restore scrolling
          document.removeEventListener('keydown', this.handleKeydown);
        }
      }
    },
    beforeUnmount() {
      document.removeEventListener('keydown', this.handleKeydown);
      document.body.style.overflow = ''; // Ensure scrolling is restored if component is unmounted while modal is open
    }
  };
  </script>

  <style scoped>
  /* Basic fade transition for the modal */
  .modal-fade-enter-active,
  .modal-fade-leave-active {
    transition: opacity 0.3s ease;
  }

  .modal-fade-enter-from,
  .modal-fade-leave-to {
    opacity: 0;
  }

  /* Scale effect for the modal content */
  .modal-fade-enter-active .transform,
  .modal-fade-leave-active .transform {
    transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55), opacity 0.3s ease;
  }

  .modal-fade-enter-from .transform,
  .modal-fade-leave-to .transform {
    transform: scale(0.9);
    opacity: 0;
  }
  </style>
