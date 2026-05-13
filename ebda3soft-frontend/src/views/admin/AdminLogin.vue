<template>
  <div
    style="
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    "
  >
    <div class="card" style="width: 400px; padding: 40px">
      <div style="text-align: center; margin-bottom: 32px">
        <div style="font-size: 3rem; margin-bottom: 12px">🖥️</div>
        <h1 style="font-size: 1.4rem; font-weight: 800; color: var(--primary)">
          SoftPro - نظامك المستقبلي
        </h1>
        <p
          style="color: var(--text-muted); font-size: 0.88rem; margin-top: 4px"
        >
          لوحة الإدارة (Admin Panel)
        </p>
      </div>

      <div v-if="error" class="alert alert-danger">{{ error }}</div>

      <form @submit.prevent="submit">
        <div class="form-group">
          <label class="form-label">البريد الإلكتروني (Email)</label>
          <input
            v-model="email"
            type="email"
            class="form-control"
            required
            placeholder="admin@ebda3soft.com"
            autofocus
          />
        </div>
        <div class="form-group">
          <label class="form-label">كلمة المرور (Password)</label>
          <input
            v-model="password"
            type="password"
            class="form-control"
            required
            placeholder="••••••••"
          />
        </div>
        <button
          type="submit"
          class="btn btn-primary btn-lg"
          style="width: 100%; justify-content: center; margin-top: 8px"
          :disabled="loading"
        >
          {{ loading ? "جارٍ تسجيل الدخول..." : "🔐 تسجيل الدخول" }}
        </button>
      </form>

      <p
        style="
          text-align: center;
          margin-top: 24px;
          font-size: 0.82rem;
          color: var(--text-muted);
        "
      >
        حساب افتراضي: admin@ebda3soft.com / Admin@123456
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/auth";

const auth = useAuthStore();
const router = useRouter();
const email = ref("");
const password = ref("");
const loading = ref(false);
const error = ref("");

async function submit() {
  loading.value = true;
  error.value = "";
  const ok = await auth.login(email.value, password.value);
  if (ok) router.push("/admin");
  else error.value = auth.error;
  loading.value = false;
}
</script>
