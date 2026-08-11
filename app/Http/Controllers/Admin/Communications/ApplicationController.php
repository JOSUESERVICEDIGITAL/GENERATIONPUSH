<?php

namespace App\Http\Controllers\Admin\Communications;

use App\Http\Controllers\Controller;
use App\Models\EngagementApplication;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $type = $request->query('type');
        $status = $request->query('status');

        $applications = EngagementApplication::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('organization', 'like', "%{$search}%");
            })
            ->when($type, fn ($query) => $query->where('type', $type))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => EngagementApplication::count(),
            'partners' => EngagementApplication::where('type', 'partner')->count(),
            'volunteers' => EngagementApplication::where('type', 'volunteer')->count(),
            'new' => EngagementApplication::where('status', 'new')->count(),
        ];

        return view('admin.communications.applications.index', compact('applications', 'stats', 'search', 'type', 'status'));
    }

    public function updateStatus(Request $request, EngagementApplication $application)
    {
        $request->validate(['status' => ['required', 'in:new,reviewed,accepted,rejected']]);

        $application->update(['status' => $request->input('status')]);

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(EngagementApplication $application)
    {
        $application->delete();

        return redirect()->back()->with('success', 'Candidature supprimée.');
    }
}
