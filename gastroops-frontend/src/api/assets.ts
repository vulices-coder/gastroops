import api from "@/api/client"
import { useUnitStore } from "@/stores/unit"

export interface Asset {
  id: number
  operational_unit_id: number
  name: string
  asset_type: string
  purchase_price: number
  purchase_date?: string
  active: boolean
}

export async function getAssets() {
  const unitStore = useUnitStore()

  const { data } = await api.get<Asset[]>("/assets", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data
}

export async function createAsset(payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.post("/assets", {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function updateAsset(id: number, payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.put(`/assets/${id}`, {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function deleteAsset(id: number) {
  const { data } = await api.delete(`/assets/${id}`)
  return data
}

export async function getAssetsSummary() {
  const unitStore = useUnitStore()

  const { data } = await api.get("/assets/summary", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data as {
    active_assets: number
    total_asset_value: number
  }
}