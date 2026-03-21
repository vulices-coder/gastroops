import api from "@/api/client"
import { useUnitStore } from "@/stores/unit"

export interface Employee {
  id: number
  operational_unit_id: number
  first_name: string
  last_name: string
  role: string
  employment_type: string
  monthly_salary: number
  hourly_rate: number
  active: boolean
}

export async function getEmployees() {
  const unitStore = useUnitStore()

  const { data } = await api.get<Employee[]>("/employees", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data
}

export async function createEmployee(payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.post("/employees", {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function updateEmployee(id: number, payload: any) {
  const unitStore = useUnitStore()

  const { data } = await api.put(`/employees/${id}`, {
    ...payload,
    operational_unit_id: unitStore.activeUnitId,
  })

  return data
}

export async function deleteEmployee(id: number) {
  const { data } = await api.delete(`/employees/${id}`)
  return data
}

export async function getEmployeesSummary() {
  const unitStore = useUnitStore()

  const { data } = await api.get("/employees/summary", {
    params: {
      unit_id: unitStore.activeUnitId,
    },
  })

  return data as {
    monthly_labor_cost: number
    employee_count: number
  }
}