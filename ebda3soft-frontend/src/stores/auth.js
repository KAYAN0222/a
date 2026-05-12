import { defineStore } from 'pinia'
import api from '../api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('ebda3_user') || 'null'),
    token: localStorage.getItem('ebda3_token') || null,
    loading: false,
    error: null,
  }),

  getters: {
    isLoggedIn: (state) => !!state.token,
    isAdmin: (state) => state.user?.role === 'admin',
    userName: (state) => state.user?.name || '',
  },

  actions: {
    async login(email, password) {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.post('/auth/login', { email, password })
        this.token = data.token
        this.user = data.user
        localStorage.setItem('ebda3_token', data.token)
        localStorage.setItem('ebda3_user', JSON.stringify(data.user))
        return true
      } catch (err) {
        this.error = err.response?.data?.message || 'بيانات الاعتماد غير صحيحة'
        return false
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try { await api.post('/auth/logout') } catch {}
      this.token = null
      this.user = null
      localStorage.removeItem('ebda3_token')
      localStorage.removeItem('ebda3_user')
    },
  },
})
