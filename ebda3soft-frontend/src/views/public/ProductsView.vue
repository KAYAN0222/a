<template>
  <div>
    <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:50px 0;color:white;text-align:center">
      <div class="container">
        <h1 style="font-size:2rem;font-weight:800">أنظمة وبرمجيات الشركة</h1>
        <p style="color:rgba(255,255,255,0.8);margin-top:10px">اختر النظام المناسب لمؤسستك من قائمتنا المتكاملة</p>
      </div>
    </div>

    <section class="section">
      <div class="container">
        <!-- Filters -->
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:36px;align-items:center">
          <button
            v-for="cat in [{ id: null, name_ar: 'الكل', slug: null }, ...categories]" :key="cat.id"
            class="btn"
            :class="selectedCategory === cat.slug ? 'btn-primary' : 'btn-outline'"
            @click="selectedCategory = cat.slug; loadProducts()"
          >{{ cat.name_ar }}</button>
          <input v-model="search" @input="debouncedSearch" class="form-control" placeholder="ابحث عن نظام..." style="max-width:260px;padding:10px 16px" />
        </div>

        <div v-if="loading" class="loading-center"><div class="spinner"></div></div>
        <div v-else-if="products.length === 0" style="text-align:center;padding:60px;color:var(--text-muted)">
          <p style="font-size:1.1rem">لا توجد أنظمة في هذا التصنيف</p>
        </div>
        <div v-else class="grid grid-3">
          <div v-for="product in products" :key="product.id" class="card product-card">
            <div class="product-card-img">
              <span>{{ emojis[product.slug] || '🖥️' }}</span>
            </div>
            <div class="product-card-body">
              <div class="product-card-category">{{ product.category?.name_ar }}</div>
              <h3 class="product-card-title">{{ product.name_ar }}</h3>
              <p class="product-card-desc">{{ product.short_desc_ar }}</p>
            </div>
            <div class="product-card-footer">
              <router-link :to="`/products/${product.slug}`" class="btn btn-primary btn-sm">التفاصيل</router-link>
              <router-link to="/request" class="btn btn-outline btn-sm">طلب النظام</router-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../api'

const products = ref([])
const categories = ref([])
const loading = ref(true)
const selectedCategory = ref(null)
const search = ref('')
let debounceTimer = null

const emojis = {
  'dotx-pro':'💰','mal-accounting':'📊','merchant-pro':'🛒','fixed-assets':'🏗️',
  'exchange-advanced':'💱','teller-advanced':'🏦','teller-plus':'💳',
  'restaurant-system':'🍽️','fuel-station':'⛽','hr-system':'👥',
  'noon-schools':'🏫','hospital-system':'🏥','electricity-station':'⚡',
  'water-projects':'💧','car-showroom':'🚗','hotel-system':'🏨',
}

async function loadProducts() {
  loading.value = true
  const params = {}
  if (selectedCategory.value) params.category = selectedCategory.value
  if (search.value) params.search = search.value
  const { data } = await api.get('/products', { params })
  products.value = data
  loading.value = false
}

function debouncedSearch() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(loadProducts, 400)
}

onMounted(async () => {
  const [p, c] = await Promise.all([api.get('/products'), api.get('/categories')])
  products.value = p.data
  categories.value = c.data
  loading.value = false
})
</script>
