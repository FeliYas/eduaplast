@extends('layouts.guest')
@section('title', 'Home')

@section('content')
    <div>
        <div class="overflow-hidden">
    <!-- Slider Track -->
    <div class="slider-track flex transition-transform duration-500 ease-in-out">
        @foreach ($sliders as $slider)
            <div class="slider-item min-w-full relative h-[600px] lg:h-[668px]">
                <div class="absolute inset-0 bg-black z-0">
                    @php
                        $extension = pathinfo($slider->path, PATHINFO_EXTENSION);
                    @endphp
                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <img src="{{ asset($slider->path) }}" alt="Slider Image" class="w-full h-full object-cover"
                            data-duration="6000">
                    @elseif (in_array($extension, ['mp4', 'webm', 'ogg']))
                        <video class="w-full h-full object-cover" autoplay muted onended="nextSlide()">
                            <source src="{{ asset($slider->path) }}" type="video/{{ $extension }}">
                            Tu navegador no soporta el formato de video.
                        </video>
                    @endif
                </div>

                <div class="absolute bg-black opacity-30 z-50"></div>
                <div class="absolute inset-0 flex z-20 max-w-[90%] lg:max-w-[1224px] mx-auto">
                    <div class="flex flex-col gap-6 lg:gap-19 w-full justify-center">
                        <div class="text-white ">
                            <div class="flex flex-col gap-4 max-w-[650px]">
                                <h1 class="text-3xl lg:text-[54px] lg:leading-13 font-semibold">
                                    {{ $slider->titulo }}
                                </h1>
                                <div class="custom-summernote text-lg lg:text-xl">
                                    <p>
                                        {!! $slider->descripcion !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('categorias') }}" class="btn-home-1 w-50">Mas información</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Slider Navigation Dots -->
    <div class="relative max-w-[90%] lg:max-w-[1224px] mx-auto">
        <div class="absolute bottom-20 w-full z-30">
            <div class="flex space-x-1 lg:space-x-2">
                @foreach ($sliders as $index => $slider)
                    <button
                        class="cursor-pointer dot w-6 lg:w-12 h-1.5 rounded-none transition-colors duration-300 bg-white {{ $index === 0 ? 'opacity-90' : 'opacity-50' }}"
                        onclick="goToSlide({{ $index }})">
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Main elements
        const sliderTrack = document.querySelector('.slider-track');
        const sliderItems = document.querySelectorAll('.slider-item');
        const dots = document.querySelectorAll('.dot');

        let currentIndex = 0;
        let autoSlideTimeout;
        let isTransitioning = false;

        // Global function for next slide (needed for video onended attribute)
        window.nextSlide = function() {
            if (isTransitioning) return;
            clearTimeout(autoSlideTimeout);
            currentIndex = (currentIndex + 1) % sliderItems.length;
            updateSlider();
        };

        // Go to specific slide (for dot navigation)
        window.goToSlide = function(index) {
            if (isTransitioning || index === currentIndex) return;
            clearTimeout(autoSlideTimeout);
            currentIndex = index;
            updateSlider();
        };

        // Initialize slider
        function initSlider() {
            // Configure videos
            sliderItems.forEach(item => {
                const video = item.querySelector('video');
                if (video) {
                    // Remove loop attribute
                    video.removeAttribute('loop');

                    // Make sure onended is properly set
                    video.onended = window.nextSlide;
                }
            });

            // Show first active slide
            updateSlider();
        }

        // Update slider to current position
        function updateSlider() {
            isTransitioning = true;

            // Stop all playing videos
            sliderItems.forEach(item => {
                const video = item.querySelector('video');
                if (video) {
                    video.pause();
                }
            });

            // Update track position
            sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;

            // Update navigation dots
            dots.forEach((dot, index) => {
                if (index === currentIndex) {
                    dot.classList.add('opacity-90');
                    dot.classList.remove('opacity-50');
                } else {
                    dot.classList.add('opacity-50');
                    dot.classList.remove('opacity-90');
                }
            });

            // Schedule next slide
            scheduleNextSlide();

            // End transition after animation duration
            setTimeout(() => {
                isTransitioning = false;
            }, 500); // Should match CSS transition duration (duration-500)
        }

        // Schedule next slide based on content type
        function scheduleNextSlide() {
            clearTimeout(autoSlideTimeout);

            const currentSlide = sliderItems[currentIndex];
            const video = currentSlide.querySelector('video');

            if (video) {
                // If it's a video, set to start from beginning
                video.currentTime = 0;
                video.play();
                // No need to add listener here as we use the onended attribute
                console.log("Video started, waiting for it to end before going to next slide");
            } else {
                // If it's an image, use specified duration or default 6000ms
                const img = currentSlide.querySelector('img');
                const duration = img && img.dataset.duration ? parseInt(img.dataset.duration) : 6000;

                console.log("Image displayed, will change in " + duration / 1000 + " seconds");
                autoSlideTimeout = setTimeout(window.nextSlide, duration);
            }
        }

        // Start slider
        initSlider();
    });
</script>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto py-25">
            <div class="flex flex-col gap-6">
                <div class="flex justify-between items-center">
                    <h2 class="titulo-home text-black">Productos</h2>
                    <a href="{{ route('categorias') }}" class="btn-home-2 w-[140px] lg:w-[120px]">VER TODOS</a>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-4 w-full gap-6">
                    @foreach ($categorias as $categoria)
                        <div
                            class="w-full h-[288px] group shadow-md hover:shadow-xl transition-shadow duration-300 rounded-xl overflow-hidden border border-gray-200">
                            <a href="" class="block w-full h-full">
                                {{-- {{ route('productos', ['id' => $categoria->id]) }} --}}
                                <img src="{{ asset($categoria->path) }}" alt="{{ $categoria->titulo }}"
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
        <div class="flex flex-col lg:flex-row bg-main-color gap-6 lg:gap-18">
            <img src="{{ asset($contenido->path) }}" alt="Contenido de la pagina"
                class="lg:w-1/2 h-[500px] lg:h-[600px] object-cover opacity-0 -translate-x-20 transition-all duration-2000 ease-in-out scroll-fade-left">
            <div
                class="lg:w-1/2 pr-[5%] pl-[5%] lg:pl-[0%] lg:pr-[calc((100vw-1224px)/2)] lg:mt-20 text-white flex flex-col gap-2 lg:gap-10 opacity-0 translate-x-20 transition-all duration-2000 ease-in-out scroll-fade-right items-center lg:items-start">
                <h2 class="titulo-home mb-1.5">{{ $contenido->titulo }}</h2>
                <div class="custom-summernote lg:text-xl text-center lg:text-left">
                    <p>{!! $contenido->descripcion !!}</p>
                </div>
                <a href="{{ route('nosotros') }}" class="btn-home-2 mb-4 mt-0 lg:mb-0">Mas información</a>
            </div>
        </div>
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto py-20 mb-4.5">
            <div class="flex flex-col gap-6">
                <div class="flex justify-between items-center">
                    <h2 class="titulo-home text-black">Novedades</h2>
                    <a href="{{ route('novedades') }}" class="btn-home-2 w-[120px]">VER TODAS</a>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 w-full gap-6">
                    @foreach ($novedades as $novedad)
                        <div class="transform hover:scale-[1.02] transition duration-300 hover:shadow-xl">
                            <x-tarjeta-novedades :novedad="$novedad" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="bg-gray-100 py-16 pb-20">
            <div class="flex flex-col gap-6 max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="flex justify-between items-center">
                    <h2 class="titulo-home text-black">Clientes</h2>
                    <a href="{{ route('clientes') }}" class="btn-home-2 w-[120px]">Ver
                        todos</a>
                </div>
                <div class="relative" x-data="{
                    activeSlide: 0,
                    totalSlides: 0,
                    autoSlideInterval: null,
                    isMobile: window.innerWidth < 1024,
                    clientesCount: {{ count($clientes) }},
        
                    init() {
                        this.calculateTotalSlides();
                        this.startAutoSlide();
        
                        // Actualizar cuando cambie el tamaño de la ventana
                        window.addEventListener('resize', () => {
                            this.isMobile = window.innerWidth < 1024;
                            this.calculateTotalSlides();
                            this.activeSlide = 0; // Reiniciar a la primera diapositiva al cambiar de tamaño
                            this.stopAutoSlide();
                            this.startAutoSlide();
                        });
                    },
        
                    calculateTotalSlides() {
                        if (this.isMobile) {
                            this.totalSlides = this.clientesCount;
                        } else {
                            this.totalSlides = Math.ceil(this.clientesCount / 6);
                        }
                        console.log('Total slides:', this.totalSlides, 'Is mobile:', this.isMobile);
                    },
        
                    startAutoSlide() {
                        if (this.totalSlides <= 1) return; // No iniciar si solo hay 1 slide
                        
                        this.autoSlideInterval = setInterval(() => {
                            this.nextSlide();
                        }, this.isMobile ? 3000 : 5000);
                    },
        
                    stopAutoSlide() {
                        if (this.autoSlideInterval) {
                            clearInterval(this.autoSlideInterval);
                            this.autoSlideInterval = null;
                        }
                    },
        
                    nextSlide() {
                        if (this.totalSlides <= 1) return;
                        
                        this.activeSlide = this.activeSlide + 1;
                        if (this.activeSlide >= this.totalSlides) {
                            this.activeSlide = 0;
                        }
                        console.log('Next slide:', this.activeSlide, 'of', this.totalSlides);
                    },
        
                    prevSlide() {
                        if (this.totalSlides <= 1) return;
                        
                        this.activeSlide = this.activeSlide - 1;
                        if (this.activeSlide < 0) {
                            this.activeSlide = this.totalSlides - 1;
                        }
                    },
        
                    goToSlide(index) {
                        if (index >= 0 && index < this.totalSlides) {
                            this.activeSlide = index;
                        }
                    }
                }" @mouseover="stopAutoSlide()" @mouseleave="startAutoSlide()">
        
                    <!-- Carrusel de clientes -->
                    <div class="overflow-hidden relative">
                        <!-- Versión para escritorio (oculta en móvil) -->
                        <div class="hidden lg:flex transition-transform duration-500 ease-in-out"
                            :style="`transform: translateX(-${activeSlide * 100}%)`">
        
                            @php
                                $chunkedClientes = $clientes->chunk(6);
                            @endphp
        
                            @foreach ($chunkedClientes as $chunk)
                                <div class="grid grid-cols-6 justify-between gap-6 min-w-full py-4">
                                    @foreach ($chunk as $cliente)
                                        <div class="h-[190px] max-w-[300px] bg-white p-4 rounded-xl shadow-md">
                                            <img src="{{ asset($cliente->path) }}" alt="cliente"
                                                class="w-full h-full object-contain transition-all duration-300 filter grayscale hover:grayscale-0">
                                        </div>
                                    @endforeach
        
                                    <!-- Agrega divs vacíos para mantener la estructura si hay menos de 6 items en el chunk -->
                                    @for ($i = count($chunk); $i < 6; $i++)
                                        <div class="h-[190px] max-w-[300px] "></div>
                                    @endfor
                                </div>
                            @endforeach
                        </div>
        
                        <!-- Versión para móvil (oculta en escritorio) -->
                        <div class="lg:hidden flex transition-transform duration-500 ease-in-out"
                            :style="`transform: translateX(-${activeSlide * 100}%)`">
        
                            @foreach ($clientes as $cliente)
                                <div class="min-w-full flex justify-center">
                                    <div class="max-h-[190px] max-w-[300px] w-full bg-white">
                                        <img src="{{ asset($cliente->path) }}" alt="cliente"
                                            class="w-full h-full object-cover transition-all duration-300 filter lg:grayscale hover:grayscale-0">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
        
                    <!-- Indicadores de paginación con forma de barras -->
                    <template x-if="totalSlides > 1">
                        <div class="absolute -bottom-10 left-0 right-0 flex justify-center space-x-2 mt-6">
                            <template x-for="(slide, index) in Array.from({length: totalSlides})" :key="index">
                                <button @click="goToSlide(index)"
                                    :class="{ 'bg-gray-400': activeSlide === index, 'bg-gray-200': activeSlide !== index }"
                                    class="w-10 h-1.5 cursor-pointer transition-colors duration-300"></button>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', '-translate-x-20',
                            'translate-x-20');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.scroll-fade-left, .scroll-fade-right').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
@endsection
