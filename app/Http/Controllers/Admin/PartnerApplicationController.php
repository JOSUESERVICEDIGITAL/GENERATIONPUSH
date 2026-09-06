<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnerApplicationController extends Controller
{
    public function index(): View
    {
        $applications = PartnerApplication::latest()->paginate(15);

        return view(
            'admin.partner-applications.index',
            compact('applications')
        );
    }

    public function show(
        PartnerApplication $partnerApplication
    ): View {
        return view(
            'admin.partner-applications.show',
            compact('partnerApplication')
        );
    }

    public function preview(
        PartnerApplication $partnerApplication
    ) {
        abort_unless(
            $partnerApplication->document_path &&
            Storage::disk('public')->exists(
                $partnerApplication->document_path
            ),
            404
        );

        return response()->file(
            Storage::disk('public')->path(
                $partnerApplication->document_path
            )
        );
    }

    public function download(
        PartnerApplication $partnerApplication
    ) {
        abort_unless(
            $partnerApplication->document_path &&
            Storage::disk('public')->exists(
                $partnerApplication->document_path
            ),
            404
        );

        return Storage::disk('public')->download(
            $partnerApplication->document_path,
            $partnerApplication->document_name
                ?? basename($partnerApplication->document_path)
        );
    }

    public function status(
        PartnerApplication $partnerApplication
    ): RedirectResponse {
        request()->validate([
            'status' => [
                'required',
                'in:pending,reviewing,accepted,rejected',
            ],
        ]);

        $partnerApplication->update([
            'status' => request('status'),
        ]);

        return back()->with(
            'success',
            'Statut de la candidature mis à jour.'
        );
    }

    public function notes(
        PartnerApplication $partnerApplication
    ): RedirectResponse {
        request()->validate([
            'admin_notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
        ]);

        $partnerApplication->update([
            'admin_notes' => request('admin_notes'),
        ]);

        return back()->with(
            'success',
            'Notes administratives enregistrées.'
        );
    }

    public function destroy(
        PartnerApplication $partnerApplication
    ): RedirectResponse {

        if (
            $partnerApplication->document_path &&
            Storage::disk('public')->exists(
                $partnerApplication->document_path
            )
        ) {
            Storage::disk('public')->delete(
                $partnerApplication->document_path
            );
        }

        $partnerApplication->delete();

        return redirect()
            ->route('admin.partner-applications.index')
            ->with(
                'success',
                'Candidature supprimée.'
            );
    }
}
