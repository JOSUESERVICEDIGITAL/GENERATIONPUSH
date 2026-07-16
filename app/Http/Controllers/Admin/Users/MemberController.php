<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Members\StoreMemberRequest;
use App\Http\Requests\Admin\Members\UpdateMemberRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $members = User::query()
            ->where('role', 'Member')
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
            'total' => User::where('role', 'Member')->count(),
            'active' => User::where('role', 'Member')->where('status', 'active')->count(),
            'suspended' => User::where('role', 'Member')->where('status', 'suspended')->count(),
        ];

        return view('admin.members.index', compact('members', 'stats', 'search', 'status'));
    }

    public function store(StoreMemberRequest $request)
    {
        User::create([
            ...$request->validated(),
            'role' => 'Member',
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Membre ajouté avec succès.');
    }

    public function update(UpdateMemberRequest $request, User $member)
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $member->update($data);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Membre mis à jour avec succès.');
    }

    public function destroy(User $member)
    {
        $member->delete();

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Membre supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        User::where('role', 'Member')->whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.members.index')
            ->with('success', count($ids) . ' membre(s) supprimé(s).');
    }
}
