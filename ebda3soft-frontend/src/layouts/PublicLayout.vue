<template>
  <div>
    <AppNavbar />
    <main>
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>
    <AppFooter />
    <!-- WhatsApp Float -->
    <a href="https://wa.me/967776400070" target="_blank" class="whatsapp-float" title="تواصل عبر واتساب">💬</a>
    <!-- Chatbot Widget -->
    <ChatbotWidget />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'
import ChatbotWidget from '../components/ChatbotWidget.vue'
import { useSettingsStore } from '../stores/settings'

const settingsStore = useSettingsStore()
onMounted(() => { settingsStore.fetch() })
</script>
