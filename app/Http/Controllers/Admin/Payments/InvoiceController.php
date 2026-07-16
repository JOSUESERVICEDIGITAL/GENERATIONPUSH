<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Invoices\StoreInvoiceRequest;
use App\Http\Requests\Admin\Invoices\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $invoices = Invoice::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('issued_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Invoice::count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'pending_amount' => (float) Invoice::where('status', 'pending')->sum('amount'),
            'overdue' => Invoice::where('status', 'overdue')->count(),
        ];

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.payments.invoices.index', compact('invoices', 'stats', 'search', 'status', 'users'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        Invoice::create([
            ...$request->validated(),
            'invoice_number' => Invoice::nextInvoiceNumber(),
        ]);

        return redirect()
            ->route('admin.payments.invoices.index')
            ->with('success', 'Facture créée avec succès.');
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());

        return redirect()
            ->route('admin.payments.invoices.index')
            ->with('success', 'Facture mise à jour avec succès.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('admin.payments.invoices.index')
            ->with('success', 'Facture supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Invoice::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.payments.invoices.index')
            ->with('success', count($ids) . ' facture(s) supprimée(s).');
    }
}
