@extends('layouts.guest')
@section('title', 'Novedades')

@section('content')
    <div class="max-w-[70%] mx-auto">
        <div>
            <div class="text-xs mt-10">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block mt-8">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('novedades') }}" class=" hover:underline">Novedades</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="" class="text-gray-500 hover:underline">{{ $novedad->titulo }}</a>
                    <span class="mx-[5px]"></span>
                </div>
            </div>
        </div>
        <div class="flex lg:flex-row flex-col justify-evenly items-start py-20 gap-10 ">
            <div class="lg:w-1/2">
                <img src="{{ asset('storage/' . $novedad->path) }}" alt="{{ $novedad->titulo }}"
                    class="w-full lg:h-[600px] object-cover max-w-none">
            </div>
            <div class="lg:w-1/2 text-black flex flex-col gap-6">
                <p class="text-orange-700 font-extrabold">{{ Str::upper($novedad->epigrafe) }}</p>
                <h2 class="text-3xl font-semibold mb-10">{{ $novedad->titulo }}</h2>
                <div>
                    {!! $novedad->descripcion !!}
                </div>
            </div>
        </div>
    </div>
@endsection
