<script setup lang="ts">
import { ref } from "vue"
import { deleteCashFlowEntry, updateCashFlowEntry, type CashFlowEntry } from "@/api/cashflow"

const props = defineProps<{
  entries: CashFlowEntry[]
}>()

const emit = defineEmits<{
  reload: []
}>()

const editingId = ref<number | null>(null)

const editForm = ref({
  entry_type: "out" as "in" | "out",
  category: "",
  amount: 0,
  entry_date: "",
  notes: "",
})

function startEdit(entry: CashFlowEntry) {
  editingId.value = entry.id
  editForm.value = {
    entry_type: entry.entry_type,
    category: entry.category,
    amount: entry.amount,
    entry_date: entry.entry_date || "",
    notes: entry.notes || "",
  }
}

function cancelEdit() {
  editingId.value = null
}

async function saveEdit(id: number) {
  await updateCashFlowEntry(id, editForm.value)
  editingId.value = null
  emit("reload")
}

async function remove(id: number) {
  if (!window.confirm("Delete this cashflow entry?")) return
  await deleteCashFlowEntry(id)
  emit("reload")
}
</script>

<template>
  <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
    <table class="min-w-full text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Type</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Category</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Amount</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Date</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Notes</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="entry in props.entries"
          :key="entry.id"
          class="border-t"
        >
          <td class="px-4 py-3">
            <select v-if="editingId === entry.id" v-model="editForm.entry_type" class="rounded border px-2 py-1">
              <option value="in">in</option>
              <option value="out">out</option>
            </select>
            <span v-else>{{ entry.entry_type }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === entry.id" v-model="editForm.category" class="rounded border px-2 py-1" />
            <span v-else>{{ entry.category }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === entry.id" v-model.number="editForm.amount" type="number" step="0.01" class="rounded border px-2 py-1" />
            <span v-else>€ {{ entry.amount }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === entry.id" v-model="editForm.entry_date" type="date" class="rounded border px-2 py-1" />
            <span v-else>{{ entry.entry_date || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <textarea v-if="editingId === entry.id" v-model="editForm.notes" class="rounded border px-2 py-1"></textarea>
            <span v-else>{{ entry.notes || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <div class="flex gap-2">
              <template v-if="editingId === entry.id">
                <button class="rounded bg-green-600 px-3 py-1 text-sm text-white" @click="saveEdit(entry.id)">
                  Save
                </button>
                <button class="rounded border px-3 py-1 text-sm" @click="cancelEdit">
                  Cancel
                </button>
              </template>

              <template v-else>
                <button class="rounded bg-blue-600 px-3 py-1 text-sm text-white" @click="startEdit(entry)">
                  Edit
                </button>
                <button class="rounded bg-red-600 px-3 py-1 text-sm text-white" @click="remove(entry.id)">
                  Delete
                </button>
              </template>
            </div>
          </td>
        </tr>

        <tr v-if="props.entries.length === 0">
          <td colspan="6" class="px-4 py-6 text-center text-slate-500">
            No cashflow entries yet.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>