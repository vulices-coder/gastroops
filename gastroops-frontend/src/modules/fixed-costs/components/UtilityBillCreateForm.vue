<script setup lang="ts">
import { computed, reactive, ref } from "vue"
import { createUtilityBill } from "@/api/utilities"
import { useUnitStore } from "@/stores/unit"

const emit = defineEmits(["created"])

const unitStore = useUnitStore()
const loading = ref(false)
const error = ref("")
const hasActiveUnit = computed(() => unitStore.activeUnitId !== null)

const form = reactive({
  utility_type: "",
  billing_period_start: "",
  billing_period_end: "",
  amount: 0,
  usage_value: 0,
  provider_name: "",
})

async function submit() {
  if (!hasActiveUnit.value) {
    error.value = "Please select an operational unit first."
    return
  }

  loading.value = true
  error.value = ""

  try {
    await createUtilityBill(form)

    form.utility_type = ""
    form.billing_period_start = ""
    form.billing_period_end = ""
    form.amount = 0
    form.usage_value = 0
    form.provider_name = ""

    emit("created")
  } catch (err: any) {
    console.error(err)
    error.value = err?.response?.data?.message || "Could not create utility bill."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Add Utility Bill</h2>

    <div
      v-if="!hasActiveUnit"
      class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
    >
      Select an operational unit in the topbar before creating utility bills.
    </div>

    <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit">
      <input v-model="form.utility_type" placeholder="Utility type" class="rounded-lg border px-3 py-2" />
      <input v-model="form.provider_name" placeholder="Provider name" class="rounded-lg border px-3 py-2" />
      <input v-model="form.billing_period_start" type="date" class="rounded-lg border px-3 py-2" />
      <input v-model="form.billing_period_end" type="date" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.amount" type="number" step="0.01" placeholder="Amount" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.usage_value" type="number" step="0.01" placeholder="Usage value" class="rounded-lg border px-3 py-2" />

      <button
        class="md:col-span-2 rounded-lg bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
        :disabled="loading || !hasActiveUnit"
      >
        {{ loading ? "Saving..." : "Add Utility Bill" }}
      </button>
    </form>

    <p v-if="error" class="mt-2 text-red-600">
      {{ error }}
    </p>
  </div>
</template>