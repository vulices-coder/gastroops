<script setup lang="ts">
import { ref } from "vue"
import { deleteChannel, updateChannel, type SalesChannel } from "@/api/channels"

const props = defineProps<{
  channels: SalesChannel[]
}>()

const emit = defineEmits<{
  reload: []
}>()

const editingId = ref<number | null>(null)

const editForm = ref({
  channel_type: "",
  platform_name: "",
  active: true,
  fee_percentage: 0,
  fee_fixed_amount: 0,
  fee_period: "monthly",
})

function startEdit(channel: SalesChannel) {
  const fee = channel.fees[0]

  editingId.value = channel.id
  editForm.value = {
    channel_type: channel.channel_type,
    platform_name: channel.platform_name || "",
    active: channel.active,
    fee_percentage: Number(fee?.percentage ?? 0),
    fee_fixed_amount: Number(fee?.fixed_amount ?? 0),
    fee_period: fee?.period || "monthly",
  }
}

function cancelEdit() {
  editingId.value = null
}

async function saveEdit(id: number) {
  await updateChannel(id, editForm.value)
  editingId.value = null
  emit("reload")
}

async function remove(id: number) {
  if (!window.confirm("Delete this channel?")) return
  await deleteChannel(id)
  emit("reload")
}
</script>

<template>
  <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
    <table class="min-w-full text-left">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Type</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Platform</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Fee %</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Fixed Fee</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Period</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Status</th>
          <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="channel in props.channels"
          :key="channel.id"
          class="border-t"
        >
          <td class="px-4 py-3">
            <input v-if="editingId === channel.id" v-model="editForm.channel_type" class="rounded border px-2 py-1" />
            <span v-else>{{ channel.channel_type }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === channel.id" v-model="editForm.platform_name" class="rounded border px-2 py-1" />
            <span v-else>{{ channel.platform_name || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === channel.id" v-model.number="editForm.fee_percentage" type="number" step="0.01" class="rounded border px-2 py-1" />
            <span v-else>{{ channel.fees[0]?.percentage ?? 0 }}</span>
          </td>

          <td class="px-4 py-3">
            <input v-if="editingId === channel.id" v-model.number="editForm.fee_fixed_amount" type="number" step="0.01" class="rounded border px-2 py-1" />
            <span v-else>€ {{ channel.fees[0]?.fixed_amount ?? 0 }}</span>
          </td>

          <td class="px-4 py-3">
            <select v-if="editingId === channel.id" v-model="editForm.fee_period" class="rounded border px-2 py-1">
              <option value="monthly">monthly</option>
              <option value="yearly">yearly</option>
            </select>
            <span v-else>{{ channel.fees[0]?.period || "-" }}</span>
          </td>

          <td class="px-4 py-3">
            <label v-if="editingId === channel.id" class="flex items-center gap-2 text-sm">
              <input v-model="editForm.active" type="checkbox" class="h-4 w-4" />
              Active
            </label>
            <span v-else>{{ channel.active ? "Active" : "Inactive" }}</span>
          </td>

          <td class="px-4 py-3">
            <div class="flex gap-2">
              <template v-if="editingId === channel.id">
                <button class="rounded bg-green-600 px-3 py-1 text-sm text-white" @click="saveEdit(channel.id)">
                  Save
                </button>
                <button class="rounded border px-3 py-1 text-sm" @click="cancelEdit">
                  Cancel
                </button>
              </template>

              <template v-else>
                <button class="rounded bg-blue-600 px-3 py-1 text-sm text-white" @click="startEdit(channel)">
                  Edit
                </button>
                <button class="rounded bg-red-600 px-3 py-1 text-sm text-white" @click="remove(channel.id)">
                  Delete
                </button>
              </template>
            </div>
          </td>
        </tr>

        <tr v-if="props.channels.length === 0">
          <td colspan="7" class="px-4 py-6 text-center text-slate-500">
            No channels yet.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>