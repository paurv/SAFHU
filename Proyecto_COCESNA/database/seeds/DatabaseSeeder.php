<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{




    /**
     * Seed the application's database.
     *
     * @return void
     */




    public function run()
    {
        //llamamos a una funcion la cual se encarga de borrar los registros en las tablas mencionadas
        $this->truncateTables([
            'areas_de_preguntas',
            'log_usuarios',
            'perdidas_de_contrasena',
            'personal',
            'posicion',
            'preguntas',
            'pregunta_filtro',
            'razones',
            'respuestas',
            'seglog',
            'tipos_de_respuesta',
            'turnos',
            'user',
            'usuarios',
        ]);


        //llamamos a los seeders de cada entidad
        $this->call(PosicionSeeder::class);
        $this->call(TurnoSeeder::class);
        $this->call(PersonalSeeder::class);
        $this->call(UsuariosSeeder::class);
        $this->call(PreguntaFiltroSeeder::class);
        $this->call(TiposRespuestaSeeder::class);
        $this->call(RespuestaSeeder::class);
        $this->call(AreaPreguntaSeeder::class);
        $this->call(PreguntaSeeder::class);

        //llamamos a la funcion de crear BD de prueba
        $this->crearBDparaPruebas();
    }




    //Esta funcion se encarga de borrar los registros de las tablas
    protected function truncateTables(array $tables){
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }




    //Esta funcion se encarga de crear una base de datos de prueba
    //mediante la clonación de la base principal
    protected function crearBDparaPruebas()
    {
        //dd(env('DB_HOST')." ".env('DB_USERNAME')." ".env('DB_PASSWORD'));
        $conexion = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'));
        echo "Conectando a mysql\n";
        $sql = 'DROP DATABASE IF EXISTS '.env('DB_DATABASE').'_test;';
        mysqli_query($conexion, $sql);
        $sql = 'CREATE DATABASE '.env('DB_DATABASE').'_test;';
        mysqli_query($conexion, $sql);
        mysqli_close($conexion);
        exec('mysqldump --user="'.env('DB_USERNAME').'" --password="'.env('DB_PASSWORD').'" --host="'.env('DB_HOST').'" '.env('DB_DATABASE').' > '.env('DB_DATABASE').'.sql');
        exec('mysqldump --user="'.env('DB_USERNAME').'" --password="'.env('DB_PASSWORD').'" --host="'.env('DB_HOST').'" '.env('DB_DATABASE').' -d -R | mysql --user="'.env('DB_USERNAME').'" --password="'.env('DB_PASSWORD').'" '.env('DB_DATABASE').'_test;');
        echo "Creación de base de datos de prueba exitosa\n";
    }



    
}





