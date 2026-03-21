<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import { getUtilityBills, type UtilityBill } from "@/api/utilities"
import { useUnitStore } from "@/stores/unit"
import UtilityBillCreateForm from "@/modules/fixed-costs/components/UtilityBillCreateForm.vue"
import UtilityBillsTable from "@/modules/fixed-costs/components/UtilityBillsTable.vue"

const unitStore = useUnitStore()
const bills = ref<UtilityBill[]>([])
const loading = ref(false)
const error = ref("")

async function load() {
  if (unitStore.activeUnitId === null) {
    bills.value = []
    return
  }

  loading.value = true
  error.value = ""

  try {
    bills.value = await getUtilityBills()
  } catch (err: any) {
    console.error("Utilities API error:", err)
    error.value = "Could not load utility bills."
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  unitStore.hydrate()
  await load()
})

watch(
  () => unitStore.activeUnitId,
  async () => {
    await load()
  }
)
</script>

<template>
  <section class="space-y-6">
    <div>
      <h1 class="text-3xl font-bold">Utilities</h1>
      <p class="mt-2 text-slate-600">
        Manage electricity, gas, water and internet bills by operational unit.
      </p>
    </div>

    <div
      v-if="unitStore.activeUnitId === null"
      class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-700"
    >
      Select an operational unit in the topbar to manage utilities.
    </div>

    <template v-else>
      <UtilityBillCreateForm @created="load" />

      <div
        v-if="loading"
        class="rounded-2xl bg-white p-6 shadow-sm"
      >
        Loading utility bills...
      </div>

      <div
        v-else-if="error"
        class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
      >
        {{ error }}
      </div>

      <UtilityBillsTable
        v-else
        :bills="bills"
        @reload="load"
      />
    </template>
  </section>
</template>