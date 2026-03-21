<script setup lang="ts">
import { computed, reactive, ref } from "vue"
import { createCashFlowEntry } from "@/api/cashflow"
import { useUnitStore } from "@/stores/unit"

const emit = defineEmits(["created"])

const unitStore = useUnitStore()
const loading = ref(false)
const error = ref("")
const hasActiveUnit = computed(() => unitStore.activeUnitId !== null)

const form = reactive({
  entry_type: "out",
  category: "",
  amount: 0,
  entry_date: "",
  notes: "",
})

async function submit() {
  if (!hasActiveUnit.value) {
    error.value = "Please select an operational unit first."
    return
  }

  loading.value = true
  error.value = ""

  try {
    await createCashFlowEntry(form)

    form.entry_type = "out"
    form.category = ""
    form.amount = 0
    form.entry_date = ""
    form.notes = ""

    emit("created")
  } catch (err: any) {
    console.error(err)
    error.value = err?.response?.data?.message || "Could not create cashflow entry."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Add Cashflow Entry</h2>

    <div
      v-if="!hasActiveUnit"
      class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
    >
      Select an operational unit in the topbar before creating cashflow entries.
    </div>

    <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit">
      <select v-model="form.entry_type" class="rounded-lg border px-3 py-2">
        <option value="in">in</option>
        <option value="out">out</option>
      </select>

      <input v-model="form.category" placeholder="Category" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.amount" type="number" step="0.01" placeholder="Amount" class="rounded-lg border px-3 py-2" />
      <input v-model="form.entry_date" type="date" class="rounded-lg border px-3 py-2" />

      <textarea
        v-model="form.notes"
        placeholder="Notes"
        class="md:col-span-2 rounded-lg border px-3 py-2"
      ></textarea>

      <button
        class="md:col-span-2 rounded-lg bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
        :disabled="loading || !hasActiveUnit"
      >
        {{ loading ? "Saving..." : "Add Entry" }}
      </button>
    </form>

    <p v-if="error" class="mt-2 text-red-600">
      {{ error }}
    </p>
  </div>
</template>