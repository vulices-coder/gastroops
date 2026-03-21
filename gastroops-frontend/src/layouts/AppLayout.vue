<script setup lang="ts">
import SidebarNav from "@/components/layout/SidebarNav.vue"
import Topbar from "@/components/layout/Topbar.vue"
</script>

<template>
  <div class="flex min-h-screen bg-slate-100">
    <SidebarNav />

    <div class="flex-1 flex flex-col">
      <Topbar />

      <main class="p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>