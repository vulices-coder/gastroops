<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import { getOperationalUnits, type OperationalUnit } from "@/api/units"
import { useUnitStore } from "@/stores/unit"

const unitStore = useUnitStore()
const units = ref<OperationalUnit[]>([])

onMounted(async () => {
  unitStore.hydrate()
  units.value = await getOperationalUnits()
})

watch(
  () => unitStore.activeUnitId,
  (value) => {
    console.log("ACTIVE UNIT ID:", value)
    unitStore.persist()
  }
)
</script>

<template>
  <div class="flex items-center gap-2">
    <label for="unit-switcher" class="text-sm text-slate-500">Unit</label>

    <select
      id="unit-switcher"
      :value="unitStore.activeUnitId === null ? 'all' : String(unitStore.activeUnitId)"
      class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
      @change="
        unitStore.setActiveUnitId(
          ($event.target as HTMLSelectElement).value === 'all'
            ? null
            : Number(($event.target as HTMLSelectElement).value)
        )
      "
    >
      <option value="all">All units</option>

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