@extends('layouts.public', ['title' => 'Semua Campaign Donasi - Peduli Kita'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-400 font-semibold mb-6">
        <a href="{{ route('public.home') }}" class="hover:text-indigo-600 transition">Beranda</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-indigo-600">Semua Campaign</span>
    </nav>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 rounded-3xl p-8 sm:p-10 text-white mb-10 shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-purple-500/20 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 bg-amber-400 text-slate-900 text-[11px] font-extrabold uppercase tracking-wider rounded-full mb-3">
                Katalog Program
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight">Semua Program Kebaikan</h1>
            <p class="text-indigo-200 text-sm mt-2 leading-relaxed">
                Pilih program donasi yang ingin Anda dukung. Bersama kita wujudkan harapan bagi mereka yang sedang berjuang.
            </p>
        </div>
    </div>

    {{-- Filter & Search Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs mb-10">
        <form method="GET" action="{{ route('public.campaigns') }}" class="flex flex-col md:flex-row gap-4 items-center">
            {{-- Search Bar --}}
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari program donasi berdasarkan judul..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-800 placeholder-slate-400 font-medium">
            </div>

            {{-- Category Filter --}}
            <div class="w-full md:w-64">
                <select name="category" 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700 font-medium cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" 
                        class="flex-1 md:flex-none px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:from-indigo-700 hover:to-purple-700 transition shadow-sm active:scale-95">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('public.campaigns') }}" 
                   class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Campaign Grid --}}
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
                {{-- Image Area --}}
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

                    {{-- Expiry Badge --}}
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

                {{-- Campaign Content --}}
                <div class="p-6">
                    <a href="{{ route('public.campaign.show', $campaign->slug) }}" class="block">
                        <h2 class="font-extrabold text-slate-900 text-base leading-snug line-clamp-2 group-hover:text-indigo-600 transition">
                            {{ $campaign->title }}
                        </h2>
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

    {{-- Clean Pagination with constrained SVG sizes --}}
    <div class="mt-12 flex justify-center [&_svg]:w-5 [&_svg]:h-5 [&_svg]:inline-block">
        {{ $campaigns->withQueryString()->links() }}
    </div>

    @else
    {{-- Empty State --}}
    <div class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs max-w-lg mx-auto">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Tidak ada campaign yang sesuai</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
            Coba gunakan kata kunci lain atau pilih kategori yang berbeda.
        </p>
        <div class="mt-6">
            <a href="{{ route('public.campaigns') }}" class="px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-indigo-700 transition inline-block">
                Tampilkan Semua Campaign
            </a>
        </div>
    </div>
    @endif

</div>
@endsection
