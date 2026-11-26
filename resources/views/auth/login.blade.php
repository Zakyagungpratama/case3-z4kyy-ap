@extends('layouts.auth', ['title' => 'Masuk'])

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-700">Selamat datang kembali</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">Masuk ke akunmu</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Lanjutkan mengatur aktivitas kuliah dan keuanganmu.</p>

        @if (session('status'))
            <p role="status" class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </p>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="mt-8 flex flex-col gap-5">
            @csrf
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Kata sandi</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('password') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="size-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                Ingat saya
            </label>
            <flux:button type="submit" variant="primary" class="w-full bg-ink-950! text-white! hover:bg-ink-900!">Masuk</flux:button>
        </form>

        <p class="mt-7 text-center text-sm text-slate-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-emerald-800 hover:text-emerald-950">Daftar sekarang</a>
        </p>
    </div>
@endsection
