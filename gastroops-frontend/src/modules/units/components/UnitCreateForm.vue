<script setup lang="ts">
import { reactive, ref } from "vue"
import { createOperationalUnit } from "@/api/units"

const emit = defineEmits<{
  created: []
}>()

const loading = ref(false)
const error = ref("")

const form = reactive({
  name: "",
  unit_type: "restaurant",
  city: "",
  active: true,
})

async function handleSubmit() {
  loading.value = true
  error.value = ""

  try {
    await createOperationalUnit({
      name: form.name,
      unit_type: form.unit_type,
      city: form.city || "",
      active: form.active,
    })

    form.name = ""
    form.unit_type = "restaurant"
    form.city = ""
    form.active = true

    emit("created")
  } catch (err: any) {
    console.error("Create unit error:", err)
    error.value =
      err?.response?.data?.message ||
      "Could not create operational unit."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl bg-white p-6 shadow-sm border">
    <div class="mb-4">
      <h2 class="text-xl font-semibold">Add Operational Unit</h2>
      <p class="mt-1 text-sm text-slate-500">
        Create a new restaurant, café, bakery or foodtruck.
      </p>
    </div>

    <form class="grid gap-4 md:grid-cols-2" @submit.prevent="handleSubmit">
      <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full rounded-xl border px-4 py-2 outline-none"
          placeholder="CholoSoy Kreuzberg"
          required
        />
      </div>

      <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Type</label>
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
        <label class="mb-1 block text-sm font-medium text-slate-700">City</label>
        <input
          v-model="form.city"
          type="text"
          class="w-full rounded-xl border px-4 py-2 outline-none"
          placeholder="Berlin"
        />
      </div>

      <div class="md:col-span-2 flex items-center gap-3">
        <input
          id="active"
          v-model="form.active"
          type="checkbox"
          class="h-4 w-4"
        />
        <label for="active" class="text-sm text-slate-700">Active</label>
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
          class="rounded-xl bg-slate-900 px-5 py-2.5 text-white disabled:opacity-50"
        >
          {{ loading ? "Saving..." : "Add Unit" }}
        </button>
      </div>
    </form>
  </div>
</template>