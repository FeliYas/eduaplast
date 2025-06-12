@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Matriceria')

@section('content')
    <div>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto">
            <div class="py-7">
                <div class="text-xs">
                    <!-- Ruta de navegaci¨®n -->
                    <div class="text-black hidden lg:block">
                        <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                        <span class="mx-[5px]">&gt;</span>
                        <a href="{{ route('matriceria') }}" class="text-gray-500 hover:underline">Matriceria</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-13.5 lg:mb-13 lg:py-8">
            <div
                class="lg:w-1/2 px-[5%] lg:px-0 lg:ml-[calc((100vw-1224px)/2)] lg:mt-10 flex flex-col justify-between gap-6 text-black text-center lg:text-left">
                <div>
                    <h2 class="titulo-home mb-6">{{ $matriceria->titulo }}</h2>
                    <div class="custom-summernote lg:text-xl text-gray-150">
                        <p>
                            {!! $matriceria->descripcion !!}
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <a href="{{ route('contacto') }}" class="btn-home-1 flex w-[280px]">Consultar</a>
                </div>
            </div>
            <img src="{{ asset($matriceria->path) }}" alt="Contenido de la pagina"
                class="lg:w-1/2 h-[500px] lg:h-[600px] object-cover p-4 lg:p-0">
        </div>
    </div>
@endsection
