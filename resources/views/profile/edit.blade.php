<x-app-layout>
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 sm:p-8">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 sm:p-8">
        @include('profile.partials.update-password-form')
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 sm:p-8">
        @include('profile.partials.delete-user-form')
    </div>
</x-app-layout>
