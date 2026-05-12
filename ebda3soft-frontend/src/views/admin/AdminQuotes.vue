<template>
  <div class="table-container">
    <div class="table-header">
      <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">طلبات عروض الأسعار (Quotes)</h3>
      <span class="badge badge-accent">{{ quotes.length }} طلب</span>
    </div>
    <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
    <table v-else class="table">
      <thead><tr><th>رقم الطلب</th><th>الشركة</th><th>المسؤول</th><th>النظام</th><th>الحالة</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
      <tbody>
        <tr v-for="q in quotes" :key="q.id">
          <td style="font-weight:700;color:var(--primary);font-size:0.85rem">{{ q.quote_number }}</td>
          <td>{{ q.company_name }}</td>
          <td style="font-size:0.87rem">{{ q.contact_name }}<br><small style="color:var(--text-muted)">{{ q.phone }}</small></td>
          <td style="font-size:0.85rem">{{ q.product?.name_ar || '—' }}</td>
          <td><span class="badge" :class="sClass(q.status)">{{ sLabel(q.status) }}</span></td>
          <td style="font-size:0.82rem;color:var(--text-muted)">{{ fmt(q.created_at) }}</td>
          <td><button class="btn btn-outline btn-sm" @click="sel=q;form={status:q.status,admin_notes:q.admin_notes||'',quoted_price:q.quoted_price||''}">تفاصيل</button></td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div v-if="sel" class="modal-overlay" @click.self="sel=null">
      <div class="modal">
        <div class="modal-header">
          <h3 class="modal-title">{{ sel.quote_number }} — {{ sel.company_name }}</h3>
          <button @click="sel=null" style="background:none;border:none;font-size:1.4rem;cursor:pointer">✕</button>
        </div>
        <div class="modal-body">
          <div style="background:var(--bg);padding:14px;border-radius:var(--radius);margin-bottom:16px;font-size:0.88rem;line-height:1.8">
            <strong>المتطلبات:</strong><br>{{ sel.requirements }}
          </div>
          <div class="form-group">
            <label class="form-label">الحالة (Status)</label>
            <select v-model="form.status" class="form-control">
              <option value="new">جديد (New)</option>
              <option value="reviewing">قيد المراجعة (Reviewing)</option>
              <option value="quoted">تم تقديم العرض (Quoted)</option>
              <option value="accepted">مقبول (Accepted)</option>
              <option value="rejected">مرفوض (Rejected)</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">السعر المقترح (ر.ي)</label>
            <input v-model.number="form.quoted_price" type="number" class="form-control" placeholder="0" />
          </div>
          <div class="form-group">
            <label class="form-label">ملاحظات الإدارة</label>
            <textarea v-model="form.admin_notes" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="sel=null">إغلاق</button>
          <button class="btn btn-primary" @click="save">💾 حفظ</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const quotes = ref([])
const loading = ref(true)
const sel = ref(null)
const form = ref({ status:'', admin_notes:'', quoted_price:'' })
const sLabel = s => ({new:'جديد',reviewing:'مراجعة',quoted:'عرض مقدم',accepted:'مقبول',rejected:'مرفوض'}[s]||s)
const sClass = s => ({new:'badge-primary',reviewing:'badge-accent',quoted:'badge-accent',accepted:'badge-success',rejected:'badge-danger'}[s])
const fmt = d => d?new Date(d).toLocaleDateString('ar-YE'):''
async function load() { loading.value=true; const {data}=await api.get('/quotes'); quotes.value=data.data||[]; loading.value=false }
async function save() { await api.patch(`/quotes/${sel.value.id}`,form.value); sel.value=null; load() }
onMounted(load)
</script>
