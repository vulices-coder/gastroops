<script setup lang="ts">
import { ref, onMounted, watch, computed } from "vue"
import ExecutiveSummaryCard from "@/components/dashboard/ExecutiveSummaryCard.vue"
import UnitsOverviewTable from "@/components/dashboard/UnitsOverviewTable.vue"
import { getFixedCostsSummary } from "@/api/fixedCosts"
import { getEmployeesSummary } from "@/api/employees"
import { getAssetsSummary } from "@/api/assets"
import { getCashFlowSummary } from "@/api/cashflow"
import { getChannelsSummary } from "@/api/channels"
import { getUtilityBillsSummary } from "@/api/utilities"
import { getLicensesSummary } from "@/api/licenses"
import { getCampaignsSummary } from "@/api/campaigns"
import { getOperationalUnits, type OperationalUnit } from "@/api/units"
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

const units = ref<OperationalUnit[]>([])

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

const totalUnits = computed(() => units.value.length)
const activeUnits = computed(() => units.value.filter((unit) => unit.active).length)
const inactiveUnits = computed(() => units.value.filter((unit) => !unit.active).length)

function toNumber(value: number | string | null | undefined) {
  const parsed = Number(value ?? 0)
  return Number.isFinite(parsed) ? parsed : 0
}

function eur(value: number | string | null | undefined) {
  return `€ ${toNumber(value).toFixed(2)}`
}

function resetDashboard() {
  monthlyFixed.value = 0
  yearlyFixed.value = 0
  monthlyLabor.value = 0
  employeeCount.value = 0
  activeAssets.value = 0
  totalAssetValue.value = 0
  cashIn.value = 0
  cashOut.value = 0
  netCashflow.value = 0
  activeChannels.value = 0
  monthlyChannelFees.value = 0
  monthlyUtilities.value = 0
  utilityBillsCount.value = 0
  activeLicenses.value = 0
  licensesExpiringSoon.value = 0
  activeCampaigns.value = 0
  totalMarketingBudget.value = 0
}

async function load() {
  loading.value = true
  error.value = ""

  try {
    const [
      fixed,
      labor,
      assets,
      cash,
      channels,
      utilities,
      licenses,
      campaigns,
      operationalUnits,
    ] = await Promise.all([
      getFixedCostsSummary(),
      getEmployeesSummary(),
      getAssetsSummary(),
      getCashFlowSummary(),
      getChannelsSummary(),
      getUtilityBillsSummary(),
      getLicensesSummary(),
      getCampaignsSummary(),
      getOperationalUnits(),
    ])

    monthlyFixed.value = toNumber(fixed?.monthly_total)
    yearlyFixed.value = toNumber(fixed?.yearly_total)

    monthlyLabor.value = toNumber(labor?.monthly_labor_cost)
    employeeCount.value = toNumber(labor?.employee_count)

    activeAssets.value = toNumber(assets?.active_assets)
    totalAssetValue.value = toNumber(assets?.total_asset_value)

    cashIn.value = toNumber(cash?.cash_in)
    cashOut.value = toNumber(cash?.cash_out)
    netCashflow.value = toNumber(cash?.net_cashflow)

    activeChannels.value = toNumber(channels?.active_channels)
    monthlyChannelFees.value = toNumber(channels?.monthly_channel_fees)

    monthlyUtilities.value = toNumber(utilities?.monthly_utilities)
    utilityBillsCount.value = toNumber(utilities?.utility_bills_count)

    activeLicenses.value = toNumber(licenses?.active_licenses)
    licensesExpiringSoon.value = toNumber(licenses?.licenses_expiring_soon)

    activeCampaigns.value = toNumber(campaigns?.active_campaigns)
    totalMarketingBudget.value = toNumber(campaigns?.total_marketing_budget)

    units.value = Array.isArray(operationalUnits) ? operationalUnits : []
  } catch (err: any) {
    console.error("Dashboard summary error:", err)
    error.value = "Could not load dashboard summary."
    resetDashboard()
    units.value = []
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
        Operational and financial overview for
        <span class="font-medium text-slate-800">
          {{ unitStore.activeUnitId === null ? "all units" : "the selected business unit" }}
        </span>.
      </p>
    </div>

    <div
      v-if="loading"
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

      <div class="grid gap-6 md:grid-cols-3">
        <ExecutiveSummaryCard
          title="Total Units"
          :value="totalUnits"
          subtitle="All operational units"
        />

        <ExecutiveSummaryCard
          title="Active Units"
          :value="activeUnits"
          subtitle="Currently operating"
        />

        <ExecutiveSummaryCard
          title="Inactive Units"
          :value="inactiveUnits"
          subtitle="Not currently operating"
        />
      </div>

      <UnitsOverviewTable :units="units" />

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