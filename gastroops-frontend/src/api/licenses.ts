import api from "@/api/client"
import { useUnitStore } from "@/stores/unit"

export interface License {
  id: number
  operational_unit_id: number
  name: string
  authority?: string | null
  issue_date?: string | null
  expiry_date?: string | null
  renewal_cost: number
  status: string
}

export async function getLicenses() {
  const unitStore = useUnitStore()

  const { data } = await api.get<License[]>("/licenses", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data
}

export async function createLicense(payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.post("/licenses", {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function updateLicense(id: number, payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.put(`/licenses/${id}`, {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function deleteLicense(id: number) {
  const { data } = await api.delete(`/licenses/${id}`)
  return data
}

export async function getLicensesSummary() {
  const unitStore = useUnitStore()

  const { data } = await api.get("/licenses/summary", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data as {
    active_licenses: number
    licenses_expiring_soon: number
  }
}