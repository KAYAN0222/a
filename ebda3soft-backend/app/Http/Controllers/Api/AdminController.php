<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Order;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\Product;
use App\Models\Post;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\Quote;

class AdminController extends Controller
{
    // إحصائيات لوحة التحكم
    public function dashboard()
    {
        return response()->json([
            'stats' => [
                'products'         => Product::count(),
                'active_products'  => Product::where('is_active', true)->count(),
                'posts'            => Post::count(),
                'published_posts'  => Post::where('is_published', true)->count(),
                'orders'           => Order::count(),
                'pending_orders'   => Order::where('status', 'pending')->count(),
                'clients'          => Client::count(),
                'messages'         => ContactMessage::count(),
                'unread_messages'  => ContactMessage::where('is_read', false)->count(),
                'job_apps'         => JobApplication::count(),
                'new_job_apps'     => JobApplication::where('status', 'new')->count(),
                'open_tickets'     => Ticket::where('status', 'open')->count(),
                'new_quotes'       => Quote::where('status', 'new')->count(),
            ],
            'recent_orders'   => Order::with(['product:id,name_ar', 'client:id,company_name'])
                ->latest()->take(5)->get(),
            'recent_messages' => ContactMessage::latest()->take(5)->get(),
        ]);
    }

    // لوحة التحليلات المتقدمة
    public function analytics()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = now()->subMonths($i)->format('Y-m');
        }

        $ordersPerMonth = Order::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as month, COUNT(*) as count")
            ->whereRaw("created_at >= ?", [now()->subMonths(6)])
            ->groupBy('month')->pluck('count', 'month');

        $messagesPerMonth = ContactMessage::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as month, COUNT(*) as count")
            ->whereRaw("created_at >= ?", [now()->subMonths(6)])
            ->groupBy('month')->pluck('count', 'month');

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        $topProducts = Product::withCount('orders')
            ->orderByDesc('orders_count')->take(5)->get(['id', 'name_ar', 'orders_count']);

        $ticketsByStatus   = Ticket::selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count','status');
        $ticketsByPriority = Ticket::selectRaw('priority, COUNT(*) as count')->groupBy('priority')->pluck('count','priority');

        return response()->json([
            'months'              => $months,
            'orders_per_month'    => array_map(fn($m) => $ordersPerMonth[$m]  ?? 0, $months),
            'messages_per_month'  => array_map(fn($m) => $messagesPerMonth[$m] ?? 0, $months),
            'orders_by_status'    => $ordersByStatus,
            'top_products'        => $topProducts,
            'tickets_by_status'   => $ticketsByStatus,
            'tickets_by_priority' => $ticketsByPriority,
        ]);
    }

    // إعدادات الموقع
    public function getSettings()
    {
        return response()->json(Setting::all()->keyBy('key')->map->value);
    }

    public function updateSettings(\Illuminate\Http\Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::set($key, $value);
        }
        return response()->json(['message' => 'تم حفظ الإعدادات بنجاح.']);
    }

    // الرسائل الواردة
    public function messages(\Illuminate\Http\Request $request)
    {
        return response()->json(ContactMessage::latest()->paginate(20));
    }

    public function markMessageRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return response()->json(['message' => 'تم تحديد الرسالة كمقروءة.']);
    }

    // طلبات التوظيف
    public function jobApplications()
    {
        return response()->json(JobApplication::latest()->paginate(20));
    }

    public function updateJobStatus(\Illuminate\Http\Request $request, JobApplication $job)
    {
        $request->validate(['status' => 'required|in:new,reviewed,interviewed,hired,rejected']);
        $job->update(['status' => $request->status]);
        return response()->json(['message' => 'تم تحديث الحالة.']);
    }

    // الطلبات
    public function orders(\Illuminate\Http\Request $request)
    {
        $orders = Order::with(['product:id,name_ar', 'client:id,company_name', 'branch:id,name_ar'])
            ->latest()->paginate(20);
        return response()->json($orders);
    }

    public function updateOrderStatus(\Illuminate\Http\Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,completed,cancelled']);
        $order->update(['status' => $request->status]);
        return response()->json(['message' => 'تم تحديث حالة الطلب.']);
    }
}
