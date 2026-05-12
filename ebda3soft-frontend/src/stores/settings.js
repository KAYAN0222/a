import { defineStore } from 'pinia'
import api from '../api'

export const useSettingsStore = defineStore('settings', {
  state: () => ({
    data: {},
    loaded: false,
  }),
  actions: {
    async fetch() {
      if (this.loaded) return
      try {
        const { data } = await api.get('/settings')
        this.data = data
        this.loaded = true
      } catch {}
    },
  },
})
