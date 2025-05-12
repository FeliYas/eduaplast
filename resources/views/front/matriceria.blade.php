@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection

@section('title', 'Matriceria')

@section('content')
    <div>
        <div>
            <div class="max-w-[70%] mx-auto text-xs mt-10">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block mt-8">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('matriceria') }}" class="text-gray-500 hover:underline">Matriceria</a>
                    <span class="mx-[5px]"></span>
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-13.5 lg:mb-0 lg:py-20">
            <div
                class="lg:w-1/2 pl-[5%] pr-[5%] lg:pr-[0%] lg:pl-[15%] mt-10 flex flex-col justify-between gap-6 text-black">
                <div>
                    <h2 class="titulo-home mb-6">{{ $matriceria->titulo }}</h2>
                    <div class="custom-summernote lg:text-xl text-gray-150">
                        <p>
                            {!! $matriceria->descripcion !!}
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{route('contacto')}}" class="btn-home-1 flex w-[280px]">Consultar</a>
                </div>
            </div>
            <img src="{{ asset('storage/' . $matriceria->path) }}" alt="Contenido de la pagina"
                class="lg:w-1/2 h-[500px] lg:h-[600px] object-cover">
        </div>
    </div>
@endsection
