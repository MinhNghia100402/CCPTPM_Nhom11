@extends('client.master')
@section('content')
    @include('client.layouts.header')
    @yield('client_content')
    @include('client.layouts.footer')
    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
@endsection
