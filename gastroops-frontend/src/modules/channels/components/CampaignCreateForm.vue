<script setup lang="ts">
import { computed, reactive, ref } from "vue"
import { createCampaign } from "@/api/campaigns"
import { useUnitStore } from "@/stores/unit"

const emit = defineEmits(["created"])

const unitStore = useUnitStore()
const loading = ref(false)
const error = ref("")
const hasActiveUnit = computed(() => unitStore.activeUnitId !== null)

const form = reactive({
  name: "",
  platform: "",
  budget: 0,
  starts_on: "",
  ends_on: "",
  objective: "",
  status: "draft",
})

async function submit() {
  if (!hasActiveUnit.value) {
    error.value = "Please select an operational unit first."
    return
  }

  loading.value = true
  error.value = ""

  try {
    await createCampaign(form)

    form.name = ""
    form.platform = ""
    form.budget = 0
    form.starts_on = ""
    form.ends_on = ""
    form.objective = ""
    form.status = "draft"

    emit("created")
  } catch (err: any) {
    console.error(err)
    error.value = err?.response?.data?.message || "Could not create campaign."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Add Campaign</h2>

    <div
      v-if="!hasActiveUnit"
      class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
    >
      Select an operational unit in the topbar before creating campaigns.
    </div>

    <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit">
      <input v-model="form.name" placeholder="Campaign name" class="rounded-lg border px-3 py-2" />
      <input v-model="form.platform" placeholder="Platform" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.budget" type="number" step="0.01" placeholder="Budget" class="rounded-lg border px-3 py-2" />
      <input v-model="form.objective" placeholder="Objective" class="rounded-lg border px-3 py-2" />
      <input v-model="form.starts_on" type="date" class="rounded-lg border px-3 py-2" />
      <input v-model="form.ends_on" type="date" class="rounded-lg border px-3 py-2" />

      <select v-model="form.status" class="md:col-span-2 rounded-lg border px-3 py-2">
        <option value="draft">draft</option>
        <option value="active">active</option>
        <option value="paused">paused</option>
        <option value="completed">completed</option>
      </select>

      <button
        class="md:col-span-2 rounded-lg bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
        :disabled="loading || !hasActiveUnit"
      >
        {{ loading ? "Saving..." : "Add Campaign" }}
      </button>
    </form>

    <p v-if="error" class="mt-2 text-red-600">
      {{ error }}
    </p>
  </div>
</template>