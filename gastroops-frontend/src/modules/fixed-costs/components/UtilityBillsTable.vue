<script setup lang="ts">
import { ref } from "vue"
import { deleteUtilityBill, updateUtilityBill, type UtilityBill } from "@/api/utilities"

const props = defineProps<{
  bills: UtilityBill[]
}>()

const emit = defineEmits<{
  reload: []
}>()

const editingId = ref<number | null>(null)

const editForm = ref({
  utility_type: "",
  billing_period_start: "",
  billing_period_end: "",
  amount: 0,
  usage_value: 0,
  provider_name: "",
})

function startEdit(bill: UtilityBill) {
  editingId.value = bill.id
  editForm.value = {
    utility_type: bill.utility_type,
    billing_period_start: bill.billing_period_start || "",
    billing_period_end: bill.billing_period_end || "",
    amount: bill.amount,
    usage_value: Number(bill.usage_value ?? 0),
    provider_name: bill.provider_name || "",
  }
}

function cancelEdit() {
  editingId.value = null
}

async function saveEdit(id: number) {
  await updateUtilityBill(id, editForm.value)
  editingId.value = null
  emit("reload")
}

async function remove(id: number) {
  if (!window.confirm("Delete this utility bill?")) return
  await deleteUtilityBill(id)
  emit("reload")
}
</script>

<template>
  <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
    <table class="min-w-full text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Type</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Provider</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Start</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">End</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Amount</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Usage</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="bill in props.bills"
          :key="bill.id"
          class="border-t"
        >
          <td class="px-4 py-3">
            <input v-if="editingId === bill.id" v-model="editForm.utility_type" class="rounded border px-2 py-1" />
            <span v-else>{{ bill.utility_type }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === bill.id" v-model="editForm.provider_name" class="rounded border px-2 py-1" />
            <span v-else>{{ bill.provider_name || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === bill.id" v-model="editForm.billing_period_start" type="date" class="rounded border px-2 py-1" />
            <span v-else>{{ bill.billing_period_start || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === bill.id" v-model="editForm.billing_period_end" type="date" class="rounded border px-2 py-1" />
            <span v-else>{{ bill.billing_period_end || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === bill.id" v-model.number="editForm.amount" type="number" step="0.01" class="rounded border px-2 py-1" />
            <span v-else>€ {{ bill.amount }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === bill.id" v-model.number="editForm.usage_value" type="number" step="0.01" class="rounded border px-2 py-1" />
            <span v-else>{{ bill.usage_value ?? "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <div class="flex gap-2">
              <template v-if="editingId === bill.id">
                <button class="rounded bg-green-600 px-3 py-1 text-sm text-white" @click="saveEdit(bill.id)">
                  Save
                </button>
                <button class="rounded border px-3 py-1 text-sm" @click="cancelEdit">
                  Cancel
                </button>
              </template>

              <template v-else>
                <button class="rounded bg-blue-600 px-3 py-1 text-sm text-white" @click="startEdit(bill)">
                  Edit
                </button>
                <button class="rounded bg-red-600 px-3 py-1 text-sm text-white" @click="remove(bill.id)">
                  Delete
                </button>
              </template>
            </div>
          </td>
        </tr>

        <tr v-if="props.bills.length === 0">
          <td colspan="7" class="px-4 py-6 text-center text-slate-500">
            No utility bills yet.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>