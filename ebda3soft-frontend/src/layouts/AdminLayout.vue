<template>
  <div class="admin-layout">
    <aside class="admin-sidebar" :class="{ open: sidebarOpen }">
      <div class="sidebar-logo">
        <span style="font-size: 1.4rem">🖥️</span>
        <span>SoftPro - نظامك المستقبلي</span>
      </div>

      <nav class="sidebar-nav">
        <div class="sidebar-nav-section">القائمة الرئيسية</div>
        <ul>
          <li class="sidebar-nav-item" v-for="item in navItems" :key="item.to">
            <router-link :to="item.to" @click="sidebarOpen = false">
              <span>{{ item.icon }}</span>
              <span>{{ item.label }}</span>
            </router-link>
          </li>
        </ul>
      </nav>

      <div
        style="
          padding: 16px 12px;
          border-top: 1px solid rgba(255, 255, 255, 0.08);
        "
      >
        <button
          class="btn btn-outline"
          style="
            width: 100%;
            color: rgba(255, 255, 255, 0.7);
            border-color: rgba(255, 255, 255, 0.2);
          "
          @click="doLogout"
        >
          🚪 تسجيل الخروج
        </button>
      </div>
    </aside>

    <div class="admin-main">
      <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px">
          <button
            class="navbar-toggle"
            @click="sidebarOpen = !sidebarOpen"
            style="color: var(--primary)"
          >
            ☰
          </button>
          <h2 style="font-size: 1rem; font-weight: 700; color: var(--primary)">
            {{ pageTitle }}
          </h2>
        </div>
        <div style="display: flex; align-items: center; gap: 12px">
          <span style="font-size: 0.9rem; color: var(--text-muted)"
            >👤 {{ auth.userName }}</span
          >
          <span class="badge badge-primary">{{ auth.user?.role_ar }}</span>
        </div>
      </header>

      <div class="admin-content">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const router = useRouter();
const route = useRoute();
const sidebarOpen = ref(false);

const navItems = [
  { to: "/admin", icon: "📊", label: "لوحة التحكم (Dashboard)" },
  { to: "/admin/products", icon: "🖥️", label: "الأنظمة والمنتجات" },
  { to: "/admin/posts", icon: "📰", label: "الأخبار والمقالات" },
  { to: "/admin/orders", icon: "📋", label: "الطلبات (Orders)" },
  { to: "/admin/tickets", icon: "🎫", label: "تذاكر الدعم (Tickets)" },
  { to: "/admin/quotes", icon: "💰", label: "عروض الأسعار (Quotes)" },
  { to: "/admin/branches", icon: "🏢", label: "الفروع والوكلاء" },
  { to: "/admin/messages", icon: "✉️", label: "الرسائل الواردة" },
  { to: "/admin/jobs", icon: "👥", label: "طلبات التوظيف" },
  { to: "/admin/settings", icon: "⚙️", label: "الإعدادات (Settings)" },
];

const titles = {
  "admin-dashboard": "لوحة التحكم",
  "admin-products": "إدارة الأنظمة",
  "admin-posts": "إدارة المقالات",
  "admin-orders": "إدارة الطلبات",
  "admin-branches": "الفروع والوكلاء",
  "admin-messages": "الرسائل الواردة",
  "admin-jobs": "طلبات التوظيف",
  "admin-settings": "إعدادات الموقع",
};
const pageTitle = computed(() => titles[route.name] || "الإدارة");

async function doLogout() {
  await auth.logout();
  router.push("/admin/login");
}
</script>
