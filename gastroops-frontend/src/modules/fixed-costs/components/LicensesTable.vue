<script setup lang="ts">
import { ref } from "vue"
import { deleteLicense, updateLicense, type License } from "@/api/licenses"

const props = defineProps<{
  licenses: License[]
}>()

const emit = defineEmits<{
  reload: []
}>()

const editingId = ref<number | null>(null)

const editForm = ref({
  name: "",
  authority: "",
  issue_date: "",
  expiry_date: "",
  renewal_cost: 0,
  status: "active",
})

function startEdit(license: License) {
  editingId.value = license.id
  editForm.value = {
    name: license.name,
    authority: license.authority || "",
    issue_date: license.issue_date || "",
    expiry_date: license.expiry_date || "",
    renewal_cost: license.renewal_cost,
    status: license.status,
  }
}

function cancelEdit() {
  editingId.value = null
}

async function saveEdit(id: number) {
  await updateLicense(id, editForm.value)
  editingId.value = null
  emit("reload")
}

async function remove(id: number) {
  if (!window.confirm("Delete this license?")) return
  await deleteLicense(id)
  emit("reload")
}
</script>

<template>
  <div class="w-full overflow-x-auto rounded-2xl border bg-white shadow-sm">
  <table class="min-w-[1100px] text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Name</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Authority</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Issue Date</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Expiry Date</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Renewal Cost</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Status</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="license in props.licenses"
          :key="license.id"
          class="border-t"
        >
          <td class="px-4 py-3">
            <input v-if="editingId === license.id" v-model="editForm.name" class="rounded border px-2 py-1" />
            <span v-else>{{ license.name }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === license.id" v-model="editForm.authority" class="rounded border px-2 py-1" />
            <span v-else>{{ license.authority || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === license.id" v-model="editForm.issue_date" type="date" class="rounded border px-2 py-1" />
            <span v-else>{{ license.issue_date || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === license.id" v-model="editForm.expiry_date" type="date" class="rounded border px-2 py-1" />
            <span v-else>{{ license.expiry_date || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === license.id" v-model.number="editForm.renewal_cost" type="number" step="0.01" class="rounded border px-2 py-1" />
            <span v-else>€ {{ license.renewal_cost }}</span>
          </td>

          <td class="px-4 py-3">
            <select v-if="editingId === license.id" v-model="editForm.status" class="rounded border px-2 py-1">
              <option value="active">active</option>
              <option value="pending">pending</option>
              <option value="expired">expired</option>
            </select>
            <span v-else>{{ license.status }}</span>
          </td>

          <td class="px-4 py-3">
            <div class="flex gap-2">
              <template v-if="editingId === license.id">
                <button class="rounded bg-green-600 px-3 py-1 text-sm text-white" @click="saveEdit(license.id)">
                  Save
                </button>
                <button class="rounded border px-3 py-1 text-sm" @click="cancelEdit">
                  Cancel
                </button>
              </template>

              <template v-else>
                <button class="rounded bg-blue-600 px-3 py-1 text-sm text-white" @click="startEdit(license)">
                  Edit
                </button>
                <button class="rounded bg-red-600 px-3 py-1 text-sm text-white" @click="remove(license.id)">
                  Delete
                </button>
              </template>
            </div>
          </td>
        </tr>

        <tr v-if="props.licenses.length === 0">
          <td colspan="7" class="px-4 py-6 text-center text-slate-500">
            No licenses yet.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>