import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

// Public Pages
const HomeView      = () => import('../views/public/HomeView.vue')
const AboutView     = () => import('../views/public/AboutView.vue')
const ProductsView  = () => import('../views/public/ProductsView.vue')
const ProductDetail = () => import('../views/public/ProductDetail.vue')
const BranchesView  = () => import('../views/public/BranchesView.vue')
const NewsView      = () => import('../views/public/NewsView.vue')
const NewsDetail    = () => import('../views/public/NewsDetail.vue')
const FaqView       = () => import('../views/public/FaqView.vue')
const ContactView   = () => import('../views/public/ContactView.vue')
const RequestSystem = () => import('../views/public/RequestSystem.vue')
const QuoteView     = () => import('../views/public/QuoteView.vue')
const SupportView   = () => import('../views/public/SupportView.vue')
const ApplyJob      = () => import('../views/public/ApplyJob.vue')

// Admin Pages
const AdminLogin     = () => import('../views/admin/AdminLogin.vue')
const AdminDashboard = () => import('../views/admin/AdminDashboard.vue')
const AdminProducts  = () => import('../views/admin/AdminProducts.vue')
const AdminPosts     = () => import('../views/admin/AdminPosts.vue')
const AdminOrders    = () => import('../views/admin/AdminOrders.vue')
const AdminBranches  = () => import('../views/admin/AdminBranches.vue')
const AdminMessages  = () => import('../views/admin/AdminMessages.vue')
const AdminJobApps   = () => import('../views/admin/AdminJobApps.vue')
const AdminSettings  = () => import('../views/admin/AdminSettings.vue')
const AdminTickets   = () => import('../views/admin/AdminTickets.vue')
const AdminQuotes    = () => import('../views/admin/AdminQuotes.vue')

// Layouts
const PublicLayout = () => import('../layouts/PublicLayout.vue')
const AdminLayout  = () => import('../layouts/AdminLayout.vue')

const routes = [
  {
    path: '/',
    component: PublicLayout,
    children: [
      { path: '',              name: 'home',           component: HomeView },
      { path: 'about',         name: 'about',          component: AboutView },
      { path: 'products',      name: 'products',       component: ProductsView },
      { path: 'products/:slug',name: 'product-detail', component: ProductDetail },
      { path: 'branches',      name: 'branches',       component: BranchesView },
      { path: 'news',          name: 'news',           component: NewsView },
      { path: 'news/:slug',    name: 'news-detail',    component: NewsDetail },
      { path: 'faq',           name: 'faq',            component: FaqView },
      { path: 'contact',       name: 'contact',        component: ContactView },
      { path: 'request',       name: 'request',        component: RequestSystem },
      { path: 'quote',         name: 'quote',          component: QuoteView },
      { path: 'support',       name: 'support',        component: SupportView },
      { path: 'careers',       name: 'careers',        component: ApplyJob },
    ],
  },
  { path: '/admin/login', name: 'admin-login', component: AdminLogin },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '',         name: 'admin-dashboard', component: AdminDashboard },
      { path: 'products', name: 'admin-products',  component: AdminProducts },
      { path: 'posts',    name: 'admin-posts',     component: AdminPosts },
      { path: 'orders',   name: 'admin-orders',    component: AdminOrders },
      { path: 'branches', name: 'admin-branches',  component: AdminBranches },
      { path: 'messages', name: 'admin-messages',  component: AdminMessages },
      { path: 'jobs',     name: 'admin-jobs',      component: AdminJobApps },
      { path: 'tickets',  name: 'admin-tickets',   component: AdminTickets },
      { path: 'quotes',   name: 'admin-quotes',    component: AdminQuotes },
      { path: 'settings', name: 'admin-settings',  component: AdminSettings },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.isLoggedIn) {
    return { name: 'admin-login' }
  }
})

export default router
