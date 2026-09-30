<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonationController extends Controller
{
    /**
     * Status yang diizinkan beserta label tampilannya.
     */
    private const STATUS_LABELS = [
        'success' => 'Berhasil / Terverifikasi',
        'pending' => 'Menunggu Pembayaran',
        'failed' => 'Gagal / Dibatalkan',
        'expired' => 'Kadaluarsa',
    ];

    /**
     * index
     */
    public function index(Request $request)
    {
        $query = Donation::with(['donatur', 'campaign']);

        // Cari berdasarkan invoice atau nama donatur.
        if ($request->filled('q')) {
            $search = escapeLike($request->q);

            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'like', '%'.$search.'%')
                    ->orWhereHas('donatur', function ($donaturQuery) use ($search) {
                        $donaturQuery->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->latest()->paginate(10);
        $totalSuccess = Donation::where('status', Donation::STATUS_SUCCESS)->sum('amount');
        $totalPending = Donation::where('status', 'pending')->count();

        return view('admin.donation.index', compact('donations', 'totalSuccess', 'totalPending'));
    }

    /**
     * filter (alias untuk index, menjaga kompatibilitas route lama)
     */
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    /**
     * updateStatus
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(self::STATUS_LABELS))],
        ]);

        $donation = Donation::with('campaign')->findOrFail($id);
        $newStatus = $validated['status'];

        // Menandai success saat campaign sudah tercapai target akan membuat
        // current_donation melebihi target, jadi tolak di sini.
        if ($newStatus === Donation::STATUS_SUCCESS
            && $donation->status !== Donation::STATUS_SUCCESS
            && $donation->campaign
            && $donation->campaign->remainingTarget() < $donation->amount
        ) {
            return back()->with('error', 'Status tidak diubah: campaign sudah mencapai target donasi.');
        }

        $donation->status = $newStatus;
        $donation->save();

        $statusLabel = self::STATUS_LABELS[$newStatus] ?? $newStatus;

        return back()->with('success', "Status donasi invoice {$donation->invoice} berhasil diubah menjadi: {$statusLabel}");
    }
}
