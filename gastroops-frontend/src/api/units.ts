import api from "@/api/client"

export interface OperationalUnit {
  id: number
  name: string
  unit_type: string
  city?: string | null
  active: boolean
}

export async function getOperationalUnits() {
  const { data } = await api.get<OperationalUnit[]>("/operational-units")
  return data
}

export async function createOperationalUnit(payload: any) {
  const { data } = await api.post("/operational-units", payload)
  return data
}

export async function updateOperationalUnit(id: number, payload: any) {
  const { data } = await api.put(`/operational-units/${id}`, payload)
  return data
}

export async function deleteOperationalUnit(id: number) {
  const { data } = await api.delete(`/operational-units/${id}`)
  return data
}