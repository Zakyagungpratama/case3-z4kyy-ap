@extends('layouts.auth', ['title' => 'Buat akun'])

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-700">Mulai perjalananmu</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">Buat akun SakuKuliah</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Daftar untuk menyimpan jadwal, tugas, dan catatan keuanganmu.</p>

        <form method="POST" action="{{ route('register.store') }}" class="mt-8 flex flex-col gap-5">
            @csrf
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nama lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Kata sandi</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('password') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Ulangi kata sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
            </div>
            <flux:button type="submit" variant="primary" class="w-full bg-ink-950! text-white! hover:bg-ink-900!">Buat akun</flux:button>
        </form>

        <p class="mt-7 text-center text-sm text-slate-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-emerald-800 hover:text-emerald-950">Masuk</a>
        </p>
    </div>
@endsection
