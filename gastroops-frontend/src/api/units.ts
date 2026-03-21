import api from "@/api/client"

export interface OperationalUnit {
  id: number
  name: string
  unit_type: string
  city?: string
  active: boolean
}

export interface CreateOperationalUnitPayload {
  name: string
  unit_type: string
  city?: string
  active: boolean
}

export interface UpdateOperationalUnitPayload {
  name: string
  unit_type: string
  city?: string
  active: boolean
}

export async function getOperationalUnits() {
  const { data } = await api.get<OperationalUnit[]>("/operational-units")
  return data
}

export async function createOperationalUnit(payload: CreateOperationalUnitPayload) {
  const { data } = await api.post<OperationalUnit>("/operational-units", payload)
  return data
}

export async function updateOperationalUnit(
  id: number,
  payload: UpdateOperationalUnitPayload
) {
  const { data } = await api.put<OperationalUnit>(`/operational-units/${id}`, payload)
  return data
}

export async function deleteOperationalUnit(id: number) {
  const { data } = await api.delete(`/operational-units/${id}`)
  return data
}