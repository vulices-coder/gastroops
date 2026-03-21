<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import { getAssets, type Asset } from "@/api/assets"
import { useUnitStore } from "@/stores/unit"
import AssetCreateForm from "@/modules/assets/components/AssetCreateForm.vue"
import AssetsTable from "@/modules/assets/components/AssetsTable.vue"

const unitStore = useUnitStore()
const assets = ref<Asset[]>([])
const loading = ref(false)
const error = ref("")

async function load() {
  if (unitStore.activeUnitId === null) {
    assets.value = []
    return
  }

  loading.value = true
  error.value = ""

  try {
    assets.value = await getAssets()
  } catch (err: any) {
    console.error("Assets API error:", err)
    error.value = "Could not load assets."
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
      <h1 class="text-3xl font-bold">Assets</h1>
      <p class="mt-2 text-slate-600">
        Manage operational equipment and asset value by unit.
      </p>
    </div>

    <div
      v-if="unitStore.activeUnitId === null"
      class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-700"
    >
      Select an operational unit in the topbar to manage assets.
    </div>

    <template v-else>
      <AssetCreateForm @created="load" />

      <div
        v-if="loading"
        class="rounded-2xl bg-white p-6 shadow-sm"
      >
        Loading assets...
      </div>

      <div
        v-else-if="error"
        class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
      >
        {{ error }}
      </div>

      <AssetsTable
        v-else
        :assets="assets"
        @reload="load"
      />
    </template>
  </section>
</template>