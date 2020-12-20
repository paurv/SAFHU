<?php

use App\Posicion;
use Illuminate\Database\Seeder;

class PosicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Posicion::class)->create([
            'posicion' => 'Administrador',
        ]);

        factory(Posicion::class)->create([
            'posicion' => 'Controlador',
        ]);

        factory(Posicion::class)->create([
            'posicion' => 'Supervisor',
        ]);

        factory(Posicion::class)->create([
            'posicion' => 'RRHH',
        ]);
    }
}
