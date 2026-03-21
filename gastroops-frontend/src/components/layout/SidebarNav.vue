<template>
  <aside class="w-64 bg-slate-900 text-white min-h-screen p-6">
    <h1 class="text-xl font-bold mb-6">GastroOps</h1>

    <nav class="space-y-2">
      <router-link to="/app/dashboard" class="block hover:text-blue-400">
        Dashboard
      </router-link>

      <router-link to="/app/units" class="block hover:text-blue-400">
        Operational Units
      </router-link>

      <router-link to="/app/fixed-costs" class="block hover:text-blue-400">
        Fixed Costs
      </router-link>

      <router-link to="/app/staff" class="block hover:text-blue-400">
        Staff
      </router-link>

      <router-link to="/app/assets" class="block hover:text-blue-400">
        Assets
      </router-link>

      <router-link to="/app/cash-flow" class="block hover:text-blue-400">
        Cashflow
      </router-link>

      <router-link to="/app/channels" class="block hover:text-blue-400">
        Channels
      </router-link>

      <router-link to="/app/utilities" class="block hover:text-blue-400">
        Utilities
      </router-link>

      <router-link to="/app/licenses" class="block hover:text-blue-400">
        Licenses
      </router-link>

      <router-link to="/app/marketing" class="block hover:text-blue-400">
        Marketing
      </router-link>
    </nav>
  </aside>
</template>