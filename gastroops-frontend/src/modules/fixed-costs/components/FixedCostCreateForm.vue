<script setup lang="ts">
import { computed, reactive, ref } from "vue"
import { createFixedCost } from "@/api/fixedCosts"
import { useUnitStore } from "@/stores/unit"

const emit = defineEmits(["created"])

const unitStore = useUnitStore()
const loading = ref(false)
const error = ref("")

const hasActiveUnit = computed(() => unitStore.activeUnitId !== null)

const form = reactive({
  name: "",
  amount: 0,
  currency: "EUR",
  category: "",
  period: "monthly",
  active: true,
})

async function submit() {
  if (!hasActiveUnit.value) {
    error.value = "Please select an operational unit first."
    return
  }

  loading.value = true
  error.value = ""

  try {
    await createFixedCost(form)

    form.name = ""
    form.amount = 0
    form.category = ""

    emit("created")
  } catch (err: any) {
    console.error(err)
    error.value =
      err?.response?.data?.message ||
      "Could not create fixed cost"
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">
      Add Fixed Cost
    </h2>

    <div
      v-if="!hasActiveUnit"
      class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
    >
      Select an operational unit in the topbar before creating fixed costs.
    </div>

    <form class="grid grid-cols-4 gap-4" @submit.prevent="submit">
      <input
        v-model="form.name"
        placeholder="Name"
        class="border rounded-lg px-3 py-2"
      />

      <input
        v-model.number="form.amount"
        type="number"
        step="0.01"
        placeholder="Amount"
        class="border rounded-lg px-3 py-2"
      />

      <input
        v-model="form.category"
        placeholder="Category"
        class="border rounded-lg px-3 py-2"
      />

      <select
        v-model="form.period"
        class="border rounded-lg px-3 py-2"
      >
        <option value="monthly">Monthly</option>
        <option value="yearly">Yearly</option>
      </select>

      <button
        class="col-span-4 rounded-lg bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
        :disabled="loading || !hasActiveUnit"
      >
        {{ loading ? "Saving..." : "Add Cost" }}
      </button>
    </form>

    <p v-if="error" class="mt-2 text-red-600">
      {{ error }}
    </p>
  </div>
</template>