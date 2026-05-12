<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div style="display:flex;align-items:center;gap:10px">
            <span style="font-size:2rem">🖥️</span>
            <span style="color:white;font-size:1.1rem;font-weight:800">إبداع سوفت</span>
          </div>
          <p>شركة إبداع سوفت للأنظمة الخاصة المحدودة — رائدة في تطوير الأنظمة المحاسبية والإدارية في اليمن.</p>
          <div class="social-links">
            <a href="https://facebook.com/EBDA3SOFTNET" target="_blank" class="social-link">f</a>
            <a href="https://twitter.com/NetEbda3" target="_blank" class="social-link">𝕏</a>
            <a href="https://youtube.com/c/ebda3soft123" target="_blank" class="social-link">▶</a>
            <a href="https://t.me/ebda3soft" target="_blank" class="social-link">✈</a>
          </div>
        </div>

        <div>
          <h4>روابط سريعة</h4>
          <ul>
            <li><router-link to="/">الرئيسية</router-link></li>
            <li><router-link to="/products">أنظمة الشركة</router-link></li>
            <li><router-link to="/branches">الفروع والوكلاء</router-link></li>
            <li><router-link to="/news">الأخبار والفعاليات</router-link></li>
            <li><router-link to="/faq">الأسئلة المتكررة (FAQ)</router-link></li>
          </ul>
        </div>

        <div>
          <h4>الخدمات</h4>
          <ul>
            <li><router-link to="/request">طلب نظام</router-link></li>
            <li><router-link to="/careers">التوظيف (Careers)</router-link></li>
            <li><router-link to="/contact">تواصل بنا</router-link></li>
            <li><a href="#">بروفايل الشركة (Profile)</a></li>
          </ul>
        </div>

        <div>
          <h4>تواصل معنا</h4>
          <div class="footer-contact">
            <p>📍 صنعاء - شارع مأرب - عمارة الأكوع - الدور الرابع</p>
            <p>📞 01 337571/72/73</p>
            <p>✉️ info@ebda3soft.com</p>
            <p>🕐 8 ص - 12 ظ | 4 م - 8 م</p>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        © {{ new Date().getFullYear() }} إبداع سوفت للأنظمة الخاصة المحدودة — جميع الحقوق محفوظة
      </div>
    </div>
  </footer>
</template>
