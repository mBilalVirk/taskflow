<?php

namespace App\Livewire;

use App\Models\Team;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class InvoicesList extends Component
{
    use WithPagination;

    public Team $team;
    public string $filter = 'all';

    public function mount(Team $team)
    {
        $this->team = $team;
    }

    #[Computed]
    public function invoices()
    {
        $query = $this->team->invoices();

        if ($this->filter === 'paid') {
            $query->where('status', 'paid');
        } elseif ($this->filter === 'pending') {
            $query->whereIn('status', ['open', 'draft']);
        }

        return $query->latest()->paginate(20);
    }

    public function downloadInvoice($invoiceId)
    {
        $invoice = $this->team->invoices()->findOrFail($invoiceId);
        
        if ($invoice->pdf_url) {
            return redirect($invoice->pdf_url);
        }
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.invoices-list');
    }
}