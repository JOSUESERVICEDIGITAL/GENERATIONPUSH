<?php

namespace App\Http\Controllers\Admin\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sponsors\StoreSponsorRequest;
use App\Http\Requests\Admin\Sponsors\UpdateSponsorRequest;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $tier = $request->query('tier');
        $status = $request->query('status');

        $sponsors = Sponsor::query()
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($tier, fn ($query) => $query->where('tier', $tier))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Sponsor::count(),
            'active' => Sponsor::where('status', 'active')->count(),
            'platinum' => Sponsor::where('tier', 'platinum')->count(),
            'gold' => Sponsor::where('tier', 'gold')->count(),
        ];

        return view('admin.partners.sponsors.index', compact('sponsors', 'stats', 'search', 'tier', 'status'));
    }

    public function store(StoreSponsorRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('sponsors', 'public');
        }

        Sponsor::create($data);

        return redirect()
            ->route('admin.partners.sponsors.index')
            ->with('success', 'Sponsor ajouté avec succès.');
    }

    public function update(UpdateSponsorRequest $request, Sponsor $sponsor)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($sponsor->logo_path) {
                Storage::disk('public')->delete($sponsor->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor->update($data);

        return redirect()
            ->route('admin.partners.sponsors.index')
            ->with('success', 'Sponsor mis à jour avec succès.');
    }

    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->logo_path) {
            Storage::disk('public')->delete($sponsor->logo_path);
        }

        $sponsor->delete();

        return redirect()
            ->route('admin.partners.sponsors.index')
            ->with('success', 'Sponsor supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $sponsors = Sponsor::whereIn('id', $ids)->get();

        foreach ($sponsors as $sponsor) {
            if ($sponsor->logo_path) {
                Storage::disk('public')->delete($sponsor->logo_path);
            }
        }

        Sponsor::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.partners.sponsors.index')
            ->with('success', count($ids) . ' sponsor(s) supprimé(s).');
    }
}
