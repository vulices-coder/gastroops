<script setup lang="ts">
import UnitSwitcher from "@/components/layout/UnitSwitcher.vue"
</script>

<template>
  <header class="h-16 bg-white border-b flex items-center px-6">
    <div class="flex-1">
      <h2 class="font-semibold text-lg">GastroOps</h2>
    </div>

    <div class="flex items-center gap-6">
      <UnitSwitcher />
      <span class="text-sm text-slate-500">User</span>
    </div>
  </header>
</template>