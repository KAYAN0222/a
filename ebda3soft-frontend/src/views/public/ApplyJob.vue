<template>
  <div>
    <div
      style="
        background: linear-gradient(
          135deg,
          var(--primary-dark),
          var(--primary)
        );
        padding: 50px 0;
        color: white;
        text-align: center;
      "
    >
      <h1 style="font-size: 2rem; font-weight: 800">التقدم لوظيفة (Careers)</h1>
      <p style="color: rgba(255, 255, 255, 0.8); margin-top: 10px">
        انضم إلى فريق SoftPro - نظامك المستقبلي
      </p>
    </div>
    <section class="section">
      <div class="container" style="max-width: 680px">
        <div
          v-if="success"
          class="alert alert-success"
          style="font-size: 1rem; text-align: center"
        >
          {{ success }}
        </div>
        <div v-else class="card card-body">
          <div v-if="error" class="alert alert-danger">{{ error }}</div>
          <form @submit.prevent="submit">
            <div
              style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px"
            >
              <div class="form-group">
                <label class="form-label">الاسم الكامل *</label>
                <input v-model="form.full_name" class="form-control" required />
              </div>
              <div class="form-group">
                <label class="form-label">رقم الجوال *</label>
                <input v-model="form.phone" class="form-control" required />
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">البريد الإلكتروني (Email)</label>
              <input v-model="form.email" type="email" class="form-control" />
            </div>
            <div
              style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px"
            >
              <div class="form-group">
                <label class="form-label">الوظيفة المطلوبة (Position)</label>
                <input
                  v-model="form.position"
                  class="form-control"
                  placeholder="مطور، محاسب، مبيعات..."
                />
              </div>
              <div class="form-group">
                <label class="form-label"
                  >سنوات الخبرة (Years of Experience)</label
                >
                <input
                  v-model="form.experience_years"
                  type="number"
                  min="0"
                  class="form-control"
                />
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">رسالة التغطية (Cover Letter)</label>
              <textarea
                v-model="form.cover_letter"
                class="form-control"
                rows="4"
                placeholder="اكتب نبذة عن نفسك وسبب تقدمك..."
              ></textarea>
            </div>
            <div class="form-group">
              <label class="form-label"
                >السيرة الذاتية (CV) — PDF أو Word</label
              >
              <input
                type="file"
                accept=".pdf,.doc,.docx"
                @change="onFile"
                class="form-control"
              />
            </div>
            <button
              type="submit"
              class="btn btn-primary btn-lg"
              style="width: 100%; justify-content: center"
              :disabled="submitting"
            >
              {{ submitting ? "جارٍ الإرسال..." : "📤 إرسال الطلب" }}
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { ref } from "vue";
import api from "../../api";
const form = ref({
  full_name: "",
  phone: "",
  email: "",
  position: "",
  experience_years: "",
  cover_letter: "",
});
const cvFile = ref(null);
const submitting = ref(false);
const success = ref("");
const error = ref("");
const onFile = (e) => {
  cvFile.value = e.target.files[0];
};
async function submit() {
  submitting.value = true;
  error.value = "";
  const fd = new FormData();
  Object.entries(form.value).forEach(([k, v]) => {
    if (v !== "") fd.append(k, v);
  });
  if (cvFile.value) fd.append("cv", cvFile.value);
  try {
    const { data } = await api.post("/apply-job", fd, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    success.value = data.message;
  } catch (e) {
    error.value = e.response?.data?.message || "حدث خطأ";
  } finally {
    submitting.value = false;
  }
}
</script>
