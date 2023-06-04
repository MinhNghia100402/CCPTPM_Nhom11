@extends('admin.master')
@section('content')
    @include('admin.layouts.header')
    @include('admin.layouts.sidebar')
    @yield('admin_content')
    @include('admin.layouts.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="{{ asset('vendor/calendar/index.global.min.js') }}"></script>
    <script src="{{ asset('js/calendar.js') }}"></script>>
@endsection