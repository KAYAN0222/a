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
      <div class="container">
        <h1 style="font-size: 2rem; font-weight: 800">الفروع والوكلاء</h1>
        <p style="color: rgba(255, 255, 255, 0.8); margin-top: 10px">
          نمتلك شبكة واسعة من الفروع والوكلاء في أنحاء المملكة العربية السعودية
        </p>
      </div>
    </div>
    <section class="section">
      <div class="container">
        <div
          style="display: flex; gap: 12px; margin-bottom: 32px; flex-wrap: wrap"
        >
          <button
            v-for="t in types"
            :key="t.val"
            class="btn"
            :class="filter === t.val ? 'btn-primary' : 'btn-outline'"
            @click="filter = t.val"
          >
            {{ t.label }}
          </button>
        </div>
        <div v-if="loading" class="loading-center">
          <div class="spinner"></div>
        </div>
        <div v-else class="grid grid-3">
          <div
            v-for="branch in filtered"
            :key="branch.id"
            class="card card-body"
            style="display: flex; flex-direction: column; gap: 10px"
          >
            <div
              style="
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
              "
            >
              <h3
                style="font-size: 1rem; font-weight: 700; color: var(--primary)"
              >
                {{ branch.name_ar }}
              </h3>
              <span
                class="badge"
                :class="
                  branch.type === 'branch' ? 'badge-primary' : 'badge-accent'
                "
                >{{ branch.type === "branch" ? "فرع" : "وكيل" }}</span
              >
            </div>
            <p style="font-size: 0.87rem; color: var(--text-muted)">
              🏙️ {{ branch.governorate }} - {{ branch.city }}
            </p>
            <p
              v-if="branch.address"
              style="font-size: 0.85rem; color: var(--text-muted)"
            >
              📍 {{ branch.address }}
            </p>
            <p
              v-if="branch.phone"
              style="
                font-size: 0.87rem;
                color: var(--primary);
                font-weight: 600;
              "
            >
              📞 {{ branch.phone }}
            </p>
            <p
              v-if="branch.manager"
              style="font-size: 0.85rem; color: var(--text-muted)"
            >
              👤 {{ branch.manager }}
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import api from "../../api";
const branches = ref([]);
const loading = ref(true);
const filter = ref(null);
const types = [
  { val: null, label: "الكل" },
  { val: "branch", label: "الفروع" },
  { val: "agent", label: "الوكلاء" },
];
const filtered = computed(() =>
  filter.value
    ? branches.value.filter((b) => b.type === filter.value)
    : branches.value,
);
onMounted(async () => {
  try {
    const { data } = await api.get("/branches");
    branches.value = data;
  } catch(e) {} finally {
    loading.value = false;
  }
});
</script>
