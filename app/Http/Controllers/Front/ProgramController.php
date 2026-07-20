<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $formations = Formation::query()
            ->where('status', '!=', 'draft')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('status')
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('front.programs', compact('formations', 'search'));
    }
}
