<template>
  <nav class="navbar">
    <div class="navbar-inner">
      <div class="navbar-logo">
        <span style="font-size:2rem">🖥️</span>
        <span>إبداع سوفت</span>
      </div>

      <ul class="navbar-menu">
        <li><router-link to="/" exact-active-class="active">الرئيسية</router-link></li>
        <li><router-link to="/about" active-class="active">من نحن</router-link></li>
        <li><router-link to="/products" active-class="active">الأنظمة</router-link></li>
        <li><router-link to="/branches" active-class="active">الفروع</router-link></li>
        <li><router-link to="/news" active-class="active">الأخبار</router-link></li>
        <li><router-link to="/support" active-class="active">الدعم الفني</router-link></li>
        <li><router-link to="/contact" active-class="active">تواصل</router-link></li>
        <li><router-link to="/quote" class="navbar-cta">طلب عرض سعر</router-link></li>
      </ul>

      <button class="navbar-toggle" @click="menuOpen = !menuOpen">☰</button>
    </div>

    <div v-if="menuOpen" style="background:var(--primary-dark);padding:12px 20px;border-top:1px solid rgba(255,255,255,0.1)">
      <ul style="list-style:none;display:flex;flex-direction:column;gap:4px">
        <li v-for="link in links" :key="link.to">
          <router-link :to="link.to" @click="menuOpen=false"
            style="display:block;padding:10px 12px;color:rgba(255,255,255,0.85);border-radius:8px">
            {{ link.icon }} {{ link.label }}
          </router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
const menuOpen = ref(false)
const links = [
  { to: '/',        icon: '🏠', label: 'الرئيسية' },
  { to: '/about',   icon: '🏢', label: 'من نحن' },
  { to: '/products',icon: '🖥️', label: 'الأنظمة' },
  { to: '/branches',icon: '📍', label: 'الفروع والوكلاء' },
  { to: '/news',    icon: '📰', label: 'الأخبار والفعاليات' },
  { to: '/support', icon: '🎫', label: 'الدعم الفني' },
  { to: '/faq',     icon: '❓', label: 'الأسئلة الشائعة' },
  { to: '/contact', icon: '📞', label: 'تواصل معنا' },
  { to: '/quote',   icon: '💰', label: 'طلب عرض سعر' },
  { to: '/careers', icon: '👥', label: 'التوظيف' },
]
</script>
