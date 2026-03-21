import { defineStore } from "pinia"

export const useUnitStore = defineStore("unit", {
  state: () => ({
    activeUnitId: null as number | null,
  }),

  actions: {
    setUnit(id: number | null) {
      this.activeUnitId = id

      if (id === null) {
        localStorage.removeItem("gastroops_active_unit_id")
        return
      }

      localStorage.setItem("gastroops_active_unit_id", String(id))
    },

    hydrate() {
      const raw = localStorage.getItem("gastroops_active_unit_id")
      this.activeUnitId = raw ? Number(raw) : null
    },
  },
})