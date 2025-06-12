@extends('layouts.guest')
@section('title', 'Presupuesto')

@section('content')
    <div class="text-black">
        <div class="bg-gray-100 ">
            <div class="max-w-[90%] lg:max-w-[1224px] mx-auto">
                <div class="py-7">
                    <div class="text-xs">
                        <!-- Ruta de navegación -->
                        <div class="text-black hidden lg:block">
                            <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                            <span class="mx-[5px]">&gt;</span>
                            <a href="{{ route('presupuesto') }}" class="text-gray-500 hover:underline">Presupuesto</a>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Productos --}}
                <div class="pb-5 max-w-[95%] lg:max-w-[85%] mx-auto flex justify-center items-center w-full">
                    @if (session('carrito_consulta') && count(session('carrito_consulta')) > 0)
                        <div class="py-12 flex flex-col gap-8 w-full">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl lg:text-[30px] font-bold text-black">Productos</h2>
                                <a href="{{ route('categorias') }}" class="btn-home-1 flex items-center">
                                    <i class="fa-solid fa-plus lg:mx-3"></i>
                                    <span class="hidden lg:inline">Agregar productos</span>
                                </a>

                            </div>
                            @foreach (session('carrito_consulta') as $producto)
                                <div
                                    class="h-[140px] rounded-lg bg-white shadow-md flex items-center justify-between overflow-hidden border border-gray-200 px-1 lg:px-5 lg:py-2.5">
                                    <div class="flex items-center gap-4 lg:gap-12">
                                        @if ($producto->imagenPrincipal()->first())
                                            <img src="{{ asset($producto->imagenPrincipal()->first()->path) }}"
                                                alt="image"
                                                class="w-[70px] lg:w-[100px] h-[70px] lg:h-[100px] object-cover">
                                        @else
                                            <div
                                                class="w-[70px] lg:w-[100px] h-[70px] lg:h-[100px] flex items-center justify-center bg-gray-200 text-gray-500 text-sm">
                                                Sin imagen
                                            </div>
                                        @endif
                                        <div class="flex flex-col gap-2 max-w-[130px] lg:max-w-[400px]">
                                            <h3 class="lg:text-2xl font-medium">{{ $producto->titulo }}</h3>
                                            <div class="overflow-auto line-clamp-2">{!! $producto->descripcion !!}</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col md:flex-row items-center gap-3 md:gap-5">
                                        <a href="{{ route('producto', ['id' => $producto->categoria->id, 'producto' => $producto->id]) }}"
                                            class="btn-home-1 w-full md:w-[184px] flex justify-center items-center">
                                            <span class="md:hidden"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg></span>
                                            <span class="hidden md:block">Ver producto</span>
                                        </a>
                                        <form method="POST" action="{{ route('presupuesto.delete', $producto->id) }}"
                                            class="w-full md:w-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn-home-2 w-full md:w-[184px] flex justify-center items-center">
                                                <span class="md:hidden"><svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg></span>
                                                <span class="hidden md:block">Eliminar</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 flex flex-col gap-8 w-full">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl lg:text-[30px] font-bold text-black">Productos</h2>
                                <a href="{{ route('categorias') }}" class="btn-home-1 flex items-center">
                                    <i class="fa-solid fa-plus lg:mx-3"></i>
                                    <span class="hidden lg:inline">Agregar productos</span>
                                </a>
                            </div>
                            <div
                                class="h-[140px] rounded-lg bg-white shadow-md flex items-center justify-center overflow-hidden border border-gray-200 px-1 lg:px-5 lg:py-2.5 py-1">
                                <div class="flex flex-col items-center">
                                    <p class="lg:text-2xl font-medium ">No hay productos en el carrito</p>
                                    <p class="text-sm text-gray-700">Seleccioná productos para enviar la consulta.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="py-14 max-w-[90%] lg:max-w-[1224px] mx-auto">
            <div class="mb-8 max-w-[95%] lg:max-w-[85%] mx-auto">
                <h2 class="text-xl lg:text-[30px] font-bold text-black pb-10">Datos personales</h2>

                <form action="{{ route('presupuesto.enviar') }}" method="POST" class="space-y-6 py-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre y apellido -->
                        <div class="space-y-2">
                            <label for="nombre" class="block font-medium text-black">Nombre y apellido*</label>
                            <input type="text" id="nombre" name="nombre" required
                                class="w-full border border-gray-200 h-12 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block font-medium text-black">Email*</label>
                            <input type="email" id="email" name="email" required
                                class="w-full border border-gray-200 h-12 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Teléfono -->
                        <div class="space-y-2">
                            <label for="telefono" class="block font-medium text-black">Teléfono*</label>
                            <input type="tel" id="telefono" name="telefono" required
                                class="w-full border border-gray-200 h-12 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Empresa -->
                        <div class="space-y-2">
                            <label for="empresa" class="block font-medium text-black">Empresa</label>
                            <input type="text" id="empresa" name="empresa"
                                class="w-full border border-gray-200 h-12 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="md:w-1/2">
                            <div class="space-y-2">
                                <label for="mensaje" class="block font-medium text-black">Mensaje*</label>
                                <textarea id="mensaje" name="mensaje" rows="10" required
                                    class="w-full h-[180px] border border-gray-300 rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                        </div>

                        <div class="md:w-1/2 flex flex-col justify-between">
                            <div class="space-y-2">
                                <label for="archivo_input" class="block font-medium text-black">Adjuntar archivo</label>

                                <!-- Botón personalizado que activa el input de archivo -->
                                <label for="archivo_input" class="cursor-pointer">
                                    <div
                                        class="flex items-center justify-between border border-gray-300 rounded p-2 hover:bg-gray-50">
                                        <span class="text-gray-500" id="file-selected">Seleccionar archivo</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                </label>

                                <!-- Input de archivo real (oculto) -->
                                <input type="file" id="archivo_input" name="archivo" class="hidden"
                                    onchange="document.getElementById('file-selected').textContent = this.files[0] ? this.files[0].name : 'Seleccionar archivo'">
                            </div>
                            <div class="mt-auto flex flex-col lg:flex-row gap-4 items-center justify-between py-4 lg:py-0">
                                <p class="text-black">*Campos obligatorios</p>
                                <!-- Agregamos campo oculto para almacenar el token de reCAPTCHA -->
                                <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
                                <button type="button" id="submitBtn" class="btn-home-1 lg:w-3/5">Enviar solicitud de
                                    presupuesto</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Script de reCAPTCHA v3 -->
    <script
        src="https://www.google.com/recaptcha/api.js?render=6LecbjgrAAAAAMajoV7MVpTz6X2K36u5LWrTVswa
                                                                                                                                                                                                                                                                                                            ">
    </script>
    <script>
        // Script para sincronizar cantidades de productos
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar evento al botón de envío
            document.getElementById('submitBtn').addEventListener('click', handleSubmit);

            function handleSubmit(e) {
                e.preventDefault();

                // Activar reCAPTCHA
                grecaptcha.ready(function() {
                    grecaptcha.execute('6LecbjgrAAAAAMajoV7MVpTz6X2K36u5LWrTVswa', {
                        action: 'submit_presupuesto'
                    }).then(function(token) {
                        // Guardar el token en el campo oculto
                        document.getElementById('recaptchaResponse').value = token;

                        // Enviar el formulario
                        document.querySelector('form[action="{{ route('presupuesto.enviar') }}"]')
                            .submit();
                    });
                });
            }

            // Actualizar campos ocultos cuando cambian las cantidades
            const cantidadInputs = document.querySelectorAll('.cantidad-producto');

            cantidadInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const cantidad = this.value;

                    // Actualizar el campo oculto correspondiente
                    const hiddenInput = document.querySelector(`.cantidad-hidden[data-id="${id}"]`);
                    if (hiddenInput) {
                        hiddenInput.value = cantidad;
                    }
                });
            });
        });
    </script>
@endsection
