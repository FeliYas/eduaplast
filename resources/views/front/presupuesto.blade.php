@extends('layouts.guest')
@section('title', 'Presupuesto')

@section('content')
    <div>
        <div class="bg-gray-100">
            <div class="max-w-[70%] mx-auto">
                <div class="text-xs mt-10">
                    <!-- Ruta de navegación -->
                    <div class="text-black hidden lg:block">
                        <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                        <span class="mx-[5px]">&gt;</span>
                        <a href="{{ route('presupuesto') }}" class="text-gray-500 hover:underline">Presupuesto</a>
                    </div>
                </div>
            </div>
        </div>


        {{-- Contenido principal --}}
        <div class="py-5">

            {{-- Alertas de éxito o error --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Productos --}}
            <div class="mb-5">
                @if (session('carrito_consulta') && count(session('carrito_consulta')) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Productos</th>
                                <th style="width: 150px">Cantidad</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (session('carrito_consulta') as $producto)
                                <tr>
                                    <td class="align-middle">
                                        <img src="{{ asset(Storage::url($producto->imagen)) }}"
                                            alt="{{ $producto->nombre }}" style="max-width: 100px;">
                                        <span class="ms-3">{{ $producto->nombre }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <input type="number" name="cantidades[{{ $producto->id }}]"
                                            class="form-control cantidad-producto" min="1" value="1"
                                            data-id="{{ $producto->id }}">
                                    </td>
                                    <td class="align-middle">
                                        <form method="POST" action="{{ route('page.consulta.eliminar', $producto->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning text-center py-5" role="alert" style="font-size: 1.2rem;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No tenés productos seleccionados. Seleccioná productos para enviar la consulta.
                    </div>
                @endif

                <div>
                    <a href="{{ route('categorias') }}" class="btn-home-1">+ Agregar Productos</a>
                </div>
            </div>


            {{-- Contacto --}}
            <div class="row">

                {{-- Formulario de compra --}}
                <div class="col-md-8">
                    <form method="post" action="{{ route('presupuesto.enviar') }}" enctype="multipart/form-data"
                        id="formularioConsulta">
                        @csrf

                        {{-- Campo oculto para verificar si el carrito está vacío --}}
                        <input type="hidden" name="carrito_vacio"
                            value="{{ session('carrito_consulta') && count(session('carrito_consulta')) > 0 ? '0' : '1' }}">

                        <div class="row mt-4">
                            <div class="form-floating col-md-6 mb-3">
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre"
                                    required>
                                <label for="nombre" class="formulario ms-3">Nombre*</label>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating col-md-6 mb-3">
                                <input type="text" class="form-control @error('apellido') is-invalid @enderror"
                                    id="apellido" name="apellido" value="{{ old('apellido') }}" placeholder="Apellido"
                                    required>
                                <label for="apellido" class="formulario ms-3">Apellido*</label>
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-floating col-md-6 mb-3">
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono"
                                    required>
                                <label for="telefono" class="formulario ms-3">Teléfono*</label>
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating col-md-6 mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" placeholder="E-mail"
                                    required>
                                <label for="email" class="formulario ms-3">E-mail*</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-floating col-md-6 mb-3">
                                <input type="text" class="form-control @error('provincia') is-invalid @enderror"
                                    id="provincia" name="provincia" value="{{ old('provincia') }}" placeholder="Provincia"
                                    required>
                                <label for="provincia" class="formulario ms-3">Provincia*</label>
                                @error('provincia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating col-md-6 mb-3">
                                <input type="text" class="form-control @error('localidad') is-invalid @enderror"
                                    id="localidad" name="localidad" value="{{ old('localidad') }}"
                                    placeholder="Localidad" required>
                                <label for="localidad" class="formulario ms-3">Localidad*</label>
                                @error('localidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-floating col-md-12 mb-3">
                                <textarea class="form-control @error('mensaje') is-invalid @enderror" placeholder="Mensaje..." id="mensaje"
                                    name="mensaje" style="height: 100px">{{ old('mensaje') }}</textarea>
                                <label for="mensaje" class="formulario ms-3">Mensaje</label>
                                @error('mensaje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Campos ocultos para almacenar la información de los productos --}}
                        @if (session('carrito_consulta') && count(session('carrito_consulta')) > 0)
                            @foreach (session('carrito_consulta') as $producto)
                                <input type="hidden" name="productos[{{ $producto->id }}]"
                                    value="{{ $producto->nombre }}">
                                <input type="hidden" name="productos_cantidades[{{ $producto->id }}]"
                                    class="cantidad-hidden" data-id="{{ $producto->id }}" value="1">
                            @endforeach
                        @endif

                        <div class="row my-4">
                            <!-- Campo oculto para reCAPTCHA -->
                            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-consulta">

                            <div class="col-md-6 d-flex justify-content-end align-items-center">
                                <button type="submit" class="btn-cv">Enviar formulario de compra</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Script para sincronizar cantidades de productos
        document.addEventListener('DOMContentLoaded', function() {
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
