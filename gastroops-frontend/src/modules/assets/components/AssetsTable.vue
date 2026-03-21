<script setup lang="ts">
import { ref } from "vue"
import { deleteAsset, updateAsset, type Asset } from "@/api/assets"

const props = defineProps<{
  assets: Asset[]
}>()

const emit = defineEmits<{
  reload: []
}>()

const editingId = ref<number | null>(null)

const editForm = ref({
  name: "",
  asset_type: "",
  purchase_price: 0,
  purchase_date: "",
  active: true,
})

function startEdit(asset: Asset) {
  editingId.value = asset.id
  editForm.value = {
    name: asset.name,
    asset_type: asset.asset_type,
    purchase_price: asset.purchase_price,
    purchase_date: asset.purchase_date || "",
    active: asset.active,
  }
}

function cancelEdit() {
  editingId.value = null
}

async function saveEdit(id: number) {
  await updateAsset(id, editForm.value)
  editingId.value = null
  emit("reload")
}

async function remove(id: number) {
  if (!window.confirm("Delete this asset?")) return
  await deleteAsset(id)
  emit("reload")
}
</script>

<template>
  <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
    <table class="min-w-full text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Name</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Type</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Value</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Purchase Date</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Status</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="asset in props.assets"
          :key="asset.id"
          class="border-t"
        >
          <td class="px-4 py-3">
            <input v-if="editingId === asset.id" v-model="editForm.name" class="rounded border px-2 py-1" />
            <span v-else>{{ asset.name }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === asset.id" v-model="editForm.asset_type" class="rounded border px-2 py-1" />
            <span v-else>{{ asset.asset_type }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === asset.id" v-model.number="editForm.purchase_price" type="number" step="0.01" class="rounded border px-2 py-1" />
            <span v-else>€ {{ asset.purchase_price }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === asset.id" v-model="editForm.purchase_date" type="date" class="rounded border px-2 py-1" />
            <span v-else>{{ asset.purchase_date || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <label v-if="editingId === asset.id" class="flex items-center gap-2 text-sm">
              <input v-model="editForm.active" type="checkbox" class="h-4 w-4" />
              Active
            </label>
            <span v-else>{{ asset.active ? "Active" : "Inactive" }}</span>
          </td>

          <td class="px-4 py-3">
            <div class="flex gap-2">
              <template v-if="editingId === asset.id">
                <button class="rounded bg-green-600 px-3 py-1 text-sm text-white" @click="saveEdit(asset.id)">
                  Save
                </button>
                <button class="rounded border px-3 py-1 text-sm" @click="cancelEdit">
                  Cancel
                </button>
              </template>

              <template v-else>
                <button class="rounded bg-blue-600 px-3 py-1 text-sm text-white" @click="startEdit(asset)">
                  Edit
                </button>
                <button class="rounded bg-red-600 px-3 py-1 text-sm text-white" @click="remove(asset.id)">
                  Delete
                </button>
              </template>
            </div>
          </td>
        </tr>

        <tr v-if="props.assets.length === 0">
          <td colspan="6" class="px-4 py-6 text-center text-slate-500">
            No assets yet.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>