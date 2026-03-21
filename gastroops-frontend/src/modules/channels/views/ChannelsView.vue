<script setup lang="ts">
import { ref, onMounted, watch } from "vue"
import { getChannels, type SalesChannel } from "@/api/channels"
import { useUnitStore } from "@/stores/unit"
import ChannelCreateForm from "@/modules/channels/components/ChannelCreateForm.vue"
import ChannelsTable from "@/modules/channels/components/ChannelsTable.vue"

const unitStore = useUnitStore()
const channels = ref<SalesChannel[]>([])
const loading = ref(false)
const error = ref("")

async function load() {
  if (unitStore.activeUnitId === null) {
    channels.value = []
    return
  }

  loading.value = true
  error.value = ""

  try {
    channels.value = await getChannels()
  } catch (err: any) {
    console.error("Channels API error:", err)
    error.value = "Could not load channels."
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
      <h1 class="text-3xl font-bold">Channels & Fees</h1>
      <p class="mt-2 text-slate-600">
        Manage sales channels and commissions by operational unit.
      </p>
    </div>

    <div
      v-if="unitStore.activeUnitId === null"
      class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-700"
    >
      Select an operational unit in the topbar to manage channels.
    </div>

    <template v-else>
      <ChannelCreateForm @created="load" />

      <div
        v-if="loading"
        class="rounded-2xl bg-white p-6 shadow-sm"
      >
        Loading channels...
      </div>

      <div
        v-else-if="error"
        class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
      >
        {{ error }}
      </div>

      <ChannelsTable
        v-else
        :channels="channels"
        @reload="load"
      />
    </template>
  </section>
</template>