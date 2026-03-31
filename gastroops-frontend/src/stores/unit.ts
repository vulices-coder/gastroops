import { defineStore } from "pinia"
import { ref } from "vue"

export const useUnitStore = defineStore("unit", () => {
  const activeUnitId = ref<number | null>(null)

  function setActiveUnitId(id: number | null) {
    activeUnitId.value = id
  }

  function hydrate() {
    const saved = localStorage.getItem("activeUnitId")

    if (saved === null || saved === "all") {
      activeUnitId.value = null
      return
    }

    activeUnitId.value = Number(saved)
  }

  function persist() {
    localStorage.setItem(
      "activeUnitId",
      activeUnitId.value === null ? "all" : String(activeUnitId.value)
    )
  }

  return {
    activeUnitId,
    setActiveUnitId,
    hydrate,
    persist,
  }
})