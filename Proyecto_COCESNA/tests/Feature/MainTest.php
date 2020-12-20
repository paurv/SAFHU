<?php

namespace Tests\Feature;

use App\Personal;
use App\Usuarios;
use App\Posicion;
use App\RegEntrada;
use App\Turno;
use Tests\TestCase;
use Illuminate\Support\Facades\DB; //Importar DB
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;

class MainTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */




    //Esta funcion se encarga de borrar los registros de las tablas
    protected function borrarTodasLasTablas()
    {
        $tables = [
            'personal',
            'posicion',
            'turnos',
            'usuarios',
            'user',
            'areas_de_preguntas',
            'preguntas',
        ];
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }



    //Genera un usuario para las pruebas
    protected function usuarioDePrueba($idPer, $noEmp, $cont, $pos, $tur)
    {
        //$this->borrarTodasLasTablas();
        factory(Personal::class)->create([
            'id_personal' => $idPer,
            'no_empleado'=> $noEmp,
        ]);
        factory(Posicion::class)->create([
            'id_posicion' => $pos
        ]);
        factory(Turno::class)->create([
            'id_turno' => $tur
        ]);
        factory(Usuarios::class)->create([
            'id_personal' => $idPer,
            'contrasena' => Crypt::encryptString($cont),
            'id_posicion' => $pos,
            'id_turno' => $tur,
        ]);
    }




    /**
     @test*/
    public function el_usuario_usa_la_ruta_de_iniciar_sesion()
    {
        $this->get('/')
        ->assertStatus(200)
        ->assertSee('Número de empleado')
        ->assertSee('Contraseña');
        $this->borrarTodasLasTablas();
    }




    /**
     @test*/
    public function el_usuario_ingresa_texto_en_la_entrada_de_numeroEmpleado()
    {
        $this->usuarioDePrueba(1,3,'23',1,1);
        $this->from('/')
            ->put('/verif',[
                'numeroEmpleado' => 'n',
            ])
            ->assertRedirect("/")
            ->assertSessionHasErrors([
                'numeroEmpleado' => 'El número de empleado es invalido',
                ]);
        $this->borrarTodasLasTablas();
    }




    /**
     @test*/
    public function el_usuario_no_ingresa_numero_de_empleado_en_la_entrada_de_numeroEmpleado()
    {
        $this->usuarioDePrueba(1,3,'Ra34$',1,1);
        $this->from('/')
            ->put('/verif',[
                'numeroEmpleado' => '',
            ])
            ->assertRedirect("/")
            ->assertSessionHasErrors([
                'numeroEmpleado' => 'El número de empleado es obligatorio',
                ]);
        $this->borrarTodasLasTablas();
    }




    /**
     @test*/
    public function el_usuario_no_ingresa_su_contrasena()
    {
        $this->usuarioDePrueba(1,3,'Ra34$',1,1);
        $this->from('/')
            ->put('/verif',[
                'numeroEmpleado' => '3',
                'contrasena' => '',
            ])
            ->assertRedirect("/")
            ->assertSessionHasErrors([
                'contrasena' => 'La contraseña es obligatoria',
                ]);
        $this->borrarTodasLasTablas();
    }




    /**
     @test*/
     public function el_usuario_ingresa_una_contrasena_incorrecta()
     {
        //$this->withoutExceptionHandling();
        //$this->borrarTodasLasTablas();
        $this->usuarioDePrueba(1,3,'Ra34$',1,1);
        $this->from('/')
            ->put('/verif',[
                'numeroEmpleado' => '3',
                'contrasena' => '222a2s',
            ])
            ->assertRedirect("/")
            ->assertSessionHasErrors([
                'contrasena' => 'La contraseña es incorrecta',
                ]);
        $this->borrarTodasLasTablas();
     }




    /**
    @test*/
    public function el_usuario_ingresa_correctamente_su_informacion_y_se_almacena_la_hora_de_entrada()
    {
        $this->usuarioDePrueba(1,3,'Ra34$',1,1);
        $this->from('/')
            ->put('/verif',[
                'numeroEmpleado' => '3',
                'contrasena' => 'Ra34$',
            ])
            ->assertRedirect("/pagPriAdm");

        $this->assertSame(1,RegEntrada::count());

        $this->borrarTodasLasTablas();
    }




    /**
    @test*/
    public function el_controlador_contesta_no_en_la_pregunta_filtro()
    {
        // //$this->usuarioDePrueba(1,3,'Ra34$',2,1);
        // $this->fromView('preguntaFiltro')
        //     ->put('/si-no',[
        //         'btn' => '0',
        //     ])
        //     ->assertRedirect("/areas");

        $this->put('/si-no',[
                    'btn' => '0',
                ])
                ->assertSee('aqui');

        $this->borrarTodasLasTablas();
    }




    /**
    @test*/
    public function el_controlador_contesta_si_en_la_pregunta_filtro()
    {
        //$this->withoutExceptionHandling();
        //$this->usuarioDePrueba(1,3,'Ra34$',2,1);
        $this->put('/si-no',[
                'btn' => '1',
            ])
            ->assertSee("Gracias por su participación");

        $this->borrarTodasLasTablas();
    }

    /**
    @test*/
    public function FunctionName(Type $var = null)
    {
        # code...
    }


}
