import api from "@/api/client"
import { useUnitStore } from "@/stores/unit"

export interface UtilityBill {
  id: number
  operational_unit_id: number
  utility_type: string
  billing_period_start?: string
  billing_period_end?: string
  amount: number
  usage_value?: number | null
  provider_name?: string | null
}

export async function getUtilityBills() {
  const unitStore = useUnitStore()

  const { data } = await api.get<UtilityBill[]>("/utility-bills", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data
}

export async function createUtilityBill(payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.post("/utility-bills", {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function updateUtilityBill(id: number, payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.put(`/utility-bills/${id}`, {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function deleteUtilityBill(id: number) {
  const { data } = await api.delete(`/utility-bills/${id}`)
  return data
}

export async function getUtilityBillsSummary() {
  const unitStore = useUnitStore()

  const { data } = await api.get("/utility-bills/summary", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data as {
    monthly_utilities: number
    utility_bills_count: number
  }
}