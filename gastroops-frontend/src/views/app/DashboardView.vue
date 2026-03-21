<script setup lang="ts">
import { ref, onMounted, watch, computed } from "vue"
import ExecutiveSummaryCard from "@/components/dashboard/ExecutiveSummaryCard.vue"
import { getFixedCostsSummary } from "@/api/fixedCosts"
import { getEmployeesSummary } from "@/api/employees"
import { getAssetsSummary } from "@/api/assets"
import { getCashFlowSummary } from "@/api/cashflow"
import { getChannelsSummary } from "@/api/channels"
import { getUtilityBillsSummary } from "@/api/utilities"
import { getLicensesSummary } from "@/api/licenses"
import { getCampaignsSummary } from "@/api/campaigns"
import { useUnitStore } from "@/stores/unit"

const unitStore = useUnitStore()

const monthlyFixed = ref(0)
const yearlyFixed = ref(0)
const monthlyLabor = ref(0)
const employeeCount = ref(0)
const activeAssets = ref(0)
const totalAssetValue = ref(0)
const cashIn = ref(0)
const cashOut = ref(0)
const netCashflow = ref(0)
const activeChannels = ref(0)
const monthlyChannelFees = ref(0)
const monthlyUtilities = ref(0)
const utilityBillsCount = ref(0)
const activeLicenses = ref(0)
const licensesExpiringSoon = ref(0)
const activeCampaigns = ref(0)
const totalMarketingBudget = ref(0)

const loading = ref(false)
const error = ref("")

const totalOperationalMonthly = computed(() => {
  return (
    monthlyFixed.value +
    monthlyLabor.value +
    monthlyChannelFees.value +
    monthlyUtilities.value
  )
})

function eur(value: number) {
  return `€ ${value.toFixed(2)}`
}

async function load() {
  if (unitStore.activeUnitId === null) return

  loading.value = true
  error.value = ""

  try {
    const fixed = await getFixedCostsSummary()
    const labor = await getEmployeesSummary()
    const assets = await getAssetsSummary()
    const cash = await getCashFlowSummary()
    const channels = await getChannelsSummary()
    const utilities = await getUtilityBillsSummary()
    const licenses = await getLicensesSummary()
    const campaigns = await getCampaignsSummary()

    monthlyFixed.value = fixed.monthly_total
    yearlyFixed.value = fixed.yearly_total
    monthlyLabor.value = labor.monthly_labor_cost
    employeeCount.value = labor.employee_count
    activeAssets.value = assets.active_assets
    totalAssetValue.value = assets.total_asset_value
    cashIn.value = cash.cash_in
    cashOut.value = cash.cash_out
    netCashflow.value = cash.net_cashflow
    activeChannels.value = channels.active_channels
    monthlyChannelFees.value = channels.monthly_channel_fees
    monthlyUtilities.value = utilities.monthly_utilities
    utilityBillsCount.value = utilities.utility_bills_count
    activeLicenses.value = licenses.active_licenses
    licensesExpiringSoon.value = licenses.licenses_expiring_soon
    activeCampaigns.value = campaigns.active_campaigns
    totalMarketingBudget.value = campaigns.total_marketing_budget
  } catch (err: any) {
    console.error("Dashboard summary error:", err)
    error.value = "Could not load dashboard summary."
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  unitStore.hydrate()
  await load()
})

watch(
  () => unitStore.activeUnitId,
  async () => {
    await load()
  }
)
</script>

<template>
  <section class="space-y-8">
    <div class="flex flex-col gap-2">
      <h1 class="text-3xl font-bold tracking-tight text-slate-900">
        Executive Dashboard
      </h1>

      <p class="text-slate-600">
        Operational and financial overview for the selected business unit.
      </p>
    </div>

    <div
      v-if="unitStore.activeUnitId === null"
      class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-700"
    >
      Select an operational unit in the topbar to see filtered dashboard data.
    </div>

    <div
      v-else-if="loading"
      class="rounded-2xl bg-white p-6 shadow-sm"
    >
      Loading dashboard...
    </div>

    <div
      v-else-if="error"
      class="rounded-2xl border border-red-200 bg-red-50 p-6 text-red-700"
    >
      {{ error }}
    </div>

    <template v-else>
      <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <ExecutiveSummaryCard
          title="Total Monthly Operational Cost"
          :value="eur(totalOperationalMonthly)"
          subtitle="Fixed + labor + channel fees + utilities"
        />

        <ExecutiveSummaryCard
          title="Net Cashflow"
          :value="eur(netCashflow)"
          subtitle="Cash in minus cash out"
        />

        <ExecutiveSummaryCard
          title="Active Employees"
          :value="employeeCount"
          subtitle="Current active staff"
        />

        <ExecutiveSummaryCard
          title="Active Assets"
          :value="activeAssets"
          subtitle="Operational equipment in use"
        />
      </div>

      <div class="space-y-3">
        <h2 class="text-xl font-semibold text-slate-900">
          Financial Overview
        </h2>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
          <ExecutiveSummaryCard title="Monthly Fixed Costs" :value="eur(monthlyFixed)" />
          <ExecutiveSummaryCard title="Yearly Fixed Costs" :value="eur(yearlyFixed)" />
          <ExecutiveSummaryCard title="Monthly Labor Cost" :value="eur(monthlyLabor)" />
          <ExecutiveSummaryCard title="Cash In" :value="eur(cashIn)" />
          <ExecutiveSummaryCard title="Cash Out" :value="eur(cashOut)" />
          <ExecutiveSummaryCard title="Total Asset Value" :value="eur(totalAssetValue)" />
        </div>
      </div>

      <div class="space-y-3">
        <h2 class="text-xl font-semibold text-slate-900">
          Operational Modules
        </h2>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
          <ExecutiveSummaryCard title="Active Channels" :value="activeChannels" />
          <ExecutiveSummaryCard title="Monthly Channel Fees" :value="eur(monthlyChannelFees)" />
          <ExecutiveSummaryCard title="Monthly Utilities" :value="eur(monthlyUtilities)" />
          <ExecutiveSummaryCard title="Utility Bills Count" :value="utilityBillsCount" />
          <ExecutiveSummaryCard title="Active Licenses" :value="activeLicenses" />
          <ExecutiveSummaryCard title="Licenses Expiring Soon" :value="licensesExpiringSoon" />
          <ExecutiveSummaryCard title="Active Campaigns" :value="activeCampaigns" />
          <ExecutiveSummaryCard title="Total Marketing Budget" :value="eur(totalMarketingBudget)" />
        </div>
      </div>
    </template>
  </section>
</template>