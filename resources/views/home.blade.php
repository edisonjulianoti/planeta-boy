@extends('layouts.app')

@section('title', 'PLANETA BOYS - Premium Directory')

@section('content')

    <x-home.banner-home />

    <x-home.filtro-categorias :cidades="$cidades" />

    <x-home.plataforma />

    <x-home.como-funciona />

@endsection
