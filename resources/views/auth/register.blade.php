<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar — {{ config('app.name', 'Sistem Barang') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-gradient {
            background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 50%, #2563eb 100%);
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
            background: rgba(255,255,255,0.05); top: 40%; right: 25%;
            transform: rotate(45deg);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(124,58,237,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #6d28d9, #4338ca);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(124,58,237,0.3);
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-4 leading-tight">Bergabung Bersama Kami</h1>
                <p class="text-lg text-white/80 leading-relaxed">Daftar sekarang dan mulai kelola inventaris barang Anda dengan lebih mudah dan efisien.</p>

                {{-- <div class="flex items-center justify-center gap-8 mt-10">
                    <div class="text-center">
                        <p class="text-2xl font-bold">🆓</p>
                        <p class="text-xs text-white/60 mt-1">Gratis</p>
                    </div>
                    <div class="w-px h-8 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-2xl font-bold">🔒</p>
                        <p class="text-xs text-white/60 mt-1">Aman</p>
                    </div>
                    <div class="w-px h-8 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-2xl font-bold">🚀</p>
                        <p class="text-xs text-white/60 mt-1">Instan</p>
                    </div>
                </div> --}}
            </div>
        </div>

        {{-- ═══ Right Panel — Register Form ═══ --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-gray-50/50">
            <div class="w-full max-w-md fade-in">

                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto shadow-lg shadow-violet-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Buat Akun Baru ✨</h2>
                    <p class="text-gray-500 mt-1">Isi data diri Anda untuk mendaftar</p>
                </div>

                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-violet-500 input-focus transition"
                               placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-violet-500 input-focus transition"
                               placeholder="nama@email.com">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-violet-500 input-focus transition"
                               placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-violet-500 input-focus transition"
                               placeholder="Ulangi password">
                    </div>

                    <button type="submit" class="w-full py-3 px-4 btn-primary text-white font-semibold rounded-xl shadow-lg shadow-violet-500/25 text-sm mt-2">
                        Daftar Sekarang
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
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-violet-600 hover:text-violet-700 transition">Masuk sekarang</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
