<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;

class TipoDocumentoSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            [
                'nombre' => 'Informe de Avance',
                'descripcion' => 'Informe mensual de progreso del proyecto',
                'obligatorio' => true,
                'categoria' => 'proyecto'
            ],
            [
                'nombre' => 'Cronograma de Actividades',
                'descripcion' => 'Planificación detallada de tareas y actividades',
                'obligatorio' => true,
                'categoria' => 'proyecto'
            ],
            [
                'nombre' => 'Presupuesto del Proyecto',
                'descripcion' => 'Análisis financiero y presupuesto detallado',
                'obligatorio' => false,
                'categoria' => 'proyecto'
            ],
            [
                'nombre' => 'Análisis de Mercado',
                'descripcion' => 'Estudio completo del mercado objetivo',
                'obligatorio' => true,
                'categoria' => 'investigacion'
            ],
            [
                'nombre' => 'Plan de Negocio',
                'descripcion' => 'Documento completo del plan de negocio',
                'obligatorio' => true,
                'categoria' => 'proyecto'
            ],
            [
                'nombre' => 'Evaluación de Competencia',
                'descripcion' => 'Análisis de la competencia en el mercado',
                'obligatorio' => false,
                'categoria' => 'investigacion'
            ],
            [
                'nombre' => 'Manual de Procedimientos',
                'descripcion' => 'Documento con procedimientos operativos',
                'obligatorio' => false,
                'categoria' => 'operacion'
            ],
            [
                'nombre' => 'Reporte de Resultados',
                'descripcion' => 'Informe de resultados obtenidos',
                'obligatorio' => true,
                'categoria' => 'evaluacion'
            ]
        ];

        foreach ($tipos as $tipo) {
            TipoDocumento::create($tipo);
        }
    }
}
