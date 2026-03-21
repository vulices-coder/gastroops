<script setup lang="ts">
import { ref } from "vue"
import { deleteEmployee, updateEmployee, type Employee } from "@/api/employees"

const props = defineProps<{
  employees: Employee[]
}>()

const emit = defineEmits<{
  reload: []
}>()

const editingId = ref<number | null>(null)

const editForm = ref({
  first_name: "",
  last_name: "",
  role: "",
  employment_type: "full_time",
  monthly_salary: 0,
  hourly_rate: 0,
  active: true,
})

function startEdit(employee: Employee) {
  editingId.value = employee.id
  editForm.value = {
    first_name: employee.first_name,
    last_name: employee.last_name,
    role: employee.role,
    employment_type: employee.employment_type,
    monthly_salary: employee.monthly_salary,
    hourly_rate: employee.hourly_rate,
    active: employee.active,
  }
}

function cancelEdit() {
  editingId.value = null
}

async function saveEdit(id: number) {
  await updateEmployee(id, editForm.value)
  editingId.value = null
  emit("reload")
}

async function remove(id: number) {
  if (!window.confirm("Delete this employee?")) return
  await deleteEmployee(id)
  emit("reload")
}
</script>

<template>
  <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
    <table class="min-w-full text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Name</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Role</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Type</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Monthly</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Hourly</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Status</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="employee in props.employees"
          :key="employee.id"
          class="border-t"
        >
          <td class="px-4 py-3">
            <div v-if="editingId === employee.id" class="grid gap-2">
              <input v-model="editForm.first_name" class="rounded border px-2 py-1" />
              <input v-model="editForm.last_name" class="rounded border px-2 py-1" />
            </div>
            <span v-else>
              {{ employee.first_name }} {{ employee.last_name }}
            </span>
          </td>

          <td class="px-4 py-3">
            <input
              v-if="editingId === employee.id"
              v-model="editForm.role"
              class="rounded border px-2 py-1"
            />
            <span v-else>{{ employee.role }}</span>
          </td>

          <td class="px-4 py-3">
            <select
              v-if="editingId === employee.id"
              v-model="editForm.employment_type"
              class="rounded border px-2 py-1"
            >
              <option value="full_time">full_time</option>
              <option value="part_time">part_time</option>
              <option value="mini_job">mini_job</option>
              <option value="freelance">freelance</option>
            </select>
            <span v-else>{{ employee.employment_type }}</span>
          </td>

          <td class="px-4 py-3">
            <input
              v-if="editingId === employee.id"
              v-model.number="editForm.monthly_salary"
              type="number"
              step="0.01"
              class="rounded border px-2 py-1"
            />
            <span v-else>€ {{ employee.monthly_salary }}</span>
          </td>

          <td class="px-4 py-3">
            <input
              v-if="editingId === employee.id"
              v-model.number="editForm.hourly_rate"
              type="number"
              step="0.01"
              class="rounded border px-2 py-1"
            />
            <span v-else>€ {{ employee.hourly_rate }}</span>
          </td>

          <td class="px-4 py-3">
            <label v-if="editingId === employee.id" class="flex items-center gap-2 text-sm">
              <input v-model="editForm.active" type="checkbox" class="h-4 w-4" />
              Active
            </label>
            <span v-else>{{ employee.active ? "Active" : "Inactive" }}</span>
          </td>

          <td class="px-4 py-3">
            <div class="flex gap-2">
              <template v-if="editingId === employee.id">
                <button class="rounded bg-green-600 px-3 py-1 text-sm text-white" @click="saveEdit(employee.id)">
                  Save
                </button>
                <button class="rounded border px-3 py-1 text-sm" @click="cancelEdit">
                  Cancel
                </button>
              </template>

              <template v-else>
                <button class="rounded bg-blue-600 px-3 py-1 text-sm text-white" @click="startEdit(employee)">
                  Edit
                </button>
                <button class="rounded bg-red-600 px-3 py-1 text-sm text-white" @click="remove(employee.id)">
                  Delete
                </button>
              </template>
            </div>
          </td>
        </tr>

        <tr v-if="props.employees.length === 0">
          <td colspan="7" class="px-4 py-6 text-center text-slate-500">
            No employees yet.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>