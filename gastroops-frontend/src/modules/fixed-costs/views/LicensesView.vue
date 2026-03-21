<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import { getLicenses, type License } from "@/api/licenses"
import { useUnitStore } from "@/stores/unit"
import LicenseCreateForm from "@/modules/fixed-costs/components/LicenseCreateForm.vue"
import LicensesTable from "@/modules/fixed-costs/components/LicensesTable.vue"

const unitStore = useUnitStore()
const licenses = ref<License[]>([])
const loading = ref(false)
const error = ref("")

async function load() {
  if (unitStore.activeUnitId === null) {
    licenses.value = []
    return
  }

  loading.value = true
  error.value = ""

  try {
    licenses.value = await getLicenses()
  } catch (err: any) {
    console.error("Licenses API error:", err)
    error.value = "Could not load licenses."
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
      <h1 class="text-3xl font-bold">Licenses</h1>
      <p class="mt-2 text-slate-600">
        Manage active licenses and renewal deadlines by operational unit.
      </p>
    </div>

    <div
      v-if="unitStore.activeUnitId === null"
      class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-700"
    >
      Select an operational unit in the topbar to manage licenses.
    </div>

    <template v-else>
      <LicenseCreateForm @created="load" />

      <div
        v-if="loading"
        class="rounded-2xl bg-white p-6 shadow-sm"
      >
        Loading licenses...
      </div>

      <div
        v-else-if="error"
        class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
      >
        {{ error }}
      </div>

      <LicensesTable
        v-else
        :licenses="licenses"
        @reload="load"
      />
    </template>
  </section>
</template>