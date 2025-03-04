<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta Única de Circulación</title>
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-red-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-6 max-w-4xl mx-auto">
        @foreach ($carnets as $carData)
            <h1 class="text-2xl font-bold text-center mb-4">Tarjeta Única de Circulación</h1>
            <!-- Front Side -->
            <div class="mb-8">
                <header class="text-center mb-4">
                    <h1 class="text-xl font-bold">MUNICIPALIDAD DISTRITAL DE ANCO HUALLO</h1>
                    <p class="text-sm">UNIDAD DE TRÁNSITO Y CIRCULACIÓN VIAL</p>
                    <h2 class="text-lg font-semibold">TARJETA ÚNICA DE CIRCULACIÓN</h2>
                </header>

                <div class="grid grid-cols-3 gap-4">
                    <!-- First Column -->
                    <div>
                        <p><strong>ASOCIACIÓN:</strong> {{ $carData['group']['name'] ?? 'N/A' }}</p>
                        <p><strong>PLACA:</strong> {{ $carData['plate'] ?? 'N/A' }}</p>
                        <p><strong>AÑO DE FABRICACIÓN:</strong> {{ $carData['year']['name'] ?? 'N/A' }}</p>
                        <p><strong>N° MOTOR:</strong> {{ $carData['motor'] ?? 'N/A' }}</p>
                        <p><strong>EMISIÓN:</strong> {{ $carData['latest_permit']['issue_date'] ?? 'N/A' }}</p>
                    </div>

                    <!-- Second Column -->
                    <div>
                        <p><strong>PROPIETARIO:</strong>
                            {{ ($carData['driver']['first_name'] ?? '') . ' ' . ($carData['driver']['last_name'] ?? '') }}
                        </p>
                        <p><strong>CLASE:</strong> {{ $carData['example']['name'] ?? 'N/A' }}</p>
                        <p><strong>MARCA:</strong> {{ $carData['brand']['name'] ?? 'N/A' }}</p>
                        <p><strong>COLOR:</strong> {{ $carData['color']['name'] ?? 'N/A' }}</p>
                        <p><strong>CADUCA:</strong> {{ $carData['latest_permit']['expiration_date'] ?? 'N/A' }}</p>
                    </div>

                    <!-- Third Column -->
                    <div>
                        <p><strong>DOCUMENTO:</strong> {{ $carData['driver']['document_number'] ?? 'N/A' }}</p>
                        <p><strong>CÓDIGO:</strong> {{ $carData['group_number'] ?? 'N/A' }}</p>
                    </div>
                </div>

                <footer class="mt-4 text-center">
                    <p><strong>RUTAS AUTORIZADAS:</strong> A TODOS LOS CENTROS POBLADOS</p>
                </footer>
            </div>

            <!-- Back Side -->
            <div>
                <header class="text-center mb-4">
                    <h1 class="text-xl font-bold">MUNICIPALIDAD DISTRITAL DE ANCO HUALLO</h1>
                    <p class="text-sm">UNIDAD DE TRÁNSITO Y CIRCULACIÓN VIAL</p>
                    <h2 class="text-lg font-semibold">OBLIGACIONES DEL CONDUCTOR</h2>
                </header>

                <div class="space-y-2">
                    <p>Prestará el servicio con vehículos autorizados con la Tarjeta de Circulación.</p>
                    <p>Facilitar la supervisión y fiscalización de los Inspectores de Transporte.</p>
                    <p>Cumplir estrictamente con ruta y demás condiciones establecidas.</p>
                    <p>La Tarjeta de Circulación será decomisada por el Inspector de Transporte cuando se dé uso
                        indebido,
                        tenga borrones o enmendaduras, o no coincida con los datos en el vehículo o esté vencida (Art.
                        69
                        del DS.017-2009/MTC).</p>
                    <p>El inspector de Transporte puede retener la Licencia de Conducir (Art. 112) cuando se niegue a
                        dar
                        información o documentos del vehículo que conduce o proporcione información falsa. Por no
                        colaborar
                        o pretender burlar la inspección.</p>
                </div>
            </div>
        @endforeach
    </div>

</body>

</html>
