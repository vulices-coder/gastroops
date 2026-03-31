<script setup lang="ts">
defineProps<{
  title: string
  value: string | number
  subtitle?: string
}>()
</script>

<template>
  <div class="bg-white rounded-2xl border p-5 shadow-sm">
    <p class="text-sm text-gray-500 mb-1">{{ title }}</p>

    <h2 class="text-2xl font-semibold text-gray-900">
      {{ value }}
    </h2>

    <p v-if="subtitle" class="text-xs text-gray-400 mt-1">
      {{ subtitle }}
    </p>
  </div>
</template>