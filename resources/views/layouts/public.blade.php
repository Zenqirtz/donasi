<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Platform donasi online terpercaya, transparan, dan amanah untuk membantu sesama yang membutuhkan.">
    <title>{{ $title ?? 'Peduli Kita - Platform Donasi Online Terpercaya' }}</title>

    <!-- Tailwind CDN with custom config (Guarantees modern styles even if local Vite dev is inactive) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Quicksand', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Vite Bundled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .gradient-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 35%, #4338ca 70%, #4f46e5 100%);
        }
        .gradient-mesh {
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(59, 130, 246, 0.12) 0px, transparent 50%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -10px rgba(79, 70, 229, 0.15), 0 10px 15px -5px rgba(0, 0, 0, 0.04);
        }
    .progress-bar-glow {
        box-shadow: 0 0 12px rgba(99, 102, 241, 0.5);
    }
    
    /* SweetAlert2 button visibility override */
    .swal2-popup .swal2-actions {
        display: flex !important;
        gap: 12px !important;
    }
    .swal2-popup .swal2-confirm,
    .swal2-popup .swal2-cancel {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        line-height: 1 !important;
        border-radius: 12px !important;
        border: 0 !important;
        min-width: 100px !important;
        text-shadow: none !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
    }
    .swal2-popup .swal2-confirm {
        background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
        color: white !important;
    }
    .swal2-popup .swal2-cancel {
        background-color: #f1f5f9 !important;
        color: #64748b !important;
        border: 2px solid #e2e8f0 !important;
    }
    /* Fallback for line-clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white">

    <!-- Top Notice Bar -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 text-white text-xs py-2 px-4 text-center font-medium border-b border-indigo-700/50">
        <span class="inline-flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Platform Donasi Resmi & Transparan &bull; Setiap rupiah disalurkan langsung kepada yang berhak
        </span>
    </div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('public.home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xl font-extrabold bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 bg-clip-text text-transparent tracking-tight">PEDULI KITA</span>
                            <span class="px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 rounded-md border border-indigo-200/60">ID</span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium tracking-wide">Platform Donasi Online</p>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('public.home') }}" 
                       class="text-sm font-semibold transition {{ request()->routeIs('public.home') ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-indigo-600' }}">
                        Beranda
                    </a>
                    <a href="{{ route('public.campaigns') }}" 
                       class="text-sm font-semibold transition {{ request()->routeIs('public.campaigns*') ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-indigo-600' }}">
                        Semua Campaign
                    </a>
                    <a href="{{ route('public.home') }}#cara-donasi" 
                       class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition">
                        Cara Donasi
                    </a>
                </nav>

                <!-- Actions -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('public.campaigns') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-md shadow-indigo-500/25 transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Donasi Sekarang
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <button id="mobileMenuBtn" type="button" class="md:hidden p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div id="mobileMenu" class="hidden md:hidden pb-5 pt-3 border-t border-slate-100 space-y-2">
                <a href="{{ route('public.home') }}" class="block px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition">
                    Beranda
                </a>
                <a href="{{ route('public.campaigns') }}" class="block px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition">
                    Semua Campaign
                </a>
                <a href="{{ route('public.home') }}#cara-donasi" class="block px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition">
                    Cara Donasi
                </a>
                <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
                    <a href="{{ route('public.campaigns') }}" class="w-full text-center px-4 py-3 text-xs font-bold uppercase tracking-wider text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-md">
                        Donasi Sekarang
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1">
        @if(session()->has('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-semibold shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>{{ session('success') }}</div>
            </div>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm font-semibold shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>{{ session('error') }}</div>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="bg-slate-950 text-slate-400 mt-28 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Column 1: Brand Info -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold text-white tracking-tight">PEDULI KITA</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-md">
                        Platform penggalangan dana dan donasi online yang aman, cepat, dan transparan. Menghubungkan orang baik dengan sesama yang membutuhkan bantuan darurat di seluruh pelosok Indonesia.
                    </p>
                    <div class="flex items-center gap-4 text-xs text-slate-500 pt-2">
                        <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> 100% Transparan</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-500"></span> Terdaftar Resmi</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Laporan Berkala</span>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Navigasi Utama</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('public.home') }}" class="hover:text-indigo-400 transition">Beranda</a></li>
                        <li><a href="{{ route('public.campaigns') }}" class="hover:text-indigo-400 transition">Semua Campaign</a></li>
                        <li><a href="{{ route('public.home') }}#cara-donasi" class="hover:text-indigo-400 transition">Cara Berdonasi</a></li>
                        <li><a href="{{ route('admin.dashboard.index') }}" class="hover:text-indigo-400 transition">Masuk Admin</a></li>
                    </ul>
                </div>

                <!-- Column 3: Kontak & Dukungan -->
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Pusat Bantuan</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>dukungan@pedulikita.id</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-900 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} Peduli Kita. Platform Donasi Online. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-6">
                    <span class="hover:text-slate-400">Kebijakan Privasi</span>
                    <span class="hover:text-slate-400">Syarat & Ketentuan</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        const menuBtn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        if (menuBtn && menu) {
            menuBtn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
