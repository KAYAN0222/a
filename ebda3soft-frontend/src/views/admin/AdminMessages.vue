<template>
  <div class="table-container">
    <div class="table-header">
      <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">الرسائل الواردة</h3>
      <span class="badge badge-danger">{{ unread }} غير مقروءة</span>
    </div>
    <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
    <table v-else class="table">
      <thead><tr><th>المرسل</th><th>الجوال</th><th>الموضوع</th><th>الرسالة</th><th>التاريخ</th><th></th></tr></thead>
      <tbody>
        <tr v-for="m in messages" :key="m.id" :style="!m.is_read?'background:rgba(232,160,32,0.05)':''">
          <td><strong>{{ m.full_name }}</strong><span v-if="!m.is_read" class="badge badge-danger" style="margin-right:8px;font-size:0.7rem">جديد</span></td>
          <td style="font-size:0.87rem">{{ m.phone||'—' }}</td>
          <td style="font-size:0.87rem;color:var(--text-muted)">{{ m.subject||'—' }}</td>
          <td style="font-size:0.85rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ m.message }}</td>
          <td style="font-size:0.83rem;color:var(--text-muted)">{{ fmt(m.created_at) }}</td>
          <td><button v-if="!m.is_read" class="btn btn-outline btn-sm" @click="markRead(m)">✓ قراءة</button></td>
        </tr>
        <tr v-if="!messages.length"><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px">لا توجد رسائل</td></tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../api'
const messages = ref([])
const loading = ref(true)
const unread = computed(() => messages.value.filter(m => !m.is_read).length)
const fmt = d => d?new Date(d).toLocaleDateString('ar-YE'):''
async function load() { loading.value=true; const {data}=await api.get('/admin/messages'); messages.value=data.data||[]; loading.value=false }
async function markRead(m) { await api.patch(`/admin/messages/${m.id}/read`); m.is_read=true }
onMounted(load)
</script>
