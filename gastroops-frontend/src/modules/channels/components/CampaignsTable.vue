<script setup lang="ts">
import { ref } from "vue"
import { deleteCampaign, updateCampaign, type Campaign } from "@/api/campaigns"

const props = defineProps<{
  campaigns: Campaign[]
}>()

const emit = defineEmits<{
  reload: []
}>()

const editingId = ref<number | null>(null)

const editForm = ref({
  name: "",
  platform: "",
  budget: 0,
  starts_on: "",
  ends_on: "",
  objective: "",
  status: "draft",
})

function startEdit(campaign: Campaign) {
  editingId.value = campaign.id
  editForm.value = {
    name: campaign.name,
    platform: campaign.platform || "",
    budget: campaign.budget,
    starts_on: campaign.starts_on || "",
    ends_on: campaign.ends_on || "",
    objective: campaign.objective || "",
    status: campaign.status,
  }
}

function cancelEdit() {
  editingId.value = null
}

async function saveEdit(id: number) {
  await updateCampaign(id, editForm.value)
  editingId.value = null
  emit("reload")
}

async function remove(id: number) {
  if (!window.confirm("Delete this campaign?")) return
  await deleteCampaign(id)
  emit("reload")
}
</script>

<template>
  <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
    <table class="min-w-full text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Name</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Platform</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Budget</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Start</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">End</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Status</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="campaign in props.campaigns"
          :key="campaign.id"
          class="border-t"
        >
          <td class="px-4 py-3">
            <input v-if="editingId === campaign.id" v-model="editForm.name" class="rounded border px-2 py-1" />
            <span v-else>{{ campaign.name }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === campaign.id" v-model="editForm.platform" class="rounded border px-2 py-1" />
            <span v-else>{{ campaign.platform || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === campaign.id" v-model.number="editForm.budget" type="number" step="0.01" class="rounded border px-2 py-1" />
            <span v-else>€ {{ campaign.budget }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === campaign.id" v-model="editForm.starts_on" type="date" class="rounded border px-2 py-1" />
            <span v-else>{{ campaign.starts_on || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === campaign.id" v-model="editForm.ends_on" type="date" class="rounded border px-2 py-1" />
            <span v-else>{{ campaign.ends_on || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <select v-if="editingId === campaign.id" v-model="editForm.status" class="rounded border px-2 py-1">
              <option value="draft">draft</option>
              <option value="active">active</option>
              <option value="paused">paused</option>
              <option value="completed">completed</option>
            </select>
            <span v-else>{{ campaign.status }}</span>
          </td>

          <td class="px-4 py-3">
            <div class="flex gap-2">
              <template v-if="editingId === campaign.id">
                <button class="rounded bg-green-600 px-3 py-1 text-sm text-white" @click="saveEdit(campaign.id)">
                  Save
                </button>
                <button class="rounded border px-3 py-1 text-sm" @click="cancelEdit">
                  Cancel
                </button>
              </template>

              <template v-else>
                <button class="rounded bg-blue-600 px-3 py-1 text-sm text-white" @click="startEdit(campaign)">
                  Edit
                </button>
                <button class="rounded bg-red-600 px-3 py-1 text-sm text-white" @click="remove(campaign.id)">
                  Delete
                </button>
              </template>
            </div>
          </td>
        </tr>

        <tr v-if="props.campaigns.length === 0">
          <td colspan="7" class="px-4 py-6 text-center text-slate-500">
            No campaigns yet.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>