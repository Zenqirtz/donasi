<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donatur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DonationPublicController extends Controller
{
    /**
     * Halaman detail campaign
     */
    public function show($slug)
    {
        $campaign = Campaign::with(['category', 'donations.donatur'])
            ->where('slug', $slug)
            ->firstOrFail();

        $recentDonations = $campaign->donations()
            ->with('donatur')
            ->where('status', 'success')
            ->latest()
            ->take(5)
            ->get();

        $totalTerkumpul = $campaign->sumDonation();
        $persentase     = $campaign->target_donation > 0
            ? min(100, round(($totalTerkumpul / $campaign->target_donation) * 100))
            : 0;

        return view('public.campaign-detail', compact('campaign', 'recentDonations', 'totalTerkumpul', 'persentase'));
    }

    /**
     * Proses donasi: simpan donatur + donation ke DB (tanpa payment gateway)
     */
    public function donate(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email',
            'amount' => 'required|integer|min:1000',
            'pray'   => 'nullable|string|max:500',
        ]);

        // Cari atau buat donatur berdasarkan email
        $donatur = Donatur::firstOrCreate(
            ['email' => $request->email],
            [
                'name'     => $request->name,
                'password' => Hash::make(Str::random(12)),
            ]
        );

        // Update nama jika sudah ada
        if ($donatur->wasRecentlyCreated === false) {
            $donatur->update(['name' => $request->name]);
        }

        // Buat invoice unik
        $invoice = 'INV-' . strtoupper(Str::random(8)) . '-' . date('YmdHis');

        // Simpan donasi dengan status pending (simulasi tanpa payment gateway)
        $donation = Donation::create([
            'invoice'     => $invoice,
            'campaign_id' => $campaign->id,
            'donatur_id'  => $donatur->id,
            'amount'      => $request->amount,
            'pray'        => $request->pray,
            'status'      => 'pending',
            'snap_token'  => null,
        ]);

        return redirect()->route('public.donation.success', $donation->invoice)
            ->with('success', 'Terima kasih! Donasi Anda sedang diproses.');
    }

    /**
     * Halaman sukses setelah donasi
     */
    public function success($invoice)
    {
        $donation = Donation::with(['campaign', 'donatur'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        return view('public.donation-success', compact('donation'));
    }
}
