<div class="flex flex-col gap-6">
    <div>
        <p class="text-sm font-semibold text-emerald-800">Pengelolaan data</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Sampah</h1>
        <p class="mt-2 text-sm text-slate-500">Data yang dihapus tersimpan di sini dan dapat dipulihkan.</p>
    </div>

    @if ($status)
        <p role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ $status }}
        </p>
    @endif

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Jadwal akademik</h2>
                <p class="mt-1 text-sm text-slate-500">Jadwal yang sudah dihapus.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $schedules->count() }}</span>
        </div>

        @forelse ($schedules as $schedule)
            <article wire:key="trash-schedule-{{ $schedule->id }}" class="flex flex-col gap-4 border-t border-slate-100 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-semibold text-slate-900">{{ $schedule->course_name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $schedule->day }}, {{ substr($schedule->starts_at, 0, 5) }}–{{ substr($schedule->ends_at, 0, 5) }} · {{ $schedule->room }}</p>
                    <p class="mt-1 text-xs text-slate-400">Dihapus {{ $schedule->deleted_at->locale('id')->diffForHumans() }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <flux:button type="button" variant="primary" size="sm" wire:click="restoreSchedule({{ $schedule->id }})" wire:loading.attr="disabled" wire:target="restoreSchedule({{ $schedule->id }})">
                        Pulihkan
                    </flux:button>
                    <flux:button type="button" variant="danger" size="sm" wire:click="deleteSchedulePermanently({{ $schedule->id }})" wire:confirm="Hapus jadwal ini secara permanen? Tindakan ini tidak dapat dibatalkan." wire:loading.attr="disabled" wire:target="deleteSchedulePermanently({{ $schedule->id }})">
                        Hapus permanen
                    </flux:button>
                </div>
            </article>
        @empty
            <p class="border-t border-slate-100 py-4 text-sm text-slate-500">Tidak ada jadwal di sampah.</p>
        @endforelse
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Tugas &amp; deadline</h2>
                <p class="mt-1 text-sm text-slate-500">Tugas yang sudah dihapus.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $tasks->count() }}</span>
        </div>

        @forelse ($tasks as $task)
            <article wire:key="trash-task-{{ $task->id }}" class="flex flex-col gap-4 border-t border-slate-100 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-semibold text-slate-900">{{ $task->title }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $task->course_name }} · Tenggat {{ $task->deadline->locale('id')->translatedFormat('d M Y, H:i') }}</p>
                    <p class="mt-1 text-xs text-slate-400">Dihapus {{ $task->deleted_at->locale('id')->diffForHumans() }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <flux:button type="button" variant="primary" size="sm" wire:click="restoreTask({{ $task->id }})" wire:loading.attr="disabled" wire:target="restoreTask({{ $task->id }})">
                        Pulihkan
                    </flux:button>
                    <flux:button type="button" variant="danger" size="sm" wire:click="deleteTaskPermanently({{ $task->id }})" wire:confirm="Hapus tugas ini secara permanen? Tindakan ini tidak dapat dibatalkan." wire:loading.attr="disabled" wire:target="deleteTaskPermanently({{ $task->id }})">
                        Hapus permanen
                    </flux:button>
                </div>
            </article>
        @empty
            <p class="border-t border-slate-100 py-4 text-sm text-slate-500">Tidak ada tugas di sampah.</p>
        @endforelse
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Catatan keuangan</h2>
                <p class="mt-1 text-sm text-slate-500">Transaksi yang sudah dihapus.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $transactions->count() }}</span>
        </div>

        @forelse ($transactions as $transaction)
            <article wire:key="trash-transaction-{{ $transaction->id }}" class="flex flex-col gap-4 border-t border-slate-100 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-semibold text-slate-900">{{ $transaction->description }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $transaction->category }} · {{ $transaction->date->locale('id')->translatedFormat('d M Y') }}</p>
                    <p class="mt-1 text-xs text-slate-400">Dihapus {{ $transaction->deleted_at->locale('id')->diffForHumans() }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <flux:button type="button" variant="primary" size="sm" wire:click="restoreTransaction({{ $transaction->id }})" wire:loading.attr="disabled" wire:target="restoreTransaction({{ $transaction->id }})">
                        Pulihkan
                    </flux:button>
                    <flux:button type="button" variant="danger" size="sm" wire:click="deleteTransactionPermanently({{ $transaction->id }})" wire:confirm="Hapus transaksi ini secara permanen? Tindakan ini tidak dapat dibatalkan." wire:loading.attr="disabled" wire:target="deleteTransactionPermanently({{ $transaction->id }})">
                        Hapus permanen
                    </flux:button>
                </div>
            </article>
        @empty
            <p class="border-t border-slate-100 py-4 text-sm text-slate-500">Tidak ada transaksi di sampah.</p>
        @endforelse
    </section>
</div>
