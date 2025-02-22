<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      {{ $title ?? env('APP_NAME') }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('top-scripts')

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
</head>

<body x-data="{ page: 'ecommerce', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
      x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
      :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
>
    <!-- Preloader -->
    @include('partials.preloader')

    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Content Area -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <!-- Header -->
            @include('partials.header')

            @yield('content')

            <!-- Footer -->
        </div>
    </div>

    @stack('bottom-scripts')
</body>
</html>