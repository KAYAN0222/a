<template>
  <div class="card card-body" style="max-width:700px">
    <h3 style="font-size:1.1rem;font-weight:800;color:var(--primary);margin-bottom:24px">إعدادات الموقع (Settings)</h3>
    <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
    <div v-else>
      <div v-if="success" class="alert alert-success">{{ success }}</div>
      <div v-for="(val, key) in settings" :key="key" class="form-group">
        <label class="form-label">{{ labels[key] || key }}</label>
        <input v-model="settings[key]" class="form-control" />
      </div>
      <button class="btn btn-primary btn-lg" @click="save" :disabled="saving">{{ saving?'جارٍ الحفظ...':'💾 حفظ الإعدادات' }}</button>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const settings = ref({})
const loading = ref(true)
const saving = ref(false)
const success = ref('')
const labels = {
  site_name_ar:'اسم الموقع (عربي)',site_name_en:'اسم الموقع (English)',
  site_phone:'الهاتف',site_email:'البريد الإلكتروني (Email)',
  site_address_ar:'العنوان',whatsapp:'واتساب (WhatsApp)',
  facebook:'فيسبوك (Facebook)',years_experience:'سنوات الخبرة',
  clients_count:'عدد العملاء',branches_count:'عدد الفروع',trainees_count:'عدد المتدربين',
}
async function load() { const {data}=await api.get('/admin/settings'); settings.value=data; loading.value=false }
async function save() {
  saving.value=true; success.value=''
  try { await api.post('/admin/settings',settings.value); success.value='تم حفظ الإعدادات بنجاح' }
  finally { saving.value=false }
}
onMounted(load)
</script>
