@extends('layouts.public', ['title' => 'Semua Campaign - Peduli Kita'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Page Header --}}
    <div class="mb-10">
        <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Campaign Aktif</span>
        <h1 class="text-3xl font-extrabold text-slate-800 mt-1">Semua Campaign Donasi</h1>
        <p class="text-slate-500 mt-2 text-sm">Temukan campaign yang sesuai dengan kepedulian Anda dan mulai berkontribusi.</p>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('public.campaigns') }}" class="flex flex-col sm:flex-row gap-3 mb-10">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama campaign..."
            class="flex-1 px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
        <select name="category" class="px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition">
            Cari
        </button>
        @if(request()->hasAny(['search', 'category']))
        <a href="{{ route('public.campaigns') }}" class="px-6 py-3 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition text-center">
            Reset
        </a>
        @endif
    </form>

    {{-- Campaign Grid --}}
    @if($campaigns->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
        @foreach($campaigns as $campaign)
        @php
            $terkumpul = $campaign->sumDonation();
            $persen = $campaign->target_donation > 0 ? min(100, round(($terkumpul / $campaign->target_donation) * 100)) : 0;
            $hariSisa = \Carbon\Carbon::parse($campaign->max_date)->diffInDays(now(), false);
        @endphp
        <a href="{{ route('public.campaign.show', $campaign->slug) }}" class="card-hover group bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm flex flex-col">
            <div class="relative h-48 overflow-hidden">
                <img src="{{ $campaign->image }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute top-3 left-3">
                    <span class="px-2.5 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full">
                        {{ $campaign->category->name ?? 'Umum' }}
                    </span>
                </div>
                @if($hariSisa <= 0)
                <div class="absolute top-3 right-3">
                    <span class="px-2.5 py-1 bg-rose-600 text-white text-xs font-bold rounded-full">Berakhir</span>
                </div>
                @elseif($hariSisa <= 7)
                <div class="absolute top-3 right-3">
                    <span class="px-2.5 py-1 bg-amber-500 text-white text-xs font-bold rounded-full">{{ $hariSisa }} hari lagi</span>
                </div>
                @endif
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h2 class="font-bold text-slate-800 text-base leading-snug line-clamp-2 group-hover:text-indigo-600 transition">{{ $campaign->title }}</h2>
                <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">{{ strip_tags($campaign->description) }}</p>
                <div class="mt-4 flex-1 flex flex-col justify-end">
                    <div class="flex justify-between text-xs text-slate-500 mb-1.5 font-medium">
                        <span>Terkumpul</span>
                        <span class="font-bold text-indigo-600">{{ $persen }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-gradient-to-r from-indigo-500 to-violet-500 h-2 rounded-full" style="width: {{ $persen }}%"></div>
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

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $campaigns->withQueryString()->links() }}
    </div>

    @else
    <div class="text-center py-24 text-slate-400">
        <svg class="mx-auto h-14 w-14 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        <p class="text-base font-semibold text-slate-500">Tidak ada campaign yang ditemukan.</p>
        <a href="{{ route('public.campaigns') }}" class="mt-4 inline-block text-sm text-indigo-600 font-semibold hover:underline">Lihat semua campaign</a>
    </div>
    @endif
</div>
@endsection
