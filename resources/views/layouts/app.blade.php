<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'SakuKuliah' }} | SakuKuliah</title>
        @fluxAppearance
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased" x-data="{ mobileMenuOpen: false }">
        <div
            x-cloak
            x-show="mobileMenuOpen"
            x-transition.opacity
            class="fixed inset-0 z-30 bg-slate-950/50 md:hidden"
            aria-hidden="true"
            @click="mobileMenuOpen = false"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-ink-950 px-4 py-6 text-white transition-transform duration-200 md:translate-x-0"
            :class="mobileMenuOpen ? 'translate-x-0' : ''"
        >
            <a href="{{ route('dashboard') }}" class="mb-9 flex items-center gap-3 px-2">
                <span class="flex size-11 items-center justify-center rounded-2xl bg-gold-400 text-lg font-black text-ink-950">S</span>
                <span>
                    <span class="block text-lg font-bold tracking-tight">SakuKuliah</span>
                    <span class="block text-xs text-slate-400">Akademik &amp; keuangan</span>
                </span>
            </a>

            <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Menu utama</p>
            <nav class="flex flex-col gap-1" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-white/10 text-white' => request()->routeIs('dashboard'),
                    'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('dashboard'),
                ]) aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <span aria-hidden="true">▦</span> Dashboard
                </a>
                <a href="{{ route('profile') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-white/10 text-white' => request()->routeIs('profile'),
                    'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('profile'),
                ]) aria-current="{{ request()->routeIs('profile') ? 'page' : 'false' }}">
                    <span aria-hidden="true">♙</span> Profil mahasiswa
                </a>
            </nav>

            <p class="mb-3 mt-8 px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Perkuliahan</p>
            <nav class="flex flex-col gap-1" aria-label="Menu perkuliahan">
                <a href="{{ route('schedules') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-white/10 text-white' => request()->routeIs('schedules'),
                    'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('schedules'),
                ]) aria-current="{{ request()->routeIs('schedules') ? 'page' : 'false' }}">
                    <span aria-hidden="true">▣</span> Jadwal akademik
                </a>
                <a href="{{ route('tasks') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-white/10 text-white' => request()->routeIs('tasks'),
                    'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('tasks'),
                ]) aria-current="{{ request()->routeIs('tasks') ? 'page' : 'false' }}">
                    <span aria-hidden="true">☑</span> Tugas &amp; deadline
                </a>
            </nav>

            <p class="mb-3 mt-8 px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Keuangan</p>
            <nav class="flex flex-col gap-1" aria-label="Menu keuangan">
                <a href="{{ route('finances') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-white/10 text-white' => request()->routeIs('finances'),
                    'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('finances'),
                ]) aria-current="{{ request()->routeIs('finances') ? 'page' : 'false' }}">
                    <span aria-hidden="true">◈</span> Catatan keuangan
                </a>
                <a href="{{ route('trash') }}" @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                    'bg-white/10 text-white' => request()->routeIs('trash'),
                    'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('trash'),
                ]) aria-current="{{ request()->routeIs('trash') ? 'page' : 'false' }}">
                    <span aria-hidden="true">▧</span> Sampah
                </a>
            </nav>

            <div class="mt-auto rounded-2xl border border-white/10 bg-white/5 p-4 text-xs leading-5 text-slate-400">
                <strong class="text-gold-400">SakuKuliah</strong><br>
                Ruang kendali aktivitas kuliahmu.
            </div>
        </aside>

        <div class="min-h-screen md:pl-64">
            <header class="sticky top-0 z-20 flex h-[76px] items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-7">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex size-10 items-center justify-center rounded-xl border border-slate-200 text-slate-700 md:hidden"
                        aria-label="Buka menu"
                        @click="mobileMenuOpen = true"
                    >☰</button>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            @if (request()->routeIs('dashboard'))
                                Dashboard
                            @elseif (request()->routeIs('profile'))
                                Profil mahasiswa
                            @elseif (request()->routeIs('schedules'))
                                Jadwal akademik
                            @elseif (request()->routeIs('tasks'))
                                Tugas &amp; deadline
                            @elseif (request()->routeIs('trash'))
                                Sampah
                            @else
                                Catatan keuangan
                            @endif
                        </p>
                        <p class="hidden text-xs text-slate-500 sm:block">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('profile') }}" class="hidden items-center gap-2 rounded-full bg-slate-100 py-1.5 pl-1.5 pr-3 sm:flex">
                        <span class="flex size-8 items-center justify-center rounded-full bg-ink-900 text-xs font-bold text-white">{{ auth()->user()->initials() }}</span>
                        <span class="max-w-36 truncate text-sm font-medium">{{ auth()->user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:button type="submit" variant="subtle" size="sm">Keluar</flux:button>
                    </form>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-7 sm:px-7 sm:py-9">
                @yield('content')
            </main>
        </div>

        @livewireScripts
        @fluxScripts
    </body>
</html>
