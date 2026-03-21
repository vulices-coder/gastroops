import api from "@/api/client"
import { useUnitStore } from "@/stores/unit"

export interface CashFlowEntry {
  id: number
  operational_unit_id: number
  entry_type: "in" | "out"
  category: string
  amount: number
  entry_date?: string
  notes?: string
}

export async function getCashFlowEntries() {
  const unitStore = useUnitStore()

  const { data } = await api.get<CashFlowEntry[]>("/cash-flow", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data
}

export async function createCashFlowEntry(payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.post("/cash-flow", {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function updateCashFlowEntry(id: number, payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.put(`/cash-flow/${id}`, {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function deleteCashFlowEntry(id: number) {
  const { data } = await api.delete(`/cash-flow/${id}`)
  return data
}

export async function getCashFlowSummary() {
  const unitStore = useUnitStore()

  const { data } = await api.get("/cash-flow/summary", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data as {
    cash_in: number
    cash_out: number
    net_cashflow: number
  }
}