@extends("Layouts.master")

@section("content")
    <h2>Ini Halaman Berita {{ Auth::user()->name}}</h2>
@endsection