<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import { getCampaigns, type Campaign } from "@/api/campaigns"
import { useUnitStore } from "@/stores/unit"
import CampaignCreateForm from "@/modules/channels/components/CampaignCreateForm.vue"
import CampaignsTable from "@/modules/channels/components/CampaignsTable.vue"

const unitStore = useUnitStore()
const campaigns = ref<Campaign[]>([])
const loading = ref(false)
const error = ref("")

async function load() {
  if (unitStore.activeUnitId === null) {
    campaigns.value = []
    return
  }

  loading.value = true
  error.value = ""

  try {
    campaigns.value = await getCampaigns()
  } catch (err: any) {
    console.error("Campaigns API error:", err)
    error.value = "Could not load campaigns."
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
      <h1 class="text-3xl font-bold">Marketing</h1>
      <p class="mt-2 text-slate-600">
        Manage campaigns and marketing budget by operational unit.
      </p>
    </div>

    <div
      v-if="unitStore.activeUnitId === null"
      class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-700"
    >
      Select an operational unit in the topbar to manage marketing campaigns.
    </div>

    <template v-else>
      <CampaignCreateForm @created="load" />

      <div
        v-if="loading"
        class="rounded-2xl bg-white p-6 shadow-sm"
      >
        Loading campaigns...
      </div>

      <div
        v-else-if="error"
        class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
      >
        {{ error }}
      </div>

      <CampaignsTable
        v-else
        :campaigns="campaigns"
        @reload="load"
      />
    </template>
  </section>
</template>