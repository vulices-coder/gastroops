<script setup lang="ts">
import { reactive, watch, ref } from "vue"
import { updateOperationalUnit } from "@/api/units"
import type { OperationalUnit } from "@/api/units"

const props = defineProps<{
  unit: OperationalUnit | null
}>()

const emit = defineEmits<{
  close: []
  updated: []
}>()

const loading = ref(false)
const error = ref("")

const form = reactive({
  id: 0,
  name: "",
  unit_type: "restaurant",
  city: "",
  active: true,
})

watch(
  () => props.unit,
  (unit) => {
    if (!unit) return

    form.id = unit.id
    form.name = unit.name
    form.unit_type = unit.unit_type
    form.city = unit.city || ""
    form.active = unit.active
  },
  { immediate: true }
)

async function handleSubmit() {
  loading.value = true
  error.value = ""

  try {
    await updateOperationalUnit(form.id, {
      name: form.name,
      unit_type: form.unit_type,
      city: form.city,
      active: form.active,
    })

    emit("updated")
    emit("close")
  } catch (err: any) {
    console.error("Update unit error:", err)
    error.value =
      err?.response?.data?.message ||
      "Could not update operational unit."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div
    v-if="unit"
    class="rounded-2xl border bg-white p-6 shadow-sm"
  >
    <div class="mb-4 flex items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold">Edit Operational Unit</h2>
        <p class="mt-1 text-sm text-slate-500">
          Update operational unit data.
        </p>
      </div>

      <button
        class="rounded-lg border px-3 py-2 text-sm"
        @click="emit('close')"
      >
        Close
      </button>
    </div>

    <form
      class="grid gap-4 md:grid-cols-2"
      @submit.prevent="handleSubmit"
    >
      <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">
          Name
        </label>
        <input
          v-model="form.name"
          type="text"
          class="w-full rounded-xl border px-4 py-2 outline-none"
          required
        />
      </div>

      <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">
          Type
        </label>
        <select
          v-model="form.unit_type"
          class="w-full rounded-xl border px-4 py-2 outline-none"
        >
          <option value="restaurant">Restaurant</option>
          <option value="cafe">Cafe</option>
          <option value="bakery">Bakery</option>
          <option value="foodtruck">Foodtruck</option>
          <option value="dark_kitchen">Dark Kitchen</option>
        </select>
      </div>

      <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">
          City
        </label>
        <input
          v-model="form.city"
          type="text"
          class="w-full rounded-xl border px-4 py-2 outline-none"
        />
      </div>

      <div class="md:col-span-2 flex items-center gap-3">
        <input
          v-model="form.active"
          type="checkbox"
          class="h-4 w-4"
        />
        <label class="text-sm text-slate-700">
          Active
        </label>
      </div>

      <div
        v-if="error"
        class="md:col-span-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
      >
        {{ error }}
      </div>

      <div class="md:col-span-2">
        <button
          type="submit"
          :disabled="loading"
          class="rounded-xl bg-slate-900 px-5 py-2.5 text-white"
        >
          {{ loading ? "Saving..." : "Save Changes" }}
        </button>
      </div>
    </form>
  </div>
</template>