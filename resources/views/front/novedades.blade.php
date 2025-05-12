@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Novedades')

@section('content')
    <div>
        <div>
            <div class="max-w-[70%] mx-auto text-xs mt-10">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block mt-8">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('novedades') }}" class="text-gray-500 hover:underline">Novedades</a>
                    <span class="mx-[5px]"></span>
                </div>
            </div>
        </div>
        <div class="py-20 max-w-[70%] mx-auto mb-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 w-full gap-6">
                @foreach ($novedades as $novedad)
                    <x-tarjeta-novedades :novedad="$novedad" />
                @endforeach
            </div>
        </div>
    </div>
@endsection
