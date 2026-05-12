<template>
  <div>
    <div class="table-container">
      <div class="table-header">
        <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">الأنظمة والمنتجات</h3>
        <button class="btn btn-primary btn-sm" @click="openModal()">+ إضافة نظام جديد</button>
      </div>
      <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
      <table v-else class="table">
        <thead><tr><th>النظام</th><th>التصنيف</th><th>مميز (Featured)</th><th>نشط (Active)</th><th>إجراءات</th></tr></thead>
        <tbody>
          <tr v-for="p in products" :key="p.id">
            <td><strong>{{ p.name_ar }}</strong></td>
            <td>{{ p.category?.name_ar || '—' }}</td>
            <td><span class="badge" :class="p.is_featured?'badge-success':'badge-danger'">{{ p.is_featured?'نعم':'لا' }}</span></td>
            <td><span class="badge" :class="p.is_active?'badge-success':'badge-danger'">{{ p.is_active?'نشط':'معطل' }}</span></td>
            <td>
              <button class="btn btn-outline btn-sm" style="margin-left:6px" @click="openModal(p)">تعديل</button>
              <button class="btn btn-sm" style="background:var(--danger);color:white" @click="del(p.id)">حذف</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay" @click.self="showModal=false">
      <div class="modal">
        <div class="modal-header">
          <h3 class="modal-title">{{ editing ? 'تعديل النظام' : 'إضافة نظام جديد' }}</h3>
          <button @click="showModal=false" style="background:none;border:none;font-size:1.4rem;cursor:pointer">✕</button>
        </div>
        <div class="modal-body">
          <div v-if="err" class="alert alert-danger">{{ err }}</div>
          <div class="form-group">
            <label class="form-label">اسم النظام (بالعربي) *</label>
            <input v-model="form.name_ar" class="form-control" required />
          </div>
          <div class="form-group">
            <label class="form-label">التصنيف (Category)</label>
            <select v-model="form.category_id" class="form-control">
              <option value="">-- بدون تصنيف --</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name_ar }}</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">وصف مختصر</label>
            <textarea v-model="form.short_desc_ar" class="form-control" rows="3"></textarea>
          </div>
          <div style="display:flex;gap:20px;margin-bottom:16px">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
              <input type="checkbox" v-model="form.is_featured" /> مميز (Featured)
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
              <input type="checkbox" v-model="form.is_active" /> نشط (Active)
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showModal=false">إلغاء</button>
          <button class="btn btn-primary" @click="save" :disabled="saving">{{ saving?'جارٍ الحفظ...':'حفظ' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'
const products = ref([])
const categories = ref([])
const loading = ref(true)
const showModal = ref(false)
const editing = ref(null)
const saving = ref(false)
const err = ref('')
const emptyForm = () => ({ name_ar: '', category_id: '', short_desc_ar: '', is_featured: false, is_active: true })
const form = ref(emptyForm())
async function load() { loading.value=true; const {data}=await api.get('/products'); products.value=data; loading.value=false }
async function loadCats() { const {data}=await api.get('/categories'); categories.value=data }
function openModal(p=null) { editing.value=p?.id||null; form.value=p?{name_ar:p.name_ar,category_id:p.category_id||'',short_desc_ar:p.short_desc_ar||'',is_featured:p.is_featured,is_active:p.is_active}:emptyForm(); err.value=''; showModal.value=true }
async function save() {
  saving.value=true; err.value=''
  try {
    if (editing.value) await api.put(`/products/${editing.value}`, form.value)
    else await api.post('/products', form.value)
    showModal.value=false; load()
  } catch(e) { err.value=e.response?.data?.message||'حدث خطأ' }
  finally { saving.value=false }
}
async function del(id) {
  if (!confirm('هل تريد حذف هذا النظام؟')) return
  await api.delete(`/products/${id}`); load()
}
onMounted(() => { load(); loadCats() })
</script>
