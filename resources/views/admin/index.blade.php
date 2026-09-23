@extends('layouts.app', ['title' => 'Dashboard - Admin'])

@section('content')  
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-6 md:p-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Welcome Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-800 p-6 sm:p-8 text-white shadow-xl shadow-indigo-500/10">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-indigo-100 backdrop-blur-md mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Aktif & Terhubung
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                <p class="mt-2 text-indigo-100 text-sm sm:text-base leading-relaxed">
                    Pantau penerimaan donasi, verifikasi donatur, dan kelola campaign amal secara real-time dari satu tempat.
                </p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('admin.campaign.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-indigo-700 hover:bg-indigo-50 font-semibold text-sm shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buat Campaign Baru
                    </a>
                    <a href="{{ route('admin.donation.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-500/30 hover:bg-indigo-500/40 text-white font-semibold text-sm backdrop-blur-sm transition border border-white/20">
                        Lihat Semua Transaksi
                    </a>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute right-20 -top-10 w-48 h-48 rounded-full bg-purple-500/20 blur-xl pointer-events-none"></div>
        </div>

        <!-- 4 Stat Metric Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- Card 1: Total Donasi -->
            <div class="relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Donasi Masuk</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mt-1">{{ moneyFormat($donations) }}</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-emerald-600 font-medium">
                    <span class="flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Status Sukses / Terverifikasi
                    </span>
                </div>
            </div>

            <!-- Card 2: Total Campaign -->
            <div class="relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Campaign</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $campaigns }}</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-500 font-medium">
                    Program galang dana terdaftar
                </div>
            </div>

            <!-- Card 3: Total Donatur -->
            <div class="relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Donatur</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $donaturs }}</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-violet-50 text-violet-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-500 font-medium">
                    Orang baik yang berkontribusi
                </div>
            </div>

            <!-- Card 4: Donasi Pending -->
            <div class="relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-slate-200/80 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Menunggu Pembayaran</p>
                        <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $donationsPending }}</h3>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-amber-700 font-medium">
                    Perlu pemantauan status
                </div>
            </div>

        </div>

        <!-- Recent Donations Section -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Transaksi Donasi Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar donasi yang baru saja masuk ke sistem</p>
                </div>
                <a href="{{ route('admin.donation.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    Lihat Seluruh Donasi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3.5">Invoice</th>
                            <th class="px-6 py-3.5">Nama Donatur</th>
                            <th class="px-6 py-3.5">Campaign</th>
                            <th class="px-6 py-3.5">Nominal</th>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($recentDonations as $donation)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-mono text-xs text-slate-500 font-semibold">
                                    {{ $donation->invoice }}
                                </td>
                                <td class="px-6 py-4 text-slate-800 font-semibold">
                                    {{ $donation->donatur->name ?? 'Anonim' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
                                    {{ $donation->campaign->title ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-emerald-600 font-bold">
                                    {{ moneyFormat($donation->amount) }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $donation->created_at }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($donation->status == 'success')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            Success
                                        </span>
                                    @elseif($donation->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($donation->status == 'pending')
                                        <form action="{{ route('admin.donation.status', $donation->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Konfirmasi pembayaran donasi ini menjadi Berhasil (Success)?');">
                                            @csrf
                                            <input type="hidden" name="status" value="success">
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition active:scale-95">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @elseif($donation->status == 'success')
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-bold">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Tervalidasi
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                    <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    Belum ada transaksi donasi yang tercatat
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>
@endsection
