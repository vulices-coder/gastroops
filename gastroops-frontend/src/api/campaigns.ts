import api from "@/api/client"
import { useUnitStore } from "@/stores/unit"

export interface Campaign {
  id: number
  operational_unit_id: number
  name: string
  platform?: string | null
  budget: number
  starts_on?: string | null
  ends_on?: string | null
  objective?: string | null
  status: string
}

export async function getCampaigns() {
  const unitStore = useUnitStore()

  const { data } = await api.get<Campaign[]>("/campaigns", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data
}

export async function createCampaign(payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.post("/campaigns", {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function updateCampaign(id: number, payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.put(`/campaigns/${id}`, {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function deleteCampaign(id: number) {
  const { data } = await api.delete(`/campaigns/${id}`)
  return data
}

export async function getCampaignsSummary() {
  const unitStore = useUnitStore()

  const { data } = await api.get("/campaigns/summary", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data as {
    active_campaigns: number
    total_marketing_budget: number
  }
}