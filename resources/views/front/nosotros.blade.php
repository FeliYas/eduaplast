@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Nosotros')

@section('content')
    <div>
        <div>
            <div class="max-w-[70%] mx-auto text-xs mt-10">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block mt-8">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('nosotros') }}" class="text-gray-500 hover:underline">Nosotros</a>
                    <span class="mx-[5px]"></span>
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-13.5 mb-5 lg:mb-0 lg:py-20">
            <img src="{{ asset('storage/' . $nosotros->path) }}" alt="Contenido de la pagina"
                class="lg:w-1/2 h-[500px] lg:h-[600px] object-cover">
            <div class="lg:w-1/2 pr-[5%] pl-[5%] lg:pl-[0%] lg:pr-[15%] mt-10 flex flex-col gap-6 text-black">
                <h2 class="titulo-home mb-1.5">{{ $nosotros->titulo }}</h2>
                <div class="custom-summernote lg:text-xl text-gray-150">
                    <p>
                        {!! $nosotros->descripcion !!}
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full bg-gray-50 flex flex-col justify-center items-center ">
            <div class="max-w-[70%] mx-auto py-10">
                <h2 class="titulo-home pb-2 text-black">¿Por que elegirnos?</h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 lg:gap-6 py-4 lg:py-10">
                    <x-tarjeta-nosotros :imagen="$nosotros->imagen1" :titulo="$nosotros->titulo1" :descripcion="$nosotros->descripcion1" />
                    <x-tarjeta-nosotros :imagen="$nosotros->imagen2" :titulo="$nosotros->titulo2" :descripcion="$nosotros->descripcion2" />
                    <x-tarjeta-nosotros :imagen="$nosotros->imagen3" :titulo="$nosotros->titulo3" :descripcion="$nosotros->descripcion3" />
                </div>
            </div>
        </div>
    </div>
@endsection
