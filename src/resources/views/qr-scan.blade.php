@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/owner-page.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/owner-page.css') }}">
@endif
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')
<livewire:qr />
@stack('scripts')
@endsection