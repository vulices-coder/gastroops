<script setup lang="ts">
import { computed, reactive, ref } from "vue"
import { createEmployee } from "@/api/employees"
import { useUnitStore } from "@/stores/unit"

const emit = defineEmits(["created"])

const unitStore = useUnitStore()
const loading = ref(false)
const error = ref("")

const hasActiveUnit = computed(() => unitStore.activeUnitId !== null)

const form = reactive({
  first_name: "",
  last_name: "",
  role: "",
  employment_type: "full_time",
  monthly_salary: 0,
  hourly_rate: 0,
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
    await createEmployee(form)

    form.first_name = ""
    form.last_name = ""
    form.role = ""
    form.employment_type = "full_time"
    form.monthly_salary = 0
    form.hourly_rate = 0
    form.active = true

    emit("created")
  } catch (err: any) {
    console.error(err)
    error.value =
      err?.response?.data?.message ||
      "Could not create employee."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="mb-4 text-lg font-semibold">Add Employee</h2>

    <div
      v-if="!hasActiveUnit"
      class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"
    >
      Select an operational unit in the topbar before creating employees.
    </div>

    <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit">
      <input v-model="form.first_name" placeholder="First name" class="rounded-lg border px-3 py-2" />
      <input v-model="form.last_name" placeholder="Last name" class="rounded-lg border px-3 py-2" />
      <input v-model="form.role" placeholder="Role" class="rounded-lg border px-3 py-2" />

      <select v-model="form.employment_type" class="rounded-lg border px-3 py-2">
        <option value="full_time">full_time</option>
        <option value="part_time">part_time</option>
        <option value="mini_job">mini_job</option>
        <option value="freelance">freelance</option>
      </select>

      <input v-model.number="form.monthly_salary" type="number" step="0.01" placeholder="Monthly salary" class="rounded-lg border px-3 py-2" />
      <input v-model.number="form.hourly_rate" type="number" step="0.01" placeholder="Hourly rate" class="rounded-lg border px-3 py-2" />

      <label class="md:col-span-2 flex items-center gap-3 text-sm text-slate-700">
        <input v-model="form.active" type="checkbox" class="h-4 w-4" />
        Active
      </label>

      <button
        class="md:col-span-2 rounded-lg bg-slate-900 px-4 py-2 text-white disabled:opacity-50"
        :disabled="loading || !hasActiveUnit"
      >
        {{ loading ? "Saving..." : "Add Employee" }}
      </button>
    </form>

    <p v-if="error" class="mt-2 text-red-600">
      {{ error }}
    </p>
  </div>
</template>