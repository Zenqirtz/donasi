@extends('layouts.public', ['title' => 'Peduli Kita - Platform Donasi Online Terpercaya'])

@section('content')

{{-- 1. Hero Section --}}
<section class="gradient-hero text-white py-20 sm:py-28 relative overflow-hidden">
    {{-- Ambient Glow Lights --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-purple-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-blue-500/10 blur-[100px] rounded-full"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md text-xs sm:text-sm font-semibold mb-6 border border-white/15 text-indigo-100 shadow-sm">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            Platform Donasi Terpercaya & Transparan Indonesia
        </div>

        {{-- Main Headline --}}
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight max-w-4xl mx-auto text-white">
            Ulurkan Tangan, Beri Harapan Nyata Bagi <span class="bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-400 bg-clip-text text-transparent">Sesama</span>
        </h1>

        {{-- Subtitle --}}
        <p class="mt-6 text-base sm:text-lg lg:text-xl text-indigo-100 max-w-2xl mx-auto leading-relaxed font-normal">
            Bantu saudara-saudara kita yang tertimpa musibah dan membutuhkan pertolongan darurat. Setiap kebaikan kecil Anda menghadirkan senyuman bagi mereka.
        </p>

        {{-- Action Buttons --}}
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('public.campaigns') }}" 
               class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-amber-400 to-yellow-400 text-slate-900 font-extrabold text-sm uppercase tracking-wider rounded-2xl hover:from-amber-300 hover:to-yellow-300 transition shadow-xl shadow-amber-500/20 active:scale-95 inline-flex items-center justify-center gap-2">
                <span>🚀 Mulai Berdonasi</span>
            </a>
            <a href="#campaign-list" 
               class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold text-sm uppercase tracking-wider rounded-2xl transition border border-white/25 backdrop-blur-md inline-flex items-center justify-center gap-2">
                <span>Lihat Campaign</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- 2. Stats Summary Floating Cards --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Stat 1 --}}
        <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Donasi Terkumpul</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ moneyFormat($totalDonasi) }}</h3>
                <p class="text-xs text-emerald-600 font-semibold mt-0.5">Tersalurkan secara amanah</p>
            </div>
        </div>

        {{-- Stat 2 --}}
        <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Donatur Terdaftar</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ number_format($totalDonatur) }}+ Orang Baik</h3>
                <p class="text-xs text-indigo-600 font-semibold mt-0.5">Tergabung bersama kami</p>
            </div>
        </div>

        {{-- Stat 3 --}}
        <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Campaign Terbantu</p>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $totalCampaign }} Program</h3>
                <p class="text-xs text-purple-600 font-semibold mt-0.5">Sedang dan telah selesai</p>
            </div>
        </div>
    </div>
</section>

{{-- 3. Sliders Banner (Jika ada) --}}
@if(isset($sliders) && $sliders->count() > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
    <div class="relative overflow-hidden rounded-3xl shadow-xl bg-slate-900 border border-slate-800">
        <div class="relative h-64 sm:h-80 md:h-96 w-full">
            @php $firstSlider = $sliders->first(); @endphp
            <img src="{{ $firstSlider->image }}" 
                 alt="Highlight Campaign" 
                 onerror="this.src='https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1200&auto=format&fit=crop&q=80'"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent flex flex-col justify-end p-6 sm:p-10">
                <span class="inline-block px-3 py-1 bg-amber-400 text-slate-900 font-extrabold text-xs rounded-full w-max mb-3 uppercase tracking-wider">
                    Sorotan Program
                </span>
                <h3 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-white max-w-2xl leading-tight">
                    Bersama Kita Peduli dan Berbagi Kebaikan untuk Indonesia
                </h3>
                <div class="mt-4">
                    <a href="{{ route('public.campaigns') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-slate-900 font-bold text-xs rounded-xl hover:bg-slate-100 transition shadow-md">
                        Ikut Berdonasi Sekarang
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- 4. Featured Campaigns List --}}
<section id="campaign-list" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-24">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10 pb-4 border-b border-slate-200">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Pilihan Utama
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Campaign yang Butuh Bantuan Mendesak</h2>
            <p class="text-sm text-slate-500 mt-1">Pilih program donasi dan bantu mereka yang sedang berjuang.</p>
        </div>
        <a href="{{ route('public.campaigns') }}" 
           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50/80 hover:bg-indigo-100 rounded-xl transition shrink-0">
            <span>Lihat Semua Program</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if($campaigns->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($campaigns as $campaign)
        @php
            $terkumpul = $campaign->sumDonation();
            $target = $campaign->target_donation > 0 ? $campaign->target_donation : 1;
            $persen = min(100, round(($terkumpul / $target) * 100));
            $diffDays = \Carbon\Carbon::parse($campaign->max_date)->diffInDays(now(), false);
            $isExpired = $diffDays < 0;
            $sisaHari = max(0, $diffDays);
        @endphp
        <div class="card-hover group bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div>
                {{-- Campaign Image --}}
                <div class="relative aspect-video w-full overflow-hidden bg-slate-100">
                    <img src="{{ $campaign->image }}" 
                         alt="{{ $campaign->title }}" 
                         onerror="this.src='https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=600&auto=format&fit=crop&q=80'"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    
                    {{-- Category Badge --}}
                    <div class="absolute top-3.5 left-3.5">
                        <span class="px-3 py-1 bg-white/95 backdrop-blur-md text-indigo-700 text-xs font-extrabold rounded-full shadow-sm border border-white/40">
                            {{ $campaign->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    {{-- Expiry / Remaining Days Badge --}}
                    <div class="absolute top-3.5 right-3.5">
                        @if($isExpired)
                            <span class="px-2.5 py-1 bg-slate-900/80 backdrop-blur-md text-slate-200 text-[11px] font-bold rounded-full">
                                Selesai
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-amber-500/90 backdrop-blur-md text-white text-[11px] font-bold rounded-full shadow-sm">
                                ⏳ {{ $sisaHari }} hari lagi
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Campaign Info --}}
                <div class="p-6">
                    <a href="{{ route('public.campaign.show', $campaign->slug) }}" class="block">
                        <h3 class="font-extrabold text-slate-900 text-base leading-snug line-clamp-2 group-hover:text-indigo-600 transition">
                            {{ $campaign->title }}
                        </h3>
                    </a>
                    <p class="text-xs text-slate-500 mt-2.5 line-clamp-2 leading-relaxed">
                        {{ strip_tags($campaign->description) }}
                    </p>
                </div>
            </div>

            {{-- Progress & Footer Actions --}}
            <div class="px-6 pb-6 pt-2 border-t border-slate-100/80 bg-slate-50/50">
                <!-- Progress Header -->
                <div class="flex justify-between text-xs text-slate-600 mb-1.5 font-bold">
                    <span class="text-slate-500">Terkumpul</span>
                    <span class="text-indigo-600 font-extrabold">{{ $persen }}%</span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2.5 rounded-full transition-all duration-700" 
                         style="width: {{ $persen }}%"></div>
                </div>

                <!-- Nominal & Action Row -->
                <div class="mt-4 flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-extrabold text-slate-900">{{ moneyFormat($terkumpul) }}</div>
                        <div class="text-[11px] text-slate-400 font-medium">Target: {{ moneyFormat($campaign->target_donation) }}</div>
                    </div>
                    <a href="{{ route('public.campaign.show', $campaign->slug) }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition shadow-sm shadow-indigo-500/25 active:scale-95">
                        <span>Donasi</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
        <p class="text-slate-500 text-sm">Belum ada campaign aktif saat ini.</p>
    </div>
    @endif
</section>

{{-- 5. Kenapa Memilih Peduli Kita (Features) --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-28">
    <div class="text-center max-w-2xl mx-auto mb-14">
        <span class="text-xs font-extrabold text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
            Mengapa Memilih Kami?
        </span>
        <h2 class="text-3xl font-extrabold text-slate-900 mt-3">Platform Terpercaya untuk Kebaikan Bersama</h2>
        <p class="text-sm text-slate-500 mt-2">Kami mengutamakan transparansi dan keamanan setiap dana donasi yang disalurkan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Feature 1 -->
        <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">100% Amanah</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Dana donasi disalurkan langsung secara transparan dengan laporan penerima yang jelas.</p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Cepat & Praktis</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Pemberian donasi mudah hanya dalam beberapa klik dari perangkat smartphone maupun komputer.</p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Tervalidasi Resmi</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Setiap campaign donasi yang terbit telah melalui verifikasi keaslian dan kebutuhan mendesak.</p>
        </div>

        <!-- Feature 4 -->
        <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Pesan Doa Hangat</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Tuliskan kata-kata doa dan semangat yang akan langsung dibaca oleh penerima manfaat.</p>
        </div>
    </div>
</section>

{{-- 6. Cara Berdonasi (Step-by-Step) --}}
<section id="cara-donasi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-28">
    <div class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 rounded-3xl p-8 sm:p-14 text-white relative overflow-hidden shadow-2xl">
        <div class="relative z-10">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-xs font-extrabold text-amber-300 uppercase tracking-widest">3 Langkah Mudah</span>
                <h2 class="text-3xl font-extrabold mt-2">Cara Berdonasi di Peduli Kita</h2>
                <p class="text-sm text-indigo-200 mt-1">Hanya membutuhkan 1 menit untuk membantu saudara kita.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-900 font-extrabold text-lg flex items-center justify-center mb-4 shadow-lg shadow-amber-400/20">
                        1
                    </div>
                    <h3 class="text-lg font-bold">Pilih Program</h3>
                    <p class="text-xs text-indigo-100 mt-2 leading-relaxed">
                        Pilih campaign donasi yang menyentuh hati dan sesuai dengan kepedulian Anda.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-900 font-extrabold text-lg flex items-center justify-center mb-4 shadow-lg shadow-amber-400/20">
                        2
                    </div>
                    <h3 class="text-lg font-bold">Isi Data & Doa</h3>
                    <p class="text-xs text-indigo-100 mt-2 leading-relaxed">
                        Tentukan nominal donasi terbaik dan sertakan pesan doa yang menguatkan.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 text-center flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-900 font-extrabold text-lg flex items-center justify-center mb-4 shadow-lg shadow-amber-400/20">
                        3
                    </div>
                    <h3 class="text-lg font-bold">Donasi Tersalurkan</h3>
                    <p class="text-xs text-indigo-100 mt-2 leading-relaxed">
                        Donasi Anda tercatat di sistem dan segera diteruskan kepada pihak yang membutuhkan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 7. Call To Action Banner --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 mb-6">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm text-center max-w-4xl mx-auto">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
            Kebaikan Kecil Anda Membawa Perubahan Besar
        </h2>
        <p class="text-sm text-slate-500 mt-3 max-w-xl mx-auto leading-relaxed">
            Tidak ada donasi yang terlalu kecil. Bersama-sama, kita bisa meringankan beban ribuan saudara yang sedang membutuhkan uluran tangan.
        </p>
        <div class="mt-8 flex justify-center">
            <a href="{{ route('public.campaigns') }}" 
               class="px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-indigo-500/25 transition active:scale-95">
                Bantu Sekarang Juga &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
