import api from "@/api/client"
import { useUnitStore } from "@/stores/unit"

export interface FixedCost {
  id: number
  operational_unit_id: number
  name: string
  amount: number
  currency: string
  category?: string
  period: string
  active: boolean
}

export async function getFixedCosts() {
  const unitStore = useUnitStore()

  const { data } = await api.get<FixedCost[]>("/fixed-costs", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data
}

export async function createFixedCost(payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.post("/fixed-costs", {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function updateFixedCost(id: number, payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.put(`/fixed-costs/${id}`, {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function deleteFixedCost(id: number) {
  const { data } = await api.delete(`/fixed-costs/${id}`)
  return data
}

export async function getFixedCostsSummary() {
  const unitStore = useUnitStore()

  const { data } = await api.get("/fixed-costs/summary", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data as {
    monthly_total: number
    yearly_total: number
  }
}