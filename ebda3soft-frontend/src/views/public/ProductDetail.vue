<template>
  <div>
    <div v-if="loading" class="loading-center" style="min-height:60vh"><div class="spinner"></div></div>
    <template v-else-if="product">
      <!-- Hero -->
      <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:60px 0;color:white">
        <div class="container" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap">
          <div style="font-size:4rem">{{ emojis[product.slug] || '🖥️' }}</div>
          <div>
            <div style="color:var(--accent-light);font-size:0.85rem;font-weight:700;margin-bottom:8px">{{ product.category?.name_ar }}</div>
            <h1 style="font-size:2rem;font-weight:900;margin-bottom:12px">{{ product.name_ar }}</h1>
            <p style="color:rgba(255,255,255,0.85);max-width:600px;line-height:1.8">{{ product.short_desc_ar }}</p>
          </div>
        </div>
      </div>

      <section class="section">
        <div class="container">
          <div style="display:grid;grid-template-columns:2fr 1fr;gap:40px;align-items:start">
            <div>
              <h2 style="font-size:1.3rem;font-weight:800;color:var(--primary);margin-bottom:24px">عن النظام</h2>
              <div style="color:var(--text-muted);line-height:1.9" v-html="product.full_desc_ar || product.short_desc_ar"></div>

              <div v-if="product.features?.length" style="margin-top:40px">
                <h3 style="font-size:1.1rem;font-weight:800;color:var(--primary);margin-bottom:20px">مميزات النظام</h3>
                <div>
                  <div v-for="f in product.features" :key="f.id" class="feature-item">
                    <div class="feature-icon">✓</div>
                    <span class="feature-text">{{ f.feature_ar }}</span>
                  </div>
                </div>
              </div>

              <div v-if="product.faqs?.length" style="margin-top:40px">
                <h3 style="font-size:1.1rem;font-weight:800;color:var(--primary);margin-bottom:20px">أسئلة شائعة عن النظام</h3>
                <div v-for="faq in product.faqs" :key="faq.id" class="card card-body" style="margin-bottom:12px">
                  <h4 style="font-weight:700;color:var(--primary);margin-bottom:8px">{{ faq.question_ar }}</h4>
                  <p style="color:var(--text-muted);font-size:0.9rem">{{ faq.answer_ar }}</p>
                </div>
              </div>
            </div>

            <!-- Sidebar -->
            <div>
              <div class="card card-body" style="position:sticky;top:90px">
                <h3 style="font-size:1rem;font-weight:800;color:var(--primary);margin-bottom:20px">طلب النظام الآن</h3>
                <router-link :to="`/request?product=${product.id}`" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:12px">📋 اطلب النظام</router-link>
                <a href="https://wa.me/967776400070" target="_blank" class="btn" style="width:100%;justify-content:center;background:#25d366;color:white">💬 تواصل عبر واتساب</a>
                <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border)">
                  <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:8px">📞 01 337571/72/73</p>
                  <p style="font-size:0.85rem;color:var(--text-muted)">✉️ info@softpro.com</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </template>
    <div v-else style="text-align:center;padding:80px">النظام غير موجود</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../api'

const route = useRoute()
const product = ref(null)
const loading = ref(true)
const emojis = {
  'dotx-pro':'💰','mal-accounting':'📊','merchant-pro':'🛒','fixed-assets':'🏗️',
  'exchange-advanced':'💱','teller-advanced':'🏦','teller-plus':'💳',
  'restaurant-system':'🍽️','fuel-station':'⛽','hr-system':'👥',
  'noon-schools':'🏫','hospital-system':'🏥','electricity-station':'⚡',
  'water-projects':'💧','car-showroom':'🚗','hotel-system':'🏨',
}
onMounted(async () => {
  try { const { data } = await api.get(`/products/${route.params.slug}`); product.value = data }
  finally { loading.value = false }
})
</script>
