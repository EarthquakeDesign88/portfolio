<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ACS Parking</title>

    @include('partials.styles')
</head>

<body>
    @include('partials.sidebar')
        <div class="all-content-wrapper">
            @include('partials.logo-responsive')
                @yield('content')

            @include('partials.footer')
        </div>
    @include('partials.scripts')
</body>

</html>