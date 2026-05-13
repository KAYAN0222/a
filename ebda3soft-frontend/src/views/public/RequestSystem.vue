<template>
  <div>
    <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:50px 0;color:white;text-align:center">
      <h1 style="font-size:2rem;font-weight:800">طلب نظام</h1>
      <p style="color:rgba(255,255,255,0.8);margin-top:10px">أخبرنا باحتياجاتك وسيتواصل معك فريقنا</p>
    </div>
    <section class="section">
      <div class="container" style="max-width:680px">
        <div v-if="success" class="alert alert-success" style="font-size:1rem;text-align:center">{{ success }}</div>
        <div v-else class="card card-body">
          <div v-if="error" class="alert alert-danger">{{ error }}</div>
          <form @submit.prevent="submit">
            <div class="form-group">
              <label class="form-label">النظام المطلوب *</label>
              <select v-model="form.product_id" class="form-control" required>
                <option value="">-- اختر النظام --</option>
                <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name_ar }}</option>
              </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
              <div class="form-group">
                <label class="form-label">الاسم الكامل *</label>
                <input v-model="form.full_name" class="form-control" required placeholder="اسمك الكامل" />
              </div>
              <div class="form-group">
                <label class="form-label">رقم الجوال *</label>
                <input v-model="form.phone" class="form-control" required placeholder="+966..." />
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">اسم الشركة / المؤسسة</label>
              <input v-model="form.company_name" class="form-control" placeholder="اختياري" />
            </div>
            <div class="form-group">
              <label class="form-label">البريد الإلكتروني (Email)</label>
              <input v-model="form.email" type="email" class="form-control" placeholder="اختياري" />
            </div>
            <div class="form-group">
              <label class="form-label">ملاحظات إضافية</label>
              <textarea v-model="form.notes" class="form-control" rows="3" placeholder="متطلبات خاصة أو استفسارات..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center" :disabled="submitting">
              {{ submitting ? 'جارٍ الإرسال...' : '📋 إرسال الطلب' }}
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../api'
const route = useRoute()
const products = ref([])
const form = ref({ product_id: route.query.product || '', full_name: '', phone: '', company_name: '', email: '', notes: '' })
const submitting = ref(false)
const success = ref('')
const error = ref('')
async function submit() {
  submitting.value = true; error.value = ''
  try {
    const { data } = await api.post('/request-system', form.value)
    success.value = data.message
  } catch (e) { error.value = e.response?.data?.message || 'حدث خطأ' }
  finally { submitting.value = false }
}
onMounted(async () => { const { data } = await api.get('/products'); products.value = data })
</script>
