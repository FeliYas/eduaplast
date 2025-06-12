@extends('layouts.guest')
@section('title', 'Productos')

@section('content')
    <div class="max-w-[90%] lg:max-w-[1224px] mx-auto">
        <div class="py-7">
            <div class="text-xs">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('presupuesto') }}" class="hover:underline">Presupuesto</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('productos', ['id' => $categoria->id]) }}"
                        class="text-gray-500 hover:underline transition-all duration-300">{{ $categoria->titulo }}</a>
                </div>
            </div>
        </div>
        <!-- Main content with sidebar and products -->
        <div class="flex flex-col lg:flex-row gap-6 lg:py-10 lg:mb-27">
            <div class="w-full lg:w-1/4">
                <h2 class="text-orange-700 font-bold text-xl py-4">Productos</h2>
                <div class="border-t border-gray-200 text-black">
                    @foreach ($categorias as $cat)
                        <a href="{{ route('productos', ['id' => $cat->id]) }}"
                            class="block py-3 px-2 border-b border-gray-200 hover:bg-gray-100 hover:pl-3 transition-all duration-300 ease-in-out
                            {{ $cat->id == $categoria->id ? 'font-bold bg-gray-50' : '' }}">
                            {{ $cat->titulo }}
                            @if ($cat->productos_count)
                                <span
                                    class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full transition-opacity duration-300">
                                    {{ $cat->productos_count }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="w-full lg::w-3/4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-4 lg:py-0">
                    @forelse($productos as $producto)
                        <div
                            class="border border-gray-200 overflow-hidden transition-transform transform hover:-translate-y-1 hover:shadow-lg duration-300 h-[396px]">
                            <a
                                href="{{ route('producto', ['id' => $producto->categoria->id, 'producto' => $producto->id]) }}">

                                @if ($producto->imagenes->count() > 0)
                                    <img src="{{ asset($producto->imagenes->first()->path) }}"
                                        alt="{{ $producto->titulo }}"
                                        class="bg-gray-100 w-full h-72 object-cover transition-transform duration-500 hover:scale-105">
                                @else
                                    <div
                                        class="w-full h-72 bg-gray-100 flex items-center justify-center text-gray-500 transition-colors duration-300 hover:text-gray-700">
                                        <span>Sin imagen</span>
                                    </div>
                                @endif
                                <div class="py-4 px-6 transition-colors duration-300 hover:bg-gray-50">
                                    <h3
                                        class="text-orange-700 font-bold group-hover:text-orange-500 transition-colors duration-300">
                                        {{ $producto->categoria->titulo }}</h3>
                                    <p class="text-gray-800 mt-2 transition-colors duration-300 line-clamp-2">
                                        {{ $producto->titulo }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-3 py-8 text-center text-gray-500">
                            No hay productos disponibles en esta categoría.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
