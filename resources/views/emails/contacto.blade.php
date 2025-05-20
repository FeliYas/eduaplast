<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Nuevo mensaje de contacto</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto;">
        <tr>
            <td
                style="background-color: #ffffff; padding: 20px 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                <h1 style="color: #374977; text-align: center; font-family: Arial, Helvetica, sans-serif;">Nuevo mensaje
                    de contacto</h1>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">
                    <span style="font-weight: 600; color: #374977;">Nombre:</span>
                    {{ $datos['nombre'] ?? 'No especificado' }}
                    
                </p>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">
                    <span style="font-weight: 600; color: #374977;">Email:</span>
                    {{ $datos['apellido'] ?? 'No especificado' }}
                </p>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">
                    <span style="font-weight: 600; color: #374977;">Teléfono:</span>
                    {{ $datos['telefono'] ?? 'No especificado' }}
                </p>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">
                    <span style="font-weight: 600; color: #374977;">Empresa:</span>
                    {{ $datos['empresa'] ?? 'No especificada' }}
                </p>

                <h2 style="color: #374977; font-family: Arial, Helvetica, sans-serif;">Mensaje:</h2>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">
                    {{ $datos['mensaje'] ?? 'No especificado' }}</p>

            </td>
        </tr>
    </table>
</body>

</html>
