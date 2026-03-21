<script setup lang="ts">
import { onMounted, ref } from "vue"
import UnitCreateForm from "@/modules/units/components/UnitCreateForm.vue"
import UnitEditForm from "@/modules/units/components/UnitEditForm.vue"
import UnitsTable from "@/modules/units/components/UnitsTable.vue"
import { getOperationalUnits, type OperationalUnit } from "@/api/units"

const units = ref<OperationalUnit[]>([])
const loading = ref(false)
const error = ref("")
const editingUnit = ref<OperationalUnit | null>(null)

async function loadUnits() {
  loading.value = true
  error.value = ""

  try {
    const data = await getOperationalUnits()
    units.value = data
  } catch (err: any) {
    console.error("Units API error:", err)
    error.value =
      err?.response?.data?.message ||
      err?.message ||
      "Could not load operational units."
  } finally {
    loading.value = false
  }
}

function handleEdit(unit: OperationalUnit) {
  editingUnit.value = unit
}

function closeEdit() {
  editingUnit.value = null
}

async function handleUpdated() {
  await loadUnits()
  closeEdit()
}

onMounted(loadUnits)
</script>

<template>
  <section class="space-y-6">
    <div>
      <h1 class="text-3xl font-bold text-slate-900">Operational Units</h1>
      <p class="mt-2 text-slate-600">
        Manage restaurants, cafés, bakeries, foodtrucks and other units.
      </p>
    </div>

    <UnitCreateForm @created="loadUnits" />

    <UnitEditForm
      v-if="editingUnit"
      :unit="editingUnit"
      @close="closeEdit"
      @updated="handleUpdated"
    />

    <div
      v-if="loading"
      class="rounded-2xl bg-white p-6 shadow-sm"
    >
      Loading operational units...
    </div>

    <div
      v-else-if="error"
      class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
    >
      {{ error }}
    </div>

    <UnitsTable
      v-else
      :units="units"
      @deleted="loadUnits"
      @edit="handleEdit"
    />
  </section>
</template>