<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Sistem Barang') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .sidebar-gradient { background: linear-gradient(180deg, #1e1b4b 0%, #312e81 50%, #3730a3 100%); }
            .sidebar-link { transition: all .2s; border-radius: 10px; }
            .sidebar-link:hover { background: rgba(255,255,255,.08); transform: translateX(3px); }
            .sidebar-link.active { background: rgba(255,255,255,.12); box-shadow: inset 3px 0 0 #818cf8; }
            .card { background: #fff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.04); border: 1px solid #f1f5f9; transition: box-shadow .2s; }
            .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.06); }
            .btn-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all .25s; display: inline-flex; align-items: center; gap: 8px; }
            .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.3); }
            .btn-secondary { background: #f1f5f9; color: #475569; padding: 10px 20px; border-radius: 10px; font-weight: 500; font-size: 14px; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; }
            .btn-secondary:hover { background: #e2e8f0; }
            .btn-red { background: linear-gradient(135deg, #ef4444, #f97316); color: #fff; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all .25s; display: inline-flex; align-items: center; gap: 8px; }
            .btn-red:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(239,68,68,.25); }
            .main-bg { background: #f8fafc; }
            .table-header { background: #f8fafc; }
            .table-header th { color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: .5px; padding: 14px 16px; border-bottom: 2px solid #e2e8f0; }
            .table-cell { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #1e293b; }
            .badge-stok { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
            .badge-stok-ok { background: #dcfce7; color: #16a34a; }
            .badge-stok-low { background: #fef9c3; color: #ca8a04; }
            .badge-stok-empty { background: #fee2e2; color: #dc2626; }
            .input-field { width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; background: #fff; transition: all .2s; }
            .input-field:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
            nav a { text-decoration: none; }
            .pagination nav a { text-decoration: none; }
        </style>
    </head>
    <body class="font-sans antialiased main-bg">
        <div class="flex h-screen overflow-hidden">

            {{-- ═══════ SIDEBAR ═══════ --}}
            <aside x-data="{ open: false }" :class="open ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-40 w-64 sidebar-gradient shadow-2xl transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:z-auto lg:shadow-none flex flex-col">

                {{-- Logo --}}
                <div class="flex items-center justify-between h-16 px-5 border-b border-white/10">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/15 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-lg tracking-tight">Sistem Barang</span>
                    </a>
                    <button @click="open = false" class="lg:hidden p-1.5 rounded-lg text-white/60 hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Nav --}}
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <p class="px-3 text-xs font-semibold text-white/40 uppercase tracking-widest mb-2">Menu</p>

                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium sidebar-link {{ request()->routeIs('dashboard') ? 'active text-white' : 'text-white/70' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11a2 2 0 110 4 2 2 0 010-4z"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('barang.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium sidebar-link {{ request()->routeIs('barang.*') ? 'active text-white' : 'text-white/70' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Data Barang
                    </a>

                    <a href="{{ route('penjualan.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium sidebar-link {{ request()->routeIs('penjualan.*') ? 'active text-white' : 'text-white/70' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Penjualan
                    </a>
                </nav>

                {{-- Profile --}}
                <div class="p-4 border-t border-white/10">
                    <div class="flex items-center mb-3 px-1">
                        <div class="w-9 h-9 rounded-full bg-indigo-400/30 flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ Auth::user()->name[0] }}</span>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-white/50 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm font-medium text-white/60 rounded-xl hover:bg-white/10 sidebar-link">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Overlay mobile --}}
            <div x-show="open" @click="open = false" class="fixed inset-0 z-30 bg-black/40 backdrop-blur-sm lg:hidden"></div>

            {{-- ═══════ MAIN CONTENT ═══════ --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                {{-- Top bar --}}
                <header class="bg-white/80 backdrop-blur-md border-b border-gray-200/60">
                    <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                        <div class="flex items-center gap-4">
                            <button @click="open = true" class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            @isset($header)
                                <div class="hidden sm:block">{{ $header }}</div>
                            @else
                                <h1 class="text-lg font-bold text-gray-900">{{ config('app.name', 'Sistem Barang') }}</h1>
                            @endisset
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                <span>{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-700 font-semibold text-sm">{{ Auth::user()->name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
