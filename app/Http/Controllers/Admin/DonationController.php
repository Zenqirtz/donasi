<?php

namespace App\Http\Controllers\Admin;

use App\Models\Donation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DonationController extends Controller
{
    /**
     * index
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Donation::with(['donatur', 'campaign']);

        // Search by invoice or donatur name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'like', '%' . $search . '%')
                  ->orWhereHas('donatur', function ($donaturQuery) use ($search) {
                      $donaturQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by status (pending, success, failed, expired)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->latest()->paginate(10);
        $totalSuccess = Donation::where('status', 'success')->sum('amount');
        $totalPending = Donation::where('status', 'pending')->count();

        return view('admin.donation.index', compact('donations', 'totalSuccess', 'totalPending'));
    }
    
    /**
     * filter (alias for index to preserve old route compatibility)
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    /**
     * updateStatus
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:success,pending,failed,expired',
        ]);

        $donation = Donation::findOrFail($id);
        $donation->status = $request->status;
        $donation->save();

        $statusLabel = [
            'success' => 'Berhasil / Terverifikasi',
            'pending' => 'Menunggu Pembayaran',
            'failed'  => 'Gagal / Dibatalkan',
            'expired' => 'Kadaluarsa',
        ][$request->status] ?? $request->status;

        return back()->with('success', "Status donasi invoice {$donation->invoice} berhasil diubah menjadi: {$statusLabel}");
    }
}
