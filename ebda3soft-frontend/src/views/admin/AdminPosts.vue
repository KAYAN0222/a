<template>
  <div>
    <div class="table-container">
      <div class="table-header">
        <h3 style="font-size:1rem;font-weight:700;color:var(--primary)">الأخبار والمقالات (Posts)</h3>
        <button class="btn btn-primary btn-sm" @click="openModal()">+ إضافة مقال</button>
      </div>
      <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
      <table v-else class="table">
        <thead><tr><th>العنوان</th><th>النوع</th><th>الحالة</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
        <tbody>
          <tr v-for="p in posts" :key="p.id">
            <td style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><strong>{{ p.title_ar }}</strong></td>
            <td><span class="badge badge-primary">{{ typeLabels[p.post_type] }}</span></td>
            <td><span class="badge" :class="p.is_published?'badge-success':'badge-danger'">{{ p.is_published?'منشور':'مسودة' }}</span></td>
            <td style="font-size:0.85rem;color:var(--text-muted)">{{ fmt(p.published_at) }}</td>
            <td>
              <button class="btn btn-outline btn-sm" style="margin-left:6px" @click="openModal(p)">تعديل</button>
              <button class="btn btn-sm" style="background:var(--danger);color:white" @click="del(p.id)">حذف</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="showModal" class="modal-overlay" @click.self="showModal=false">
      <div class="modal">
        <div class="modal-header">
          <h3 class="modal-title">{{ editing?'تعديل المقال':'إضافة مقال جديد' }}</h3>
          <button @click="showModal=false" style="background:none;border:none;font-size:1.4rem;cursor:pointer">✕</button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label class="form-label">العنوان *</label><input v-model="form.title_ar" class="form-control" required /></div>
          <div class="form-group">
            <label class="form-label">النوع (Type)</label>
            <select v-model="form.post_type" class="form-control">
              <option value="news">خبر (News)</option>
              <option value="blog">مقال (Blog)</option>
              <option value="event">فعالية (Event)</option>
            </select>
          </div>
          <div class="form-group"><label class="form-label">مقتطف (Excerpt)</label><textarea v-model="form.excerpt_ar" class="form-control" rows="2"></textarea></div>
          <div class="form-group"><label class="form-label">المحتوى</label><textarea v-model="form.content_ar" class="form-control" rows="5"></textarea></div>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-bottom:16px">
            <input type="checkbox" v-model="form.is_published" /> نشر الآن
          </label>
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
const posts = ref([])
const loading = ref(true)
const showModal = ref(false)
const editing = ref(null)
const saving = ref(false)
const typeLabels = { news:'خبر', blog:'مقال', event:'فعالية' }
const emptyForm = () => ({ title_ar:'', post_type:'news', excerpt_ar:'', content_ar:'', is_published:false })
const form = ref(emptyForm())
const fmt = d => d?new Date(d).toLocaleDateString('ar-YE'):''
async function load() { loading.value=true; const {data}=await api.get('/posts?per_page=100'); posts.value=data.data||[]; loading.value=false }
function openModal(p=null) {
  editing.value=p?.id||null
  form.value=p?{title_ar:p.title_ar,post_type:p.post_type,excerpt_ar:p.excerpt_ar||'',content_ar:p.content_ar||'',is_published:p.is_published}:emptyForm()
  showModal.value=true
}
async function save() {
  saving.value=true
  try { if(editing.value) await api.put(`/posts/${editing.value}`,form.value); else await api.post('/posts',form.value); showModal.value=false; load() }
  finally { saving.value=false }
}
async function del(id) { if(!confirm('حذف المقال؟')) return; await api.delete(`/posts/${id}`); load() }
onMounted(load)
</script>
