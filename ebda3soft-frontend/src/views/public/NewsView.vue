<template>
  <div>
    <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:50px 0;color:white;text-align:center">
      <h1 style="font-size:2rem;font-weight:800">الأخبار والفعاليات</h1>
    </div>
    <section class="section">
      <div class="container">
        <div style="display:flex;gap:12px;margin-bottom:32px;flex-wrap:wrap">
          <button v-for="t in types" :key="t.val" class="btn" :class="typeFilter===t.val?'btn-primary':'btn-outline'" @click="typeFilter=t.val;load()">{{ t.label }}</button>
        </div>
        <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
        <div v-else class="grid grid-3">
          <div v-for="post in posts" :key="post.id" class="card news-card">
            <div style="height:180px;background:linear-gradient(135deg,var(--primary-dark),var(--primary-light));display:flex;align-items:center;justify-content:center;font-size:3rem">📰</div>
            <div class="card-body">
              <div class="news-card-meta">
                <span>📅 {{ fmt(post.published_at) }}</span>
                <span class="badge badge-primary">{{ labels[post.post_type] }}</span>
              </div>
              <h3 class="news-card-title">{{ post.title_ar }}</h3>
              <p class="news-card-excerpt">{{ post.excerpt_ar?.substring(0,120) }}...</p>
              <router-link :to="`/news/${post.slug}`" class="btn btn-primary btn-sm" style="margin-top:14px">اقرأ المزيد</router-link>
            </div>
          </div>
        </div>
        <div class="pagination" v-if="meta.last_page > 1">
          <button v-for="p in meta.last_page" :key="p" :class="{ active: p === meta.current_page }" @click="page=p;load()">{{ p }}</button>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const posts = ref([])
const loading = ref(true)
const typeFilter = ref(null)
const page = ref(1)
const meta = ref({})
const types = [{ val: null, label: 'الكل' }, { val: 'news', label: 'الأخبار' }, { val: 'blog', label: 'المدونة' }, { val: 'event', label: 'الفعاليات' }]
const labels = { news: 'خبر', blog: 'مقال', event: 'فعالية' }
const fmt = d => d ? new Date(d).toLocaleDateString('ar-YE', { year: 'numeric', month: 'long', day: 'numeric' }) : ''
async function load() {
  loading.value = true
  const params = { page: page.value, per_page: 9 }
  if (typeFilter.value) params.type = typeFilter.value
  try {
    const { data } = await api.get('/posts', { params })
    posts.value = data.data || []
    meta.value = { last_page: data.last_page, current_page: data.current_page }
  } catch(e) {} finally {
    loading.value = false
  }
}
onMounted(load)
</script>
