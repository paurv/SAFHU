<?php

use App\Usuarios;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  //Manejo de SQL
use Illuminate\Support\Facades\Crypt;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Usuarios::class)->create([
            'id_personal'=>'1',
            'id_turno'=>'1',
            'id_posicion'=>'1',
            'email'=>'el@administrador.p',
            'contrasena'=> Crypt::encryptString('123'),
        ]);

        factory(Usuarios::class)->create([
            'id_personal'=>'2',
            'id_turno'=>'1',
            'id_posicion'=>'2',
            'email'=>'primer@controlador.p',
            'contrasena'=> Crypt::encryptString('123'),
        ]);

        factory(Usuarios::class)->create([
            'id_personal'=>'3',
            'id_turno'=>'1',
            'id_posicion'=>'3',
            'email'=>'el@supervisor.p',
            'contrasena'=> Crypt::encryptString('123'),
        ]);

        factory(Usuarios::class)->create([
            'id_personal'=>'4',
            'id_turno'=>'1',
            'id_posicion'=>'4',
            'email'=>'RR@HH.p',
            'contrasena'=> Crypt::encryptString('123'),
        ]);

        $id1 = DB::table('personal')->get('id_personal');
        $id2 = DB::table('posicion')->get('id_posicion');
        $id3 = DB::table('turnos')->get('id_turno');
        //dd($id1[0]->id_personal);
        //dd($id2);
        // for( $i=4 ; $i<count($id1) ; $i++)
        for( $i=4 ; $i<11 ; $i++)
        {
            $x = $id2->random()->id_posicion;
            $y = $id3->random()->id_turno;
            factory(Usuarios::class)->create([
                'id_personal' => $id1[$i]->id_personal,
                'id_turno' => $y,
                'id_posicion' => $x
            ]);
        }
    }
}
