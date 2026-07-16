<?php

namespace App\Http\Controllers\Admin\Programs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Courses\StoreCourseRequest;
use App\Http\Requests\Admin\Courses\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Formation;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $formationId = $request->query('formation_id');

        $courses = Course::query()
            ->with('formation')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($formationId, fn ($query) => $query->where('formation_id', $formationId))
            ->orderBy('formation_id')
            ->orderBy('order')
            ->paginate(10)
            ->withQueryString();

        $formations = Formation::orderBy('name')->get(['id', 'name']);

        $stats = [
            'total' => Course::count(),
            'published' => Course::where('status', 'published')->count(),
            'draft' => Course::where('status', 'draft')->count(),
            'formations' => Formation::has('courses')->count(),
        ];

        return view('admin.programs.courses.index', compact('courses', 'formations', 'stats', 'search', 'status', 'formationId'));
    }

    public function store(StoreCourseRequest $request)
    {
        Course::create($request->validated());

        return redirect()
            ->route('admin.programs.courses.index')
            ->with('success', 'Cours créé avec succès.');
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return redirect()
            ->route('admin.programs.courses.index')
            ->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()
            ->route('admin.programs.courses.index')
            ->with('success', 'Cours supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Course::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.programs.courses.index')
            ->with('success', count($ids) . ' cours supprimé(s).');
    }
}
