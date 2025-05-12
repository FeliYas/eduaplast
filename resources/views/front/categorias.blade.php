@extends('layouts.guest')
@section('title', 'Productos')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection
@section('content')
    <div class="max-w-[70%] mx-auto ">
        <div>
            <div class="text-xs mt-10">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block mt-8">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('contacto') }}" class="text-gray-500 hover:underline">Contacto</a>
                    <span class="mx-[5px]"></span>
                </div>
            </div>
        </div>
        <div class="py-20 mb-13">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($categorias as $categoria)
                    <div
                        class="w-full h-[288px] group shadow-md hover:shadow-xl transition-shadow duration-300 rounded-xl overflow-hidden border border-gray-200">
                        <a href="{{ route('productos', ['id' => $categoria->id]) }}" class="block w-full h-full">

                            <img src="{{ asset('storage/' . $categoria->path) }}" alt="{{ $categoria->titulo }}"
                                class="object-cover w-full h-[230px] transform group-hover:scale-105 transition-transform duration-500">
                            <p
                                class="text-black text-xl lg:text-[22px] text-center py-3 opacity-90 group-hover:opacity-100 transition-opacity duration-300">
                                {{ strtoupper($categoria->titulo) }}
                            </p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
