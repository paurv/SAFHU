<?php

use App\AreaPregunta;
use Illuminate\Database\Seeder;

class AreaPreguntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(AreaPregunta::class)->create([
            'nombre' => 'Enfermedad',
            'descripcion' => 'Acerca del estado fisico',
        ]);
        factory(AreaPregunta::class)->create([
            'nombre' => 'Automedicación',
            'descripcion' => 'Uso de medicamentos',
        ]);
        factory(AreaPregunta::class)->create([
            'nombre' => 'Estado de animo',
            'descripcion' => 'Acerca de problemas psicologicos',
        ]);
        factory(AreaPregunta::class)->create([
            'nombre' => 'Fatiga',
            'descripcion' => 'Causas que generan fatiga',
        ]);
        factory(AreaPregunta::class)->create([
            'nombre' => 'Alimentación',
            'descripcion' => 'Confirmar niveles de energia',
        ]);
        factory(AreaPregunta::class)->create([
            'nombre' => 'Matemáticas',
            'descripcion' => 'Problemas fáciles',
        ]);
    }
}
