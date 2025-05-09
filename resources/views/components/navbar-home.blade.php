@props(['logos', 'contactos'])
<nav class="w-full z-50 fixed top-0 left-0" x-data="navbarData">
    <!-- Versión móvil: Logo y menú hamburguesa -->
    <div class="bg-main-color lg:hidden">
        <div class="flex justify-between items-center h-[70px] max-w-[80%] mx-auto">
            <div>
                <a href="{{ route('home') }}">
                    <img src="{{ asset('storage/' . (Route::currentRouteName() == 'home' ? $logos[0]->path : $logos[1]->path)) }}"
                        alt="logo" class="w-20">
                </a>
            </div>
            <div class="mt-1.5">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class=" focus:outline-none">
                    <i class="fa-solid fa-bars text-xl text-white"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="lg:hidden bg-white shadow-lg overflow-hidden transition-all duration-300 absolute w-full z-40"
        :class="mobileMenuOpen ? 'max-h-screen' : 'max-h-0'" x-cloak>
        <div class="flex flex-col px-4 py-2">
            {{-- <a href="{{ route('nosotros') }}" class="py-2 border-b border-gray-200">NOSOTROS</a>
            <a href="{{ route('categorias') }}" class="py-2 border-b border-gray-200">PRODUCTOS</a>
            <a href="{{ route('novedades') }}" class="py-2 border-b border-gray-200">NOVEDADES</a>
            <a href="{{ route('contacto') }}" class="py-2 border-b border-gray-200">CONTACTO</a> --}}
            <div class="flex items-center py-2">
                <i class="fa-solid fa-envelope mr-2 text-gray-600"></i>
                @foreach ($contactos as $contacto)
                    @if ($contacto->email)
                        <a href="mailto:{{ $contacto->email }}">
                            <p class="text-gray-600">{{ $contacto->email }}</p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @if (Route::currentRouteName() == 'home')
        <div class="hidden lg:block transition-colors duration-300 ease-in-out py-4 "
            :class="scrolled ? 'bg-white border-b border-gray-200' : 'bg-transparent'"
            @scroll.window="scrolled = (window.pageYOffset > 50)">
            <div class="max-w-[80%] mx-auto flex justify-between items-center">
                <div>
                    <a href="{{ route('home') }}">
                        <img :src="scrolled ? '{{ asset('storage/' . $logos[1]->path) }}' :
                            '{{ asset('storage/' . $logos[0]->path) }}'"
                            alt="logo">
                    </a>
                </div>
                <div class="flex lg:gap-3 2xl:gap-6 lg:text-[14px] 2xl:text-[15px] text-center items-center">
                    {{-- <a href="{{ route('nosotros') }}" class="hover:underline"
                        :class="scrolled ? 'text-black' : 'text-white'">NOSOTROS</a>
                    <a href="{{ route('categorias') }}" class="hover:underline"
                        :class="scrolled ? 'text-black' : 'text-white'">PRODUCTOS</a>
                    <a href="{{ route('novedades') }}" class="hover:underline"
                        :class="scrolled ? 'text-black' : 'text-white'">NOVEDADES</a>
                    <a href="{{ route('contacto') }}" class="hover:underline"
                        :class="scrolled ? 'text-black' : 'text-white'">CONTACTO</a> --}}
                </div>
            </div>
        </div>
    @else
        <div class="hidden lg:block py-4 bg-white border-b border-gray-200">
            <div class="max-w-[80%] mx-auto flex justify-between items-center relative">
                <div>
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('storage/' . $logos[0]->path) }}" alt="logo">
                    </a>
                </div>
                <div class="flex lg:gap-2 2xl:gap-4 lg:text-[13px] 2xl:text-base relative items-center">
                    @php $currentRoute = Route::currentRouteName(); @endphp
                    {{-- <a href="{{ route('nosotros') }}"
                        class="relative {{ $currentRoute == 'nosotros' ? 'text-bold' : '' }}">
                        NOSOTROS
                        @if ($currentRoute == 'nosotros')
                            <span class="absolute left-0 -bottom-10 2xl:-bottom-9.5 w-full h-1 bg-main-color"></span>
                        @endif
                    </a>
                    <a href="{{ route('categorias') }}"
                        class="relative {{ $currentRoute == 'categorias' ? 'text-bold' : '' }}">
                        PRODUCTOS
                        @if ($currentRoute == 'categorias' || $currentRoute == 'productos' || $currentRoute == 'producto')
                            <span class="absolute left-0 -bottom-10 2xl:-bottom-9.5 w-full h-1 bg-main-color"></span>
                        @endif
                    </a>
                    <a href="{{ route('novedades') }}"
                        class="relative {{ $currentRoute == 'novedades' ? 'text-bold' : '' }}">
                        NOVEDADES
                        @if ($currentRoute == 'novedades' || $currentRoute == 'novedad')
                            <span class="absolute left-0 -bottom-10 2xl:-bottom-9.5 w-full h-1 bg-main-color"></span>
                        @endif
                    </a>
                    <a href="{{ route('contacto') }}"
                        class="relative {{ $currentRoute == 'contacto' ? 'text-bold' : '' }}">
                        CONTACTO
                        @if ($currentRoute == 'contacto')
                            <span class="absolute left-0 -bottom-10 2xl:-bottom-9.5 w-full h-1 bg-main-color"></span>
                        @endif
                    </a> --}}
                </div>
            </div>


        </div>
    @endif
</nav>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbarData', () => ({
            scrolled: false,
            mobileMenuOpen: false,
        }));
    });
</script>
