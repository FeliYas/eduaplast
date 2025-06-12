@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Clientes')

@section('content')
    <div class="max-w-[90%] lg:max-w-[1224px] mx-auto min-h-[500px] mb-10 lg:mb-0">
        <div class="py-7">
            <div class="text-xs">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('clientes') }}" class="text-gray-500 hover:underline">Clientes</a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-6 gap-6 lg:py-8 mb-0 lg:mb-15">
            @foreach ($clientes as $cliente)
                <div class="h-[190px] max-w-[300px] bg-white p-4 rounded-xl shadow-md border border-gray-200">
                    <img src="{{ asset($cliente->path) }}" alt="cliente"
                        class="w-full h-full object-contain transition-all duration-300 filter grayscale hover:grayscale-0">
                </div>
            @endforeach
        </div>


    </div>
@endsection
