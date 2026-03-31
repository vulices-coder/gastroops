import { createRouter, createWebHistory } from "vue-router"

import PublicLayout from "@/layouts/PublicLayout.vue"
import AuthLayout from "@/layouts/AuthLayout.vue"
import AppLayout from "@/layouts/AppLayout.vue"

import HomeView from "@/views/public/HomeView.vue"
import LoginView from "@/views/auth/LoginView.vue"
import RegisterView from "@/views/auth/RegisterView.vue"
import DashboardView from "@/views/app/DashboardView.vue"
import UnitsListView from "@/modules/units/views/UnitsListView.vue"
import FixedCostsView from "@/modules/fixed-costs/views/FixedCostsView.vue"
import StaffView from "@/modules/staff/views/StaffView.vue"
import AssetsView from "@/modules/assets/views/AssetsView.vue"
import CashFlowView from "@/modules/finance/views/CashFlowView.vue"
import ChannelsView from "@/modules/channels/views/ChannelsView.vue"
import UtilitiesView from "@/modules/fixed-costs/views/UtilitiesView.vue"
import LicensesView from "@/modules/fixed-costs/views/LicensesView.vue"
import MarketingView from "@/modules/channels/views/MarketingView.vue"

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      redirect: "/app/dashboard",
    },
    {
      path: "/home",
      component: PublicLayout,
      children: [
        {
          path: "",
          name: "home",
          component: HomeView,
        },
      ],
    },
    {
      path: "/login",
      component: AuthLayout,
      children: [
        {
          path: "",
          name: "login",
          component: LoginView,
        },
      ],
    },
    {
      path: "/register",
      component: AuthLayout,
      children: [
        {
          path: "",
          name: "register",
          component: RegisterView,
        },
      ],
    },
    {
      path: "/app",
      component: AppLayout,
      children: [
        {
          path: "dashboard",
          name: "dashboard",
          component: DashboardView,
        },
        {
          path: "units",
          name: "units",
          component: UnitsListView,
        },
        {
          path: "fixed-costs",
          name: "fixed-costs",
          component: FixedCostsView,
        },
        {
          path: "staff",
          name: "staff",
          component: StaffView,
        },
        {
          path: "assets",
          name: "assets",
          component: AssetsView,
        },
        {
          path: "cash-flow",
          name: "cash-flow",
          component: CashFlowView,
        },
        {
          path: "channels",
          name: "channels",
          component: ChannelsView,
        },
        {
          path: "utilities",
          name: "utilities",
          component: UtilitiesView,
        },
        {
          path: "licenses",
          name: "licenses",
          component: LicensesView,
        },
        {
          path: "marketing",
          name: "marketing",
          component: MarketingView,
        },
      ],
    },
  ],
})

export default router