<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    // ===================================================
    // الشات الذكي — يستخدم OpenAI إذا كان المفتاح موجوداً
    // وإلا يرجع لنظام الكلمات المفتاحية المحلي
    // ===================================================
    public function chat(Request $request)
    {
        $request->validate([
            'message'    => 'required|string|max:500',
            'session_id' => 'nullable|string',
        ]);

        $userMsg   = trim($request->message);
        $sessionId = $request->session_id ?? Str::uuid()->toString();

        // هل مفتاح OpenAI موجود في .env ؟
        $openaiKey = config('services.openai.key');

        if ($openaiKey) {
            // ===== ذكاء اصطناعي حقيقي =====
            return $this->chatWithOpenAI($userMsg, $sessionId, $openaiKey);
        } else {
            // ===== نظام الكلمات المفتاحية المحلي =====
            return $this->chatWithKeywords($userMsg, $sessionId);
        }
    }

    // -------------------------------------------------
    // نظام OpenAI ChatGPT
    // -------------------------------------------------
    private function chatWithOpenAI(string $msg, string $sessionId, string $apiKey)
    {
        // سياق الشركة
        $systemPrompt = "أنت مساعد ذكي لشركة إبداع سوفت للأنظمة الخاصة المحدودة في المملكة .
الشركة تأسست عام 2012 وتقدم أنظمة محاسبية وإدارية متخصصة.
العنوان:  - الرياض - شارع الملك خالد - عمارة الروضة - الدور الرابع.
الهاتف: 577777777.
البريد: info@ebda3soft.com.
أجب باللغة العربية فقط، بشكل مختصر وواضح ومهذب.
إذا لم تعرف الإجابة، اقترح التواصل مع الفريق مباشرة.";

        try {
            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'      => 'gpt-3.5-turbo',
                    'messages'   => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => $msg],
                    ],
                    'max_tokens' => 300,
                    'temperature'=> 0.7,
                ]);

            if ($response->successful()) {
                $reply = $response->json('choices.0.message.content');
                return response()->json([
                    'reply'       => $reply,
                    'found'       => true,
                    'session_id'  => $sessionId,
                    'engine'      => 'openai',
                    'suggestions' => $this->getSuggestions(),
                ]);
            }
        } catch (\Exception $e) {
            // إذا فشل OpenAI، ارجع للنظام المحلي
        }

        return $this->chatWithKeywords($msg, $sessionId);
    }

    // -------------------------------------------------
    // نظام الكلمات المفتاحية المحلي (Fallback)
    // -------------------------------------------------
    private function chatWithKeywords(string $userMsg, string $sessionId)
    {
        $faqs = ChatbotFaq::where('is_active', true)->get();
        $bestMatch = null;
        $bestScore = 0;
        $msgWords  = array_filter(explode(' ', $userMsg));

        foreach ($faqs as $faq) {
            $score = 0;
            foreach ($faq->keywords as $kw) {
                foreach ($msgWords as $word) {
                    if (str_contains(mb_strtolower($word), mb_strtolower($kw)) ||
                        str_contains(mb_strtolower($kw), mb_strtolower($word))) {
                        $score++;
                    }
                }
            }
            if ($score > $bestScore) { $bestScore = $score; $bestMatch = $faq; }
        }

        if ($bestScore > 0 && $bestMatch) {
            $reply = $bestMatch->answer;
            $found = true;
        } elseif ($this->isGreeting($userMsg)) {
            $reply = 'أهلاً وسهلاً! 👋 أنا مساعد إبداع سوفت. كيف يمكنني مساعدتك؟';
            $found = true;
        } else {
            $reply = "عذراً، لم أتمكن من فهم سؤالك. 🤔\nتواصل مع فريقنا مباشرة:\n📞 01 337571\n💬 واتساب: 967776400070+\n✉️ info@ebda3soft.com";
            $found = false;
        }

        return response()->json([
            'reply'       => $reply,
            'found'       => $found,
            'session_id'  => $sessionId,
            'engine'      => 'keywords',
            'suggestions' => $this->getSuggestions(),
        ]);
    }

    private function getSuggestions(): array
    {
        return ['ما هي أنظمة إبداع سوفت؟', 'كيف أطلب نظاماً؟', 'ما هي أسعار الأنظمة؟', 'أين مكاتب الشركة؟', 'كيف أفتح تذكرة دعم؟'];
    }

    private function isGreeting(string $text): bool
    {
        foreach (['مرحبا','مرحباً','أهلاً','اهلا','هلا','السلام','hello','hi','hey'] as $g) {
            if (str_contains(mb_strtolower($text), $g)) return true;
        }
        return false;
    }

    // إدارة أسئلة الـ Chatbot (للمشرف)
    public function faqs()
    {
        return response()->json(ChatbotFaq::orderBy('sort_order')->get());
    }

    public function storeFaq(Request $request)
    {
        return response()->json(ChatbotFaq::create($request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'keywords' => 'required|array',
            'category' => 'nullable|string|max:80',
        ])), 201);
    }

    public function updateFaq(Request $request, ChatbotFaq $faq)
    {
        $faq->update($request->all());
        return response()->json($faq);
    }

    public function deleteFaq(ChatbotFaq $faq)
    {
        $faq->delete();
        return response()->json(['message' => 'تم الحذف.']);
    }
}
