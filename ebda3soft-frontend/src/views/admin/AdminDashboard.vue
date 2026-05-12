<template>
  <div>
    <!-- Stats Row -->
    <div class="grid grid-4" style="margin-bottom:28px">
      <div v-for="s in statCards" :key="s.label" class="stat-card">
        <div class="stat-icon" :class="s.color">{{ s.icon }}</div>
        <div>
          <div class="stat-num">{{ stats[s.key] ?? '—' }}</div>
          <div class="stat-label">{{ s.label }}</div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:24px">
      <!-- Line Chart: Orders & Messages -->
      <div class="card card-body">
        <h3 style="font-size:1rem;font-weight:700;color:var(--primary);margin-bottom:20px">📈 الطلبات والرسائل (آخر 6 أشهر)</h3>
        <canvas ref="lineChart" height="180"></canvas>
      </div>

      <!-- Doughnut: Orders by Status -->
      <div class="card card-body">
        <h3 style="font-size:1rem;font-weight:700;color:var(--primary);margin-bottom:20px">🔵 توزيع الطلبات</h3>
        <canvas ref="donutChart" height="180"></canvas>
      </div>
    </div>

    <!-- Top Products + Recent -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
      <!-- Top Products -->
      <div class="table-container">
        <div class="table-header"><h3 style="font-size:1rem;font-weight:700;color:var(--primary)">🏆 أكثر الأنظمة طلباً</h3></div>
        <table class="table">
          <thead><tr><th>النظام</th><th>عدد الطلبات</th></tr></thead>
          <tbody>
            <tr v-for="p in topProducts" :key="p.id">
              <td>{{ p.name_ar }}</td>
              <td><span class="badge badge-primary">{{ p.orders_count }}</span></td>
            </tr>
            <tr v-if="!topProducts.length"><td colspan="2" style="text-align:center;color:var(--text-muted)">لا توجد بيانات</td></tr>
          </tbody>
        </table>
      </div>

      <!-- Recent Orders -->
      <div class="table-container">
        <div class="table-header">
          <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">آخر الطلبات</h3>
          <router-link to="/admin/orders" class="btn btn-outline btn-sm">عرض الكل</router-link>
        </div>
        <table class="table">
          <thead><tr><th>رقم الطلب</th><th>النظام</th><th>الحالة</th></tr></thead>
          <tbody>
            <tr v-for="o in recentOrders" :key="o.id">
              <td style="font-weight:600;color:var(--primary)">{{ o.order_number }}</td>
              <td>{{ o.product?.name_ar }}</td>
              <td><span class="badge" :class="statusClass(o.status)">{{ statusLabel(o.status) }}</span></td>
            </tr>
            <tr v-if="!recentOrders.length"><td colspan="3" style="text-align:center;color:var(--text-muted)">لا توجد طلبات</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Chart, registerables } from 'chart.js'
import api from '../../api'

Chart.register(...registerables)

const stats = ref({})
const recentOrders = ref([])
const recentMessages = ref([])
const topProducts = ref([])
const lineChart = ref(null)
const donutChart = ref(null)

const statCards = [
  { key: 'active_products', icon: '🖥️', label: 'الأنظمة النشطة', color: 'blue' },
  { key: 'pending_orders',  icon: '📋', label: 'طلبات معلقة (Pending)', color: 'gold' },
  { key: 'open_tickets',    icon: '🎫', label: 'تذاكر مفتوحة', color: 'green' },
  { key: 'unread_messages', icon: '✉️', label: 'رسائل غير مقروءة', color: 'red' },
]

const statusLabel = s => ({ pending:'معلق', processing:'قيد التنفيذ', completed:'مكتمل', cancelled:'ملغى' }[s]||s)
const statusClass = s => ({ pending:'badge-accent', processing:'badge-primary', completed:'badge-success', cancelled:'badge-danger' }[s])

const monthNames = { '01':'يناير','02':'فبراير','03':'مارس','04':'أبريل','05':'مايو','06':'يونيو','07':'يوليو','08':'أغسطس','09':'سبتمبر','10':'أكتوبر','11':'نوفمبر','12':'ديسمبر' }
function fmtMonth(m) { const [, mo] = m.split('-'); return monthNames[mo] || m }

onMounted(async () => {
  // Dashboard stats
  const { data: dash } = await api.get('/admin/dashboard')
  stats.value = dash.stats
  recentOrders.value = dash.recent_orders || []

  // Analytics charts
  try {
    const { data: ana } = await api.get('/admin/analytics')
    topProducts.value = ana.top_products || []

    const labels = (ana.months || []).map(fmtMonth)

    // Line Chart
    new Chart(lineChart.value, {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'الطلبات',
            data: ana.orders_per_month || [],
            borderColor: '#1a3a6b',
            backgroundColor: 'rgba(26,58,107,0.08)',
            tension: 0.4, fill: true, pointRadius: 5,
          },
          {
            label: 'الرسائل',
            data: ana.messages_per_month || [],
            borderColor: '#e8a020',
            backgroundColor: 'rgba(232,160,32,0.08)',
            tension: 0.4, fill: true, pointRadius: 5,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
      },
    })

    // Donut Chart
    const statusData = ana.orders_by_status || {}
    const statusColors = { pending:'#e8a020', processing:'#2d5aa8', completed:'#22c55e', cancelled:'#ef4444' }
    const statusAr = { pending:'معلق', processing:'قيد التنفيذ', completed:'مكتمل', cancelled:'ملغى' }
    new Chart(donutChart.value, {
      type: 'doughnut',
      data: {
        labels: Object.keys(statusData).map(k => statusAr[k] || k),
        datasets: [{
          data: Object.values(statusData),
          backgroundColor: Object.keys(statusData).map(k => statusColors[k] || '#64748b'),
        }],
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        cutout: '65%',
      },
    })
  } catch {}
})
</script>
