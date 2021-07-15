<!DOCTYPE html>
<html>

<head>
    @include('site.partials.style')
</head>

<body dir=@if(Lang::locale()=='ar') "rtl" @else "ltr" @endif>
    @include('site.partials.navbar')
    @yield('content')
    @include('site.partials.script')

</body>

</html>
