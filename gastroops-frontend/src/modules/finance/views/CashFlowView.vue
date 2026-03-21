<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import { getCashFlowEntries, type CashFlowEntry } from "@/api/cashflow"
import { useUnitStore } from "@/stores/unit"
import CashFlowCreateForm from "@/modules/finance/components/CashFlowCreateForm.vue"
import CashFlowTable from "@/modules/finance/components/CashFlowTable.vue"

const unitStore = useUnitStore()
const entries = ref<CashFlowEntry[]>([])
const loading = ref(false)
const error = ref("")

async function load() {
  if (unitStore.activeUnitId === null) {
    entries.value = []
    return
  }

  loading.value = true
  error.value = ""

  try {
    entries.value = await getCashFlowEntries()
  } catch (err: any) {
    console.error("Cashflow API error:", err)
    error.value = "Could not load cashflow entries."
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
      <h1 class="text-3xl font-bold">Cashflow</h1>
      <p class="mt-2 text-slate-600">
        Manage inflows and outflows by operational unit.
      </p>
    </div>

    <div
      v-if="unitStore.activeUnitId === null"
      class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-700"
    >
      Select an operational unit in the topbar to manage cashflow.
    </div>

    <template v-else>
      <CashFlowCreateForm @created="load" />

      <div
        v-if="loading"
        class="rounded-2xl bg-white p-6 shadow-sm"
      >
        Loading cashflow...
      </div>

      <div
        v-else-if="error"
        class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
      >
        {{ error }}
      </div>

      <CashFlowTable
        v-else
        :entries="entries"
        @reload="load"
      />
    </template>
  </section>
</template>