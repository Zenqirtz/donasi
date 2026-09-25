@extends('layouts.public', ['title' => $campaign->title . ' - Peduli Kita'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium mb-8">
        <a href="{{ route('public.home') }}" class="hover:text-indigo-600 transition">Beranda</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('public.campaigns') }}" class="hover:text-indigo-600 transition">Campaign</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-600 truncate max-w-xs">{{ $campaign->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">

        {{-- LEFT: Campaign Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Campaign Image --}}
            <div class="rounded-3xl overflow-hidden shadow-lg aspect-video bg-slate-100">
                <img src="{{ $campaign->image }}" alt="{{ $campaign->title }}" onerror="this.src='https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1200&auto=format&fit=crop&q=80'" class="w-full h-full object-cover">
            </div>

            {{-- Title & Category --}}
            <div>
                <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full mb-3">
                    {{ $campaign->category->name ?? 'Umum' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 leading-snug">{{ $campaign->title }}</h1>
            </div>

            {{-- Progress Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Total Terkumpul</p>
                        <p class="text-2xl font-extrabold text-indigo-600">{{ moneyFormat($totalTerkumpul) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Target</p>
                        <p class="text-lg font-bold text-slate-700">{{ moneyFormat($campaign->target_donation) }}</p>
                    </div>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 mb-3">
                    <div class="bg-gradient-to-r from-indigo-500 to-violet-500 h-3 rounded-full transition-all duration-1000" style="width: {{ $persentase }}%"></div>
                </div>
                <div class="flex justify-between text-xs font-semibold text-slate-500">
                    <span>{{ $persentase }}% tercapai</span>
                    <span>Berakhir: {{ \Carbon\Carbon::parse($campaign->max_date)->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-extrabold text-slate-800 mb-4">Tentang Campaign Ini</h2>
                <div class="text-sm text-slate-600 leading-relaxed prose prose-sm max-w-none">
                    {!! nl2br(e($campaign->description)) !!}
                </div>
            </div>

            {{-- Recent Donors --}}
            @if($recentDonations->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-extrabold text-slate-800 mb-4">💝 Donatur Terbaru</h2>
                <div class="space-y-3">
                    @foreach($recentDonations as $don)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-indigo-50/50 transition">
                        <img src="{{ $don->donatur->avatar }}" alt="{{ $don->donatur->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-indigo-200">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $don->donatur->name }}</p>
                            @if($don->pray)
                                <p class="text-xs text-slate-500 italic truncate">"{{ $don->pray }}"</p>
                            @endif
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-extrabold text-emerald-600">{{ moneyFormat($don->amount) }}</p>
                            <p class="text-[10px] text-slate-400">{{ $don->created_at }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Donation Form --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-5 text-white">
                        <h3 class="text-lg font-extrabold">Donasi Sekarang</h3>
                        <p class="text-xs text-indigo-200 mt-0.5">Setiap kebaikan membuat perbedaan besar</p>
                    </div>

                    <form action="{{ route('public.donation.store', $campaign->slug) }}" method="POST" id="donationForm" class="p-6 space-y-5">
                        @csrf

                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama Anda"
                                class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition @error('name') border-rose-400 @enderror">
                            @error('name')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition @error('email') border-rose-400 @enderror">
                            @error('email')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nominal --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nominal Donasi <span class="text-rose-500">*</span></label>
                            {{-- Quick amount buttons --}}
                            <div class="grid grid-cols-3 gap-2 mb-3">
                                @foreach([10000, 25000, 50000, 100000, 250000, 500000] as $nominal)
                                <button type="button" onclick="setAmount({{ $nominal }})"
                                    class="quick-amount py-2 px-1 text-xs font-bold rounded-lg border border-slate-300 text-slate-800 hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50 transition bg-white">
                                    {{ number_format($nominal, 0, '', '.') }}
                                </button>
                                @endforeach
                            </div>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                                    min="1000" placeholder="Minimal Rp 1.000"
                                    class="w-full pl-10 pr-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition @error('amount') border-rose-400 @enderror">
                            </div>
                            @error('amount')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pesan Doa --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Pesan / Doa <span class="text-slate-400 font-normal">(opsional)</span></label>
                            <textarea name="pray" id="pray" rows="3"
                                placeholder="Tuliskan doa atau pesan Anda..."
                                class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition resize-none @error('pray') border-rose-400 @enderror">{{ old('pray') }}</textarea>
                            @error('pray')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" id="submitBtn"
                            class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl hover:opacity-90 transition shadow-lg shadow-indigo-500/25 text-sm">
                            💝 Kirim Donasi Sekarang
                        </button>

                        <p class="text-[11px] text-slate-400 text-center">
                            Data Anda aman dan dilindungi. Donasi tercatat secara transparan.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function setAmount(value) {
        document.getElementById('amount').value = value;
        // highlight selected button
        document.querySelectorAll('.quick-amount').forEach(btn => {
            btn.classList.remove('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
        });
        event.target.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
    }

    // Confirm before submit
    document.getElementById('donationForm').addEventListener('submit', function(e) {
        const amount = document.getElementById('amount').value;
        const name = document.getElementById('name').value;
        if (!name || !amount) return;

        e.preventDefault();
        const formatted = 'Rp ' + parseInt(amount).toLocaleString('id-ID');

        Swal.fire({
            title: 'Konfirmasi Donasi',
            html: `<p class="text-sm text-gray-600">Anda akan mendonasikan <strong class="text-indigo-600">${formatted}</strong><br>atas nama <strong>${name}</strong></p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Donasi Sekarang!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('donationForm').submit();
            }
        });
    });
</script>
@endpush
