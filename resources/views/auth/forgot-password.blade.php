<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password — {{ config('app.name', 'Sistem Barang') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 50%, #ec4899 100%);
        }
        .auth-shape-1 {
            position: absolute; width: 300px; height: 300px; border-radius: 50%;
            background: rgba(255,255,255,0.08); top: -80px; left: -80px;
        }
        .auth-shape-2 {
            position: absolute; width: 200px; height: 200px; border-radius: 50%;
            background: rgba(255,255,255,0.06); bottom: -60px; right: -40px;
        }
        .auth-shape-3 {
            position: absolute; width: 120px; height: 120px; border-radius: 30px;
            background: rgba(255,255,255,0.05); top: 45%; left: 20%;
            transform: rotate(45deg);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(245,158,11,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #d97706, #dc2626);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(245,158,11,0.3);
        }
        .fade-in { animation: fadeIn 0.6s ease-out; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body class="font-sans antialiased bg-white">
    <div class="flex min-h-screen">

        {{-- ═══ Left Panel — Branding ═══ --}}
        <div class="hidden lg:flex lg:w-1/2 auth-gradient relative overflow-hidden items-center justify-center p-12">
            <div class="auth-shape-1"></div>
            <div class="auth-shape-2"></div>
            <div class="auth-shape-3"></div>

            <div class="relative z-10 text-center text-white max-w-md">
                <div class="w-20 h-20 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-4 leading-tight">Reset Password<br/>Anda</h1>
                <p class="text-lg text-white/80 leading-relaxed">Jangan khawatir, kami akan mengirimkan kode OTP ke email Anda untuk mengatur ulang kata sandi sehingga Anda dapat kembali mengakses akun.</p>
            </div>
        </div>

        {{-- ═══ Right Panel — Forgot Password Form ═══ --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-gray-50/50">
            <div class="w-full max-w-md fade-in">

                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-rose-600 rounded-xl flex items-center justify-center mx-auto shadow-lg shadow-amber-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Lupa Password? 🔑</h2>
                    <p class="text-gray-500 mt-1">Masukkan email Anda dan kami akan mengirimkan kode OTP reset</p>
                </div>

                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-amber-800">Masukkan email yang terdaftar. Kami akan mengirim kode OTP 6 digit untuk mengatur ulang kata sandi Anda.</p>
                    </div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-amber-500 input-focus transition"
                               placeholder="nama@email.com">
                    </div>

                    <button type="submit" class="w-full py-3 px-4 btn-primary text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 text-sm">
                        Kirim Kode OTP
                    </button>
                </form>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-gray-50/50 text-gray-400">atau</span>
                    </div>
                </div>

                <p class="text-center text-sm text-gray-500">
                    Ingat kata sandi Anda?
                    <a href="{{ route('login') }}" class="font-semibold text-amber-600 hover:text-amber-700 transition">Kembali ke halaman masuk</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
