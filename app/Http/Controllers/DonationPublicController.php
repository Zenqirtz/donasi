<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donatur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationPublicController extends Controller
{
    /**
     * Nominal donasi minimal dan maksimal.
     */
    private const MIN_AMOUNT = 1000;

    private const MAX_AMOUNT = 1000000000;

    /**
     * Halaman detail campaign
     */
    public function show($slug)
    {
        $campaign = Campaign::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $recentDonations = $campaign->donations()
            ->with('donatur')
            ->where('status', Donation::STATUS_SUCCESS)
            ->latest()
            ->take(5)
            ->get();

        $totalTerkumpul = $campaign->sumDonation();
        $persentase = $campaign->target_donation > 0
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'required|integer|min:'.self::MIN_AMOUNT.'|max:'.self::MAX_AMOUNT,
            'pray' => 'nullable|string|max:500',
        ]);

        if ($campaign->isExpired()) {
            return back()->withErrors(['campaign' => 'Donasi sudah ditutup.']);
        }

        if ($campaign->isFullyFunded()) {
            return back()->withErrors(['campaign' => 'Campaign ini sudah mencapai target donasi.']);
        }

        // Sisa target dicek ulang setelah validasi supaya donasi yang dikirim
        // bersamaan tidak membuat current_donation melewati target.
        $amount = (int) $validated['amount'];
        $remaining = $campaign->remainingTarget();

        if ($amount > $remaining) {
            return back()->withErrors([
                'amount' => 'Nominal donasi melebihi sisa target campaign ('.moneyFormat($remaining).').',
            ]);
        }

        // Donatur dicari ulang dengan withTrashed supaya donatur yang pernah
        // di-soft-delete tidak memicu unique violation pada kolom email.
        $donatur = Donatur::findOrCreateByEmail($validated['email'], $validated['name']);

        $donation = DB::transaction(function () use ($campaign, $donatur, $amount, $validated) {
            $campaign->refresh();

            if ($campaign->isExpired() || $campaign->isFullyFunded()) {
                abort(409, 'Campaign sudah tidak menerima donasi.');
            }

            return Donation::create([
                'invoice' => $this->generateInvoice(),
                'campaign_id' => $campaign->id,
                'donatur_id' => $donatur->id,
                'amount' => $amount,
                'pray' => $validated['pray'] ?? null,
                'status' => 'pending',
                'snap_token' => null,
            ]);
        });

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

        // Jika sudah selesai, arahkan ke halaman sukses.
        if ($donation->status === Donation::STATUS_SUCCESS) {
            return redirect()->route('public.donation.success', $donation->invoice);
        }

        if (in_array($donation->status, ['failed', 'expired'], true)) {
            return redirect()->route('public.campaign.show', $donation->campaign->slug)
                ->with('error', 'Donasi ini sudah '.$donation->status.' dan tidak bisa dibayar.');
        }

        $qrisPayload = '00020101021226580016ID.CO.PEDULIKITA.WWW0118'.$donation->invoice
            .'520454995303360540'.strlen($donation->amount).$donation->amount
            .'5802ID5910PEDULI KITA6007JAKARTA62070703A016304';

        return view('public.donation-payment', compact('donation', 'qrisPayload'));
    }

    /**
     * Konfirmasi pembayaran donasi (simulasi, tanpa payment gateway).
     *
     * Route ini memakai signed URL sehingga invoice tidak bisa ditebak, dan
     * hanya donasi berstatus pending yang boleh diubah jadi success supaya
     * donasi orang lain tidak bisa dimanipulasi lewat URL miliknya.
     */
    public function confirm($invoice)
    {
        $donation = Donation::with('campaign')
            ->where('invoice', $invoice)
            ->firstOrFail();

        if ($donation->status === Donation::STATUS_SUCCESS) {
            return redirect()->route('public.donation.success', $donation->invoice);
        }

        if ($donation->status !== 'pending') {
            return redirect()->route('public.campaign.show', $donation->campaign->slug)
                ->with('error', 'Donasi ini sudah tidak dapat dikonfirmasi.');
        }

        if ($donation->campaign && $donation->campaign->isFullyFunded()) {
            return redirect()->route('public.campaign.show', $donation->campaign->slug)
                ->with('error', 'Campaign ini sudah mencapai target donasi.');
        }

        DB::transaction(function () use ($donation) {
            $donation->campaign?->refresh();

            if ($donation->campaign && $donation->campaign->isFullyFunded()) {
                abort(409, 'Campaign sudah mencapai target donasi.');
            }

            $donation->status = Donation::STATUS_SUCCESS;
            $donation->save();
        });

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

    /**
     * Buat nomor invoice yang dijamin belum terpakai.
     */
    protected function generateInvoice(): string
    {
        do {
            $invoice = 'INV-'.strtoupper(Str::random(10)).'-'.now()->format('YmdHis');
        } while (Donation::where('invoice', $invoice)->exists());

        return $invoice;
    }
}
