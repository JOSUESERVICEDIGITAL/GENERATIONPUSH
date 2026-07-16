<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $method = $request->query('method');

        $transactions = Transaction::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($method, fn ($query) => $query->where('method', $method))
            ->latest('date')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Transaction::count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'volume' => (float) Transaction::where('status', 'completed')->where('type', 'payment')->sum('amount'),
            'pending' => (float) Transaction::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.payments.transactions.index', compact('transactions', 'stats', 'search', 'status', 'method'));
    }

    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load('user'));
    }
}
