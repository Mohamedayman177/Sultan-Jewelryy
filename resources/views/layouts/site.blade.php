<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('partials.site-head')
    @stack('styles')
</head>
<body>
    @include('partials.site-preloader-whatsapp')
    @include('partials.site-navbar')
    @include('partials.site-mobile-menu')

    <main>
        @yield('content')
    </main>

    @include('partials.site-footer')
    @include('partials.site-scroll-top')

    @include('partials.site-scripts')
    @stack('scripts')
</body>
</html>
