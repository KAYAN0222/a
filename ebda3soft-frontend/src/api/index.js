import axios from 'axios'

const api = axios.create({
  baseURL: 'https://a-iles.onrender.com/api',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

// إضافة التوكن تلقائياً
api.interceptors.request.use(config => {
  const token = localStorage.getItem('ebda3_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

// معالجة الأخطاء العامة
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('ebda3_token')
      localStorage.removeItem('ebda3_user')
      window.location.href = '/admin/login'
    }
    return Promise.reject(error)
  }
)

export default api
