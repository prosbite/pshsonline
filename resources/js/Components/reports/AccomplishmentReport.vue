<template>
  <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 bg-gradient-to-r from-slate-950 via-indigo-950 to-violet-900 px-6 py-5 text-white">
      <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-200">
            Admin Report
          </p>
          <h1 class="mt-2 text-3xl font-extrabold">
            Accomplishment Report by Club
          </h1>
          <p class="mt-2 text-sm text-indigo-100/80">
            All accomplishment report entries from the database, grouped by club
          </p>
        </div>

        <button
          type="button"
          class="inline-flex items-center justify-center rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/20 transition hover:bg-white/20"
          @click="printPage"
        >
          Print
        </button>
      </div>
    </div>

    <div class="space-y-6 p-6">
      <section
        v-for="group in groupedReports"
        :key="group.club_name"
        class="overflow-hidden rounded-2xl border border-gray-200"
      >
        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
          <h2 class="text-lg font-bold text-gray-900">
            {{ group.club_name }}
          </h2>
          <p class="text-sm text-gray-500">
            {{ group.reports.length }} report{{ group.reports.length === 1 ? '' : 's' }}
          </p>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                  Quarter
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                  School Year
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                  Strength
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                  Weakness
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                  Gap
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                  Recommendation
                </th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
              <tr v-for="report in group.reports" :key="report.id" class="align-top hover:bg-gray-50">
                <td class="px-4 py-4 text-sm text-gray-700">
                  {{ report.quarter_name }}
                </td>
                <td class="px-4 py-4 text-sm text-gray-700">
                  {{ report.school_year_name }}
                </td>
                <td class="px-4 py-4 text-sm text-gray-700">
                  <ul v-if="report.strength.length" class="list-disc space-y-1 pl-5">
                    <li v-for="(item, index) in report.strength" :key="index">
                      {{ item }}
                    </li>
                  </ul>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-4 py-4 text-sm text-gray-700">
                  <ul v-if="report.weakness.length" class="list-disc space-y-1 pl-5">
                    <li v-for="(item, index) in report.weakness" :key="index">
                      {{ item }}
                    </li>
                  </ul>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-4 py-4 text-sm text-gray-700">
                  <ul v-if="report.gap.length" class="list-disc space-y-1 pl-5">
                    <li v-for="(item, index) in report.gap" :key="index">
                      {{ item }}
                    </li>
                  </ul>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-4 py-4 text-sm text-gray-700">
                  <ul v-if="report.recommendation.length" class="list-disc space-y-1 pl-5">
                    <li v-for="(item, index) in report.recommendation" :key="index">
                      {{ item }}
                    </li>
                  </ul>
                  <span v-else class="text-gray-400">-</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <div v-if="!groupedReports.length" class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-4 py-10 text-center text-sm text-gray-500">
        No accomplishment reports found.
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  reports: {
    type: Array,
    default: () => [],
  },
})

const normalizeList = (value) => {
  if (Array.isArray(value)) {
    return value.filter((item) => item !== null && item !== undefined && `${item}`.trim() !== '')
  }

  if (typeof value === 'string' && value.trim() !== '') {
    return [value]
  }

  return []
}

const quarterLabel = (quarter) => {
  const quarterNumber = Number(quarter)
  const suffixMap = ['st', 'nd', 'rd', 'th']
  const suffix = suffixMap[quarterNumber - 1] ?? 'th'

  return quarterNumber ? `${quarterNumber}${suffix} Quarter` : 'N/A'
}

const normalizedReports = computed(() => {
  return props.reports.map((report) => {
    const reportData = report.report ?? {}

    return {
      id: report.id,
      club_name: report.clubRegister?.club?.name ?? 'Unnamed Club',
      quarter_name: quarterLabel(report.quarter?.quarter),
      school_year_name: report.schoolYear?.school_year ?? 'N/A',
      strength: normalizeList(reportData.strengths ?? reportData.strength),
      weakness: normalizeList(reportData.weaknesses ?? reportData.weakness),
      gap: normalizeList(reportData.gaps ?? reportData.gap),
      recommendation: normalizeList(reportData.recommendations ?? reportData.recommendation),
    }
  })
})

const groupedReports = computed(() => {
  const groups = new Map()

  for (const report of normalizedReports.value) {
    const key = report.club_name

    if (!groups.has(key)) {
      groups.set(key, {
        club_name: key,
        reports: [],
      })
    }

    groups.get(key).reports.push(report)
  }

  return Array.from(groups.values())
})

const escapeHtml = (value) => {
  return `${value ?? ''}`
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
}

const listToHtml = (items) => {
  if (!items.length) {
    return '<span style="color:#9ca3af;">-</span>'
  }

  return `<ul style="margin:0;padding-left:18px;">${items.map((item) => `<li>${escapeHtml(item)}</li>`).join('')}</ul>`
}

const printPage = () => {
  const printWindow = window.open('', '_blank', 'width=1100,height=800')

  if (!printWindow) {
    return
  }

  const html = groupedReports.value
    .map((group) => {
      const rows = group.reports
        .map((report) => `
          <tr>
            <td>${escapeHtml(report.quarter_name)}</td>
            <td>${escapeHtml(report.school_year_name)}</td>
            <td>${listToHtml(report.strength)}</td>
            <td>${listToHtml(report.weakness)}</td>
            <td>${listToHtml(report.gap)}</td>
            <td>${listToHtml(report.recommendation)}</td>
          </tr>
        `)
        .join('')

      return `
        <section class="club-block">
          <div class="club-header">
            <div>
              <h2>${escapeHtml(group.club_name)}</h2>
              <p>${group.reports.length} report${group.reports.length === 1 ? '' : 's'}</p>
            </div>
          </div>

          <table>
            <thead>
              <tr>
                <th>Quarter</th>
                <th>School Year</th>
                <th>Strength</th>
                <th>Weakness</th>
                <th>Gap</th>
                <th>Recommendation</th>
              </tr>
            </thead>
            <tbody>
              ${rows}
            </tbody>
          </table>
        </section>
      `
    })
    .join('')

  printWindow.document.open()
  printWindow.document.write(`
    <!doctype html>
    <html>
      <head>
        <meta charset="utf-8" />
        <title>Accomplishment Report</title>
        <style>
          @page { size: landscape; margin: 0.5in; }
          body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            background: #fff;
          }
          .page {
            padding: 24px;
          }
          .title {
            margin: 0 0 12px;
            font-size: 22px;
            font-weight: 800;
          }
          .subtitle {
            margin: 0 0 24px;
            color: #4b5563;
            font-size: 13px;
          }
          .club-block {
            margin-bottom: 24px;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            overflow: hidden;
          }
          .club-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
          }
          .club-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
          }
          .club-header p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #6b7280;
          }
          table {
            width: 100%;
            border-collapse: collapse;
          }
          th, td {
            border-top: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            padding: 10px 12px;
            vertical-align: top;
            font-size: 12px;
            text-align: left;
          }
          th:last-child, td:last-child {
            border-right: none;
          }
          thead th {
            border-top: none;
            background: #f3f4f6;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #4b5563;
          }
          ul { margin: 0; }
        </style>
      </head>
      <body>
        <div class="page">
          <h1 class="title">Accomplishment Report by Club</h1>
          <p class="subtitle">All accomplishment report entries from the database, grouped by club</p>
          ${html || '<p>No accomplishment reports found.</p>'}
        </div>
      </body>
    </html>
  `)
  printWindow.document.close()
  printWindow.focus()
  printWindow.onload = () => {
    printWindow.print()
    printWindow.close()
  }
}
</script>
