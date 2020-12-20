<?php

use App\TiposRespuesta;
use Illuminate\Database\Seeder;

class TiposRespuestaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(TiposRespuesta::class)->create([
            'tipo' => 'Cerrada si-no'
        ]);
        factory(TiposRespuesta::class)->create([
            'tipo' => 'Escala númerica 1-5'
        ]);
        factory(TiposRespuesta::class)->create([
            'tipo' => 'Escala ordinal bajo-alto'
        ]);
        factory(TiposRespuesta::class)->create([
            'tipo' => 'Escala ordinal bien-mal'
        ]);
        factory(TiposRespuesta::class)->create([
            'tipo' => 'Escala ordinal mucho-poco'
        ]);
    }
}
