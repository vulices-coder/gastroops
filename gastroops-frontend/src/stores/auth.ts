import { defineStore } from "pinia"
import api from "@/api/client"

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null as any,
    loading: false,
  }),

  actions: {
    async fetchUser() {
      this.loading = true
      try {
        const res = await api.get("/auth/me")
        this.user = res.data
      } catch {
        this.user = null
      } finally {
        this.loading = false
      }
    },

    async login(email: string, password: string) {
      await api.post("/auth/login", { email, password })
      await this.fetchUser()
    },

    async logout() {
      await api.post("/auth/logout")
      this.user = null
    },
  },
})