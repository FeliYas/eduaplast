@extends('layouts.guest')
@section('title', 'Novedades')

@section('content')
    <div class="max-w-[90%] lg:max-w-[1224px] mx-auto">
        <div class="py-7">
            <div class="text-xs">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('novedades') }}" class= "hover:underline">Novedades</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="" class="text-gray-500 hover:underline">{{ $novedad->titulo }}</a>
                    <span class="mx-[5px]"></span>
                </div>
            </div>
        </div>
        <div class="flex lg:flex-row flex-col justify-evenly items-start lg:py-20 gap-10 ">
            <div class="lg:w-1/2">
                <img src="{{ asset($novedad->path) }}" alt="{{ $novedad->titulo }}"
                    class="w-full object-cover max-w-none border border-gray-300">
            </div>
            <div class="lg:w-1/2 text-black flex flex-col gap-6">
                <p class="text-orange-700 font-extrabold">{{ Str::upper($novedad->epigrafe) }}</p>
                <h2 class="text-3xl font-semibold lg:mb-10">{{ $novedad->titulo }}</h2>
                <div class="py-10 lg:py-0">
                    {!! $novedad->descripcion !!}
                </div>
            </div>
        </div>
    </div>
@endsection
