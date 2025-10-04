<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
 <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>iband dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        referrerpolicy="no-referrer" />



    <link href="{{ asset('backend/assets/dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/dist/css/demo.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/dist/css/toastr.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/dist/css/datatable.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/dist/css/custom.css') }}" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body>
    <script src="{{ asset('backend/assets/dist/js/demo-theme.min.js') }}"></script>
    <div class="page">
        <!-- Navbar -->
        @include('admin.layouts.header')
        @include('admin.layouts.navbar')
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
            @include('admin.layouts.footer')
        </div>
    </div>

    <!-- Libs JS -->
    {{-- <script src="{{ asset('backend/assets/dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/libs/jsvectormap/dist/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/libs/jsvectormap/dist/maps/world.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/libs/jsvectormap/dist/maps/world-merc.js') }}"></script> --}}
    <!-- Tabler Core -->

    <script src="{{ asset('backend/assets/dist/js/jquery.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/js/datatable.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/js/sweet_alert.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/js/tabler.min.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/js/demo.min.js') }}"></script>
    <script src="{{ asset('backend/assets/dist/js/toastr.js') }}"></script>
    <script>
        @if (Session::has('message'))
            let type = "{{ Session::get('alert-type', 'info') }}";
            let message = "{{ Session::get('message') }}";
            switch (type) {
                case 'info':
                    toastr.info(message);
                    break;
                case 'success':
                    toastr.success(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
            }
        @endif
    </script>
    <script>
        window.availableLanguages = @json(array_keys(config('languages')));
    </script>
    <script src="{{ asset('backend/assets/dist/js/custom.js') }}"></script>
    @stack('scripts')
</body>

</html>
