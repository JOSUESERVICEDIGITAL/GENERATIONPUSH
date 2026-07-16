<?php

namespace App\Http\Controllers\Admin\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Testimonials\StoreTestimonialRequest;
use App\Http\Requests\Admin\Testimonials\UpdateTestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $testimonials = Testimonial::query()
            ->when($search !== '', fn ($query) => $query->where('author_name', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Testimonial::count(),
            'published' => Testimonial::where('status', 'published')->count(),
            'featured' => Testimonial::where('featured', true)->count(),
            'avg_rating' => round(Testimonial::avg('rating') ?? 0, 1),
        ];

        return view('admin.partners.testimonials.index', compact('testimonials', 'stats', 'search', 'status'));
    }

    public function store(StoreTestimonialRequest $request)
    {
        $data = $request->validated();
        $data['featured'] = $request->boolean('featured');

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return redirect()
            ->route('admin.partners.testimonials.index')
            ->with('success', 'Témoignage ajouté avec succès.');
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $data = $request->validated();
        $data['featured'] = $request->boolean('featured');

        if ($request->hasFile('photo')) {
            if ($testimonial->photo_path) {
                Storage::disk('public')->delete($testimonial->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        return redirect()
            ->route('admin.partners.testimonials.index')
            ->with('success', 'Témoignage mis à jour avec succès.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->photo_path) {
            Storage::disk('public')->delete($testimonial->photo_path);
        }

        $testimonial->delete();

        return redirect()
            ->route('admin.partners.testimonials.index')
            ->with('success', 'Témoignage supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $testimonials = Testimonial::whereIn('id', $ids)->get();

        foreach ($testimonials as $testimonial) {
            if ($testimonial->photo_path) {
                Storage::disk('public')->delete($testimonial->photo_path);
            }
        }

        Testimonial::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.partners.testimonials.index')
            ->with('success', count($ids) . ' témoignage(s) supprimé(s).');
    }
}
