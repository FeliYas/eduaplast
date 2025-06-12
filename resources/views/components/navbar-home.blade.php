@props(['logos', 'contactos'])
<nav class="w-full z-50" x-data="navbarData">
    <!-- Versión móvil: Logo y menú hamburguesa -->
    <div class="bg-white lg:hidden">
        <div class="flex justify-between items-center h-[70px] max-w-[90%] lg:max-w-[1224px] mx-auto">
            <div>
                <a href="{{ route('home') }}">
                    <img src="{{ asset(Route::currentRouteName() == 'home' ? $logos[0]->path : $logos[1]->path) }}"
                        alt="logo" class="w-30">
                </a>
            </div>
            <div class="mt-1.5">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class=" focus:outline-none">
                    <i class="fa-solid fa-bars text-xl text-main-color"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="lg:hidden bg-white shadow-lg overflow-hidden transition-all duration-300 absolute w-full z-40"
        :class="mobileMenuOpen ? 'max-h-screen' : 'max-h-0'" x-cloak>
        <div class="flex flex-col px-4 py-2 text-black">
            <a href="{{ route('nosotros') }}" class="py-2 border-b border-gray-200">Nosotros</a>
            <a href="{{ route('categorias') }}" class="py-2 border-b border-gray-200">Productos</a>
            <a href="{{ route('sectores') }}" class="py-2 border-b border-gray-200">Sectores</a>
            <a href="{{ route('matriceria') }}" class="py-2 border-b border-gray-200">Matriceria propia</a>
            <a href="{{ route('clientes') }}" class="py-2 border-b border-gray-200">Clientes</a>
            <a href="{{ route('novedades') }}" class="py-2 border-b border-gray-200">Novedades</a>
            <a href="{{ route('contacto') }}" class="py-2 border-b border-gray-200">Contacto</a>
            <a href="{{ route('presupuesto') }}" class="py-2 border-b border-gray-200">Presupuesto</a>
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
    <div class="hidden lg:block py-4 bg-white border-b border-gray-200 fixed w-full h-[123px] ">
        <div class="max-w-[90%] lg:max-w-[1224px] mx-auto flex justify-between items-center relative">
            <div>
                <a href="{{ route('home') }}">
                    <img src="{{ asset($logos[0]->path) }}" alt="logo">
                </a>
            </div>
            <div class="flex lg:gap-3 2xl:gap-5 lg:text-[13px] 2xl:text-base relative items-center text-black">
                @php $currentRoute = Route::currentRouteName(); @endphp
                <a href="{{ route('nosotros') }}" class="{{ $currentRoute == 'nosotros' ? 'font-bold' : '' }}">
                    Nosotros
                </a>
                <a href="{{ route('categorias') }}"
                    class="relative {{ in_array($currentRoute, ['categorias', 'productos', 'producto']) ? 'font-bold' : '' }}">
                    Productos
                </a>
                <a href="{{ route('sectores') }}" class="{{ $currentRoute == 'sectores' ? 'font-bold' : '' }}">
                    Sectores
                </a>
                <a href="{{ route('matriceria') }}" class="{{ $currentRoute == 'matriceria' ? 'font-bold' : '' }}">
                    Matriceria propia
                </a>
                <a href="{{ route('clientes') }}" class="{{ $currentRoute == 'clientes' ? 'font-bold' : '' }}">
                    Clientes
                </a>
                <a href="{{ route('novedades') }}"
                    class="{{ in_array($currentRoute, ['novedades', 'novedad']) ? 'font-bold' : '' }}">
                    Novedades
                </a>
                <a href="{{ route('contacto') }}"
                    class="relative {{ $currentRoute == 'contacto' ? 'font-bold' : '' }}">
                    Contacto
                </a>
                <a href="{{ route('presupuesto') }}" class="btn-home-2 w-[226px]">Solicitud de presupuesto</a>
            </div>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbarData', () => ({
            scrolled: false,
            mobileMenuOpen: false,
        }));
    });
</script>
