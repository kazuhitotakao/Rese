@extends('layouts.app')

@section('content')

<div style="margin: 10rem 1rem; text-align: center; background-color: #f2f2f2">
    {!! QrCode::size(300)->generate($reservation_id); !!}
</div>

@endsection