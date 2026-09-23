@extends('layouts.app', ['title' => 'Transaksi Donasi - Admin'])

@section('content')
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50">
    <div class="container mx-auto px-6 py-8">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Transaksi Donasi</h2>
                <p class="text-sm text-slate-500 mt-1">Pantau, verifikasi, dan kelola seluruh aliran dana donasi masuk.</p>
            </div>
            
            {{-- Mini Stats --}}
            <div class="flex items-center gap-3">
                <div class="bg-white border border-slate-200/80 rounded-2xl px-4 py-2.5 shadow-xs">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Donasi Sukses</p>
                    <p class="text-lg font-extrabold text-emerald-600">{{ moneyFormat($totalSuccess ?? 0) }}</p>
                </div>
                <div class="bg-white border border-slate-200/80 rounded-2xl px-4 py-2.5 shadow-xs">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Verifikasi</p>
                    <p class="text-lg font-extrabold text-amber-600">{{ $totalPending ?? 0 }} Transaksi</p>
                </div>
            </div>
        </div>

        {{-- Filter & Search Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs mb-8">
            <form action="{{ route('admin.donation.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                {{-- Search Query --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pencarian</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari invoice atau nama donatur..."
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700">
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700 font-medium cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success (Sukses)</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed (Gagal)</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired (Kadaluarsa)</option>
                    </select>
                </div>

                {{-- Tanggal Awal --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700">
                </div>

                {{-- Submit & Reset --}}
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm active:scale-95 text-center">
                        Filter
                    </button>
                    @if(request()->hasAny(['q', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('admin.donation.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl transition flex items-center justify-center">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Transactions Table Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Invoice</th>
                            <th class="px-6 py-4">Nama Donatur</th>
                            <th class="px-6 py-4">Campaign</th>
                            <th class="px-6 py-4">Nominal</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($donations as $donation)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">
                                        {{ $donation->invoice }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $donation->donatur->name ?? 'Anonim' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $donation->donatur->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate text-slate-600">
                                    {{ $donation->campaign->title ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-emerald-600 font-extrabold">
                                    {{ moneyFormat($donation->amount) }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $donation->created_at }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($donation->status == 'success')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Success
                                        </span>
                                    @elseif($donation->status == 'pending')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($donation->status == 'pending')
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Konfirmasi Sukses --}}
                                            <form action="{{ route('admin.donation.status', $donation->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran donasi ini menjadi Berhasil (Success)?');">
                                                @csrf
                                                <input type="hidden" name="status" value="success">
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition active:scale-95">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Terima
                                                </button>
                                            </form>

                                            {{-- Batalkan / Reject --}}
                                            <form action="{{ route('admin.donation.status', $donation->id) }}" method="POST" onsubmit="return confirm('Tolak atau batalkan donasi ini?');">
                                                @csrf
                                                <input type="hidden" name="status" value="failed">
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-lg transition active:scale-95">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($donation->status == 'success')
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-bold">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Terverifikasi
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <p class="text-base font-semibold text-slate-600">Tidak ada data transaksi donasi.</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan filter atau rentang tanggal pencarian Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-slate-100 flex justify-center [&_svg]:w-4 [&_svg]:h-4 [&_svg]:inline-block">
                {{ $donations->withQueryString()->links() }}
            </div>
        </div>

    </div>
</main>
@endsection
