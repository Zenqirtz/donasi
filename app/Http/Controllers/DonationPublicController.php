<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donatur;
use Illuminate\Http\Request;
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

        if ($campaign->isExpired()) {
            return back()->withErrors(['campaign' => 'Donasi sudah ditutup.']);
        }

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

        return redirect()->route('public.donation.payment', $donation->invoice);
    }

    /**
     * Halaman pembayaran / QRIS
     */
    public function payment($invoice)
    {
        $donation = Donation::with(['campaign', 'donatur'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        // Jika sudah success, langsung ke success page
        if ($donation->status === 'success') {
            return redirect()->route('public.donation.success', $donation->invoice);
        }

        $qrisPayload = '00020101021226580016ID.CO.PEDULIKITA.WWW0118' . $donation->invoice . '520454995303360540' . strlen($donation->amount) . $donation->amount . '5802ID5910PEDULI KITA6007JAKARTA62070703A016304';

        return view('public.donation-payment', compact('donation', 'qrisPayload'));
    }

    /**
     * Konfirmasi pembayaran donasi (simulasi)
     */
    public function confirm($invoice)
    {
        $donation = Donation::where('invoice', $invoice)->firstOrFail();

        // Ubah status jadi success
        $donation->status = 'success';
        $donation->save();

        return redirect()->route('public.donation.success', $donation->invoice)
            ->with('success', 'Alhamdulillah! Pembayaran donasi Anda telah berhasil dikonfirmasi.');
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
