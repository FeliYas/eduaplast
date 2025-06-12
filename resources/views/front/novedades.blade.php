@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Novedades')

@section('content')
    <div class="max-w-[90%] lg:max-w-[1224px] mx-auto">
        <div class="py-7">
            <div class="text-xs">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('novedades') }}" class="text-gray-500 hover:underline">Novedades</a>
                </div>
            </div>
        </div>
        <div class="lg:py-8 mb-10 lg:mb-15">
            <div class="grid grid-cols-1 lg:grid-cols-3 w-full gap-6">
                @foreach ($novedades as $novedad)
                    <x-tarjeta-novedades :novedad="$novedad" />
                @endforeach
            </div>
        </div>
    </div>
@endsection
