<?php


use App\Pregunta;
use Illuminate\Database\Seeder;

class PreguntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Pregunta::class)->create([
            'contenido' => '¿Tengo algún malestar fisico?',
            'id_tipo' => '1',
            'id_area' => '1',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Tengo algún dolor?',
            'id_tipo' => '1',
            'id_area' => '1',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Tengo algún sintoma?',
            'id_tipo' => '1',
            'id_area' => '1',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Representa ese sintoma alguna enfermedad?',
            'id_tipo' => '1',
            'id_area' => '1',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Me encuentro usando algún medicamento autorecetado?',
            'id_tipo' => '1',
            'id_area' => '2',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Me encuentro usando algún medicamento recomendado por un amigo?',
            'id_tipo' => '1',
            'id_area' => '2',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿He vuelto a tomar algún medicamento si consultar a un especialista?',
            'id_tipo' => '1',
            'id_area' => '2',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Me siento bajo presión psicologica?',
            'id_tipo' => '1',
            'id_area' => '3',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Siento que tengo problemas en mi ambiente laboral?',
            'id_tipo' => '1',
            'id_area' => '3',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Siento que tengo problemas personales?',
            'id_tipo' => '1',
            'id_area' => '3',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Estoy cansado?',
            'id_tipo' => '1',
            'id_area' => '4',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Tengo sueño constantemente?',
            'id_tipo' => '1',
            'id_area' => '4',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Tengo necesidad constantemente de acostarme o recostarme?',
            'id_tipo' => '1',
            'id_area' => '4',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿Siento que todo me cuesta el doble?',
            'id_tipo' => '1',
            'id_area' => '4',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿He comido en los horarios correspondientes hoy?',
            'id_tipo' => '1',
            'id_area' => '5',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿2 + 2?',
            'id_tipo' => '2',
            'id_area' => '6',
        ]);
        factory(Pregunta::class)->create([
            'contenido' => '¿2 = 5?',
            'id_tipo' => '1',
            'id_area' => '6',
        ]);
    }
}
