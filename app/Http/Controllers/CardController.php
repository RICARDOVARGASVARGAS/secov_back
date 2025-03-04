<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CardController extends Controller
{
    public function show()
    {
        // Datos generativos para múltiples carnets
        $carnets = [];
        for ($i = 0; $i < 5; $i++) {
            $carnets[] = [
                'group' => ['name' => 'Asociación de Transportistas'],
                'plate' => 'ABC-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'year' => ['name' => '2020'],
                'motor' => 'MOT123456',
                'latest_permit' => [
                    'issue_date' => '2023-01-15',
                    'expiration_date' => '2024-01-15',
                ],
                'driver' => [
                    'first_name' => 'Juan',
                    'last_name' => 'Pérez',
                    'document_number' => '12345678',
                ],
                'example' => ['name' => 'Clase A'],
                'brand' => ['name' => 'Toyota'],
                'color' => ['name' => 'Rojo'],
                'group_number' => 'GRP-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
            ];
        }

        return view('card', compact('carnets'));
    }

    public function download()
    {
        // Paso 1: Renderizar la vista Blade con los datos
        $carnets = [];
        for ($i = 0; $i < 2; $i++) {
            $carnets[] = [
                'group' => ['name' => 'Asociación de Transportistas'],
                'plate' => 'ABC-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'year' => ['name' => '2020'],
                'motor' => 'MOT123456',
                'latest_permit' => [
                    'issue_date' => '2023-01-15',
                    'expiration_date' => '2024-01-15',
                ],
                'driver' => [
                    'first_name' => 'Juan',
                    'last_name' => 'Pérez',
                    'document_number' => '12345678',
                ],
                'example' => ['name' => 'Clase A'],
                'brand' => ['name' => 'Toyota'],
                'color' => ['name' => 'Rojo'],
                'group_number' => 'GRP-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
            ];
        }

        $view = view('card', compact('carnets'))->render();

        // Paso 2: Guardar la vista en un archivo HTML temporal
        $tempHtmlPath = public_path('storage/temp_carnets.html');
        file_put_contents($tempHtmlPath, $view);

        // Paso 3: Usar Puppeteer para generar el PDF
        $pdfPath = public_path('storage/carnets.pdf');
        $process = new Process([
            'node',
            base_path('generate-pdf.js'),
            $tempHtmlPath,
            $pdfPath
        ]);

        try {
            $process->mustRun();
            Log::info("PDF generado correctamente en {$pdfPath}"); // Registrar éxito
        } catch (\Exception $e) {
            Log::error("Error al ejecutar el proceso: " . $e->getMessage());
            Log::error("Output: " . $process->getOutput());
            Log::error("Error Output: " . $process->getErrorOutput());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Paso 4: Devolver el PDF como respuesta
        if (!file_exists($pdfPath)) {
            return response()->json(['error' => 'El archivo PDF no fue generado correctamente'], 500);
        }

        return response()->download($pdfPath)
            ->deleteFileAfterSend(false); // No eliminar el archivo después de descargar
    }
}
