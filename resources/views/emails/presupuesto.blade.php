<html>

<head>
    <meta charset="UTF-8">
    <title>Presupuesto</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto;">
        <tr>
            <td
                style="background-color: #ffffff; padding: 20px 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                <h1 style="color: #374977; text-align: center;">Presupuesto</h1>

                <h2 style="color: #374977;">Datos del cliente:</h2>
                <p><strong>Nombre:</strong> {{ $datos['nombre'] }}</p>
                <p><strong>Email:</strong> {{ $datos['email'] }}</p>
                <p><strong>Teléfono:</strong> {{ $datos['telefono'] }}</p>
                <p><strong>Empresa:</strong> {{ $datos['empresa'] }}</p>
                <p><strong>Mensaje:</strong> {{ $datos['mensaje'] }}</p>

                <h2 style="color: #374977;">Productos seleccionados:</h2>
                <table cellpadding="5" cellspacing="0" border="1" width="100%" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Descripcion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carrito as $producto)
                            <tr>
                                <td>{{ $producto->titulo }}</td>
                                <td>{!! $producto->descripcion !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="margin-top: 20px;">Gracias por su consulta. Nos pondremos en contacto a la brevedad.</p>
            </td>
        </tr>
    </table>
</body>

</html>
