<template>
  <div>
    <!-- Hero -->
    <section style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:80px 0;color:white">
      <div class="container" style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center">
        <div>
          <span style="background:rgba(232,160,32,0.2);color:var(--accent-light);padding:6px 16px;border-radius:20px;font-size:0.82rem;font-weight:700">من نحن — About Us</span>
          <h1 style="font-size:2.3rem;font-weight:900;margin:16px 0;line-height:1.3">إبداع سوفت<br>للأنظمة الخاصة المحدودة</h1>
          <p style="color:rgba(255,255,255,0.85);line-height:1.9;font-size:1rem">تأسست إبداع سوفت على يد مجموعة من الخبراء لإحداث نقلة نوعية في مجال الأنظمة المحاسبية والإدارية، بتقديم حلول متخصصة بطرق إبداعية تناسب حاجة كل عميل.</p>
          <div style="display:flex;gap:20px;margin-top:36px;flex-wrap:wrap">
            <div v-for="s in stats" :key="s.label" style="text-align:center">
              <div style="font-size:2rem;font-weight:900;color:var(--accent-light)">{{ s.val }}</div>
              <div style="font-size:0.8rem;color:rgba(255,255,255,0.7);margin-top:4px">{{ s.label }}</div>
            </div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div v-for="v in values" :key="v.title" class="card card-body" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:white;text-align:center;padding:28px 20px">
            <div style="font-size:2.5rem;margin-bottom:12px">{{ v.icon }}</div>
            <h4 style="font-weight:700;font-size:0.95rem;margin-bottom:8px">{{ v.title }}</h4>
            <p style="font-size:0.82rem;color:rgba(255,255,255,0.7)">{{ v.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- History / Timeline -->
    <section class="section" style="background:white">
      <div class="container" style="max-width:800px">
        <div style="text-align:center;margin-bottom:50px">
          <h2 class="section-title" style="text-align:center">قصتنا ومسيرتنا</h2>
          <div class="divider" style="margin:12px auto 0"></div>
        </div>
        <div v-for="(item, i) in timeline" :key="i" style="display:flex;gap:24px;margin-bottom:32px;align-items:flex-start">
          <div style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--primary-light),var(--primary));display:flex;align-items:center;justify-content:center;color:white;font-weight:900;font-size:0.9rem;flex-shrink:0">{{ item.year }}</div>
          <div class="card card-body" style="flex:1">
            <h4 style="font-weight:700;color:var(--primary);margin-bottom:6px">{{ item.title }}</h4>
            <p style="font-size:0.88rem;color:var(--text-muted)">{{ item.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="section">
      <div class="container">
        <div style="text-align:center;margin-bottom:50px">
          <h2 class="section-title" style="text-align:center">فريقنا المتخصص</h2>
          <div class="divider" style="margin:12px auto 0"></div>
        </div>
        <div class="grid grid-4">
          <div v-for="m in team" :key="m.name" class="card card-body" style="text-align:center">
            <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,var(--primary-light),var(--primary));display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.8rem">{{ m.emoji }}</div>
            <h4 style="font-weight:700;color:var(--primary);margin-bottom:4px">{{ m.name }}</h4>
            <p style="font-size:0.83rem;color:var(--text-muted)">{{ m.role }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Partners / Clients -->
    <section class="section" style="background:white">
      <div class="container">
        <div style="text-align:center;margin-bottom:40px">
          <h2 class="section-title" style="text-align:center">شراكاتنا الاستراتيجية</h2>
          <div class="divider" style="margin:12px auto 0"></div>
        </div>
        <div style="display:flex;gap:20px;flex-wrap:wrap;justify-content:center">
          <div v-for="p in partners" :key="p" style="background:var(--bg);border-radius:var(--radius);padding:16px 28px;font-size:0.88rem;font-weight:600;color:var(--text-muted)">{{ p }}</div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:60px 0;text-align:center">
      <div class="container">
        <h2 style="color:white;font-size:1.7rem;font-weight:800;margin-bottom:16px">انضم إلى عائلة إبداع سوفت</h2>
        <p style="color:rgba(255,255,255,0.8);margin-bottom:28px">كن جزءاً من أكثر من 1500 عميل راضٍ في جميع أنحاء المملكة</p>
        <router-link to="/request" class="btn btn-accent btn-lg">طلب نظام الآن</router-link>
      </div>
    </section>
  </div>
</template>

<script setup>
const stats = [
  { val: '12+', label: 'سنة خبرة' },
  { val: '1500+', label: 'عميل راضٍ' },
  { val: '16', label: 'نظام متكامل' },
  { val: '15+', label: 'فرع ووكيل' },
]

const values = [
  { icon: '🎯', title: 'رؤيتنا', desc: 'الرافد الأول إقليمياً في الأنظمة الإدارية الرقمية' },
  { icon: '💡', title: 'رسالتنا', desc: 'حلول مبتكرة تلبي احتياجات كل عميل بدقة' },
  { icon: '🏆', title: 'قيمنا', desc: 'الجودة، الثقة، الشفافية، والتطوير المستمر' },
  { icon: '🤝', title: 'التزامنا', desc: 'دعم فني متواصل ومرافقة العميل بعد التسليم' },
]

const timeline = [
  { year: '2012', title: 'التأسيس', desc: 'تأسيس شركة إبداع سوفت للأنظمة الخاصة المحدودة في الرياض.' },
  { year: '2014', title: 'أول نظام محاسبي', desc: 'إطلاق نظام DOTX Pro للمحاسبة العامة وتحقيق نجاح واسع.' },
  { year: '2016', title: 'التوسع الجغرافي', desc: 'افتتاح أول شبكة من الوكلاء في المملكة .' },
  { year: '2018', title: 'الأنظمة القطاعية', desc: 'إطلاق أنظمة متخصصة للمدارس والمستشفيات والفنادق.' },
  { year: '2020', title: 'الحوسبة السحابية', desc: 'الانتقال نحو الحلول السحابية وأنظمة Web-Based.' },
  { year: '2024', title: 'المنصة الرقمية', desc: 'إطلاق المنصة الرقمية المتكاملة للتعامل مع العملاء إلكترونياً.' },
]

const team = [
  { emoji: '👨‍💼', name: 'المدير العام', role: 'General Manager' },
  { emoji: '👨‍💻', name: 'مدير التطوير', role: 'Development Manager' },
  { emoji: '👩‍🔧', name: 'مدير الدعم الفني', role: 'Support Manager' },
  { emoji: '👨‍📊', name: 'مدير المبيعات', role: 'Sales Manager' },
]

const partners = [
  'جامعة الملك سعود', 'معهد التدريب الحكومي', 'اتحاد الغرف التجارية',
  'وزارة المالية', 'البنك المركزي ', 'هيئة الاستثمار',
]
</script>
