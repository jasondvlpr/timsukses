<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BossGroupHub | Enterprise Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            /* Slate 50 */
            color: #0f172a;
            /* Slate 900 */
        }

        .hero-bg {
            background-image:
                radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 0) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 0.05) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 0.05) 0, transparent 50%);
            background-color: white;
        }

        .premium-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03), 0 20px 25px -5px rgba(0, 0, 0, 0.02);
        }

        .btn-primary {
            background-color: #0f172a;
            color: white;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #334155;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary {
            background-color: white;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .feature-icon {
            background: linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%);
            color: #4f46e5;
        }
    </style>
</head>

<body class="antialiased">
    <div class="hero-bg min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="w-full border-b border-gray-100 bg-white/80 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-slate-900 rounded flex items-center justify-center font-bold text-white text-sm shadow-sm">
                        B</div>
                    <span class="hidden sm:block text-xl font-bold tracking-tight text-slate-900">BossGroupHub</span>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm font-bold text-slate-600 hover:text-slate-900 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold text-slate-600 hover:text-slate-900 transition px-2">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="btn-primary px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Hero -->
        <main class="flex-grow flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 pt-20 pb-24">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 leading-tight">
                    Kelola Promotor & <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Resolusi
                        Keluhan Cepat</span>
                </h1>
                <p class="mt-4 text-lg md:text-xl text-slate-500 max-w-2xl mx-auto font-light leading-relaxed mb-10">
                    Tinggalkan grup WhatsApp yang berantakan. Beralih ke sistem ticketing profesional untuk mengelola
                    keluhan dan pengajuan website promotor Anda dengan rapi dan terukur.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="btn-primary px-8 py-3 rounded-lg font-semibold text-lg flex items-center justify-center gap-2">
                            Akses Dashboard
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="btn-primary px-8 py-3 rounded-lg font-semibold text-lg flex items-center justify-center gap-2">
                            Mulai Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="btn-secondary px-8 py-3 rounded-lg font-semibold text-lg">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Features -->
            <div class="max-w-7xl mx-auto w-full mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl premium-shadow border border-slate-100">
                    <div class="w-12 h-12 feature-icon rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Sistem Ticketing Rapi</h3>
                    <p class="text-slate-500 font-light leading-relaxed">
                        Setiap keluhan promotor dicatat sebagai tiket terpisah. Pantau status penyelesaian dari Open
                        hingga Resolved tanpa ada chat yang tertumpuk.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl premium-shadow border border-slate-100">
                    <div class="w-12 h-12 feature-icon rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-1.343 3-3s-1.343-3-3-3m0 12c-1.657 0-3-1.343-3-3s1.343-3 3-3m0 0V3m0 18v-3">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Pengajuan Website Baru</h3>
                    <p class="text-slate-500 font-light leading-relaxed">
                        Promotor dapat dengan mudah mengajukan website baru untuk dipromosikan, lengkap dengan sistem
                        approval satu klik untuk Admin.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl premium-shadow border border-slate-100">
                    <div class="w-12 h-12 feature-icon rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Dashboard Real-time</h3>
                    <p class="text-slate-500 font-light leading-relaxed">
                        Pantau kinerja penyelesaian keluhan melalui dashboard statistik yang informatif, baik dari sisi
                        Admin maupun Promotor.
                    </p>
                </div>
            </div>
        </main>
    </div>
</body>

</html>