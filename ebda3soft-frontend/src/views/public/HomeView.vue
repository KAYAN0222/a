<template>
  <div>
    <!-- Hero Section -->
    <section class="hero">
      <div class="container">
        <div class="hero-content">
          <h1>
            الحل الأمثل للأنظمة <span>المحاسبية والإدارية</span> في المملكة
            العربية السعودية
          </h1>
          <p>
            نقدم منذ أكثر من 12 عاماً أنظمة محوسبة متكاملة للشركات والمؤسسات بكل
            قطاعاتها، بطرق إبداعية تناسب احتياج كل عميل داخل المملكة والوطن
            العربي.
          </p>
          <div class="hero-btns">
            <router-link to="/products" class="btn btn-accent btn-lg"
              >🖥️ تصفح الأنظمة</router-link
            >
            <router-link
              to="/request"
              class="btn btn-outline btn-lg"
              style="border-color: rgba(255, 255, 255, 0.5); color: white"
              >📋 طلب نظام</router-link
            >
          </div>
          <div class="hero-stats">
            <div v-for="stat in stats" :key="stat.label">
              <div class="hero-stat-num">{{ stat.num }}</div>
              <div class="hero-stat-label">{{ stat.label }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section class="section" style="background: white">
      <div class="container">
        <div
          style="
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
          "
        >
          <div>
            <p
              class="section-subtitle"
              style="
                color: var(--accent);
                font-weight: 700;
                font-size: 0.9rem;
                text-transform: uppercase;
                letter-spacing: 1px;
              "
            >
              عن الشركة
            </p>
            <h2 class="section-title">من نحن — SoftPro - نظامك المستقبلي</h2>
            <div class="divider"></div>
            <p
              style="
                color: var(--text-muted);
                line-height: 1.9;
                margin-bottom: 20px;
              "
            >
              تأسست SoftPro - نظامك المستقبلي للأنظمة الخاصة على يد مجموعة من
              الخبراء لتحدث نقلة نوعية في عالم الأنظمة المحاسبية، بتقديم حلول
              متخصصة بطرق إبداعية تناسب حاجة كل عميل على حده.
            </p>
            <p style="color: var(--text-muted); line-height: 1.9">
              سعت الشركة منذ وقت مبكر إلى تأسيس شراكات وعلاقات استراتيجية مع
              الجهات ذات الصلة لتحقيق رؤيتها بأن تكون الرافد الأول محلياً
              وإقليمياً لسوق العمل بأنظمة مالية وإدارية رقمية حديثة.
            </p>
            <div
              style="
                display: flex;
                gap: 16px;
                margin-top: 28px;
                flex-wrap: wrap;
              "
            >
              <router-link to="/products" class="btn btn-primary"
                >أنظمتنا</router-link
              >
              <router-link to="/contact" class="btn btn-outline"
                >تواصل معنا</router-link
              >
            </div>
          </div>
          <div class="grid grid-2" style="gap: 16px">
            <div
              v-for="val in values"
              :key="val.title"
              class="card card-body"
              style="text-align: center; padding: 28px 20px"
            >
              <div style="font-size: 2.5rem; margin-bottom: 12px">
                {{ val.icon }}
              </div>
              <h4
                style="
                  font-weight: 700;
                  color: var(--primary);
                  margin-bottom: 8px;
                "
              >
                {{ val.title }}
              </h4>
              <p style="font-size: 0.85rem; color: var(--text-muted)">
                {{ val.desc }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Products Section -->
    <section class="section">
      <div class="container">
        <div style="text-align: center; margin-bottom: 50px">
          <h2 class="section-title" style="text-align: center">
            أنظمة وبرمجيات الشركة
          </h2>
          <div class="divider" style="margin: 12px auto 0"></div>
          <p class="section-subtitle">
            حلول محوسبة متكاملة لجميع القطاعات والمجالات
          </p>
        </div>

        <div v-if="loadingProducts" class="loading-center">
          <div class="spinner"></div>
        </div>
        <div v-else class="grid grid-3">
          <div
            v-for="product in featuredProducts"
            :key="product.id"
            class="card product-card"
          >
            <div class="product-card-img">
              <span>{{ productEmojis[product.slug] || "🖥️" }}</span>
            </div>
            <div class="product-card-body">
              <div class="product-card-category">
                {{ product.category?.name_ar }}
              </div>
              <h3 class="product-card-title">{{ product.name_ar }}</h3>
              <p class="product-card-desc">{{ product.short_desc_ar }}</p>
            </div>
            <div class="product-card-footer">
              <router-link
                :to="`/products/${product.slug}`"
                class="btn btn-primary btn-sm"
                >التفاصيل</router-link
              >
              <router-link to="/request" class="btn btn-outline btn-sm"
                >طلب النظام</router-link
              >
            </div>
          </div>
        </div>

        <div style="text-align: center; margin-top: 40px">
          <router-link to="/products" class="btn btn-primary btn-lg"
            >عرض جميع الأنظمة</router-link
          >
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="section" style="background: white">
      <div class="container">
        <div style="text-align: center; margin-bottom: 50px">
          <h2 class="section-title" style="text-align: center">
            مجالات الشركة
          </h2>
          <div class="divider" style="margin: 12px auto 0"></div>
        </div>
        <div class="grid grid-4">
          <div
            v-for="svc in services"
            :key="svc.title"
            style="
              text-align: center;
              padding: 32px 20px;
              border-radius: var(--radius-lg);
              background: var(--bg);
              transition: var(--transition);
            "
            class="card"
          >
            <div style="font-size: 3rem; margin-bottom: 16px">
              {{ svc.icon }}
            </div>
            <h3
              style="
                font-size: 1rem;
                font-weight: 700;
                color: var(--primary);
                margin-bottom: 10px;
              "
            >
              {{ svc.title }}
            </h3>
            <p style="font-size: 0.87rem; color: var(--text-muted)">
              {{ svc.desc }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- News Section -->
    <section class="section">
      <div class="container">
        <div
          style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 16px;
          "
        >
          <div>
            <h2 class="section-title">أخبار وفعاليات</h2>
            <div class="divider"></div>
          </div>
          <router-link to="/news" class="btn btn-outline"
            >جميع الأخبار</router-link
          >
        </div>
        <div v-if="loadingNews" class="loading-center">
          <div class="spinner"></div>
        </div>
        <div v-else class="grid grid-3">
          <div v-for="post in recentNews" :key="post.id" class="card news-card">
            <div
              style="
                height: 180px;
                background: linear-gradient(
                  135deg,
                  var(--primary-dark),
                  var(--primary-light)
                );
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 3rem;
              "
            >
              📰
            </div>
            <div class="card-body">
              <div class="news-card-meta">
                <span>📅 {{ formatDate(post.published_at) }}</span>
                <span class="badge badge-primary">{{
                  typeLabels[post.post_type]
                }}</span>
              </div>
              <h3 class="news-card-title">{{ post.title_ar }}</h3>
              <p class="news-card-excerpt" v-if="post.excerpt_ar">
                {{ post.excerpt_ar?.substring(0, 100) }}...
              </p>
              <router-link
                :to="`/news/${post.slug}`"
                class="btn btn-primary btn-sm"
                style="margin-top: 14px"
                >اقرأ المزيد</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section
      style="
        background: linear-gradient(
          135deg,
          var(--primary-dark),
          var(--primary)
        );
        padding: 80px 0;
        text-align: center;
      "
    >
      <div class="container">
        <h2
          style="
            color: white;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 16px;
          "
        >
          هل تحتاج إلى نظام مناسب لمؤسستك؟
        </h2>
        <p
          style="
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.05rem;
            margin-bottom: 36px;
          "
        >
          تواصل معنا اليوم وسيقوم فريق خبرائنا بمساعدتك في اختيار الحل الأمثل
        </p>
        <div
          style="
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
          "
        >
          <router-link to="/request" class="btn btn-accent btn-lg"
            >📋 طلب نظام الآن</router-link
          >
          <a
            href="https://wa.me/966571870415"
            target="_blank"
            class="btn btn-lg"
            style="
              background: rgba(255, 255, 255, 0.15);
              color: white;
              border: 2px solid rgba(255, 255, 255, 0.3);
            "
            >💬 تواصل عبر واتساب</a
          >
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "../../api";

const loadingProducts = ref(true);
const loadingNews = ref(true);
const featuredProducts = ref([]);
const recentNews = ref([]);

const stats = [
  { num: "12+", label: "عاماً من الخبرة" },
  { num: "1500+", label: "عميل راضٍ" },
  { num: "20+", label: "فرع ووكيل بالمملكة" },
  { num: "16", label: "نظام متخصص" },
];

const values = [
  {
    icon: "🎯",
    title: "رؤيتنا",
    desc: "أن نكون الرافد الأول محلياً وإقليمياً بأنظمة مالية وإدارية رقمية حديثة",
  },
  {
    icon: "💡",
    title: "رسالتنا",
    desc: "تقديم أنظمة وخدمات لتطوير البنى التحتية المعلوماتية للمؤسسات",
  },
  {
    icon: "🤝",
    title: "شراكاتنا",
    desc: "شراكات استراتيجية مع جامعات ومعاهد تدريبية رائدة",
  },
  {
    icon: "🏆",
    title: "جودتنا",
    desc: "التزام تام بأعلى معايير الجودة في تطوير وتسليم الأنظمة",
  },
];

const services = [
  {
    icon: "💻",
    title: "تطوير وبرمجة النظم",
    desc: "قسم مهتم بتطوير وبرمجة النظم وتقديم الحلول المحوسبة",
  },
  {
    icon: "🖧",
    title: "استضافة السيرفرات (Hosting)",
    desc: "سيرفرات عالية الأداء لضمان استمرارية أعمالك",
  },
  {
    icon: "☁️",
    title: "الحوسبة السحابية (Cloud)",
    desc: "حلول سحابية آمنة وموثوقة للمؤسسات",
  },
  {
    icon: "🖨️",
    title: "إبداع هارد وير (Hardware)",
    desc: "توفير جميع احتياجاتكم من الأجهزة والمعدات",
  },
];

const productEmojis = {
  "dotx-pro": "💰",
  "mal-accounting": "📊",
  "merchant-pro": "🛒",
  "fixed-assets": "🏗️",
  "exchange-advanced": "💱",
  "restaurant-system": "🍽️",
  "fuel-station": "⛽",
  "hr-system": "👥",
  "noon-schools": "🏫",
  "hospital-system": "🏥",
  "water-projects": "💧",
  "car-showroom": "🚗",
  "hotel-system": "🏨",
};
const typeLabels = { news: "خبر", blog: "مقال", event: "فعالية" };

function formatDate(d) {
  if (!d) return "";
  return new Date(d).toLocaleDateString("ar-SA", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

onMounted(async () => {
  try {
    const [p, n] = await Promise.all([
      api.get("/products?featured=1"),
      api.get("/posts?per_page=3"),
    ]);
    featuredProducts.value = p.data.slice(0, 6);
    recentNews.value = n.data.data || [];
  } catch (error) {
    console.error("Error fetching home data:", error);
  } finally {
    loadingProducts.value = false;
    loadingNews.value = false;
  }
});
</script>
