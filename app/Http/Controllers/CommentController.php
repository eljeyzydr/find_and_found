<?php
// app/Http/Controllers/CommentController.php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:5|max:1000',
        ], [
            'content.required' => 'Komentar harus diisi.',
            'content.min' => 'Komentar minimal 5 karakter.',
            'content.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Comment::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if (!$comment->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak dapat mengedit komentar ini.');
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:5|max:1000',
        ], [
            'content.required' => 'Komentar harus diisi.',
            'content.min' => 'Komentar minimal 5 karakter.',
            'content.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil diperbarui!',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'updated_at' => $comment->created_at_human,
            ]
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (!$comment->canBeDeletedBy(Auth::user())) {
            abort(403, 'Anda tidak dapat menghapus komentar ini.');
        }

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus!'
            ]);
        }

        return back()->with('success', 'Komentar berhasil dihapus!');
    }

    // Admin methods
    public function adminIndex(Request $request)
    {
        $query = Comment::with(['user', 'item'])
            ->latest();

        // Filter berdasarkan status approval
        if ($request->status === 'pending') {
            $query->pending();
        } elseif ($request->status === 'approved') {
            $query->approved();
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('content', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($subQ) use ($request) {
                      $subQ->where('name', 'like', "%{$request->search}%");
                  })
                  ->orWhereHas('item', function($subQ) use ($request) {
                      $subQ->where('title', 'like', "%{$request->search}%");
                  });
            });
        }

        $comments = $query->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approve();

        return back()->with('success', 'Komentar berhasil disetujui!');
    }

    public function reject($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->reject();

        return back()->with('success', 'Komentar berhasil ditolak!');
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject,delete',
            'comments' => 'required|array|min:1',
            'comments.*' => 'exists:comments,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $comments = Comment::whereIn('id', $request->comments);

        switch ($request->action) {
            case 'approve':
                $comments->update(['is_approved' => true]);
                $message = 'Komentar terpilih berhasil disetujui!';
                break;
            case 'reject':
                $comments->update(['is_approved' => false]);
                $message = 'Komentar terpilih berhasil ditolak!';
                break;
            case 'delete':
                $comments->delete();
                $message = 'Komentar terpilih berhasil dihapus!';
                break;
        }

        return back()->with('success', $message);
    }
}