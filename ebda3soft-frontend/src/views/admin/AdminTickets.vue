<template>
  <div class="table-container">
    <div class="table-header">
      <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">تذاكر الدعم الفني (Support Tickets)</h3>
      <div style="display:flex;gap:8px">
        <select v-model="filterStatus" @change="load" class="form-control" style="padding:8px 14px;font-size:0.85rem;width:auto">
          <option value="">جميع الحالات</option>
          <option value="open">مفتوحة</option>
          <option value="in_progress">قيد المعالجة</option>
          <option value="resolved">محلولة</option>
          <option value="closed">مغلقة</option>
        </select>
        <select v-model="filterPriority" @change="load" class="form-control" style="padding:8px 14px;font-size:0.85rem;width:auto">
          <option value="">جميع الأولويات</option>
          <option value="low">منخفضة</option>
          <option value="medium">متوسطة</option>
          <option value="high">عالية</option>
          <option value="critical">حرجة</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
    <table v-else class="table">
      <thead>
        <tr>
          <th>رقم التذكرة</th><th>الموضوع</th><th>المُرسِل</th>
          <th>النظام</th><th>الأولوية</th><th>الحالة</th><th>التاريخ</th><th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="t in tickets" :key="t.id">
          <td style="font-weight:700;color:var(--primary);font-size:0.85rem">{{ t.ticket_number }}</td>
          <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ t.subject }}</td>
          <td>{{ t.requester_name }}<br><small style="color:var(--text-muted)">{{ t.requester_phone }}</small></td>
          <td style="font-size:0.85rem">{{ t.product?.name_ar || '—' }}</td>
          <td><span class="badge" :class="prioClass(t.priority)">{{ prioLabel(t.priority) }}</span></td>
          <td><span class="badge" :class="statusClass(t.status)">{{ statusLabel(t.status) }}</span></td>
          <td style="font-size:0.82rem;color:var(--text-muted)">{{ fmt(t.created_at) }}</td>
          <td>
            <button class="btn btn-outline btn-sm" @click="openTicket(t)">عرض</button>
          </td>
        </tr>
        <tr v-if="!tickets.length"><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">لا توجد تذاكر</td></tr>
      </tbody>
    </table>

    <!-- Modal عرض التذكرة -->
    <div v-if="selected" class="modal-overlay" @click.self="selected=null">
      <div class="modal" style="max-width:620px">
        <div class="modal-header">
          <div>
            <h3 class="modal-title">{{ selected.subject }}</h3>
            <span style="font-size:0.82rem;color:var(--text-muted)">{{ selected.ticket_number }}</span>
          </div>
          <button @click="selected=null" style="background:none;border:none;font-size:1.4rem;cursor:pointer">✕</button>
        </div>
        <div class="modal-body" style="max-height:60vh;overflow-y:auto">
          <!-- معلومات -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding:14px;background:var(--bg);border-radius:var(--radius)">
            <div><small style="color:var(--text-muted)">المُرسِل</small><br><strong>{{ selected.requester_name }}</strong></div>
            <div><small style="color:var(--text-muted)">الجوال</small><br>{{ selected.requester_phone || '—' }}</div>
            <div><span class="badge" :class="prioClass(selected.priority)">{{ prioLabel(selected.priority) }}</span></div>
            <div>
              <select :value="selected.status" @change="updateStatus(selected.id,$event.target.value)" class="form-control" style="padding:6px 10px;font-size:0.82rem">
                <option value="open">مفتوحة</option>
                <option value="in_progress">قيد المعالجة</option>
                <option value="waiting">في الانتظار</option>
                <option value="resolved">محلولة</option>
                <option value="closed">مغلقة</option>
              </select>
            </div>
          </div>
          <!-- الوصف -->
          <div style="padding:14px;background:var(--bg);border-radius:var(--radius);margin-bottom:16px">
            <strong style="font-size:0.88rem;color:var(--primary)">وصف المشكلة:</strong>
            <p style="margin-top:8px;font-size:0.9rem;line-height:1.8;color:var(--text)">{{ selected.description }}</p>
          </div>
          <!-- الردود -->
          <div v-if="selected.replies?.length">
            <h4 style="font-weight:700;color:var(--primary);margin-bottom:12px">الردود ({{ selected.replies.length }})</h4>
            <div v-for="r in selected.replies" :key="r.id"
              :style="`margin-bottom:10px;padding:12px;border-radius:var(--radius);${r.is_staff?'background:rgba(26,58,107,0.06);border-right:3px solid var(--primary)':'background:var(--bg);border-right:3px solid var(--accent)'}`">
              <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                <strong style="font-size:0.85rem">{{ r.is_staff ? '👨‍💼 ' + r.author_name : '👤 ' + r.author_name }}</strong>
                <small style="color:var(--text-muted)">{{ fmt(r.created_at) }}</small>
              </div>
              <p style="font-size:0.88rem;color:var(--text)">{{ r.message }}</p>
            </div>
          </div>
          <!-- رد الموظف -->
          <div style="margin-top:16px;border-top:1px solid var(--border);padding-top:16px">
            <label class="form-label">رد الدعم الفني</label>
            <textarea v-model="staffReply" class="form-control" rows="3" placeholder="اكتب ردك..."></textarea>
            <button class="btn btn-primary btn-sm" style="margin-top:10px" @click="sendStaffReply" :disabled="!staffReply.trim()">📤 إرسال الرد</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'

const tickets = ref([])
const loading = ref(true)
const selected = ref(null)
const filterStatus = ref('')
const filterPriority = ref('')
const staffReply = ref('')

const statusLabel = s => ({ open:'مفتوحة', in_progress:'قيد المعالجة', waiting:'في الانتظار', resolved:'محلولة', closed:'مغلقة' }[s]||s)
const statusClass = s => ({ open:'badge-primary', in_progress:'badge-accent', waiting:'badge-accent', resolved:'badge-success', closed:'badge-danger' }[s])
const prioLabel = p => ({ low:'منخفضة', medium:'متوسطة', high:'عالية', critical:'حرجة' }[p]||p)
const prioClass = p => ({ low:'badge-primary', medium:'badge-accent', high:'badge-danger', critical:'badge-danger' }[p])
const fmt = d => d ? new Date(d).toLocaleDateString('ar-YE') : ''

async function load() {
  loading.value = true
  const params = {}
  if (filterStatus.value)   params.status   = filterStatus.value
  if (filterPriority.value) params.priority = filterPriority.value
  const { data } = await api.get('/tickets', { params })
  tickets.value = data.data || []
  loading.value = false
}

async function openTicket(t) {
  const { data } = await api.get(`/tickets/${t.id}`)
  selected.value = data
  staffReply.value = ''
}

async function updateStatus(id, status) {
  await api.patch(`/tickets/${id}/status`, { status })
  if (selected.value) selected.value.status = status
  load()
}

async function sendStaffReply() {
  if (!selected.value || !staffReply.value.trim()) return
  await api.post(`/tickets/${selected.value.id}/reply`, { message: staffReply.value })
  staffReply.value = ''
  openTicket(selected.value)
  load()
}

onMounted(load)
</script>
