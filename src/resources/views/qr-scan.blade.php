@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner-page.css') }}">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')
<livewire:qr />
@stack('scripts')
@endsection