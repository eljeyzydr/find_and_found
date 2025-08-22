<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function create($itemId)
    {
        $item = Item::findOrFail($itemId);

        // Check if user already reported this item
        $existingReport = Report::where('item_id', $itemId)
            ->where('reporter_id', Auth::id())
            ->first();

        if ($existingReport) {
            return back()->with('error', 'Anda sudah melaporkan item ini sebelumnya.');
        }

        // Can't report own item
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat melaporkan item milik sendiri.');
        }

        $reasons = Report::getReasonOptions();

        return view('reports.create', compact('item', 'reasons'));
    }

    public function store(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        // Check if user already reported this item
        $existingReport = Report::where('item_id', $itemId)
            ->where('reporter_id', Auth::id())
            ->first();

        if ($existingReport) {
            return back()->with('error', 'Anda sudah melaporkan item ini sebelumnya.');
        }

        // Can't report own item
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat melaporkan item milik sendiri.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|in:' . implode(',', array_keys(Report::getReasonOptions())),
            'description' => 'nullable|string|max:1000',
        ], [
            'reason.required' => 'Alasan laporan harus dipilih.',
            'reason.in' => 'Alasan laporan tidak valid.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Report::create([
            'item_id' => $itemId,
            'reporter_id' => Auth::id(),
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return redirect()->route('items.show', $itemId)
            ->with('success', 'Laporan berhasil dikirim. Terima kasih atas partisipasi Anda.');
    }

    public function myReports()
    {
        $reports = Report::where('reporter_id', Auth::id())
            ->with(['item', 'reviewer'])
            ->latest()
            ->paginate(20);

        return view('reports.my-reports', compact('reports'));
    }

    // Admin methods
    public function adminIndex(Request $request)
    {
        $query = Report::with(['item', 'reporter', 'reviewer'])
            ->latest();

        // Filter by status
        if ($request->status && in_array($request->status, ['pending', 'reviewed', 'resolved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Filter by reason
        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', "%{$request->search}%")
                  ->orWhereHas('item', function($subQ) use ($request) {
                      $subQ->where('title', 'like', "%{$request->search}%");
                  })
                  ->orWhereHas('reporter', function($subQ) use ($request) {
                      $subQ->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $reports = $query->paginate(20);
        $reasons = Report::getReasonOptions();

        return view('admin.reports.index', compact('reports', 'reasons'));
    }

    public function adminShow($id)
    {
        $report = Report::with(['item.user', 'item.category', 'item.location', 'reporter', 'reviewer'])
            ->findOrFail($id);

        return view('admin.reports.show', compact('report'));
    }

    public function review(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $report->review(Auth::id(), $request->admin_note);

        return back()->with('success', 'Laporan berhasil ditinjau!');
    }

    public function resolve(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'admin_note' => 'nullable|string|max:1000',
            'action' => 'required|in:block_item,block_user,warning,no_action',
        ], [
            'action.required' => 'Tindakan harus dipilih.',
            'action.in' => 'Tindakan tidak valid.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Take action based on admin decision
        switch ($request->action) {
            case 'block_item':
                $report->item->update(['is_active' => false]);
                break;
            case 'block_user':
                $report->item->user->update(['status' => 'blocked']);
                break;
            case 'warning':
                // TODO: Send warning notification to user
                break;
            case 'no_action':
                // No action needed
                break;
        }

        $report->resolve(Auth::id(), $request->admin_note);

        return back()->with('success', 'Laporan berhasil diselesaikan!');
    }

    public function reject(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $report->reject(Auth::id(), $request->admin_note);

        return back()->with('success', 'Laporan berhasil ditolak!');
    }

    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:review,resolve,reject,delete',
            'reports' => 'required|array|min:1',
            'reports.*' => 'exists:reports,id',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $reports = Report::whereIn('id', $request->reports);

        switch ($request->action) {
            case 'review':
                $reports->update([
                    'status' => 'reviewed',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'admin_note' => $request->admin_note,
                ]);
                $message = 'Laporan terpilih berhasil ditinjau!';
                break;
            case 'resolve':
                $reports->update([
                    'status' => 'resolved',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'admin_note' => $request->admin_note,
                ]);
                $message = 'Laporan terpilih berhasil diselesaikan!';
                break;
            case 'reject':
                $reports->update([
                    'status' => 'rejected',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'admin_note' => $request->admin_note,
                ]);
                $message = 'Laporan terpilih berhasil ditolak!';
                break;
            case 'delete':
                $reports->delete();
                $message = 'Laporan terpilih berhasil dihapus!';
                break;
        }

        return back()->with('success', $message);
    }
}