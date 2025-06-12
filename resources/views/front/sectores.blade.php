@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Sectores')

@section('content')
    <div class="max-w-[90%] lg:max-w-[1224px] mx-auto min-h-[510px]">
        <div class="py-7">
            <div class="text-xs">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('sectores') }}" class="text-gray-500 hover:underline">Sectores</a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 py-4 lg:py-8 ">
            @foreach ($sectores as $sector)
                <div
                    class="rounded-[15px] overflow-hidden shadow-lg relative group transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                    <img src="{{ asset($sector->path) }}" alt="Image" class="object-cover w-full h-[250px]">

                    <div
                        class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-50 transition duration-300 group-hover:opacity-60">
                    </div>

                    <div class="absolute inset-0 flex items-end justify-start p-4">
                        <h2 class="text-white text-xl font-semibold text-start px-2">{{ Str::upper($sector->titulo) }}</h2>
                    </div>
                </div>
            @endforeach
        </div>


    </div>
@endsection
