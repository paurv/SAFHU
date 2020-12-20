<?php

use App\PreguntaFiltro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  //Manejo de SQL

class PreguntaFiltroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::select('call preg_filtro_crear(?)',
        array(
            '¿Estoy en forma para realizar el turno?',
        ));
    }
}
