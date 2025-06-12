@extends('layouts.guest')
@section('meta')
    <meta name="{{ $metadatos->seccion }}" content="{{ $metadatos->keyword }}">
@endsection
@section('title', 'Contacto')

@section('content')
    <div class="max-w-[90%] lg:max-w-[1224px] mx-auto">
        <div class="py-7">
            <div class="text-xs">
                <!-- Ruta de navegación -->
                <div class="text-black hidden lg:block">
                    <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                    <span class="mx-[5px]">&gt;</span>
                    <a href="{{ route('contacto') }}" class="text-gray-500 hover:underline">Contacto</a>
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
        <div>
            <div class="flex flex-col lg:flex-row justify-between gap-12 lg:py-8">
                <div class="lg:w-1/3 flex flex-col gap-2 lg:gap-4 text-black">
                    <div class="flex flex-col text-center lg:text-left gap-2 mb-3">
                        <p>Para mayor información, no dude en contactarse mediante el siguiente
                            formulario, o a través de
                            nuestras vías de comunicación.</p>
                    </div>
                    @foreach ($contactos as $contacto)
                        @if ($contacto->direccion)
                            <a href="https://maps.google.com/?q={{ urlencode($contacto->direccion) }}" target="_blank"
                                class="block no-underline text-inherit hover:text-main-color">
                                <p class="lg:text-sm 2xl:text-[15px] mt-4.5">
                                    <span class="flex items-center gap-2">
                                        <svg width="35" height="35" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16.6668 8.33341C16.6668 13.3334 10.0002 18.3334 10.0002 18.3334C10.0002 18.3334 3.3335 13.3334 3.3335 8.33341C3.3335 6.5653 4.03588 4.86961 5.28612 3.61937C6.53636 2.36913 8.23205 1.66675 10.0002 1.66675C11.7683 1.66675 13.464 2.36913 14.7142 3.61937C15.9644 4.86961 16.6668 6.5653 16.6668 8.33341Z"
                                                stroke="#CD2930" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M10 10.8333C11.3807 10.8333 12.5 9.71396 12.5 8.33325C12.5 6.95254 11.3807 5.83325 10 5.83325C8.61929 5.83325 7.5 6.95254 7.5 8.33325C7.5 9.71396 8.61929 10.8333 10 10.8333Z"
                                                stroke="#CD2930" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg> {{ $contacto->direccion }}
                                    </span>
                                </p>
                            </a>
                        @endif
                        @if ($contacto->email)
                            <a href="mailto:{{ $contacto->email }}"
                                class="block no-underline text-inherit hover:text-main-color">
                                <p class="lg:text-sm 2xl:text-[15px] mt-4">
                                    <span class="flex items-center gap-2">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16.6665 3.33325H3.33317C2.4127 3.33325 1.6665 4.07944 1.6665 4.99992V14.9999C1.6665 15.9204 2.4127 16.6666 3.33317 16.6666H16.6665C17.587 16.6666 18.3332 15.9204 18.3332 14.9999V4.99992C18.3332 4.07944 17.587 3.33325 16.6665 3.33325Z"
                                                stroke="#CD2930" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M18.3332 5.83325L10.8582 10.5833C10.6009 10.7444 10.3034 10.8299 9.99984 10.8299C9.69624 10.8299 9.39878 10.7444 9.1415 10.5833L1.6665 5.83325"
                                                stroke="#CD2930" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        {{ $contacto->email }}
                                    </span>
                                </p>
                            </a>
                        @endif
                        @if ($contacto->telefono)
                            <a href="tel:{{ preg_replace('/\s+/', '', $contacto->telefono) }}"
                                class="block no-underline text-inherit hover:text-main-color">
                                <p class="lg:text-sm 2xl:text-[15px] mt-4">
                                    <span class="flex items-center gap-2">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_2927_271)">
                                                <path
                                                    d="M18.3332 14.0999V16.5999C18.3341 16.832 18.2866 17.0617 18.1936 17.2744C18.1006 17.487 17.9643 17.6779 17.7933 17.8348C17.6222 17.9917 17.4203 18.1112 17.2005 18.1855C16.9806 18.2599 16.7477 18.2875 16.5165 18.2666C13.9522 17.988 11.489 17.1117 9.32486 15.7083C7.31139 14.4288 5.60431 12.7217 4.32486 10.7083C2.91651 8.53426 2.04007 6.05908 1.76653 3.48325C1.7457 3.25281 1.77309 3.02055 1.84695 2.80127C1.9208 2.58199 2.03951 2.38049 2.1955 2.2096C2.3515 2.03871 2.54137 1.90218 2.75302 1.80869C2.96468 1.7152 3.19348 1.6668 3.42486 1.66658H5.92486C6.32928 1.6626 6.72136 1.80582 7.028 2.06953C7.33464 2.33324 7.53493 2.69946 7.59153 3.09992C7.69705 3.89997 7.89274 4.68552 8.17486 5.44158C8.28698 5.73985 8.31125 6.06401 8.24478 6.37565C8.17832 6.68729 8.02392 6.97334 7.79986 7.19992L6.74153 8.25825C7.92783 10.3445 9.65524 12.072 11.7415 13.2583L12.7999 12.1999C13.0264 11.9759 13.3125 11.8215 13.6241 11.755C13.9358 11.6885 14.2599 11.7128 14.5582 11.8249C15.3143 12.107 16.0998 12.3027 16.8999 12.4083C17.3047 12.4654 17.6744 12.6693 17.9386 12.9812C18.2029 13.2931 18.3433 13.6912 18.3332 14.0999Z"
                                                    stroke="#CD2930" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_2927_271">
                                                    <rect width="20" height="20" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        {{ $contacto->telefono }}
                                    </span>
                                </p>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div class="lg:w-2/3">
                    <form action="{{ route('contacto.enviar') }}" method="POST" class="w-full space-y-6 text-black"
                        id="contactForm">
                        @csrf
                        <div class="grid lg:grid-cols-2 gap-6">
                            <div class="w-full">
                                <label for="nombre" class="block mb-1">Nombre y apellido*</label>
                                <input type="text" name="nombre" id="nombre"
                                    class="border border-gray-300 w-full h-12 px-4">
                            </div>
                            <div class="w-full">
                                <label for="apellido" class="block mb-1">Email*</label>
                                <input type="text" name="apellido" id="apellido"
                                    class="border border-gray-300 w-full h-12 px-4">
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-2 gap-6">
                            <div class="w-full">
                                <label for="telefono" class="block mb-1">Teléfono*</label>
                                <input type="text" name="telefono" id="telefono"
                                    class="border border-gray-300 w-full h-12 px-4">
                            </div>
                            <div class="w-full">
                                <label for="empresa" class="block mb-1">Empresa</label>
                                <input type="text" name="empresa" id="empresa"
                                    class="border border-gray-300 w-full h-12 px-4">
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-6">
                            <div class="w-full py-2">
                                <label for="mensaje" class="block mb-1">Mensaje*</label>
                                <textarea name="mensaje" id="mensaje" cols="30" rows="10"
                                    class="border border-gray-300 w-full px-4 py-2"></textarea>
                            </div>

                            <div class="w-full flex items-end justify-center gap-10">
                                <div class="mb-4 text-lg">
                                    <p>*campos obligatorios</p>
                                </div>
                                <div class="mt-auto py-1 flex justify-center ">

                                    <!-- Agregamos campo oculto para almacenar el token de reCAPTCHA -->
                                    <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">

                                    <button type="button" id="submitBtn" class="btn-home-1 w-[180px]">
                                        Enviar
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>

                    <!-- Script de reCAPTCHA v3 -->
                    <script
                        src="https://www.google.com/recaptcha/api.js?render=6LecbjgrAAAAAMajoV7MVpTz6X2K36u5LWrTVswa
                                                                                                                                                                                                                                                                                                ">
                    </script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Agregar evento al botón de envío
                            document.getElementById('submitBtn').addEventListener('click', handleSubmit);

                            function handleSubmit(e) {
                                e.preventDefault();

                                // Activar reCAPTCHA
                                grecaptcha.ready(function() {
                                    grecaptcha.execute('6LecbjgrAAAAAMajoV7MVpTz6X2K36u5LWrTVswa', {
                                        action: 'submit_contact'
                                    }).then(function(token) {
                                        // Guardar el token en el campo oculto
                                        document.getElementById('recaptchaResponse').value = token;

                                        // Enviar el formulario
                                        document.getElementById('contactForm').submit();
                                    });
                                });
                            }
                        });
                    </script>
                </div>
            </div>
            <div class="mb-20">
                <div class="w-full h-[600px]">
                    {!! preg_replace(['/width="[^"]*"/', '/height="[^"]*"/'], ['width="100%"', 'height="100%"'], $mapa) !!}
                </div>
            </div>
        </div>
    </div>

@endsection
