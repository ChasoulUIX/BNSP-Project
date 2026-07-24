<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP — {{ config('app.name', 'Sistem Barang') }}</title>
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

        .otp-input {
            width: 52px; height: 60px;
            text-align: center; font-size: 24px; font-weight: 700;
            border: 2px solid #e5e7eb; border-radius: 14px;
            background: #fff; color: #1f2937;
            outline: none; transition: all 0.25s;
            caret-color: #f59e0b;
        }
        .otp-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.18);
            transform: translateY(-2px);
        }
        .otp-input.filled {
            border-color: #f59e0b;
            background: #fffbeb;
        }
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-4 leading-tight">Verifikasi<br/>Kode OTP</h1>
                <p class="text-lg text-white/80 leading-relaxed">Kami telah mengirimkan kode 6 digit ke email Anda. Silakan masukkan kode tersebut untuk melanjutkan proses reset password.</p>
            </div>
        </div>

        {{-- ═══ Right Panel — OTP Form ═══ --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-gray-50/50">
            <div class="w-full max-w-md fade-in">

                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-rose-600 rounded-xl flex items-center justify-center mx-auto shadow-lg shadow-amber-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Masukkan Kode OTP 🛡️</h2>
                    <p class="text-gray-500 mt-1">Kode 6 digit telah dikirim ke <span class="font-semibold text-gray-700">{{ $email }}</span></p>
                </div>

                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-amber-800">Kode berlaku selama <strong>10 menit</strong>. Jika tidak menerima email, cek folder spam.</p>
                    </div>
                </div>

                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('password.otp.verify.post') }}" id="otpForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="otp" id="otpHidden" value="">

                    <div class="flex justify-center gap-3" id="otpContainer">
                        <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" data-index="0" autofocus>
                        <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="1">
                        <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="2">
                        <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="3">
                        <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="4">
                        <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="5">
                    </div>

                    <button type="submit" id="submitBtn" class="w-full py-3 px-4 btn-primary text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 text-sm opacity-50 pointer-events-none" disabled>
                        Verifikasi Kode
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
                    Kirim ulang kode?
                    <form method="POST" action="{{ route('password.email') }}" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" class="font-semibold text-amber-600 hover:text-amber-700 transition">Kirim ulang</button>
                    </form>
                </p>

                <p class="text-center text-sm text-gray-500 mt-3">
                    <a href="{{ route('login') }}" class="font-semibold text-amber-600 hover:text-amber-700 transition">Kembali ke halaman masuk</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otpHidden');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('otpForm');

            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    const val = this.value.replace(/[^0-9]/g, '');
                    this.value = val;

                    if (val) {
                        this.classList.add('filled');
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    } else {
                        this.classList.remove('filled');
                    }
                    updateHidden();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        inputs[index - 1].classList.remove('filled');
                        updateHidden();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                    pasteData.split('').forEach((char, i) => {
                        if (inputs[i]) {
                            inputs[i].value = char;
                            inputs[i].classList.add('filled');
                        }
                    });
                    if (pasteData.length > 0) {
                        const focusIndex = Math.min(pasteData.length, inputs.length - 1);
                        inputs[focusIndex].focus();
                    }
                    updateHidden();
                });

                input.addEventListener('focus', function() {
                    this.select();
                });
            });

            function updateHidden() {
                const otp = Array.from(inputs).map(i => i.value).join('');
                hiddenInput.value = otp;
                if (otp.length === 6) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'pointer-events-none');
                }
            }
        });
    </script>
</body>
</html>
