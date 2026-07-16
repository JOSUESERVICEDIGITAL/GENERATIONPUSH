@if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                window.notify('success', @js(session('success')));
            @endif

            @if (session('error'))
                window.notify('error', @js(session('error')));
            @endif

            @if (session('warning'))
                window.notify('warning', @js(session('warning')));
            @endif

            @if (session('info'))
                window.notify('info', @js(session('info')));
            @endif

            @if ($errors->any())
                window.notify('error', @js($errors->first()), 'Erreur de validation');
            @endif
        });
    </script>
@endif
