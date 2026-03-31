<script setup lang="ts">
import type { OperationalUnit } from "@/api/units"

defineProps<{
  units: OperationalUnit[]
}>()
</script>

<template>
  <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
    <div class="border-b px-6 py-4">
      <h3 class="text-lg font-semibold text-slate-900">
        Operational Units Overview
      </h3>
      <p class="mt-1 text-sm text-slate-500">
        Active and inactive operational units across the business.
      </p>
    </div>

    <table class="min-w-full text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-6 py-3 text-sm font-semibold text-slate-600">Name</th>
          <th class="px-6 py-3 text-sm font-semibold text-slate-600">Type</th>
          <th class="px-6 py-3 text-sm font-semibold text-slate-600">City</th>
          <th class="px-6 py-3 text-sm font-semibold text-slate-600">Status</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="unit in units"
          :key="unit.id"
          class="border-t"
        >
          <td class="px-6 py-4">{{ unit.name }}</td>
          <td class="px-6 py-4 capitalize">{{ unit.unit_type }}</td>
          <td class="px-6 py-4">{{ unit.city || "-" }}</td>
          <td class="px-6 py-4">
            <span
              class="rounded-full px-3 py-1 text-xs font-medium"
              :class="unit.active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600'"
            >
              {{ unit.active ? "Active" : "Inactive" }}
            </span>
          </td>
        </tr>

        <tr v-if="units.length === 0">
          <td colspan="4" class="px-6 py-6 text-center text-slate-500">
            No operational units found.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>