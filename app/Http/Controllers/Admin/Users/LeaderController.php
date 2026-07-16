<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Leaders\StoreLeaderRequest;
use App\Http\Requests\Admin\Leaders\UpdateLeaderRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LeaderController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $leaders = User::query()
            ->where('role', 'Leader')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => User::where('role', 'Leader')->count(),
            'active' => User::where('role', 'Leader')->where('status', 'active')->count(),
            'suspended' => User::where('role', 'Leader')->where('status', 'suspended')->count(),
        ];

        return view('admin.leaders.index', compact('leaders', 'stats', 'search', 'status'));
    }

    public function store(StoreLeaderRequest $request)
    {
        User::create([
            ...$request->validated(),
            'role' => 'Leader',
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()
            ->route('admin.leaders.index')
            ->with('success', 'Leader ajouté avec succès.');
    }

    public function update(UpdateLeaderRequest $request, User $leader)
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $leader->update($data);

        return redirect()
            ->route('admin.leaders.index')
            ->with('success', 'Leader mis à jour avec succès.');
    }

    public function destroy(User $leader)
    {
        $leader->delete();

        return redirect()
            ->route('admin.leaders.index')
            ->with('success', 'Leader supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        User::where('role', 'Leader')->whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.leaders.index')
            ->with('success', count($ids) . ' leader(s) supprimé(s).');
    }
}
