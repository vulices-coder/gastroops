<script setup lang="ts">
import { ref } from "vue"
import { deleteOperationalUnit, type OperationalUnit } from "@/api/units"

const props = defineProps<{
  units: OperationalUnit[]
}>()

const emit = defineEmits<{
  deleted: []
  edit: [OperationalUnit]
}>()

const deletingId = ref<number | null>(null)
const error = ref("")

async function handleDelete(id: number) {
  const confirmed = window.confirm("Delete this operational unit?")
  if (!confirmed) return

  deletingId.value = id
  error.value = ""

  try {
    await deleteOperationalUnit(id)
    emit("deleted")
  } catch (err: any) {
    console.error("Delete unit error:", err)
    error.value =
      err?.response?.data?.message ||
      "Could not delete operational unit."
  } finally {
    deletingId.value = null
  }
}
</script>

<template>
  <div class="space-y-4">
    <div
      v-if="error"
      class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
    >
      {{ error }}
    </div>

    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
      <table class="min-w-full text-left">

        <thead class="bg-slate-50">
          <tr>
            <th class="px-4 py-3 text-sm font-semibold text-slate-600">Name</th>
            <th class="px-4 py-3 text-sm font-semibold text-slate-600">Type</th>
            <th class="px-4 py-3 text-sm font-semibold text-slate-600">City</th>
            <th class="px-4 py-3 text-sm font-semibold text-slate-600">Status</th>
            <th class="px-4 py-3 text-sm font-semibold text-slate-600">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="unit in props.units"
            :key="unit.id"
            class="border-t"
          >
            <td class="px-4 py-3">{{ unit.name }}</td>

            <td class="px-4 py-3 capitalize">
              {{ unit.unit_type }}
            </td>

            <td class="px-4 py-3">
              {{ unit.city || "-" }}
            </td>

            <td class="px-4 py-3">
              <span
                class="rounded-full px-3 py-1 text-xs font-medium"
                :class="unit.active
                ? 'bg-green-100 text-green-700'
                : 'bg-slate-200 text-slate-600'"
              >
                {{ unit.active ? "Active" : "Inactive" }}
              </span>
            </td>

            <td class="px-4 py-3 flex gap-2">

              <button
                class="rounded-lg bg-blue-600 px-3 py-2 text-sm text-white"
                @click="emit('edit', unit)"
              >
                Edit
              </button>

              <button
                type="button"
                class="rounded-lg bg-red-600 px-3 py-2 text-sm text-white"
                :disabled="deletingId === unit.id"
                @click="handleDelete(unit.id)"
              >
                {{ deletingId === unit.id ? "Deleting..." : "Delete" }}
              </button>

            </td>
          </tr>

          <tr v-if="props.units.length === 0">
            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
              No operational units yet.
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
</template>