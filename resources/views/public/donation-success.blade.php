@extends('layouts.public', ['title' => 'Donasi Berhasil - Peduli Kita'])

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16">
    <div class="max-w-lg w-full">

        {{-- Success Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">

            {{-- Top gradient banner --}}
            <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-green-600 px-8 py-10 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
                </div>
                <div class="relative z-10">
                    <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4 backdrop-blur-sm border border-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold">Donasi Berhasil! 🎉</h1>
                    <p class="text-green-100 text-sm mt-2">Terima kasih atas kebaikan hati Anda</p>
                </div>
            </div>

            {{-- Donation Details --}}
            <div class="px-8 py-7 space-y-5">
                <div class="text-center">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Nominal Donasi</p>
                    <p class="text-4xl font-extrabold text-indigo-600">{{ moneyFormat($donation->amount) }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-100 divide-y divide-slate-100 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">No. Invoice</span>
                        <span class="text-sm font-bold text-slate-700 font-mono">{{ $donation->invoice }}</span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Nama Donatur</span>
                        <span class="text-sm font-bold text-slate-700">{{ $donation->donatur->name }}</span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Campaign</span>
                        <span class="text-sm font-bold text-slate-700 text-right max-w-[180px] line-clamp-2">{{ $donation->campaign->title }}</span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Menunggu Konfirmasi
                        </span>
                    </div>
                    @if($donation->pray)
                    <div class="px-5 py-3.5">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Pesan Doa</p>
                        <p class="text-sm text-slate-600 italic">"{{ $donation->pray }}"</p>
                    </div>
                    @endif
                </div>

                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 text-sm text-indigo-800">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="leading-relaxed">Donasi Anda sedang dalam proses verifikasi. Setelah dikonfirmasi, nominal akan ditambahkan ke campaign ini.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('public.campaign.show', $donation->campaign->slug) }}"
                        class="flex-1 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl hover:opacity-90 transition text-center text-sm shadow-lg shadow-indigo-500/20">
                        Kembali ke Campaign
                    </a>
                    <a href="{{ route('public.home') }}"
                        class="flex-1 py-3.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition text-center text-sm">
                        Ke Beranda
                    </a>
                </div>
            </div>

        </div>

        {{-- Motivational footer --}}
        <p class="text-center text-xs text-slate-400 mt-6 leading-relaxed">
            💝 Setiap kebaikan yang Anda lakukan akan kembali kepada Anda berlipat ganda.<br>
            Terima kasih telah menjadi bagian dari perubahan!
        </p>
    </div>
</div>

@push('scripts')
<script>
    // Confetti-like celebration on load
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Terima Kasih! 🙏',
            text: 'Donasi Anda sudah berhasil dicatat.',
            icon: 'success',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
        });
    });
</script>
@endpush
@endsection
