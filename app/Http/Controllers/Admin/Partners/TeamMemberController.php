<?php

namespace App\Http\Controllers\Admin\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMembers\StoreTeamMemberRequest;
use App\Http\Requests\Admin\TeamMembers\UpdateTeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $members = TeamMember::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => TeamMember::count(),
            'active' => TeamMember::where('status', 'active')->count(),
        ];

        return view('admin.partners.team.index', compact('members', 'stats', 'search', 'status'));
    }

    public function store(StoreTeamMemberRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('team', 'public');
        }

        TeamMember::create($data);

        return redirect()
            ->route('admin.partners.team.index')
            ->with('success', 'Membre ajouté avec succès.');
    }

    public function update(UpdateTeamMemberRequest $request, TeamMember $member)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('team', 'public');
        }

        $member->update($data);

        return redirect()
            ->route('admin.partners.team.index')
            ->with('success', 'Membre mis à jour avec succès.');
    }

    public function destroy(TeamMember $member)
    {
        if ($member->photo_path) {
            Storage::disk('public')->delete($member->photo_path);
        }

        $member->delete();

        return redirect()
            ->route('admin.partners.team.index')
            ->with('success', 'Membre supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $members = TeamMember::whereIn('id', $ids)->get();

        foreach ($members as $member) {
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }
        }

        TeamMember::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.partners.team.index')
            ->with('success', count($ids) . ' membre(s) supprimé(s).');
    }
}
