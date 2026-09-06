<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerApplication;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class EngagementController extends Controller
{
    /**
     * Afficher toutes les candidatures partenaires + bénévoles.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $type = $request->input('type');
        $status = $request->input('status');

        /*
        |--------------------------------------------------------------------------
        | PARTENAIRES
        |--------------------------------------------------------------------------
        */

        $partners = PartnerApplication::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('organization', 'like', "%{$search}%")
                        ->orWhere('contact_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('sector', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->get()
            ->map(function ($application) {
                $application->type = 'partner';

                return $application;
            });


        /*
        |--------------------------------------------------------------------------
        | BÉNÉVOLES
        |--------------------------------------------------------------------------
        */

        $volunteers = VolunteerApplication::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('city_country', 'like', "%{$search}%")
                        ->orWhere('profession', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->get()
            ->map(function ($application) {
                $application->type = 'volunteer';

                /*
                 * contribution_areas peut être stocké sous forme de JSON
                 * ou déjà être casté en tableau dans le modèle.
                 */
                if (is_string($application->contribution_areas)) {
                    $decoded = json_decode($application->contribution_areas, true);

                    $application->contribution_areas = is_array($decoded)
                        ? $decoded
                        : [];
                }

                return $application;
            });


        /*
        |--------------------------------------------------------------------------
        | FILTRE TYPE
        |--------------------------------------------------------------------------
        */

        if ($type === 'partner') {
            $applications = $partners;
        } elseif ($type === 'volunteer') {
            $applications = $volunteers;
        } else {
            $applications = $partners
                ->concat($volunteers);
        }


        /*
        |--------------------------------------------------------------------------
        | TRI
        |--------------------------------------------------------------------------
        */

        $applications = $applications
            ->sortByDesc(function ($application) {
                return $application->created_at;
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total' => PartnerApplication::count() + VolunteerApplication::count(),

            'partners' => PartnerApplication::count(),

            'volunteers' => VolunteerApplication::count(),

            'pending' =>
                PartnerApplication::where('status', 'pending')->count()
                + VolunteerApplication::where('status', 'pending')->count(),

            'reviewing' =>
                PartnerApplication::where('status', 'reviewing')->count()
                + VolunteerApplication::where('status', 'reviewing')->count(),

            'accepted' =>
                PartnerApplication::where('status', 'accepted')->count()
                + VolunteerApplication::where('status', 'accepted')->count(),

            'rejected' =>
                PartnerApplication::where('status', 'rejected')->count()
                + VolunteerApplication::where('status', 'rejected')->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        |
        | On fusionne les deux tables en mémoire puis on recrée un paginator.
        | Cela évite d'utiliser hasPages() sur une simple Collection.
        |
        */

        $perPage = 15;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentItems = $applications
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $applications = new LengthAwarePaginator(
            $currentItems,
            $applications->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );


        return view('admin.engagements.index', compact(
            'applications',
            'stats'
        ));
    }


    /**
     * Afficher une candidature.
     */
    public function show(string $type, int $id)
    {
        $application = $this->findApplication($type, $id);

        $application->type = $type;

        if (
            $type === 'volunteer'
            && is_string($application->contribution_areas)
        ) {
            $decoded = json_decode($application->contribution_areas, true);

            $application->contribution_areas = is_array($decoded)
                ? $decoded
                : [];
        }

        return view('admin.engagements.show', compact(
            'application'
        ));
    }


    /**
     * Modifier le statut d'une candidature.
     */
    public function updateStatus(
        Request $request,
        string $type,
        int $id
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,reviewing,accepted,rejected',
            ],
        ]);

        $application = $this->findApplication($type, $id);

        $application->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.engagements.index')
            ->with('success', 'Le statut de la candidature a été mis à jour.');
    }


    /**
     * Modifier les notes administratives.
     */
    public function updateNotes(
        Request $request,
        string $type,
        int $id
    ) {
        $validated = $request->validate([
            'admin_notes' => [
                'nullable',
                'string',
            ],
        ]);

        $application = $this->findApplication($type, $id);

        $application->update([
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()
            ->route('admin.engagements.index')
            ->with('success', 'Les notes administratives ont été mises à jour.');
    }


    /**
     * Supprimer une candidature.
     */
    public function destroy(string $type, int $id)
    {
        $application = $this->findApplication($type, $id);

        $application->delete();

        return redirect()
            ->route('admin.engagements.index')
            ->with('success', 'La candidature a été supprimée.');
    }


    /**
     * Prévisualiser le document.
     *
     * Pour le moment, aucune des deux tables fournies ne possède
     * de champ document_path.
     */
    public function previewDocument(string $type, int $id)
    {
        $application = $this->findApplication($type, $id);

        if (!isset($application->document_path) || !$application->document_path) {
            abort(404, 'Aucun document associé à cette candidature.');
        }

        if (!Storage::disk('public')->exists($application->document_path)) {
            abort(404, 'Document introuvable.');
        }

        return response()->file(
            Storage::disk('public')->path($application->document_path)
        );
    }


    /**
     * Télécharger le document.
     *
     * Pour le moment, aucune des deux tables fournies ne possède
     * de champ document_path.
     */
    public function downloadDocument(string $type, int $id)
    {
        $application = $this->findApplication($type, $id);

        if (!isset($application->document_path) || !$application->document_path) {
            abort(404, 'Aucun document associé à cette candidature.');
        }

        if (!Storage::disk('public')->exists($application->document_path)) {
            abort(404, 'Document introuvable.');
        }

        return Storage::disk('public')->download(
            $application->document_path
        );
    }


    /**
     * Récupérer une candidature selon son type.
     */
    private function findApplication(
        string $type,
        int $id
    ) {
        return match ($type) {

            'partner' => PartnerApplication::findOrFail($id),

            'volunteer' => VolunteerApplication::findOrFail($id),

            default => abort(404, 'Type de candidature invalide.'),

        };
    }
}
