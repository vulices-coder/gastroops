<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import { getEmployees, type Employee } from "@/api/employees"
import { useUnitStore } from "@/stores/unit"
import EmployeeCreateForm from "@/modules/staff/components/EmployeeCreateForm.vue"
import EmployeesTable from "@/modules/staff/components/EmployeesTable.vue"

const unitStore = useUnitStore()
const employees = ref<Employee[]>([])
const loading = ref(false)
const error = ref("")

async function load() {
  if (unitStore.activeUnitId === null) {
    employees.value = []
    return
  }

  loading.value = true
  error.value = ""

  try {
    employees.value = await getEmployees()
  } catch (err: any) {
    console.error("Employees API error:", err)
    error.value = "Could not load employees."
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  unitStore.hydrate()
  await load()
})

watch(
  () => unitStore.activeUnitId,
  async () => {
    await load()
  }
)
</script>

<template>
  <section class="space-y-6">
    <div>
      <h1 class="text-3xl font-bold">Staff</h1>
      <p class="mt-2 text-slate-600">
        Manage employees and labor structure by operational unit.
      </p>
    </div>

    <div
      v-if="unitStore.activeUnitId === null"
      class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-700"
    >
      Select an operational unit in the topbar to manage staff data.
    </div>

    <template v-else>
      <EmployeeCreateForm @created="load" />

      <div
        v-if="loading"
        class="rounded-2xl bg-white p-6 shadow-sm"
      >
        Loading employees...
      </div>

      <div
        v-else-if="error"
        class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
      >
        {{ error }}
      </div>

      <EmployeesTable
        v-else
        :employees="employees"
        @reload="load"
      />
    </template>
  </section>
</template>