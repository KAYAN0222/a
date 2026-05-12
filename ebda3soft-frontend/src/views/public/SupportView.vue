<template>
  <div>
    <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:50px 0;color:white;text-align:center">
      <h1 style="font-size:2rem;font-weight:800">الدعم الفني — Support Tickets</h1>
      <p style="color:rgba(255,255,255,0.8);margin-top:10px">فتح تذكرة دعم أو متابعة تذكرة قائمة</p>
    </div>

    <section class="section">
      <div class="container">
        <!-- Tabs -->
        <div style="display:flex;gap:4px;background:var(--bg);border-radius:var(--radius);padding:4px;max-width:400px;margin:0 auto 40px">
          <button v-for="t in tabs" :key="t.val" class="btn" :class="tab===t.val?'btn-primary':''" style="flex:1;justify-content:center;border-radius:8px;padding:10px"  @click="tab=t.val">{{ t.label }}</button>
        </div>

        <!-- فتح تذكرة جديدة -->
        <div v-if="tab==='new'" style="max-width:680px;margin:0 auto">
          <div v-if="success" class="alert alert-success" style="text-align:center">
            <strong>✅ تم فتح التذكرة بنجاح!</strong><br>
            رقم تذكرتك: <strong style="font-size:1.2rem;color:var(--primary)">{{ ticketNumber }}</strong><br>
            <small>احتفظ بهذا الرقم لمتابعة حالة التذكرة</small>
          </div>
          <div v-else class="card card-body">
            <div v-if="error" class="alert alert-danger">{{ error }}</div>
            <form @submit.prevent="submit" enctype="multipart/form-data">
              <div class="form-group">
                <label class="form-label">النظام المتعلق بالمشكلة</label>
                <select v-model="form.product_id" class="form-control">
                  <option value="">-- اختر النظام (اختياري) --</option>
                  <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name_ar }}</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">عنوان المشكلة / الاستفسار *</label>
                <input v-model="form.subject" class="form-control" required placeholder="وصف مختصر للمشكلة" />
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                  <label class="form-label">اسمك *</label>
                  <input v-model="form.requester_name" class="form-control" required />
                </div>
                <div class="form-group">
                  <label class="form-label">رقم الجوال</label>
                  <input v-model="form.requester_phone" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">الأولوية (Priority)</label>
                <select v-model="form.priority" class="form-control">
                  <option value="low">منخفضة (Low)</option>
                  <option value="medium">متوسطة (Medium)</option>
                  <option value="high">عالية (High)</option>
                  <option value="critical">حرجة (Critical)</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">وصف المشكلة التفصيلي *</label>
                <textarea v-model="form.description" class="form-control" rows="5" required placeholder="اشرح المشكلة بالتفصيل..."></textarea>
              </div>
              <div class="form-group">
                <label class="form-label">مرفق (صورة/ملف PDF)</label>
                <input type="file" accept=".pdf,.jpg,.jpeg,.png" @change="e=>file=e.target.files[0]" class="form-control" />
              </div>
              <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center" :disabled="submitting">
                {{ submitting ? 'جارٍ الإرسال...' : '🎫 فتح تذكرة دعم' }}
              </button>
            </form>
          </div>
        </div>

        <!-- متابعة تذكرة -->
        <div v-if="tab==='track'" style="max-width:560px;margin:0 auto">
          <div class="card card-body">
            <h3 style="font-weight:700;color:var(--primary);margin-bottom:20px">أدخل رقم تذكرتك</h3>
            <div style="display:flex;gap:12px">
              <input v-model="trackNum" class="form-control" placeholder="مثال: TKT-XXXXXXX" style="flex:1" />
              <button class="btn btn-primary" @click="trackTicket" :disabled="tracking">بحث</button>
            </div>
          </div>

          <div v-if="tracked" class="card card-body" style="margin-top:20px">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px">
              <div>
                <h3 style="font-weight:700;color:var(--primary)">{{ tracked.subject }}</h3>
                <span style="font-size:0.85rem;color:var(--text-muted)">{{ tracked.ticket_number }}</span>
              </div>
              <span class="badge" :class="statusClass(tracked.status)">{{ statusLabel(tracked.status) }}</span>
            </div>
            <p style="color:var(--text-muted);font-size:0.9rem;line-height:1.8;margin-bottom:20px">{{ tracked.description }}</p>

            <div v-if="tracked.replies?.length" style="border-top:1px solid var(--border);padding-top:16px">
              <h4 style="font-weight:700;margin-bottom:16px;color:var(--primary)">الردود</h4>
              <div v-for="r in tracked.replies" :key="r.id"
                :style="`margin-bottom:12px;padding:14px;border-radius:var(--radius);${r.is_staff?'background:rgba(26,58,107,0.06);border-right:3px solid var(--primary)':'background:var(--bg);border-right:3px solid var(--accent)'}`">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                  <strong style="font-size:0.87rem">{{ r.is_staff ? '👨‍💼 فريق الدعم' : '👤 ' + r.author_name }}</strong>
                  <span style="font-size:0.78rem;color:var(--text-muted)">{{ fmt(r.created_at) }}</span>
                </div>
                <p style="font-size:0.88rem;color:var(--text);line-height:1.7">{{ r.message }}</p>
              </div>
            </div>

            <!-- رد العميل -->
            <div style="margin-top:16px;border-top:1px solid var(--border);padding-top:16px">
              <textarea v-model="replyMsg" class="form-control" rows="3" placeholder="أضف رداً على تذكرتك..."></textarea>
              <button class="btn btn-primary btn-sm" style="margin-top:10px" @click="sendReply" :disabled="!replyMsg.trim()">📤 إرسال الرد</button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'

const tab = ref('new')
const tabs = [{ val: 'new', label: '🎫 تذكرة جديدة' }, { val: 'track', label: '🔍 متابعة تذكرة' }]
const products = ref([])
const file = ref(null)
const submitting = ref(false)
const success = ref(false)
const error = ref('')
const ticketNumber = ref('')
const trackNum = ref('')
const tracked = ref(null)
const tracking = ref(false)
const replyMsg = ref('')

const form = ref({ product_id: '', subject: '', requester_name: '', requester_phone: '', priority: 'medium', description: '' })

const statusLabel = s => ({ open:'مفتوحة', in_progress:'قيد المعالجة', waiting:'في الانتظار', resolved:'محلولة', closed:'مغلقة' }[s]||s)
const statusClass = s => ({ open:'badge-primary', in_progress:'badge-accent', waiting:'badge-accent', resolved:'badge-success', closed:'badge-danger' }[s])
const fmt = d => d ? new Date(d).toLocaleDateString('ar-YE') : ''

async function submit() {
  submitting.value = true; error.value = ''
  const fd = new FormData()
  Object.entries(form.value).forEach(([k,v]) => { if (v !== '') fd.append(k, v) })
  if (file.value) fd.append('attachment', file.value)
  try {
    const { data } = await api.post('/tickets', fd, { headers: { 'Content-Type': 'multipart/form-data' } })
    ticketNumber.value = data.ticket_number
    success.value = true
  } catch (e) { error.value = e.response?.data?.message || 'حدث خطأ' }
  finally { submitting.value = false }
}

async function trackTicket() {
  if (!trackNum.value.trim()) return
  tracking.value = true
  try {
    const { data } = await api.get(`/tickets/track/${trackNum.value.trim()}`)
    tracked.value = data
  } catch { alert('لم يتم العثور على التذكرة. تأكد من الرقم.') }
  finally { tracking.value = false }
}

async function sendReply() {
  if (!tracked.value) return
  await api.post(`/tickets/${tracked.value.id}/reply`, { message: replyMsg.value })
  replyMsg.value = ''
  trackTicket()
}

onMounted(async () => {
  const { data } = await api.get('/products')
  products.value = data
})
</script>
