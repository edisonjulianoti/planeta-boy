@extends('layouts.app')

@section('title', 'PLANETA BOYS - Premium Directory')

@section('content')

@component('components.home.banner-home')
@endcomponent

@component('components.home.filtro-categorias', ['cidades' => $cidades])
@endcomponent

@component('components.home.plataforma')
@endcomponent

@component('components.home.como-funciona')
@endcomponent

@endsection
