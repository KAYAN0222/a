<template>
  <div>
    <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:50px 0;color:white;text-align:center">
      <h1 style="font-size:2rem;font-weight:800">الأسئلة الشائعة (FAQ)</h1>
      <p style="color:rgba(255,255,255,0.8);margin-top:10px">إجابات على أكثر الأسئلة شيوعاً</p>
    </div>
    <section class="section">
      <div class="container" style="max-width:780px">
        <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
        <div v-else>
          <div v-for="faq in faqs" :key="faq.id" class="card" style="margin-bottom:12px;overflow:hidden">
            <div @click="toggle(faq.id)" style="padding:18px 24px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;user-select:none">
              <h3 style="font-size:0.97rem;font-weight:700;color:var(--primary)">{{ faq.question_ar }}</h3>
              <span style="color:var(--accent);font-size:1.2rem;transition:transform 0.3s" :style="open===faq.id?'transform:rotate(45deg)':''">+</span>
            </div>
            <div v-if="open===faq.id" style="padding:0 24px 18px;color:var(--text-muted);font-size:0.92rem;line-height:1.8;border-top:1px solid var(--border)">
              {{ faq.answer_ar }}
            </div>
          </div>
        </div>
        <div style="text-align:center;margin-top:50px;padding:40px;background:var(--primary);border-radius:var(--radius-lg)">
          <h3 style="color:white;font-weight:800;margin-bottom:12px">لم تجد إجابة على سؤالك؟</h3>
          <p style="color:rgba(255,255,255,0.8);margin-bottom:24px;font-size:0.9rem">تواصل مع فريق الدعم الفني مباشرة</p>
          <router-link to="/contact" class="btn btn-accent">تواصل معنا</router-link>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const faqs = ref([])
const loading = ref(true)
const open = ref(null)
const toggle = id => { open.value = open.value === id ? null : id }
onMounted(async () => {
  const { data } = await api.get('/faqs')
  faqs.value = data
  loading.value = false
})
</script>
