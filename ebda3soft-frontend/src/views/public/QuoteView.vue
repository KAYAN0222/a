<template>
  <div>
    <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:50px 0;color:white;text-align:center">
      <h1 style="font-size:2rem;font-weight:800">طلب عرض سعر (Request a Quote)</h1>
      <p style="color:rgba(255,255,255,0.8);margin-top:10px">أخبرنا بمتطلباتك وسنعود إليك بأفضل عرض خلال 24 ساعة</p>
    </div>
    <section class="section">
      <div class="container" style="max-width:780px">
        <div v-if="success" class="card card-body" style="text-align:center;padding:50px">
          <div style="font-size:4rem;margin-bottom:20px">✅</div>
          <h2 style="color:var(--primary);margin-bottom:12px">تم استلام طلبك بنجاح!</h2>
          <p style="color:var(--text-muted)">رقم الطلب: <strong style="color:var(--primary)">{{ quoteNum }}</strong></p>
          <p style="color:var(--text-muted);margin-top:8px">سيتواصل معك فريقنا خلال 24 ساعة عمل.</p>
          <button class="btn btn-outline" style="margin-top:24px" @click="success=false">تقديم طلب جديد</button>
        </div>
        <div v-else class="card card-body">
          <div v-if="error" class="alert alert-danger">{{ error }}</div>
          <form @submit.prevent="submit">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
              <div class="form-group">
                <label class="form-label">اسم الشركة / المؤسسة *</label>
                <input v-model="form.company_name" class="form-control" required />
              </div>
              <div class="form-group">
                <label class="form-label">اسم المسؤول *</label>
                <input v-model="form.contact_name" class="form-control" required />
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
              <div class="form-group">
                <label class="form-label">رقم الجوال *</label>
                <input v-model="form.phone" class="form-control" required />
              </div>
              <div class="form-group">
                <label class="form-label">البريد الإلكتروني (Email)</label>
                <input v-model="form.email" type="email" class="form-control" />
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
              <div class="form-group">
                <label class="form-label">المحافظة / المدينة</label>
                <input v-model="form.city" class="form-control" />
              </div>
              <div class="form-group">
                <label class="form-label">عدد الموظفين (تقريباً)</label>
                <input v-model.number="form.employees_count" type="number" min="1" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">النظام المطلوب</label>
              <select v-model="form.product_id" class="form-control">
                <option value="">-- اختر النظام --</option>
                <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name_ar }}</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">المتطلبات التفصيلية *</label>
              <textarea v-model="form.requirements" class="form-control" rows="5" required
                placeholder="اشرح متطلباتك بالتفصيل: عدد المستخدمين، الوحدات المطلوبة، أي تخصيصات..."></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">مرفقات (وثائق، مواصفات، شاشات مرجعية)</label>
              <input type="file" accept=".pdf,.doc,.docx,.jpg,.png" @change="e=>file=e.target.files[0]" class="form-control" />
              <small style="color:var(--text-muted)">الأنواع المقبولة: PDF، Word، صور — الحد الأقصى: 10MB</small>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center" :disabled="submitting">
              {{ submitting ? 'جارٍ الإرسال...' : '📨 إرسال طلب العرض' }}
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const products = ref([])
const file = ref(null)
const submitting = ref(false)
const success = ref(false)
const error = ref('')
const quoteNum = ref('')
const form = ref({ company_name:'', contact_name:'', phone:'', email:'', city:'', employees_count:'', product_id:'', requirements:'' })
async function submit() {
  submitting.value = true; error.value = ''
  const fd = new FormData()
  Object.entries(form.value).forEach(([k,v]) => { if (v !== '') fd.append(k, v) })
  if (file.value) fd.append('attachment', file.value)
  try {
    const { data } = await api.post('/quotes', fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    quoteNum.value = data.quote_number; success.value = true
  } catch (e) { error.value = e.response?.data?.message || 'حدث خطأ' }
  finally { submitting.value = false }
}
onMounted(async () => { const {data} = await api.get('/products'); products.value = data })
</script>
