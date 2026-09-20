<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerApplication;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class EngagementController extends Controller
{
    /**
     * Afficher toutes les candidatures :
     *
     * - community  : inscriptions à la communauté
     * - partner    : candidatures partenaires
     * - volunteer  : candidatures bénévoles
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
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
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
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->get()
            ->map(function ($application) {

                $application->type = 'volunteer';

                /*
                 * contribution_areas peut être :
                 *
                 * - une chaîne JSON
                 * - un tableau si le modèle possède déjà un cast
                 */
                if (is_string($application->contribution_areas)) {
                    $decoded = json_decode(
                        $application->contribution_areas,
                        true
                    );

                    $application->contribution_areas = is_array($decoded)
                        ? $decoded
                        : [];
                }

                return $application;
            });


        /*
        |--------------------------------------------------------------------------
        | COMMUNAUTÉ
        |--------------------------------------------------------------------------
        |
        | Les inscriptions à la communauté utilisent la table users.
        |
        | role :
        |     Member
        |
        | status réel :
        |     inactive  => pending
        |     active    => accepted
        |     suspended => rejected
        |
        */

        $communityMembers = User::query()
            ->where('role', 'Member')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {

                /*
                 * Le statut affiché dans le back-office
                 * est différent du statut réel de users.
                 */

                if ($status === 'pending') {

                    $query->where('status', 'inactive');

                } elseif ($status === 'accepted') {

                    $query->where('status', 'active');

                } elseif ($status === 'rejected') {

                    $query->where('status', 'suspended');

                } elseif ($status === 'reviewing') {

                    /*
                     * La table users ne possède pas
                     * de statut reviewing.
                     */
                    $query->whereRaw('1 = 0');
                }
            })
            ->get()
            ->map(function ($user) {

                /*
                 * Type virtuel utilisé par le système
                 * centralisé des candidatures.
                 */
                $user->type = 'community';

                /*
                 * Conserver le statut réel du compte.
                 */
                $user->original_status = $user->status;

                /*
                 * Conversion vers les statuts du back-office.
                 */
                $user->status = match ($user->status) {
                    'inactive' => 'pending',
                    'active' => 'accepted',
                    'suspended' => 'rejected',
                    default => 'pending',
                };

                /*
                 * Localisation pratique pour la vue.
                 */
                $user->city_country = collect([
                    $user->city,
                    $user->country,
                ])
                    ->filter()
                    ->implode(', ');

                /*
                 * Une inscription communauté
                 * n'a pas de profession obligatoire.
                 */
                $user->profession = null;

                return $user;
            });


        /*
        |--------------------------------------------------------------------------
        | FILTRE TYPE
        |--------------------------------------------------------------------------
        */

        if ($type === 'community') {

            $applications = $communityMembers;

        } elseif ($type === 'partner') {

            $applications = $partners;

        } elseif ($type === 'volunteer') {

            $applications = $volunteers;

        } else {

            /*
             * Aucun filtre :
             * toutes les candidatures sont fusionnées.
             */
            $applications = $communityMembers
                ->concat($partners)
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

            /*
             * Total.
             */
            'total' =>
                User::where('role', 'Member')->count()
                + PartnerApplication::count()
                + VolunteerApplication::count(),

            /*
             * Communauté.
             */
            'community' =>
                User::where('role', 'Member')->count(),

            /*
             * Partenaires.
             */
            'partners' =>
                PartnerApplication::count(),

            /*
             * Bénévoles.
             */
            'volunteers' =>
                VolunteerApplication::count(),

            /*
             * En attente.
             */
            'pending' =>
                User::where('role', 'Member')
                    ->where('status', 'inactive')
                    ->count()
                + PartnerApplication::where('status', 'pending')->count()
                + VolunteerApplication::where('status', 'pending')->count(),

            /*
             * En cours d'étude.
             *
             * La communauté ne possède pas de statut reviewing.
             */
            'reviewing' =>
                PartnerApplication::where('status', 'reviewing')->count()
                + VolunteerApplication::where('status', 'reviewing')->count(),

            /*
             * Acceptées.
             */
            'accepted' =>
                User::where('role', 'Member')
                    ->where('status', 'active')
                    ->count()
                + PartnerApplication::where('status', 'accepted')->count()
                + VolunteerApplication::where('status', 'accepted')->count(),

            /*
             * Refusées.
             */
            'rejected' =>
                User::where('role', 'Member')
                    ->where('status', 'suspended')
                    ->count()
                + PartnerApplication::where('status', 'rejected')->count()
                + VolunteerApplication::where('status', 'rejected')->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        |
        | Les trois sources sont fusionnées en mémoire.
        |
        */

        $perPage = 15;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentItems = $applications
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
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


        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.engagements.index',
            compact(
                'applications',
                'stats'
            )
        );
    }


    /**
     * Afficher une candidature.
     */
    public function show(
        string $type,
        int $id
    ) {
        $application = $this->findApplication(
            $type,
            $id
        );

        $application->type = $type;


        /*
        |--------------------------------------------------------------------------
        | COMMUNAUTÉ
        |--------------------------------------------------------------------------
        */

        if ($type === 'community') {

            /*
             * Conserver le statut réel du compte.
             */
            $application->original_status = $application->status;

            /*
             * Convertir vers le statut du back-office.
             */
            $application->status = match ($application->status) {
                'inactive' => 'pending',
                'active' => 'accepted',
                'suspended' => 'rejected',
                default => 'pending',
            };

            /*
             * Construire la localisation.
             */
            $application->city_country = collect([
                $application->city,
                $application->country,
            ])
                ->filter()
                ->implode(', ');

            $application->profession = null;
        }


        /*
        |--------------------------------------------------------------------------
        | BÉNÉVOLE
        |--------------------------------------------------------------------------
        */

        if (
            $type === 'volunteer'
            && is_string($application->contribution_areas)
        ) {
            $decoded = json_decode(
                $application->contribution_areas,
                true
            );

            $application->contribution_areas = is_array($decoded)
                ? $decoded
                : [];
        }


        return view(
            'admin.engagements.show',
            compact('application')
        );
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


        /*
        |--------------------------------------------------------------------------
        | COMMUNAUTÉ
        |--------------------------------------------------------------------------
        */

        if ($type === 'community') {

            $user = User::query()
                ->where('role', 'Member')
                ->findOrFail($id);

            /*
             * Traduction du statut du back-office
             * vers le statut réel de users.
             */
            $newStatus = match ($validated['status']) {

                /*
                 * En attente.
                 */
                'pending' => 'inactive',

                /*
                 * Pas de statut reviewing dans users.
                 *
                 * Le compte reste donc inactif.
                 */
                'reviewing' => 'inactive',

                /*
                 * Validation.
                 */
                'accepted' => 'active',

                /*
                 * Refus / suspension.
                 */
                'rejected' => 'suspended',
            };


            $user->update([
                'status' => $newStatus,
            ]);


            $message = match ($validated['status']) {

                'accepted' =>
                    'L’inscription à la communauté a été validée. Le compte est maintenant actif.',

                'rejected' =>
                    'L’inscription à la communauté a été refusée.',

                'reviewing' =>
                    'L’inscription à la communauté reste en cours d’étude.',

                default =>
                    'L’inscription à la communauté est maintenant en attente de validation.',
            };


            return redirect()
                ->route('admin.engagements.index')
                ->with(
                    'success',
                    $message
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PARTENAIRE / BÉNÉVOLE
        |--------------------------------------------------------------------------
        */

        $application = $this->findApplication(
            $type,
            $id
        );

        $application->update([
            'status' => $validated['status'],
        ]);


        return redirect()
            ->route('admin.engagements.index')
            ->with(
                'success',
                'Le statut de la candidature a été mis à jour.'
            );
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


        /*
        |--------------------------------------------------------------------------
        | COMMUNAUTÉ
        |--------------------------------------------------------------------------
        |
        | La table users ne possède pas actuellement
        | de champ admin_notes.
        |
        */

        if ($type === 'community') {

            return redirect()
                ->route(
                    'admin.engagements.show',
                    [
                        'type' => 'community',
                        'id' => $id,
                    ]
                )
                ->with(
                    'info',
                    'Les notes administratives ne sont pas disponibles pour les inscriptions à la communauté.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PARTENAIRE / BÉNÉVOLE
        |--------------------------------------------------------------------------
        */

        $application = $this->findApplication(
            $type,
            $id
        );

        $application->update([
            'admin_notes' => $validated['admin_notes'],
        ]);


        return redirect()
            ->route('admin.engagements.index')
            ->with(
                'success',
                'Les notes administratives ont été mises à jour.'
            );
    }


    /**
     * Supprimer une candidature.
     */
    public function destroy(
        string $type,
        int $id
    ) {
        /*
        |--------------------------------------------------------------------------
        | COMMUNAUTÉ
        |--------------------------------------------------------------------------
        |
        | La suppression d'une inscription communauté
        | supprime le compte Member correspondant.
        |
        */

        if ($type === 'community') {

            $user = User::query()
                ->where('role', 'Member')
                ->findOrFail($id);

            $user->delete();

            return redirect()
                ->route('admin.engagements.index')
                ->with(
                    'success',
                    'L’inscription à la communauté et le compte associé ont été supprimés.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PARTENAIRE / BÉNÉVOLE
        |--------------------------------------------------------------------------
        */

        $application = $this->findApplication(
            $type,
            $id
        );

        $application->delete();


        return redirect()
            ->route('admin.engagements.index')
            ->with(
                'success',
                'La candidature a été supprimée.'
            );
    }


    /**
     * Prévisualiser le document.
     */
    public function previewDocument(
        string $type,
        int $id
    ) {
        /*
        |--------------------------------------------------------------------------
        | COMMUNAUTÉ
        |--------------------------------------------------------------------------
        |
        | Les inscriptions communauté n'ont actuellement
        | aucun document associé.
        |
        */

        if ($type === 'community') {

            abort(
                404,
                'Aucun document associé à cette inscription à la communauté.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PARTENAIRE / BÉNÉVOLE
        |--------------------------------------------------------------------------
        */

        $application = $this->findApplication(
            $type,
            $id
        );


        if (
            !isset($application->document_path)
            || !$application->document_path
        ) {
            abort(
                404,
                'Aucun document associé à cette candidature.'
            );
        }


        $disk = Storage::disk('public');


        if (!$disk->exists($application->document_path)) {

            abort(
                404,
                'Document introuvable.'
            );
        }


        return response()->file(
            $disk->path(
                $application->document_path
            )
        );
    }


    /**
     * Télécharger le document.
     */
    public function downloadDocument(
        string $type,
        int $id
    ) {
        /*
        |--------------------------------------------------------------------------
        | COMMUNAUTÉ
        |--------------------------------------------------------------------------
        */

        if ($type === 'community') {

            abort(
                404,
                'Aucun document associé à cette inscription à la communauté.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PARTENAIRE / BÉNÉVOLE
        |--------------------------------------------------------------------------
        */

        $application = $this->findApplication(
            $type,
            $id
        );


        if (
            !isset($application->document_path)
            || !$application->document_path
        ) {
            abort(
                404,
                'Aucun document associé à cette candidature.'
            );
        }


        $disk = Storage::disk('public');


        if (!$disk->exists($application->document_path)) {

            abort(
                404,
                'Document introuvable.'
            );
        }


        $path = $disk->path(
            $application->document_path
        );


        return response()->download(
            $path,
            $application->document_name
                ?? basename($path)
        );
    }


    /**
     * Récupérer une candidature selon son type.
     *
     * Types autorisés :
     *
     * - community
     * - partner
     * - volunteer
     */
    private function findApplication(
        string $type,
        int $id
    ) {
        return match ($type) {

            /*
             * Inscription communauté.
             */
            'community' =>
                User::query()
                    ->where('role', 'Member')
                    ->findOrFail($id),

            /*
             * Partenaire.
             */
            'partner' =>
                PartnerApplication::findOrFail($id),

            /*
             * Bénévole.
             */
            'volunteer' =>
                VolunteerApplication::findOrFail($id),

            /*
             * Type inconnu.
             */
            default =>
                abort(
                    404,
                    'Type de candidature invalide.'
                ),
        };
    }
}
