@extends('layouts.public', ['title' => 'Beranda - Peduli Kita'])

@section('content')

{{-- Hero Section --}}
<section class="gradient-hero text-white py-20 sm:py-28 relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-purple-500/10 blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-sm text-sm font-semibold mb-6 border border-white/20">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Platform Donasi Terpercaya Indonesia
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
            Bersama Kita Bisa Membuat<br>
            <span class="text-yellow-300">Perubahan Nyata</span>
        </h1>
        <p class="mt-6 text-lg sm:text-xl text-indigo-100 max-w-2xl mx-auto leading-relaxed">
            Bergabunglah dengan ribuan donatur yang telah membantu sesama melalui campaign-campaign bermakna di Peduli Kita.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('public.campaigns') }}" class="px-8 py-4 bg-white text-indigo-700 font-bold rounded-2xl hover:bg-indigo-50 transition shadow-xl text-base">
                🚀 Mulai Berdonasi
            </a>
            <a href="{{ route('public.campaigns') }}" class="px-8 py-4 bg-white/15 text-white font-bold rounded-2xl hover:bg-white/25 transition border border-white/30 text-base backdrop-blur-sm">
                Lihat Campaign
            </a>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-100 text-center">
            <div class="text-3xl font-extrabold text-indigo-600 mb-1">{{ moneyFormat($totalDonasi) }}</div>
            <div class="text-sm font-semibold text-slate-500">Total Donasi Terkumpul</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-100 text-center">
            <div class="text-3xl font-extrabold text-emerald-600 mb-1">{{ number_format($totalDonatur) }}+</div>
            <div class="text-sm font-semibold text-slate-500">Donatur Aktif</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-100 text-center">
            <div class="text-3xl font-extrabold text-violet-600 mb-1">{{ $totalCampaign }}+</div>
            <div class="text-sm font-semibold text-slate-500">Campaign Berjalan</div>
        </div>
    </div>
</section>

{{-- Featured Campaigns --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
        <div>
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Campaign Terpilih</span>
            <h2 class="text-3xl font-extrabold text-slate-800 mt-1">Campaign yang Butuh Bantuanmu</h2>
        </div>
        <a href="{{ route('public.campaigns') }}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition shrink-0">
            Lihat Semua
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if($campaigns->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($campaigns as $campaign)
        @php
            $terkumpul = $campaign->sumDonation();
            $persen = $campaign->target_donation > 0 ? min(100, round(($terkumpul / $campaign->target_donation) * 100)) : 0;
            $sisa = max(0, \Carbon\Carbon::parse($campaign->max_date)->diffInDays(now(), false));
        @endphp
        <a href="{{ route('public.campaign.show', $campaign->slug) }}" class="card-hover group bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm flex flex-col">
            <div class="relative h-48 overflow-hidden">
                <img src="{{ $campaign->image }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute top-3 left-3">
                    <span class="px-2.5 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full">
                        {{ $campaign->category->name ?? 'Umum' }}
                    </span>
                </div>
                @if($sisa <= 0)
                <div class="absolute top-3 right-3">
                    <span class="px-2.5 py-1 bg-rose-600 text-white text-xs font-bold rounded-full">Berakhir</span>
                </div>
                @endif
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h3 class="font-bold text-slate-800 text-base leading-snug line-clamp-2 group-hover:text-indigo-600 transition">{{ $campaign->title }}</h3>
                <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">{{ strip_tags($campaign->description) }}</p>

                <div class="mt-4 flex-1 flex flex-col justify-end">
                    <!-- Progress Bar -->
                    <div class="flex justify-between text-xs text-slate-500 mb-1.5 font-medium">
                        <span>Terkumpul</span>
                        <span class="font-bold text-indigo-600">{{ $persen }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-gradient-to-r from-indigo-500 to-violet-500 h-2 rounded-full progress-bar" style="width: {{ $persen }}%"></div>
                    </div>
                    <div class="mt-3 flex justify-between items-center">
                        <div>
                            <div class="text-sm font-extrabold text-slate-800">{{ moneyFormat($terkumpul) }}</div>
                            <div class="text-xs text-slate-400">dari {{ moneyFormat($campaign->target_donation) }}</div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-xl group-hover:bg-indigo-700 transition">
                            Donasi
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="text-center py-20 text-slate-400">
        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        Belum ada campaign aktif.
    </div>
    @endif
</section>

{{-- How it works --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 mb-10">
    <div class="text-center mb-12">
        <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Cara Kerja</span>
        <h2 class="text-3xl font-extrabold text-slate-800 mt-1">Cara Berdonasi di Peduli Kita</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
        @foreach([
            ['step' => '01', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'title' => 'Pilih Campaign', 'desc' => 'Telusuri campaign yang sesuai dengan kepedulian dan semangat Anda.'],
            ['step' => '02', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Isi Form Donasi', 'desc' => 'Masukkan nama, email, nominal donasi, dan pesan doa untuk penerima.'],
            ['step' => '03', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Donasi Terkirim', 'desc' => 'Donasi Anda tercatat dan membantu mereka yang membutuhkan bantuan.'],
        ] as $step)
        <div class="text-center p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                </svg>
            </div>
            <span class="text-xs font-extrabold text-indigo-400 tracking-widest">LANGKAH {{ $step['step'] }}</span>
            <h3 class="text-base font-extrabold text-slate-800 mt-1">{{ $step['title'] }}</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">{{ $step['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

@endsection
