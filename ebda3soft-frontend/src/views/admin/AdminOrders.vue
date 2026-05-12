<template>
  <div class="table-container">
    <div class="table-header">
      <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">الطلبات الواردة (Orders)</h3>
      <span class="badge badge-accent">{{ orders.length }} طلب</span>
    </div>
    <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
    <table v-else class="table">
      <thead><tr><th>رقم الطلب</th><th>النظام</th><th>الفرع</th><th>الحالة</th><th>التاريخ</th><th>تحديث الحالة</th></tr></thead>
      <tbody>
        <tr v-for="o in orders" :key="o.id">
          <td style="font-weight:700;color:var(--primary)">{{ o.order_number }}</td>
          <td>{{ o.product?.name_ar }}</td>
          <td>{{ o.branch?.name_ar||'—' }}</td>
          <td><span class="badge" :class="statusClass(o.status)">{{ statusLabel(o.status) }}</span></td>
          <td style="font-size:0.83rem;color:var(--text-muted)">{{ fmt(o.created_at) }}</td>
          <td>
            <select class="form-control" style="padding:6px 10px;font-size:0.85rem;width:140px" :value="o.status" @change="updateStatus(o.id, $event.target.value)">
              <option value="pending">معلق (Pending)</option>
              <option value="processing">قيد التنفيذ</option>
              <option value="completed">مكتمل</option>
              <option value="cancelled">ملغى</option>
            </select>
          </td>
        </tr>
        <tr v-if="!orders.length"><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px">لا توجد طلبات</td></tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const orders = ref([])
const loading = ref(true)
const statusLabel = s => ({ pending:'معلق', processing:'قيد التنفيذ', completed:'مكتمل', cancelled:'ملغى' }[s]||s)
const statusClass = s => ({ pending:'badge-accent', processing:'badge-primary', completed:'badge-success', cancelled:'badge-danger' }[s])
const fmt = d => d?new Date(d).toLocaleDateString('ar-YE'):''
async function load() { loading.value=true; const {data}=await api.get('/admin/orders'); orders.value=data.data||[]; loading.value=false }
async function updateStatus(id, status) { await api.patch(`/admin/orders/${id}/status`,{status}); load() }
onMounted(load)
</script>
