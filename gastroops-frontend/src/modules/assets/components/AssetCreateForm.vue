<script setup lang="ts">
import { computed, reactive, ref } from "vue"
import { createAsset } from "@/api/assets"
import { useUnitStore } from "@/stores/unit"

const emit = defineEmits(["created"])

const unitStore = useUnitStore()
const loading = ref(false)
const error = ref("")
const hasActiveUnit = computed(() => unitStore.activeUnitId !== null)

const form = reactive({
  name: "",
  asset_type: "",
  purchase_price: 0,
  purchase_date: "",
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
    await createAsset(form)

    form.name = ""
    form.asset_type = ""
    form.purchase_price = 0
    form.purchase_date = ""
    form.active = true

    emit("created")
  } catch (err: any) {
    console.error(err)
    error.value = err?.response?.data?.message || "Could not create asset."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Add Asset</h2>

    <div
      v-if="!hasActiveUnit"
      class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
    >
      Select an operational unit in the topbar before creating assets.
    </div>

    <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit">
      <input v-model="form.name" placeholder="Asset name" class="rounded-lg border px-3 py-2" />
      <input v-model="form.asset_type" placeholder="Asset type" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.purchase_price" type="number" step="0.01" placeholder="Purchase price" class="rounded-lg border px-3 py-2" />
      <input v-model="form.purchase_date" type="date" class="rounded-lg border px-3 py-2" />

      <label class="md:col-span-2 flex items-center gap-3 text-sm text-slate-700">
        <input v-model="form.active" type="checkbox" class="h-4 w-4" />
        Active
      </label>

      <button
        class="md:col-span-2 rounded-lg bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
        :disabled="loading || !hasActiveUnit"
      >
        {{ loading ? "Saving..." : "Add Asset" }}
      </button>
    </form>

    <p v-if="error" class="mt-2 text-red-600">
      {{ error }}
    </p>
  </div>
</template>