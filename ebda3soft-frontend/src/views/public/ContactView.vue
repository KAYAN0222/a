<template>
  <div>
    <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:50px 0;color:white;text-align:center">
      <h1 style="font-size:2rem;font-weight:800">تواصل معنا</h1>
    </div>
    <section class="section">
      <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:50px;align-items:start">
          <div>
            <h2 style="font-size:1.3rem;font-weight:800;color:var(--primary);margin-bottom:24px">أرسل لنا رسالة</h2>
            <div v-if="success" class="alert alert-success">{{ success }}</div>
            <div v-if="error" class="alert alert-danger">{{ error }}</div>
            <form @submit.prevent="submit">
              <div class="form-group">
                <label class="form-label">الاسم الكامل *</label>
                <input v-model="form.full_name" class="form-control" required placeholder="أدخل اسمك الكامل" />
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                  <label class="form-label">رقم الجوال</label>
                  <input v-model="form.phone" class="form-control" placeholder="+966..." />
                </div>
                <div class="form-group">
                  <label class="form-label">البريد الإلكتروني (Email)</label>
                  <input v-model="form.email" type="email" class="form-control" placeholder="example@mail.com" />
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">الموضوع</label>
                <input v-model="form.subject" class="form-control" placeholder="موضوع الرسالة" />
              </div>
              <div class="form-group">
                <label class="form-label">الرسالة *</label>
                <textarea v-model="form.message" class="form-control" rows="5" required placeholder="اكتب رسالتك هنا..."></textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-lg" :disabled="submitting">
                {{ submitting ? 'جارٍ الإرسال...' : '📤 إرسال الرسالة' }}
              </button>
            </form>
          </div>

          <div>
            <h2 style="font-size:1.3rem;font-weight:800;color:var(--primary);margin-bottom:24px">معلومات التواصل</h2>
            <div class="card card-body" style="margin-bottom:16px;display:flex;gap:16px;align-items:flex-start">
              <span style="font-size:1.5rem">📍</span>
              <div><strong>العنوان</strong><br><span style="color:var(--text-muted);font-size:0.9rem">الرياض، المملكة العربية السعودية — فروع ووكلاء في أنحاء المملكة</span></div>
            </div>
            <div class="card card-body" style="margin-bottom:16px;display:flex;gap:16px;align-items:center">
              <span style="font-size:1.5rem">📞</span>
              <div><strong>الهاتف / واتساب</strong><br><span style="color:var(--text-muted);font-size:0.9rem">0571870415 &nbsp;| <a href="https://wa.me/966571870415" target="_blank" style="color:var(--primary)">فتح واتساب</a></span></div>
            </div>
            <div class="card card-body" style="margin-bottom:16px;display:flex;gap:16px;align-items:center">
              <span style="font-size:1.5rem">✉️</span>
              <div><strong>البريد الإلكتروني (Email)</strong><br><span style="color:var(--text-muted);font-size:0.9rem">info@softpro.com</span></div>
            </div>
            <div class="card card-body" style="display:flex;gap:16px;align-items:flex-start">
              <span style="font-size:1.5rem">🕐</span>
              <div><strong>ساعات العمل</strong><br><span style="color:var(--text-muted);font-size:0.9rem">8 ص - 12 ظ | 4 م - 8 م (الخميس: 8 ص - 12 ظ)</span></div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import api from '../../api'
const form = ref({ full_name: '', phone: '', email: '', subject: '', message: '' })
const submitting = ref(false)
const success = ref('')
const error = ref('')
async function submit() {
  submitting.value = true; success.value = ''; error.value = ''
  try {
    const { data } = await api.post('/contact', form.value)
    success.value = data.message
    form.value = { full_name: '', phone: '', email: '', subject: '', message: '' }
  } catch (e) { error.value = e.response?.data?.message || 'حدث خطأ، يرجى المحاولة مجدداً' }
  finally { submitting.value = false }
}
</script>
