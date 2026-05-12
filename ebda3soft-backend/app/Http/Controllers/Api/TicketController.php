<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    // عرض التذاكر (للعام — بالبريد أو الهاتف)
    public function index(Request $request)
    {
        $query = Ticket::with(['product:id,name_ar', 'assignee:id,name'])
            ->latest();

        if ($request->status)   $query->where('status', $request->status);
        if ($request->priority) $query->where('priority', $request->priority);

        return response()->json($query->paginate(20));
    }

    // عرض تذكرة واحدة
    public function show(string $number)
    {
        $ticket = Ticket::with(['product:id,name_ar', 'replies', 'assignee:id,name'])
            ->where('ticket_number', $number)
            ->firstOrFail();

        return response()->json($ticket);
    }

    // إنشاء تذكرة جديدة (عام)
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject'          => 'required|string|max:200',
            'description'      => 'required|string|max:3000',
            'requester_name'   => 'required|string|max:100',
            'requester_email'  => 'nullable|email',
            'requester_phone'  => 'nullable|string|max:30',
            'product_id'       => 'nullable|exists:products,id',
            'priority'         => 'in:low,medium,high,critical',
            'attachment'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('tickets', 'public');
        }

        $ticketNumber = 'TKT-' . strtoupper(substr(uniqid(), -7));
        $data['ticket_number'] = $ticketNumber;

        $ticket = Ticket::create($data);

        return response()->json([
            'message'       => 'تم فتح تذكرة دعم فني بنجاح.',
            'ticket_number' => $ticketNumber,
            'ticket'        => $ticket,
        ], 201);
    }

    // الرد على التذكرة
    public function reply(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'message'    => 'required|string|max:3000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('ticket-replies', 'public');
        }

        $isStaff = auth()->check();
        $data['is_staff']    = $isStaff;
        $data['user_id']     = auth()->id();
        $data['author_name'] = $isStaff ? auth()->user()->name : ($ticket->requester_name);
        $data['ticket_id']   = $ticket->id;

        $reply = TicketReply::create($data);

        // تحديث حالة التذكرة
        if ($isStaff && $ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return response()->json($reply, 201);
    }

    // تحديث حالة التذكرة (للموظفين)
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status'      => 'required|in:open,in_progress,waiting,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update([
            'status'      => $request->status,
            'assigned_to' => $request->assigned_to,
            'resolved_at' => $request->status === 'resolved' ? now() : $ticket->resolved_at,
        ]);

        return response()->json(['message' => 'تم تحديث التذكرة.']);
    }

    // بحث تذكرة (للعميل بالرقم)
    public function track(string $number)
    {
        $ticket = Ticket::with('replies')
            ->where('ticket_number', strtoupper($number))
            ->firstOrFail();

        return response()->json($ticket);
    }
}
