<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VolunteerApplicationController extends Controller
{
    public function index(): View
    {
        $applications = VolunteerApplication::latest()->paginate(15);

        return view(
            'admin.volunteer-applications.index',
            compact('applications')
        );
    }

    public function show(
        VolunteerApplication $volunteerApplication
    ): View {
        return view(
            'admin.volunteer-applications.show',
            compact('volunteerApplication')
        );
    }

    public function preview(
        VolunteerApplication $volunteerApplication
    ) {
        abort_unless(
            $volunteerApplication->document_path &&
            Storage::disk('public')->exists(
                $volunteerApplication->document_path
            ),
            404
        );

        return response()->file(
            Storage::disk('public')->path(
                $volunteerApplication->document_path
            )
        );
    }

    public function download(
        VolunteerApplication $volunteerApplication
    ) {
        abort_unless(
            $volunteerApplication->document_path &&
            Storage::disk('public')->exists(
                $volunteerApplication->document_path
            ),
            404
        );

        return Storage::disk('public')->download(
            $volunteerApplication->document_path,
            $volunteerApplication->document_name
                ?? basename($volunteerApplication->document_path)
        );
    }

    public function status(
        VolunteerApplication $volunteerApplication
    ): RedirectResponse {
        request()->validate([
            'status' => [
                'required',
                'in:pending,reviewing,accepted,rejected',
            ],
        ]);

        $volunteerApplication->update([
            'status' => request('status'),
        ]);

        return back()->with(
            'success',
            'Statut de la candidature mis à jour.'
        );
    }

    public function notes(
        VolunteerApplication $volunteerApplication
    ): RedirectResponse {
        request()->validate([
            'admin_notes' => [
                'nullable',
                'string',
                'max:10000',
            ],
        ]);

        $volunteerApplication->update([
            'admin_notes' => request('admin_notes'),
        ]);

        return back()->with(
            'success',
            'Notes administratives enregistrées.'
        );
    }

    public function destroy(
        VolunteerApplication $volunteerApplication
    ): RedirectResponse {

        if (
            $volunteerApplication->document_path &&
            Storage::disk('public')->exists(
                $volunteerApplication->document_path
            )
        ) {
            Storage::disk('public')->delete(
                $volunteerApplication->document_path
            );
        }

        $volunteerApplication->delete();

        return redirect()
            ->route('admin.volunteer-applications.index')
            ->with(
                'success',
                'Candidature supprimée.'
            );
    }
}
