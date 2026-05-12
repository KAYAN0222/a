<template>
  <div>
    <div v-if="loading" class="loading-center" style="min-height:60vh"><div class="spinner"></div></div>
    <template v-else-if="post">
      <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:60px 0;color:white">
        <div class="container">
          <div class="news-card-meta" style="color:rgba(255,255,255,0.7);margin-bottom:16px">
            <span>📅 {{ fmt(post.published_at) }}</span>
            <span class="badge badge-accent">{{ labels[post.post_type] }}</span>
          </div>
          <h1 style="font-size:1.8rem;font-weight:900;max-width:700px;line-height:1.4">{{ post.title_ar }}</h1>
        </div>
      </div>
      <section class="section">
        <div class="container" style="max-width:780px">
          <div style="color:var(--text);line-height:2;font-size:1rem" v-html="post.content_ar"></div>
          <div style="margin-top:40px;padding-top:24px;border-top:1px solid var(--border)">
            <router-link to="/news" class="btn btn-outline">← العودة للأخبار</router-link>
          </div>
        </div>
      </section>
    </template>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../api'
const route = useRoute()
const post = ref(null)
const loading = ref(true)
const labels = { news: 'خبر', blog: 'مقال', event: 'فعالية' }
const fmt = d => d ? new Date(d).toLocaleDateString('ar-YE', { year: 'numeric', month: 'long', day: 'numeric' }) : ''
onMounted(async () => {
  try { const { data } = await api.get(`/posts/${route.params.slug}`); post.value = data }
  finally { loading.value = false }
})
</script>
