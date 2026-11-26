<div class="flex flex-col gap-6">
    <div>
        <p class="text-sm font-semibold text-emerald-800">Perkuliahan</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Jadwal akademik</h1>
        <p class="mt-2 text-sm text-slate-500">Kelola jadwal mata kuliah dan informasi ruang kelas.</p>
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-slate-900">{{ $scheduleId ? 'Ubah jadwal kuliah' : 'Tambah jadwal kuliah' }}</h2>
            <p class="mt-1 text-sm text-slate-500">Isi mata kuliah, dosen, hari, waktu, dan ruang.</p>
        </div>
        @if ($status)
            <p role="status" class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ $status }}</p>
        @endif
        <form wire:submit="save" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            <div>
                <label for="course-name" class="mb-2 block text-sm font-medium text-slate-700">Nama mata kuliah</label>
                <input id="course-name" wire:model="courseName" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10" placeholder="Contoh: Kalkulus">
                @error('courseName') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="lecturer" class="mb-2 block text-sm font-medium text-slate-700">Dosen pengampu</label>
                <input id="lecturer" wire:model="lecturer" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10" placeholder="Nama dosen">
                @error('lecturer') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="day" class="mb-2 block text-sm font-medium text-slate-700">Hari</label>
                <select id="day" wire:model="day" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                    @foreach ($days as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                @error('day') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="starts-at" class="mb-2 block text-sm font-medium text-slate-700">Jam mulai</label>
                <input id="starts-at" wire:model="startsAt" type="time" step="300" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('startsAt') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="ends-at" class="mb-2 block text-sm font-medium text-slate-700">Jam selesai</label>
                <input id="ends-at" wire:model="endsAt" type="time" step="300" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('endsAt') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="room" class="mb-2 block text-sm font-medium text-slate-700">Ruang</label>
                <input id="room" wire:model="room" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10" placeholder="Contoh: A101">
                @error('room') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-end gap-3 sm:col-span-2 xl:col-span-3">
                <flux:button type="submit" variant="primary" class="bg-ink-950! text-white! hover:bg-ink-900!">{{ $scheduleId ? 'Simpan perubahan' : 'Simpan jadwal' }}</flux:button>
                @if ($scheduleId)
                    <button type="button" wire:click="cancel" class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                @endif
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5 sm:px-7">
            <div>
                <h2 class="font-semibold text-slate-900">Daftar mata kuliah</h2>
                <p class="mt-1 text-xs text-slate-500">{{ $schedules->count() }} jadwal tersimpan</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold sm:px-7">Mata kuliah</th>
                        <th class="px-5 py-3 font-semibold">Dosen</th>
                        <th class="px-5 py-3 font-semibold">Hari &amp; waktu</th>
                        <th class="px-5 py-3 font-semibold">Ruang</th>
                        <th class="px-5 py-3 text-right font-semibold sm:px-7">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($schedules as $schedule)
                        <tr wire:key="schedule-{{ $schedule->id }}">
                            <td class="px-5 py-4 font-semibold text-slate-900 sm:px-7">{{ $schedule->course_name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $schedule->lecturer }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $schedule->day }}, {{ substr($schedule->starts_at, 0, 5) }}–{{ substr($schedule->ends_at, 0, 5) }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $schedule->room }}</td>
                            <td class="px-5 py-4 text-right sm:px-7">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="edit({{ $schedule->id }})" class="rounded-lg px-3 py-2 text-xs font-semibold text-emerald-800 hover:bg-emerald-50">Ubah</button>
                                    <button type="button" wire:click="delete({{ $schedule->id }})" wire:confirm="Hapus jadwal ini?" class="rounded-lg px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada jadwal kuliah. Tambahkan jadwal pertamamu di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
