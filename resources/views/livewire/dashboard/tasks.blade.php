<div class="flex flex-col gap-6">
    <div>
        <p class="text-sm font-semibold text-emerald-800">Perkuliahan</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Tugas &amp; deadline</h1>
        <p class="mt-2 text-sm text-slate-500">Catat pekerjaan, atur tenggat, dan tandai tugas yang sudah selesai.</p>
    </div>

    @php
        $completion = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-slate-900">{{ $taskId ? 'Ubah tugas' : 'Tambah tugas' }}</h2>
            <p class="mt-1 text-sm text-slate-500">Simpan tugas beserta mata kuliah, waktu pengumpulan, dan prioritas.</p>
        </div>
        @if ($status)
            <p role="status" class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ $status }}</p>
        @endif
        <form wire:submit="save" class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="task-title" class="mb-2 block text-sm font-medium text-slate-700">Nama tugas</label>
                <input id="task-title" wire:model="title" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10" placeholder="Masukkan nama tugas">
                @error('title') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="task-course" class="mb-2 block text-sm font-medium text-slate-700">Mata kuliah</label>
                <input id="task-course" wire:model="courseName" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10" placeholder="Contoh: Pemrograman Web">
                @error('courseName') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="task-deadline" class="mb-2 block text-sm font-medium text-slate-700">Tanggal &amp; jam deadline</label>
                <input id="task-deadline" wire:model="deadline" type="datetime-local" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('deadline') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="task-priority" class="mb-2 block text-sm font-medium text-slate-700">Prioritas</label>
                <select id="task-priority" wire:model="priority" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                    @foreach ($priorities as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                @error('priority') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-end gap-3 sm:col-span-2">
                <flux:button type="submit" variant="primary" class="bg-ink-950! text-white! hover:bg-ink-900!">{{ $taskId ? 'Simpan perubahan' : 'Tambah tugas' }}</flux:button>
                @if ($taskId)
                    <button type="button" wire:click="cancel" class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                @endif
            </div>
        </form>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="font-semibold text-slate-900">Daftar tugas mahasiswa</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai · {{ $completion }}%</p>
            </div>
            <div class="flex flex-wrap gap-2" aria-label="Filter tugas">
                @foreach (['all' => 'Semua', 'open' => 'Belum selesai', 'completed' => 'Selesai', 'high' => 'Prioritas tinggi'] as $value => $label)
                    <button type="button" wire:click="$set('filter', '{{ $value }}')" @class([
                        'rounded-full px-3 py-2 text-xs font-semibold transition',
                        'bg-ink-950 text-white' => $filter === $value,
                        'bg-slate-100 text-slate-600 hover:bg-slate-200' => $filter !== $value,
                    ])>{{ $label }}</button>
                @endforeach
            </div>
        </div>
        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full bg-emerald-600 transition-all" style="width: {{ $completion }}%"></div>
        </div>

        <div class="mt-5 grid gap-3">
            @forelse ($tasks as $task)
                <article wire:key="task-{{ $task->id }}" class="flex flex-col gap-4 rounded-xl border border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-start gap-3">
                        <button
                            type="button"
                            wire:click="toggleCompletion({{ $task->id }})"
                            class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-md border text-xs font-bold {{ $task->is_completed ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 text-transparent' }}"
                            aria-label="{{ $task->is_completed ? 'Tandai belum selesai' : 'Tandai selesai' }}"
                            aria-pressed="{{ $task->is_completed ? 'true' : 'false' }}"
                        >✓</button>
                        <div class="min-w-0">
                            <h3 @class(['truncate text-sm font-semibold', 'text-slate-400 line-through' => $task->is_completed, 'text-slate-900' => ! $task->is_completed])>{{ $task->title }}</h3>
                            <p class="mt-1 text-xs text-slate-500">{{ $task->course_name }} · Tenggat {{ $task->deadline->locale('id')->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-3 sm:justify-end">
                        <span @class([
                            'rounded-full px-2.5 py-1 text-[11px] font-semibold',
                            'bg-rose-50 text-rose-700' => $task->priority === 'Tinggi',
                            'bg-amber-50 text-amber-700' => $task->priority === 'Sedang',
                            'bg-slate-100 text-slate-600' => $task->priority === 'Rendah',
                        ])>{{ $task->priority }}</span>
                        <button type="button" wire:click="edit({{ $task->id }})" class="rounded-lg px-3 py-2 text-xs font-semibold text-emerald-800 hover:bg-emerald-50">Ubah</button>
                        <button type="button" wire:click="delete({{ $task->id }})" wire:confirm="Hapus tugas ini?" class="rounded-lg px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                    </div>
                </article>
            @empty
                <div class="rounded-xl bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">Tidak ada tugas pada filter ini.</div>
            @endforelse
        </div>
    </section>
</div>
