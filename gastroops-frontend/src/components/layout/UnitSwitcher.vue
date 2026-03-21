<script setup lang="ts">
import { onMounted, ref } from "vue"
import { getOperationalUnits, type OperationalUnit } from "@/api/units"
import { useUnitStore } from "@/stores/unit"

const units = ref<OperationalUnit[]>([])
const unitStore = useUnitStore()

async function loadUnits() {
  units.value = await getOperationalUnits()
}

onMounted(async () => {
  unitStore.hydrate()
  await loadUnits()
})
</script>

<template>
  <div class="flex items-center gap-3">
    <label class="text-sm text-slate-500">Unit</label>

    <select
      :value="unitStore.activeUnitId ?? ''"
      class="rounded-lg border px-3 py-2 text-sm"
      @change="unitStore.setUnit(($event.target as HTMLSelectElement).value ? Number(($event.target as HTMLSelectElement).value) : null)"
    >
      <option value="">All units</option>

      <option
        v-for="unit in units"
        :key="unit.id"
        :value="unit.id"
      >
        {{ unit.name }}
      </option>
    </select>
  </div>
</template>