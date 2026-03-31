<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import FixedCostCreateForm from "@/modules/fixed-costs/components/FixedCostCreateForm.vue"
import FixedCostsTable from "@/modules/fixed-costs/components/FixedCostsTable.vue"
import { getFixedCosts, type FixedCost } from "@/api/fixedCosts"
import { useUnitStore } from "@/stores/unit"

const unitStore = useUnitStore()

const costs = ref<FixedCost[]>([])
const loading = ref(false)
const error = ref("")

async function load() {
  loading.value = true
  error.value = ""

  try {
    costs.value = await getFixedCosts()
  } catch (err: any) {
    console.error("FixedCosts API error:", err)
    error.value = "Could not load fixed costs"
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
      <h1 class="text-3xl font-bold text-slate-900">Fixed Costs</h1>
      <p class="mt-2 text-slate-600">
        Manage recurring operational expenses.
      </p>
    </div>

    <FixedCostCreateForm @created="load" />

    <div
      v-if="loading"
      class="rounded-2xl bg-white p-6 shadow-sm"
    >
      Loading fixed costs...
    </div>

    <div
      v-else-if="error"
      class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
    >
      {{ error }}
    </div>

    <FixedCostsTable
      v-else
      :costs="costs"
      @reload="load"
    />
  </section>
</template>