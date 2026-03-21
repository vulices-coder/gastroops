import api from "@/api/client"
import { useUnitStore } from "@/stores/unit"

export interface ChannelFee {
  id: number
  percentage?: number | null
  fixed_amount?: number | null
  period: string
}

export interface SalesChannel {
  id: number
  operational_unit_id: number
  channel_type: string
  platform_name?: string | null
  active: boolean
  fees: ChannelFee[]
}

export async function getChannels() {
  const unitStore = useUnitStore()

  const { data } = await api.get<SalesChannel[]>("/channels", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data
}

export async function createChannel(payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.post("/channels", {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function updateChannel(id: number, payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.put(`/channels/${id}`, {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function deleteChannel(id: number) {
  const { data } = await api.delete(`/channels/${id}`)
  return data
}

export async function getChannelsSummary() {
  const unitStore = useUnitStore()

  const { data } = await api.get("/channels/summary", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data as {
    active_channels: number
    monthly_channel_fees: number
  }
}