<template>
    <div class="w-fullmx-auto bg-white p-10 rounded-2xl shadow-lg border">

    <!-- Header -->
    <div class="text-center border-b pb-4 mb-6">
      <h1 class="text-xl font-bold text-gray-800">Accomplishment Report</h1>
      <p class="text-gray-600">1<sup>st</sup> / 2<sup>nd</sup> / 3<sup>rd</sup> / 4<sup>th</sup> Quarter S.Y. 2024-2025</p>
    </div>

    <!-- Info -->
    <div class="grid grid-cols-2 gap-6 mb-6">
      <div>
        <label class="block font-semibold text-gray-700">ALP:</label>
        <input type="text" class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label class="block font-semibold text-gray-700">Adviser:</label>
        <input type="text" class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
    </div>

    <!-- Activities -->
    <div class="mb-8">
      <h2 class="font-semibold text-gray-800 mb-2">List of activities conducted:</h2>
      <ol class="list-decimal pl-6 space-y-2 text-gray-700">
        <li><input type="text" class="w-full border rounded-lg px-3 py-2"></li>
        <li><input type="text" class="w-full border rounded-lg px-3 py-2"></li>
        <li><input type="text" class="w-full border rounded-lg px-3 py-2"></li>
      </ol>
    </div>

    <!-- Separate Sections Instead of Table -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-gray-50 border rounded-lg p-4">
        <h3 class="font-semibold text-gray-800 mb-2">Strengths</h3>
        <ul class="space-y-2">
          <li><input type="text" class="w-full border rounded px-3 py-2"></li>
          <li><input type="text" class="w-full border rounded px-3 py-2"></li>
        </ul>
      </div>
      <div class="bg-gray-50 border rounded-lg p-4">
        <h3 class="font-semibold text-gray-800 mb-2">Weaknesses</h3>
        <ul class="space-y-2">
          <li><input type="text" class="w-full border rounded px-3 py-2"></li>
          <li><input type="text" class="w-full border rounded px-3 py-2"></li>
        </ul>
      </div>
      <div class="bg-gray-50 border rounded-lg p-4">
        <h3 class="font-semibold text-gray-800 mb-2">Gaps</h3>
        <ul class="space-y-2">
          <li><input type="text" class="w-full border rounded px-3 py-2"></li>
          <li><input type="text" class="w-full border rounded px-3 py-2"></li>
        </ul>
      </div>
      <div class="bg-gray-50 border rounded-lg p-4">
        <h3 class="font-semibold text-gray-800 mb-2">Recommendations</h3>
        <ul class="space-y-2">
          <li><input type="text" class="w-full border rounded px-3 py-2"></li>
          <li><input type="text" class="w-full border rounded px-3 py-2"></li>
        </ul>
      </div>
    </div>

    <!-- Footer -->
    <div class="mt-10">
      <p class="font-semibold">Prepared by:</p>
      <div class="mt-8">
        <p class="border-t border-gray-400 w-64 text-center pt-2">Name and signature of ALA Adviser</p>
      </div>
    </div>

    <p class="text-xs text-gray-500 mt-10 text-center">Form created by: Jocelyn H. Terania, PSHS-WVC</p>

  </div>
</template>

<script lang="ts" setup>
import MainLayout from '@/Layouts/MainLayout.vue';

defineOptions({
    layout: MainLayout
})
</script>

<style>

</style>
