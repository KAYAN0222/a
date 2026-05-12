<template>
  <div>
    <!-- زر الفتح -->
    <button @click="open=!open" class="chatbot-btn" :title="open?'إغلاق':'تحدث معنا'">
      <span v-if="!open">💬</span>
      <span v-else style="font-size:1.2rem">✕</span>
    </button>

    <transition name="slide-up">
      <div v-if="open" class="chatbot-window">
        <!-- Header مع تبويبات -->
        <div class="chatbot-header">
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.2rem">🤖</div>
            <div>
              <div style="font-weight:700;font-size:0.9rem">مساعد إبداع سوفت</div>
              <div style="font-size:0.72rem;opacity:0.8">● متاح الآن</div>
            </div>
          </div>
          <div style="display:flex;gap:4px">
            <button @click="mode='bot'" :style="`border:none;border-radius:6px;padding:4px 10px;font-size:0.75rem;cursor:pointer;font-family:Cairo,sans-serif;${mode==='bot'?'background:white;color:var(--primary)':'background:rgba(255,255,255,0.2);color:white'}`">🤖 ذكاء</button>
            <button @click="mode='live'" :style="`border:none;border-radius:6px;padding:4px 10px;font-size:0.75rem;cursor:pointer;font-family:Cairo,sans-serif;${mode==='live'?'background:white;color:var(--primary)':'background:rgba(255,255,255,0.2);color:white'}`">👤 مباشر</button>
            <button @click="open=false" style="background:none;border:none;color:white;font-size:1.1rem;cursor:pointer;margin-right:4px">✕</button>
          </div>
        </div>

        <!-- === وضع الـ Chatbot الذكي === -->
        <template v-if="mode==='bot'">
          <div class="chatbot-messages" ref="msgContainer">
            <div v-for="(msg, i) in messages" :key="i" :class="`chatbot-msg chatbot-msg-${msg.role}`">
              <div class="chatbot-bubble">{{ msg.text }}</div>
              <div class="chatbot-time">{{ msg.time }}</div>
            </div>
            <div v-if="typing" class="chatbot-msg chatbot-msg-bot">
              <div class="chatbot-bubble" style="display:flex;gap:4px;align-items:center">
                <span class="dot-anim">●</span><span class="dot-anim" style="animation-delay:0.2s">●</span><span class="dot-anim" style="animation-delay:0.4s">●</span>
              </div>
            </div>
          </div>

          <div v-if="messages.length <= 1" class="chatbot-suggestions">
            <button v-for="s in suggestions" :key="s" class="chatbot-suggest" @click="sendMsg(s)">{{ s }}</button>
          </div>

          <div class="chatbot-input-row">
            <input v-model="input" @keyup.enter="sendMsg()" class="chatbot-input" placeholder="اكتب سؤالك..." :disabled="typing" />
            <button @click="sendMsg()" class="chatbot-send" :disabled="!input.trim() || typing">➤</button>
          </div>
        </template>

        <!-- === وضع الشات المباشر === -->
        <template v-else>
          <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;text-align:center;background:#f8faff">
            <div style="font-size:3rem;margin-bottom:16px">👋</div>
            <h3 style="color:var(--primary);font-size:1rem;margin-bottom:10px">تحدث مع فريقنا مباشرة</h3>
            <p style="color:var(--text-muted);font-size:0.85rem;line-height:1.7;margin-bottom:20px">فريق الدعم متاح من 8 صباحاً حتى 8 مساءً</p>

            <a :href="`https://wa.me/${whatsapp}?text=مرحباً، أريد الاستفسار عن أنظمة إبداع سوفت`"
               target="_blank"
               style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;background:#25d366;color:white;padding:12px;border-radius:10px;font-weight:700;margin-bottom:10px;text-decoration:none;font-size:0.9rem">
              <span style="font-size:1.3rem">💬</span> واتساب (WhatsApp)
            </a>

            <a href="tel:0571870415"
               style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;background:var(--primary);color:white;padding:12px;border-radius:10px;font-weight:700;margin-bottom:10px;text-decoration:none;font-size:0.9rem">
              <span style="font-size:1.3rem">📞</span> اتصال هاتفي — 0571870415
            </a>

            <a href="mailto:info@ebda3soft.com"
               style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;background:var(--accent);color:white;padding:12px;border-radius:10px;font-weight:700;text-decoration:none;font-size:0.9rem">
              <span style="font-size:1.3rem">✉️</span> بريد إلكتروني
            </a>

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);width:100%">
              <p style="font-size:0.78rem;color:var(--text-muted)">أو افتح تذكرة دعم فني</p>
              <router-link to="/support" @click="open=false" style="color:var(--primary);font-size:0.82rem;font-weight:600">🎫 فتح تذكرة دعم ←</router-link>
            </div>
          </div>
        </template>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import api from '../api'

const open      = ref(false)
const mode      = ref('bot')   // 'bot' | 'live'
const input     = ref('')
const typing    = ref(false)
const sessionId = ref(null)
const msgContainer = ref(null)
const whatsapp  = '966571870415'

const messages = ref([
  { role: 'bot', text: 'أهلاً وسهلاً! 👋 أنا مساعد إبداع سوفت. كيف يمكنني مساعدتك؟', time: now() },
])
const suggestions = ref([
  'ما هي أنظمة إبداع سوفت؟',
  'كيف أطلب نظاماً؟',
  'أين مكاتبكم؟',
  'كيف أفتح تذكرة دعم؟',
])

function now() {
  return new Date().toLocaleTimeString('ar', { hour: '2-digit', minute: '2-digit' })
}

async function sendMsg(text = null) {
  const msg = text || input.value.trim()
  if (!msg) return
  input.value = ''
  messages.value.push({ role: 'user', text: msg, time: now() })
  typing.value = true
  await scrollBottom()
  try {
    const { data } = await api.post('/chatbot', { message: msg, session_id: sessionId.value })
    sessionId.value = data.session_id
    await new Promise(r => setTimeout(r, 600))
    messages.value.push({ role: 'bot', text: data.reply, time: now() })
  } catch {
    messages.value.push({ role: 'bot', text: 'عذراً، حدث خطأ. يرجى المحاولة لاحقاً.', time: now() })
  } finally {
    typing.value = false
    await scrollBottom()
  }
}

async function scrollBottom() {
  await nextTick()
  if (msgContainer.value) msgContainer.value.scrollTop = msgContainer.value.scrollHeight
}
</script>

<style scoped>
.chatbot-btn {
  position: fixed;
  bottom: 96px;
  left: 28px;
  width: 56px; height: 56px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary-light), var(--primary));
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
  box-shadow: 0 4px 20px rgba(26,58,107,0.35);
  z-index: 998;
  transition: transform 0.3s;
}
.chatbot-btn:hover { transform: scale(1.1); }

.chatbot-window {
  position: fixed;
  bottom: 164px;
  left: 28px;
  width: 340px;
  height: 500px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 50px rgba(0,0,0,0.2);
  z-index: 997;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.chatbot-header {
  background: linear-gradient(135deg, var(--primary-light), var(--primary));
  padding: 14px 16px;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-shrink: 0;
}

.chatbot-messages {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  background: #f8faff;
}

.chatbot-msg { display: flex; flex-direction: column; }
.chatbot-msg-bot  { align-items: flex-end; }
.chatbot-msg-user { align-items: flex-start; }

.chatbot-bubble {
  max-width: 82%;
  padding: 10px 14px;
  border-radius: 16px;
  font-size: 0.87rem;
  line-height: 1.6;
}
.chatbot-msg-bot  .chatbot-bubble { background: white; color: var(--text); border-bottom-right-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.chatbot-msg-user .chatbot-bubble { background: var(--primary); color: white; border-bottom-left-radius: 4px; }
.chatbot-time { font-size: 0.7rem; color: var(--text-muted); margin-top: 3px; }

.chatbot-suggestions {
  padding: 10px 12px;
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  border-top: 1px solid var(--border);
  background: white;
  flex-shrink: 0;
}
.chatbot-suggest {
  font-size: 0.75rem;
  padding: 5px 10px;
  border: 1px solid var(--border);
  border-radius: 16px;
  background: white;
  cursor: pointer;
  color: var(--primary);
  font-family: 'Tajawal', 'Noto Kufi Arabic', sans-serif;
  transition: all 0.2s;
}
.chatbot-suggest:hover { background: var(--primary); color: white; border-color: var(--primary); }

.chatbot-input-row {
  display: flex;
  gap: 8px;
  padding: 12px;
  border-top: 1px solid var(--border);
  background: white;
  flex-shrink: 0;
}
.chatbot-input {
  flex: 1;
  padding: 10px 14px;
  border: 2px solid var(--border);
  border-radius: 20px;
  font-family: 'Tajawal', 'Noto Kufi Arabic', sans-serif;
  font-size: 0.88rem;
  outline: none;
  transition: 0.2s;
}
.chatbot-input:focus { border-color: var(--primary-light); }
.chatbot-send {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: var(--primary);
  border: none;
  color: white;
  font-size: 1rem;
  cursor: pointer;
}
.chatbot-send:disabled { opacity: 0.5; }

.dot-anim { animation: blink 1s infinite; }
@keyframes blink { 0%,100%{opacity:0.3} 50%{opacity:1} }

.slide-up-enter-active { animation: slideUp 0.3s ease; }
.slide-up-leave-active { animation: slideUp 0.3s ease reverse; }
@keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
</style>
