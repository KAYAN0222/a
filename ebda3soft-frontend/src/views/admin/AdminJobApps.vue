<template>
  <div class="table-container">
    <div class="table-header">
      <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">طلبات التوظيف (Job Applications)</h3>
    </div>
    <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
    <table v-else class="table">
      <thead><tr><th>الاسم</th><th>الجوال</th><th>الوظيفة</th><th>الخبرة</th><th>الحالة</th><th>تحديث</th></tr></thead>
      <tbody>
        <tr v-for="j in jobs" :key="j.id">
          <td><strong>{{ j.full_name }}</strong></td>
          <td style="font-size:0.87rem">{{ j.phone }}</td>
          <td style="font-size:0.87rem">{{ j.position||'—' }}</td>
          <td style="font-size:0.87rem">{{ j.experience_years!=null?j.experience_years+' سنة':'—' }}</td>
          <td><span class="badge" :class="sClass(j.status)">{{ sLabel(j.status) }}</span></td>
          <td>
            <select class="form-control" style="padding:6px 10px;font-size:0.82rem;width:130px" :value="j.status" @change="upd(j.id,$event.target.value)">
              <option value="new">جديد (New)</option>
              <option value="reviewed">مراجعة (Reviewed)</option>
              <option value="interviewed">مقابلة (Interviewed)</option>
              <option value="hired">مقبول (Hired)</option>
              <option value="rejected">مرفوض (Rejected)</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const jobs = ref([])
const loading = ref(true)
const sLabel = s => ({new:'جديد',reviewed:'مراجعة',interviewed:'مقابلة',hired:'مقبول',rejected:'مرفوض'}[s]||s)
const sClass = s => ({new:'badge-primary',reviewed:'badge-accent',interviewed:'badge-primary',hired:'badge-success',rejected:'badge-danger'}[s])
async function load() { loading.value=true; const {data}=await api.get('/admin/job-applications'); jobs.value=data.data||[]; loading.value=false }
async function upd(id, status) { await api.patch(`/admin/job-applications/${id}/status`,{status}); load() }
onMounted(load)
</script>
