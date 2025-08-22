<?php
// app/Http/Controllers/ChatController.php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = Chat::getConversations(Auth::id());
        
        // Hanya kirim conversations tanpa otherUser dan chats
        return view('chats.index', compact('conversations'));
    }

    public function show($userId)
    {
        $otherUser = User::findOrFail($userId);
        
        if ($otherUser->id === Auth::id()) {
            return redirect()->route('chats.index')
                ->with('error', 'Anda tidak dapat chat dengan diri sendiri.');
        }

        $chats = Chat::getConversationWith(Auth::id(), $userId);
        $conversations = Chat::getConversations(Auth::id());
        
        // Mark messages as read
        Chat::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chats.show', compact('otherUser', 'chats', 'conversations'));
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'receiver_id' => 'required|exists:users,id|different:' . Auth::id(),
        'message' => 'required|string|max:1000',
        'item_id' => 'nullable|exists:items,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $chat = Chat::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $request->receiver_id,
        'item_id' => $request->item_id,
        'message' => $request->message,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pesan berhasil dikirim!',
        'chat' => [
            'id' => $chat->id,
            'message' => $chat->message,
            'sender_id' => $chat->sender_id,
            'sender_name' => $chat->sender->name,
            'created_at' => $chat->created_at->format('H:i'),
        ]
    ]);
}


    public function startChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|different:' . Auth::id(),
            'item_id' => 'nullable|exists:items,id',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if chat already exists
        $existingChat = Chat::betweenUsers(Auth::id(), $request->user_id)->first();

        if ($existingChat) {
            return redirect()->route('chats.show', $request->user_id);
        }

        // Create new chat
        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->user_id,
            'item_id' => $request->item_id,
            'message' => $request->message,
        ]);

        return redirect()->route('chats.show', $request->user_id)
            ->with('success', 'Chat berhasil dimulai!');
    }

    public function getMessages($userId)
    {
        $chats = Chat::getConversationWith(Auth::id(), $userId);

        return response()->json([
            'chats' => $chats->map(function($chat) {
                return [
                    'id' => $chat->id,
                    'message' => $chat->message,
                    'sender_id' => $chat->sender_id,
                    'sender_name' => $chat->sender->name,
                    'receiver_id' => $chat->receiver_id,
                    'receiver_name' => $chat->receiver->name,
                    'is_read' => $chat->is_read,
                    'created_at' => $chat->created_at_formatted,
                    'created_at_human' => $chat->created_at_human,
                    'item' => $chat->item ? [
                        'id' => $chat->item->id,
                        'title' => $chat->item->title,
                        'url' => route('items.show', $chat->item->id),
                    ] : null,
                ];
            })
        ]);
    }

    public function markAsRead($userId)
    {
        Chat::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->getUnreadChatsCount();

        return response()->json(['count' => $count]);
    }

    public function deleteConversation($userId)
    {
        Chat::betweenUsers(Auth::id(), $userId)->delete();

        return redirect()->route('chats.index')
            ->with('success', 'Percakapan berhasil dihapus!');
    }

    // Admin methods
    public function adminIndex(Request $request)
    {
        $query = Chat::with(['sender', 'receiver', 'item'])
            ->latest();

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('message', 'like', "%{$request->search}%")
                  ->orWhereHas('sender', function($subQ) use ($request) {
                      $subQ->where('name', 'like', "%{$request->search}%");
                  })
                  ->orWhereHas('receiver', function($subQ) use ($request) {
                      $subQ->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        // Filter by date
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $chats = $query->paginate(20);

        return view('admin.chats.index', compact('chats'));
    }

    public function adminDestroy($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();

        return back()->with('success', 'Chat berhasil dihapus!');
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chats' => 'required|array|min:1',
            'chats.*' => 'exists:chats,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        Chat::whereIn('id', $request->chats)->delete();

        return back()->with('success', 'Chat terpilih berhasil dihapus!');
    }
}