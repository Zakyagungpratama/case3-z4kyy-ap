<div class="flex flex-col gap-6">
    <div>
        <p class="text-sm font-semibold text-emerald-800">Akun mahasiswa</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Profil mahasiswa</h1>
        <p class="mt-2 text-sm text-slate-500">Perbarui informasi pribadi dan akademikmu.</p>
    </div>

    <div class="grid gap-5 lg:grid-cols-[0.8fr_1.2fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
            <span class="mx-auto flex size-24 items-center justify-center rounded-full bg-ink-950 text-3xl font-bold text-gold-400">{{ $student->initials() }}</span>
            <h2 class="mt-4 text-xl font-bold text-slate-900">{{ $student->name }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $student->nim ?: 'NIM belum diisi' }}</p>
            <p class="mt-4 text-sm font-medium text-slate-700">{{ $student->study_program ?: 'Program studi belum diisi' }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ $student->faculty ?: 'Fakultas belum diisi' }}</p>
            <p class="mt-1 text-xs text-slate-400">SakuKuliah</p>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-900">Informasi pribadi &amp; akademik</h2>
                <p class="mt-1 text-sm text-slate-500">Data tersimpan hanya untuk akunmu.</p>
            </div>

            @if ($status)
                <p role="status" class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ $status }}</p>
            @endif

            <form wire:submit="save" class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="profile-name" class="mb-2 block text-sm font-medium text-slate-700">Nama lengkap</label>
                    <input id="profile-name" wire:model="name" type="text" autocomplete="name" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                    @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="profile-email" class="mb-2 block text-sm font-medium text-slate-700">Email akun</label>
                    <input id="profile-email" value="{{ $student->email }}" type="email" disabled class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">
                </div>
                <div>
                    <label for="profile-nim" class="mb-2 block text-sm font-medium text-slate-700">NIM</label>
                    <input id="profile-nim" wire:model="nim" type="text" maxlength="30" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                    @error('nim') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="profile-faculty" class="mb-2 block text-sm font-medium text-slate-700">Fakultas</label>
                    <input id="profile-faculty" wire:model="faculty" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                    @error('faculty') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="profile-program" class="mb-2 block text-sm font-medium text-slate-700">Program studi</label>
                    <input id="profile-program" wire:model="studyProgram" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                    @error('studyProgram') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <flux:button type="submit" variant="primary" class="bg-ink-950! text-white! hover:bg-ink-900!">Simpan profil</flux:button>
                </div>
            </form>
        </section>
    </div>
</div>
