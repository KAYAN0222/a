<template>
  <div>
    <div class="table-container" style="margin-bottom:24px">
      <div class="table-header">
        <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">الفروع والوكلاء</h3>
        <button class="btn btn-primary btn-sm" @click="openModal()">+ إضافة فرع/وكيل</button>
      </div>
      <table class="table">
        <thead><tr><th>الاسم</th><th>النوع</th><th>المحافظة</th><th>الهاتف</th><th>حذف</th></tr></thead>
        <tbody>
          <tr v-for="b in branches" :key="b.id">
            <td><strong>{{ b.name_ar }}</strong></td>
            <td><span class="badge" :class="b.type==='branch'?'badge-primary':'badge-accent'">{{ b.type==='branch'?'فرع':'وكيل' }}</span></td>
            <td>{{ b.governorate }}</td>
            <td style="font-size:0.87rem">{{ b.phone||'—' }}</td>
            <td><button class="btn btn-sm" style="background:var(--danger);color:white" @click="del(b.id)">حذف</button></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="showModal" class="modal-overlay" @click.self="showModal=false">
      <div class="modal">
        <div class="modal-header">
          <h3 class="modal-title">إضافة فرع / وكيل</h3>
          <button @click="showModal=false" style="background:none;border:none;font-size:1.4rem;cursor:pointer">✕</button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label class="form-label">الاسم *</label><input v-model="form.name_ar" class="form-control" required /></div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group">
              <label class="form-label">النوع (Type)</label>
              <select v-model="form.type" class="form-control"><option value="branch">فرع (Branch)</option><option value="agent">وكيل (Agent)</option></select>
            </div>
            <div class="form-group"><label class="form-label">المحافظة</label><input v-model="form.governorate" class="form-control" /></div>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group"><label class="form-label">المدينة</label><input v-model="form.city" class="form-control" /></div>
            <div class="form-group"><label class="form-label">الهاتف</label><input v-model="form.phone" class="form-control" /></div>
          </div>
          <div class="form-group"><label class="form-label">العنوان</label><input v-model="form.address" class="form-control" /></div>
          <div class="form-group"><label class="form-label">المسؤول (Manager)</label><input v-model="form.manager" class="form-control" /></div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showModal=false">إلغاء</button>
          <button class="btn btn-primary" @click="save">حفظ</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const branches = ref([])
const showModal = ref(false)
const emptyForm = () => ({ name_ar:'', type:'branch', governorate:'', city:'', phone:'', address:'', manager:'' })
const form = ref(emptyForm())
async function load() { const {data}=await api.get('/branches'); branches.value=data }
function openModal() { form.value=emptyForm(); showModal.value=true }
async function save() { await api.post('/branches',form.value); showModal.value=false; load() }
async function del(id) { if(!confirm('حذف؟')) return; await api.delete(`/branches/${id}`); load() }
onMounted(load)
</script>
