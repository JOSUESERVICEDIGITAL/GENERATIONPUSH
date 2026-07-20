<x-layouts.public :title="($page->meta_title ?? $page->title) . ' — Generation PUSH'" :meta-description="$page->meta_description">

    <x-front.page-banner :title="$page->title" />

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-gray max-w-none" x-data x-reveal>
                {!! nl2br(e($page->content)) !!}
            </div>
        </div>
    </section>

</x-layouts.public>
