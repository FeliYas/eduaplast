@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Clientes')

@section('content')
    <div class="max-w-[70%] mx-auto">
        <div>
            <!-- Ruta de navegación -->
            <div class="text-black hidden lg:block mt-8 text-xs">
                <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                <span class="mx-[5px]">&gt;</span>
                <a href="{{ route('clientes') }}" class="text-gray-500 hover:underline">Clientes</a>
                <span class="mx-[5px]"></span>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-6 gap-6 py-20">
            @foreach ($clientes as $cliente)
                <div class="h-[190px] max-w-[300px] bg-white p-4 rounded-xl shadow-md border border-gray-200">
                    <img src="{{ asset('storage/' . $cliente->path) }}" alt="cliente"
                        class="w-full h-full object-contain transition-all duration-300 filter grayscale hover:grayscale-0">
                </div>
            @endforeach
        </div>


    </div>
@endsection
