<script setup lang="ts">
import { computed, reactive, ref } from "vue"
import { createLicense } from "@/api/licenses"
import { useUnitStore } from "@/stores/unit"

const emit = defineEmits(["created"])

const unitStore = useUnitStore()
const loading = ref(false)
const error = ref("")
const hasActiveUnit = computed(() => unitStore.activeUnitId !== null)

const form = reactive({
  name: "",
  authority: "",
  issue_date: "",
  expiry_date: "",
  renewal_cost: 0,
  status: "active",
})

async function submit() {
  if (!hasActiveUnit.value) {
    error.value = "Please select an operational unit first."
    return
  }

  loading.value = true
  error.value = ""

  try {
    await createLicense(form)

    form.name = ""
    form.authority = ""
    form.issue_date = ""
    form.expiry_date = ""
    form.renewal_cost = 0
    form.status = "active"

    emit("created")
  } catch (err: any) {
    console.error(err)
    error.value = err?.response?.data?.message || "Could not create license."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Add License</h2>

    <div
      v-if="!hasActiveUnit"
      class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
    >
      Select an operational unit in the topbar before creating licenses.
    </div>

    <form
      class="grid gap-4 md:grid-cols-2 xl:grid-cols-3"
      @submit.prevent="submit"
>
      <input v-model="form.name" placeholder="License name" class="rounded-lg border px-3 py-2" />
      <input v-model="form.authority" placeholder="Authority" class="rounded-lg border px-3 py-2" />
      <input v-model="form.issue_date" type="date" class="rounded-lg border px-3 py-2" />
      <input v-model="form.expiry_date" type="date" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.renewal_cost" type="number" step="0.01" placeholder="Renewal cost" class="rounded-lg border px-3 py-2" />

      <select v-model="form.status" class="rounded-lg border px-3 py-2">
        <option value="active">active</option>
        <option value="pending">pending</option>
        <option value="expired">expired</option>
      </select>

      <button
        type="submit"
        class="md:col-span-2 rounded-lg bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
        :disabled="loading || !hasActiveUnit"
      >
        {{ loading ? "Saving..." : "Add License" }}
      </button>
    </form>

    <p v-if="error" class="mt-2 text-red-600">
      {{ error }}
    </p>
  </div>
</template>