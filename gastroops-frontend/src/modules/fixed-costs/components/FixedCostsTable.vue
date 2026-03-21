<script setup lang="ts">
import { ref } from "vue"
import { deleteFixedCost, updateFixedCost, type FixedCost } from "@/api/fixedCosts"

const props = defineProps<{
  costs: FixedCost[]
}>()

const emit = defineEmits<{
  reload: []
}>()

const editingId = ref<number | null>(null)

const editForm = ref({
  name: "",
  amount: 0,
  category: "",
  period: "monthly",
  active: true,
  currency: "EUR",
})

function startEdit(cost: FixedCost) {
  editingId.value = cost.id
  editForm.value = {
    name: cost.name,
    amount: cost.amount,
    category: cost.category || "",
    period: cost.period,
    active: cost.active,
    currency: cost.currency,
  }
}

function cancelEdit() {
  editingId.value = null
}

async function saveEdit(id: number) {
  await updateFixedCost(id, editForm.value)
  editingId.value = null
  emit("reload")
}

async function remove(id: number) {
  if (!window.confirm("Delete this fixed cost?")) return
  await deleteFixedCost(id)
  emit("reload")
}
</script>

<template>
  <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
    <table class="min-w-full text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Name</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Amount</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Category</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Period</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="cost in props.costs"
          :key="cost.id"
          class="border-t"
        >
          <td class="px-4 py-3">
            <input
              v-if="editingId === cost.id"
              v-model="editForm.name"
              class="w-full rounded border px-2 py-1"
            />
            <span v-else>
              {{ cost.name }}
            </span>
          </td>

          <td class="px-4 py-3">
            <input
              v-if="editingId === cost.id"
              v-model.number="editForm.amount"
              type="number"
              step="0.01"
              class="w-full rounded border px-2 py-1"
            />
            <span v-else>
              {{ cost.amount }} {{ cost.currency }}
            </span>
          </td>

          <td class="px-4 py-3">
            <input
              v-if="editingId === cost.id"
              v-model="editForm.category"
              class="w-full rounded border px-2 py-1"
            />
            <span v-else>
              {{ cost.category || "-" }}
            </span>
          </td>

          <td class="px-4 py-3">
            <select
              v-if="editingId === cost.id"
              v-model="editForm.period"
              class="w-full rounded border px-2 py-1"
            >
              <option value="monthly">monthly</option>
              <option value="yearly">yearly</option>
            </select>
            <span v-else>
              {{ cost.period }}
            </span>
          </td>

          <td class="px-4 py-3">
            <div class="flex gap-2">
              <template v-if="editingId === cost.id">
                <button
                  class="rounded bg-green-600 px-3 py-1 text-sm text-white"
                  @click="saveEdit(cost.id)"
                >
                  Save
                </button>

                <button
                  class="rounded border px-3 py-1 text-sm"
                  @click="cancelEdit"
                >
                  Cancel
                </button>
              </template>

              <template v-else>
                <button
                  class="rounded bg-blue-600 px-3 py-1 text-sm text-white"
                  @click="startEdit(cost)"
                >
                  Edit
                </button>

                <button
                  class="rounded bg-red-600 px-3 py-1 text-sm text-white"
                  @click="remove(cost.id)"
                >
                  Delete
                </button>
              </template>
            </div>
          </td>
        </tr>

        <tr v-if="props.costs.length === 0">
          <td colspan="5" class="px-4 py-6 text-center text-slate-500">
            No fixed costs yet.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>