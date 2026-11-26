<div class="flex flex-col gap-6">
    <div>
        <p class="text-sm font-semibold text-emerald-800">Keuangan</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Catatan keuangan</h1>
        <p class="mt-2 text-sm text-slate-500">Catat uang masuk dan keluar agar pengeluaran kuliah tetap terpantau.</p>
    </div>

    @php
        $balance = $income - $expenses;
    @endphp

    <section class="grid gap-4 md:grid-cols-3" aria-label="Ringkasan keuangan">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total saldo</p>
            <p class="mt-3 text-2xl font-bold tracking-tight {{ $balance < 0 ? 'text-rose-700' : 'text-slate-900' }}">Rp{{ number_format($balance, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Pemasukan dikurangi pengeluaran</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total pemasukan</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-emerald-700">Rp{{ number_format($income, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Dari seluruh transaksi</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total pengeluaran</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-rose-700">Rp{{ number_format($expenses, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500">Dari seluruh transaksi</p>
        </article>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-slate-900">{{ $transactionId ? 'Ubah transaksi' : 'Catat transaksi' }}</h2>
            <p class="mt-1 text-sm text-slate-500">Masukkan jenis, kategori, keterangan, nominal, dan tanggal.</p>
        </div>
        @if ($status)
            <p role="status" class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ $status }}</p>
        @endif
        <form wire:submit="save" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            <div>
                <label for="transaction-type" class="mb-2 block text-sm font-medium text-slate-700">Jenis transaksi</label>
                <select id="transaction-type" wire:model="type" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                    <option value="income">Pemasukan</option>
                    <option value="expense">Pengeluaran</option>
                </select>
                @error('type') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="transaction-category" class="mb-2 block text-sm font-medium text-slate-700">Kategori</label>
                <select id="transaction-category" wire:model="category" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                    @foreach ($categories as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="transaction-amount" class="mb-2 block text-sm font-medium text-slate-700">Nominal (Rp)</label>
                <input id="transaction-amount" wire:model="amount" type="number" min="1" step="1" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10" placeholder="Contoh: 50000">
                @error('amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="xl:col-span-2">
                <label for="transaction-description" class="mb-2 block text-sm font-medium text-slate-700">Keterangan</label>
                <input id="transaction-description" wire:model="description" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10" placeholder="Keterangan transaksi">
                @error('description') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="transaction-date" class="mb-2 block text-sm font-medium text-slate-700">Tanggal</label>
                <input id="transaction-date" wire:model="date" type="date" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10">
                @error('date') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-end gap-3 sm:col-span-2 xl:col-span-3">
                <flux:button type="submit" variant="primary" class="bg-ink-950! text-white! hover:bg-ink-900!">{{ $transactionId ? 'Simpan perubahan' : 'Simpan transaksi' }}</flux:button>
                @if ($transactionId)
                    <button type="button" wire:click="cancel" class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                @endif
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-5 sm:px-7">
            <div>
                <h2 class="font-semibold text-slate-900">Riwayat transaksi</h2>
                <p class="mt-1 text-xs text-slate-500">{{ $transactions->count() }} transaksi tersimpan</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold sm:px-7">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Keterangan</th>
                        <th class="px-5 py-3 font-semibold">Kategori</th>
                        <th class="px-5 py-3 font-semibold">Jenis</th>
                        <th class="px-5 py-3 text-right font-semibold">Nominal</th>
                        <th class="px-5 py-3 text-right font-semibold sm:px-7">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($transactions as $transaction)
                        <tr wire:key="transaction-{{ $transaction->id }}">
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600 sm:px-7">{{ $transaction->date->locale('id')->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $transaction->description }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $transaction->category }}</td>
                            <td class="px-5 py-4">
                                <span @class([
                                    'rounded-full px-2.5 py-1 text-[11px] font-semibold',
                                    'bg-emerald-50 text-emerald-700' => $transaction->type === 'income',
                                    'bg-rose-50 text-rose-700' => $transaction->type === 'expense',
                                ])>{{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span>
                            </td>
                            <td @class([
                                'whitespace-nowrap px-5 py-4 text-right font-semibold',
                                'text-emerald-700' => $transaction->type === 'income',
                                'text-slate-800' => $transaction->type === 'expense',
                            ])>{{ $transaction->type === 'income' ? '+' : '−' }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right sm:px-7">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="edit({{ $transaction->id }})" class="rounded-lg px-3 py-2 text-xs font-semibold text-emerald-800 hover:bg-emerald-50">Ubah</button>
                                    <button type="button" wire:click="delete({{ $transaction->id }})" wire:confirm="Hapus transaksi ini?" class="rounded-lg px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada transaksi keuangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
