@extends("layouts.master")

@section("content")
    <h2>Ini Halaman Home {{ Auth::user()->name}}</h2>
@endsection