@extends('layouts.guest')
@section('title', ' Producto')

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
                        class="hover:underline transition-all duration-300">{{ $categoria->titulo }}</a>
                    <span class="mx-[5px]">&gt;</span>
                    <span class="text-gray-500">{{ $producto->titulo }}</span>
                </div>
            </div>
        </div>
        <!-- Main content with sidebar and product detail -->
        <div class="flex flex-col lg:flex-row gap-6 lg:py-10 lg:mb-27">
            <!-- Sidebar (1/4 width) -->
            <div class="w-full md:w-1/4 block">
                <div class="border-t border-gray-200 text-black">
                    @foreach ($categorias as $cat)
                        <a href="{{ route('productos', ['id' => $cat->id]) }}"
                            class="block py-3 px-2 border-b border-gray-200 hover:bg-gray-100 transition 
                                  {{ $cat->id == $producto->categoria_id ? 'font-bold bg-gray-50' : '' }}">
                            {{ $cat->titulo }}
                            @if ($cat->productos_count)
                                <span class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded-full">
                                    {{ $cat->productos_count }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Product Detail (3/4 width) -->
            <div class="w-full md:w-3/4">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Image Gallery -->
                    <div class="w-full md:w-1/2">
                        <!-- Main Image -->
                        <div class="mb-4 flex items-center justify-center">
                            @if ($producto->imagenes->first())
                                <img id="mainImage" src="{{ asset($producto->imagenes->first()->path) }}"
                                    alt="{{ $producto->titulo }}"
                                    class="w-full object-contain transition-opacity duration-300 ease-in-out">
                            @else
                                <div
                                    class="w-full h-[400px] bg-gray-100 text-gray-400 flex items-center justify-center transition-opacity duration-300 ease-in-out">
                                    <span class="text-sm">Sin imagen disponible</span>
                                </div>
                            @endif
                        </div>


                        <!-- Thumbnails -->
                        <div class="flex lg:justify-start justify-center gap-2 overflow-x-auto">
                            @foreach ($producto->imagenes as $imagen)
                                <div class="border border-gray-200 w-24 h-24 cursor-pointer hover:border-main-color flex-shrink-0
                                          {{ $loop->first ? 'border-second-color' : '' }}"
                                    onclick="changeMainImage('{{ asset($imagen->path) }}', this)">
                                    <img src="{{ asset($imagen->path) }}" alt="Thumbnail"
                                        class="w-full h-full object-contain">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="w-full md:w-1/2 text-black lg:h-[487px] flex flex-col lg:justify-between">
                        <div>
                            <h1 class="font-bold text-orange-700">{{ $producto->categoria->titulo }}</h1>
                            <h1 class="text-[28px] font-bold ">{{ $producto->titulo }}</h1>
                            <div class="prose max-w-none custom-summernote mt-4">
                                {!! $producto->descripcion !!}
                            </div>
                        </div>
                        <div class="flex flex-col justify-end mt-10 lg:mt-0 mb-10 lg:mb-20">
                            @if ($producto->ficha)
                                <div class="flex items-center justify-center gap-6">
                                    <a href="{{ asset($producto->ficha) }}" download="{{ basename($producto->ficha) }}"
                                        class="btn-secondary-home">
                                        Ficha técnica
                                    </a>
                                    <a href="{{ route('presupuesto', ['producto' => $producto->id]) }}" class="btn-home-1">
                                        Agregar al presupuesto
                                    </a>
                                </div>
                            @else
                                <div class="flex gap-6">
                                    <a href="{{ route('presupuesto', ['producto' => $producto->id]) }}"
                                        class="btn-home-1 w-full">
                                        Agregar al presupuesto
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Productos relacionados -->
                @if ($productosRelacionados->isNotEmpty())
                    <div class="py-20">
                        <h2 class="titulo-home text-black">Productos relacionados</h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($productosRelacionados as $prodRelacionado)
                                <div
                                    class="border border-gray-200 overflow-hidden transition-transform transform hover:-translate-y-1 hover:shadow-lg duration-300 h-[396px]">
                                    <a
                                        href="{{ route('producto', ['id' => $prodRelacionado->categoria->id, 'producto' => $prodRelacionado->id]) }}">

                                        @if ($prodRelacionado->imagenes->count() > 0)
                                            <img src="{{ asset($prodRelacionado->imagenes->first()->path) }}"
                                                alt="{{ $prodRelacionado->titulo }}"
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
                                                {{ $prodRelacionado->categoria->titulo }}</h3>
                                            <p class="text-gray-800 mt-2 transition-colors duration-300 line-clamp-2">
                                                {{ $prodRelacionado->titulo }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(src, thumbnail) {
            const mainImage = document.getElementById('mainImage');

            // Fade out effect
            mainImage.style.opacity = '0';

            // Change image after fade out completes
            setTimeout(() => {
                mainImage.src = src;

                // Fade in the new image
                mainImage.style.opacity = '1';

                // Update thumbnail borders
                document.querySelectorAll('.flex.gap-2 > div').forEach(thumb => {
                    thumb.classList.remove('border-main-color');
                });
                thumbnail.classList.add('border-main-color');
            }, 300);
        }

        // Ensure image is visible on initial load
        document.addEventListener('DOMContentLoaded', () => {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.opacity = '1';
        });
    </script>

    <style>
        #mainImage {
            opacity: 0;
        }
    </style>
@endsection
