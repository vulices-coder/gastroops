<script setup lang="ts">
defineProps<{
  title: string
  value: string | number
  subtitle?: string
}>()
</script>

<template>
  <div class="rounded-2xl border bg-white p-6 shadow-sm">
    <p class="text-sm font-medium text-slate-500">
      {{ title }}
    </p>

    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
      {{ value }}
    </h2>

    <p v-if="subtitle" class="mt-2 text-sm text-slate-500">
      {{ subtitle }}
    </p>
  </div>
</template>