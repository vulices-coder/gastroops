<script setup lang="ts">
import { computed, reactive, ref } from "vue"
import { createChannel } from "@/api/channels"
import { useUnitStore } from "@/stores/unit"

const emit = defineEmits(["created"])

const unitStore = useUnitStore()
const loading = ref(false)
const error = ref("")
const hasActiveUnit = computed(() => unitStore.activeUnitId !== null)

const form = reactive({
  channel_type: "",
  platform_name: "",
  active: true,
  fee_percentage: 0,
  fee_fixed_amount: 0,
  fee_period: "monthly",
})

async function submit() {
  if (!hasActiveUnit.value) {
    error.value = "Please select an operational unit first."
    return
  }

  loading.value = true
  error.value = ""

  try {
    await createChannel(form)

    form.channel_type = ""
    form.platform_name = ""
    form.active = true
    form.fee_percentage = 0
    form.fee_fixed_amount = 0
    form.fee_period = "monthly"

    emit("created")
  } catch (err: any) {
    console.error(err)
    error.value = err?.response?.data?.message || "Could not create channel."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Add Channel</h2>

    <div
      v-if="!hasActiveUnit"
      class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
    >
      Select an operational unit in the topbar before creating channels.
    </div>

    <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit">
      <input v-model="form.channel_type" placeholder="Channel type" class="rounded-lg border px-3 py-2" />
      <input v-model="form.platform_name" placeholder="Platform name" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.fee_percentage" type="number" step="0.01" placeholder="Fee %" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.fee_fixed_amount" type="number" step="0.01" placeholder="Fixed fee" class="rounded-lg border px-3 py-2" />

      <select v-model="form.fee_period" class="rounded-lg border px-3 py-2">
        <option value="monthly">monthly</option>
        <option value="yearly">yearly</option>
      </select>

      <label class="flex items-center gap-3 text-sm text-slate-700">
        <input v-model="form.active" type="checkbox" class="h-4 w-4" />
        Active
      </label>

      <button
        class="md:col-span-2 rounded-lg bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
        :disabled="loading || !hasActiveUnit"
      >
        {{ loading ? "Saving..." : "Add Channel" }}
      </button>
    </form>

    <p v-if="error" class="mt-2 text-red-600">
      {{ error }}
    </p>
  </div>
</template>