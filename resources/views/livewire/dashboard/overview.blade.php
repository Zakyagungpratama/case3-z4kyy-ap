<div class="flex flex-col gap-7">
    <section class="relative overflow-hidden rounded-3xl bg-ink-950 px-6 py-7 text-white shadow-sm sm:px-9 sm:py-9">
        <div class="absolute -right-10 -top-20 size-64 rounded-full border-[32px] border-white/5"></div>
        <div class="absolute -bottom-28 right-36 size-56 rounded-full bg-emerald-500/10 blur-3xl"></div>
        <div class="relative max-w-2xl">
            <p class="text-sm font-semibold text-gold-400">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Halo, {{ $student->name }} 👋</h1>
            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300 sm:text-base">Selamat datang di SakuKuliah, ruang untuk mengatur perkuliahan dan menjaga pengeluaran tetap terarah.</p>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan aktivitas">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Mata kuliah</p>
                <span class="flex size-10 items-center justify-center rounded-xl bg-blue-50 text-lg text-blue-700" aria-hidden="true">▣</span>
            </div>
            <p class="mt-5 text-3xl font-bold tracking-tight">{{ $stats['courses'] }}</p>
            <p class="mt-1 text-xs text-slate-500">Jadwal yang tersimpan</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Tugas berjalan</p>
                <span class="flex size-10 items-center justify-center rounded-xl bg-amber-50 text-lg text-amber-700" aria-hidden="true">☑</span>
            </div>
            <p class="mt-5 text-3xl font-bold tracking-tight">{{ $stats['open_tasks'] }}</p>
            <a href="{{ route('tasks') }}" class="mt-1 inline-block text-xs font-medium text-emerald-800 hover:underline">Lihat daftar tugas</a>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Total pemasukan</p>
                <span class="flex size-10 items-center justify-center rounded-xl bg-emerald-50 text-lg text-emerald-700" aria-hidden="true">↗</span>
            </div>
            <p class="mt-5 text-2xl font-bold tracking-tight">Rp{{ number_format($stats['income'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Dari seluruh transaksi</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Pengeluaran bulan ini</p>
                <span class="flex size-10 items-center justify-center rounded-xl bg-rose-50 text-lg text-rose-700" aria-hidden="true">↘</span>
            </div>
            <p class="mt-5 text-2xl font-bold tracking-tight">Rp{{ number_format($stats['expenses'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Pengeluaran bulan berjalan</p>
        </article>
    </section>

    <section class="grid gap-5 xl:grid-cols-2">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 flex items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-slate-900">Jadwal perkuliahan hari ini</h2>
                    <p class="mt-1 text-xs text-slate-500">{{ now()->locale('id')->translatedFormat('l') }}</p>
                </div>
                <a href="{{ route('schedules') }}" class="text-sm font-semibold text-emerald-800 hover:underline">Semua jadwal</a>
            </div>
            <div class="flex flex-col divide-y divide-slate-100">
                @forelse ($todaySchedule as $schedule)
                    <div class="flex items-start gap-4 py-4 first:pt-0 last:pb-0">
                        <div class="min-w-20 rounded-xl bg-slate-100 px-3 py-2 text-center text-xs font-semibold text-slate-700">
                            {{ substr($schedule->starts_at, 0, 5) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $schedule->course_name }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $schedule->lecturer }} · {{ $schedule->room }}</p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl bg-slate-50 px-4 py-7 text-center text-sm text-slate-500">
                        Belum ada jadwal untuk hari ini.
                        <a href="{{ route('schedules') }}" class="ml-1 font-semibold text-emerald-800">Tambah jadwal</a>
                    </div>
                @endforelse
            </div>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 flex items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-slate-900">Tugas mendatang</h2>
                    <p class="mt-1 text-xs text-slate-500">Jangan lewatkan tenggat waktumu</p>
                </div>
                <a href="{{ route('tasks') }}" class="text-sm font-semibold text-emerald-800 hover:underline">Semua tugas</a>
            </div>
            <div class="flex flex-col divide-y divide-slate-100">
                @forelse ($upcomingTasks as $task)
                    <div class="flex items-start justify-between gap-4 py-4 first:pt-0 last:pb-0">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $task->title }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $task->course_name }} · {{ $task->deadline->locale('id')->translatedFormat('d M, H:i') }}</p>
                        </div>
                        <span @class([
                            'shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold',
                            'bg-rose-50 text-rose-700' => $task->priority === 'Tinggi',
                            'bg-amber-50 text-amber-700' => $task->priority === 'Sedang',
                            'bg-slate-100 text-slate-600' => $task->priority === 'Rendah',
                        ])>{{ $task->priority }}</span>
                    </div>
                @empty
                    <div class="rounded-xl bg-slate-50 px-4 py-7 text-center text-sm text-slate-500">
                        Tidak ada tugas mendatang.
                        <a href="{{ route('tasks') }}" class="ml-1 font-semibold text-emerald-800">Tambah tugas</a>
                    </div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-slate-900">Informasi mahasiswa</h2>
                <p class="mt-1 text-xs text-slate-500">Data akademik akunmu</p>
            </div>
            <a href="{{ route('profile') }}" class="text-sm font-semibold text-emerald-800 hover:underline">Ubah profil</a>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                'Nama' => $student->name,
                'NIM' => $student->nim ?: 'Belum diisi',
                'Fakultas' => $student->faculty ?: 'Belum diisi',
                'Program studi' => $student->study_program ?: 'Belum diisi',
            ] as $label => $value)
                <div class="rounded-xl bg-slate-50 px-4 py-3">
                    <p class="text-xs text-slate-500">{{ $label }}</p>
                    <p class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>
