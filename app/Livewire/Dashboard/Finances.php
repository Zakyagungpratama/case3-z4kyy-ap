<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Concerns\InteractsWithAuthenticatedStudent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Finances extends Component
{
    use InteractsWithAuthenticatedStudent;

    public ?int $transactionId = null;

    public string $type = 'expense';

    public string $category = 'Makanan';

    public string $description = '';

    public string $amount = '';

    public string $date = '';

    public string $status = '';

    private const CATEGORIES = [
        'Uang Saku',
        'Beasiswa',
        'Gaji / Freelance',
        'Makanan',
        'Transportasi',
        'Keperluan Kuliah',
        'Tempat Tinggal',
        'Internet',
        'Hiburan',
        'Lainnya',
    ];

    public function mount(): void
    {
        $this->date = now()->toDateString();
    }

    public function save(): void
    {
        $student = $this->student();
        $validated = $this->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'category' => ['required', Rule::in(self::CATEGORIES)],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date'],
        ]);

        if ($this->transactionId) {
            $student->financeTransactions()->findOrFail($this->transactionId)->update($validated);
        } else {
            $student->financeTransactions()->create($validated);
        }

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->resetForm();
        $this->status = 'Transaksi berhasil disimpan.';
    }

    public function edit(int $transactionId): void
    {
        $transaction = $this->student()->financeTransactions()->findOrFail($transactionId);

        $this->transactionId = $transaction->id;
        $this->type = $transaction->type;
        $this->category = $transaction->category;
        $this->description = $transaction->description;
        $this->amount = (string) $transaction->amount;
        $this->date = $transaction->date->toDateString();
        $this->status = '';
    }

    public function delete(int $transactionId): void
    {
        $student = $this->student();
        $student->financeTransactions()->findOrFail($transactionId)->delete();
        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Transaksi berhasil dihapus.';
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->status = '';
    }

    public function render(): View
    {
        $transactions = $this->student()->financeTransactions();

        return view('livewire.dashboard.finances', [
            'transactions' => $transactions->orderByDesc('date')->orderByDesc('id')->get(),
            'income' => (int) $this->student()->financeTransactions()->where('type', 'income')->sum('amount'),
            'expenses' => (int) $this->student()->financeTransactions()->where('type', 'expense')->sum('amount'),
            'categories' => self::CATEGORIES,
        ]);
    }

    private function resetForm(): void
    {
        $this->reset('transactionId', 'description', 'amount');
        $this->type = 'expense';
        $this->category = 'Makanan';
        $this->date = now()->toDateString();
        $this->resetValidation();
    }
}
