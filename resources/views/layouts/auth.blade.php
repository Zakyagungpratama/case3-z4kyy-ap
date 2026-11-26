<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Masuk' }} | SakuKuliah</title>
        @fluxAppearance
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
        <div class="grid min-h-screen lg:grid-cols-[1.05fr_0.95fr]">
            <section class="relative hidden overflow-hidden bg-ink-950 px-12 py-14 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="absolute -right-32 -top-32 size-96 rounded-full border-[48px] border-white/5"></div>
                <div class="absolute -bottom-36 -left-20 size-96 rounded-full bg-gold-400/10 blur-3xl"></div>
                <a href="{{ route('login') }}" class="relative flex items-center gap-3">
                    <span class="flex size-12 items-center justify-center rounded-2xl bg-gold-400 text-xl font-black text-ink-950">S</span>
                    <span class="text-xl font-bold">SakuKuliah</span>
                </a>
                <div class="relative max-w-xl">
                    <p class="mb-5 text-sm font-semibold uppercase tracking-[0.2em] text-gold-400">Satu ruang untuk kuliahmu</p>
                    <h1 class="text-5xl font-bold leading-tight tracking-tight">Atur kuliah.<br>Jaga isi saku.</h1>
                    <p class="mt-6 max-w-lg text-lg leading-8 text-slate-300">Kelola jadwal, tugas, dan pemasukan maupun pengeluaran mahasiswa dalam satu dashboard yang sederhana.</p>
                </div>
                <p class="relative text-sm text-slate-500">SakuKuliah · Dasbor Akademik Mahasiswa</p>
            </section>

            <main class="flex items-center justify-center px-5 py-10 sm:px-10">
                <div class="w-full max-w-md">
                    <a href="{{ route('login') }}" class="mb-10 flex items-center gap-3 lg:hidden">
                        <span class="flex size-11 items-center justify-center rounded-2xl bg-gold-400 text-lg font-black text-ink-950">S</span>
                        <span class="text-lg font-bold">SakuKuliah</span>
                    </a>
                    @yield('content')
                </div>
            </main>
        </div>
        @livewireScripts
        @fluxScripts
    </body>
</html>
