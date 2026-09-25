@extends('layouts.public', ['title' => 'Pembayaran Donasi - Peduli Kita'])

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-8">
        <a href="{{ route('public.home') }}" class="hover:text-indigo-600 transition">Beranda</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('public.campaigns') }}" class="hover:text-indigo-600 transition">Campaign</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-600">Pembayaran</span>
    </nav>

    {{-- Payment Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-5 text-white">
            <h3 class="text-lg font-extrabold">💳 Halaman Pembayaran</h3>
            <p class="text-xs text-indigo-200 mt-0.5">Scan QRIS atau salin kode pembayaran untuk menyelesaikan donasi</p>
        </div>

        <div class="p-6 space-y-6">
            {{-- Invoice Info --}}
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-400 font-semibold uppercase tracking-wide text-xs">Invoice</p>
                        <p class="font-bold text-slate-800 font-mono text-sm">{{ $donation->invoice }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-semibold uppercase tracking-wide text-xs">Status</p>
                        <p class="font-bold text-amber-600 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            Menunggu Pembayaran
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-semibold uppercase tracking-wide text-xs">Campaign</p>
                        <p class="font-bold text-slate-800 truncate">{{ $donation->campaign->title }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-semibold uppercase tracking-wide text-xs">Nama Donatur</p>
                        <p class="font-bold text-slate-800">{{ $donation->donatur->name }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <p class="text-slate-400 font-semibold uppercase tracking-wide text-xs">Nominal Donasi</p>
                    <p class="text-3xl font-extrabold text-indigo-600 mt-1">{{ moneyFormat($donation->amount) }}</p>
                </div>
            </div>

            {{-- QRIS Section --}}
            <div class="text-center space-y-4">
                <p class="text-sm text-slate-500">Scan QRIS berikut untuk membayar</p>
                
                {{-- QRIS Image --}}
                <div class="inline-block p-4 bg-white rounded-xl border-2 border-slate-200 shadow-sm">
                    <div class="bg-indigo-600 text-white text-xs font-extrabold tracking-widest px-4 py-2 rounded-t-lg">
                        QRIS PEMBAYARAN
                    </div>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=10&data={{ urlencode($qrisPayload) }}"
                         alt="QRIS {{ $donation->invoice }}"
                         class="w-56 h-56 block">
                    <div class="text-[10px] font-mono font-bold text-slate-600 px-2 py-2 border-t border-slate-200">
                        {{ $donation->invoice }}
                    </div>
                </div>
                
                <p class="text-xs text-slate-400">QRIS Demo - Simulasi Pembayaran</p>
            </div>

            {{-- Payment Details --}}
            <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100 space-y-3">
                <h4 class="text-sm font-bold text-indigo-800 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Detail Pembayaran
                </h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-white rounded-lg p-3">
                        <p class="text-indigo-400 font-semibold text-xs">Metode</p>
                        <p class="font-bold text-slate-700">QRIS / Virtual Account</p>
                    </div>
                    <div class="bg-white rounded-lg p-3">
                        <p class="text-indigo-400 font-semibold text-xs">Kode Pembayaran</p>
                        <p class="font-bold text-slate-700 font-mono text-xs">{{ strtoupper($donation->invoice) }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3">
                        <p class="text-indigo-400 font-semibold text-xs">Nominal</p>
                        <p class="font-bold text-indigo-600">{{ moneyFormat($donation->amount) }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3">
                        <p class="text-indigo-400 font-semibold text-xs">Berlaku Sampai</p>
                        <p class="font-bold text-slate-700">{{ \Carbon\Carbon::now()->addHours(24)->translatedFormat('d M Y H:i') }}</p>
                    </div>
                </div>
                
                <p class="text-xs text-indigo-600/80 mt-2">
                    <strong>Catatan:</strong> Gunakan kode invoice di atas sebagai referensi pembayaran. 
                    Pembayaran akan diproses maksimal 24 jam. Jika sudah melakukan pembayaran, klik tombol "Sudah Bayar" di bawah.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3 pt-2">
                {{-- Sudah Bayar Button --}}
                <form action="{{ route('public.donation.confirm', $donation->invoice) }}" method="POST" id="confirmForm">
                    @csrf
                    <button type="button" onclick="confirmPayment()"
                        class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl hover:opacity-90 transition shadow-lg shadow-emerald-500/25 text-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        ✅ Sudah Bayar - Verifikasi Pembayaran
                    </button>
                </form>

                {{-- Copy Invoice --}}
                <button type="button" onclick="copyInvoice()" 
                    class="w-full py-3 border-2 border-indigo-300 text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    📋 Salin Invoice
                </button>

                {{-- Back to Campaign --}}
                <a href="{{ route('public.campaign.show', $donation->campaign->slug) }}" 
                   class="w-full py-3 text-center text-slate-500 hover:text-indigo-600 font-medium transition">
                    ← Kembali ke Campaign
                </a>
            </div>

            {{-- Warning --}}
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 text-center">
                <svg class="w-5 h-5 text-rose-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-sm text-rose-700 font-semibold">Perhatian!</p>
                <p class="text-xs text-rose-600 mt-1">Halaman ini simulasi. Di production, integrasikan dengan payment gateway Midtrans/Xendit. 
                Klik "Sudah Bayar" hanya untuk demo mengubah status jadi sukses.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyInvoice() {
        const invoice = '{{ $donation->invoice }}';
        navigator.clipboard.writeText(invoice).then(() => {
            Swal.fire({
                title: 'Tersalin!',
                text: 'Invoice ' + invoice + ' telah disalin ke clipboard',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    }

    function confirmPayment() {
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `<p class="text-sm text-gray-600">Anda sudah melakukan pembayaran untuk <strong class="text-emerald-600">{{ moneyFormat($donation->amount) }}</strong>?</p>
                   <p class="text-xs text-gray-400 mt-2">Pastikan sudah mentransfer ke rekening/QRIS yang sesuai.</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Sudah Bayar',
            cancelButtonText: 'Belum',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memverifikasi...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                document.getElementById('confirmForm').submit();
            }
        });
    }
</script>
@endpush