<?php

namespace App\Http\Controllers;

use App\Personal;
use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;      //Importar DB
use Illuminate\Support\Facades\Crypt;   //Encriptar/Desencriptar contraseñas
use Illuminate\Support\Facades\Hash;    //Manejo de hashes
use Mail;
use App\Mail\SendMailable;

class UserController extends Controller
{




    // De https://gist.github.com/yoga-/8c2c196173be3d4aff56
    //  generates a random password of length minimum 8 
    //  contains at least one lower case letter, one upper case letter,
    // one number and one special character, 
    //  not including ambiguous characters like iIl|1 0oO 
    protected function randomPassword($len = 8) 
    {
        //  enforce min length 8
        if($len < 8)
            $len = 8;
        //  define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '23456789';
        $sets[]  = '~!@#$%^&*(){}[],./?';
        $password = '';
        //  append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
        //  use all characters to fill up to $len
        while(strlen($password) < $len) {
            //  get a random set
            $randomSet = $sets[array_rand($sets)];   
            //  add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        //  shuffle the password string before returning!
        return str_shuffle($password);
    }




    public function agregarUsuario(Request $request)
    {
        if(!($request->session()->get('auth')=='1'))
        {
            return abort(404);
        }

        $idPersonal = DB::table('personal')
                        // ->select('id_personal')
                        ->where('no_empleado','=',$request->no_empleado)
                        ->first();

        $idTurno = DB::table('turnos')
                        ->select('id_turno')
                        ->where('id_turno','=',$request->turno)
                        ->first();

        $idPosicion = DB::table('posicion')
                        ->select('id_posicion')
                        ->where('id_posicion','=',$request->posicion)
                        ->first();

        $contrasena = Crypt::encryptString('0000');

        DB::select('call usuarios_guardar(?,?,?,?,?)',
        array(
            $idPersonal->id_personal,
            $idTurno->id_turno,
            $idPosicion->id_posicion,
            $request->email,
            $contrasena
        ));

        $user = array();
        $user = DB::table('usuarios')
                        ->select('personal.no_empleado' , 'usuarios.email', 'personal.nombres' , 'personal.apellidos','posicion.posicion' )
                        ->join('personal','usuarios.id_personal','=','personal.id_personal')
                        ->join('posicion','posicion.id_posicion','=','usuarios.id_posicion')
                        ->orderBy('personal.no_empleado')
                        ->get();

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
            array(
                $request->session()->get('noEmpleado'),
                substr($request->session()->get('nombres'),0,20),
                'Nuevo usuario',
                'usuarios',
                'Guardar el personal con numero de empleado '.$idPersonal->no_empleado.' en la tabla usuarios',
                'INSERT',
                $request->ip(),
            ));
        return $user;
    }




    public function mostrarUsuarios()
    {
        if(!(request()->session()->get('auth')=='1'))
        {
            return abort(404);
        }
        $user = array();
        $user = DB::table('usuarios')
                        ->select('personal.no_empleado' , 'usuarios.email', 'personal.nombres' , 'personal.apellidos','posicion.posicion', 'turnos.turno')
                        ->join('personal' , 'usuarios.id_personal' ,'=' ,'personal.id_personal')
                        ->join('posicion' , 'posicion.id_posicion','=','usuarios.id_posicion')
                        ->join('turnos' , 'turnos.id_turno','=','usuarios.id_turno')
                        ->orderBy('personal.no_empleado')
                        ->get();

        $personal = DB::table('personal')
                        ->orderBy('no_empleado','asc')
                        ->get();

        $posicion = DB::table('posicion')
                        ->get();

        $turnos = DB::table('turnos')
                        ->get();

        $disponibles = DB::table('personal')
                        ->select('personal.no_empleado')
                        ->leftJoin('usuarios','personal.id_personal','=','usuarios.id_personal')
                        ->whereNull('usuarios.id_personal')
                        ->orderBy('personal.no_empleado')
                        ->get();

        $user2 = DB::table('usuarios')
                        ->join('personal' , 'usuarios.id_personal' ,'=' ,'personal.id_personal')
                        ->orderBy('personal.no_empleado')
                        ->where('usuarios.id_posicion','=','2')
                        ->get();

        return view('usuarios' , [
            'usuarios' => $user,
            'personal' => $personal,
            'id_disponibles' => $disponibles,
            'posiciones' => $posicion,
            'turnos' => $turnos,
            'numerosDeEmpleados' => $user2
        ]);
    }




    public function actualizarUsuario(Request $request)
    {
        if(!($request->session()->get('auth')=='1'))
        {
            return abort(404);
        }

        $idPersonal = DB::table('personal')
                        ->select('id_personal')
                        ->where('no_empleado','=',$request->no_empleado)
                        ->first();

        $posicion = DB::table('posicion')
                        ->select('id_posicion')
                        ->where('posicion','=',$request->posicion)
                        ->first();
        
        DB::table('usuarios')
            ->where('id_personal','=',$idPersonal->id_personal)
            ->update([
                'id_posicion' => $posicion->id_posicion,
                'email' => $request->email,
            ]);

            $usuarios = DB::table('usuarios')
                            ->select('personal.no_empleado' , 'usuarios.email', 'personal.nombres' , 'personal.apellidos','posicion.posicion', 'turnos.turno')
                            ->join('personal' , 'usuarios.id_personal' ,'=' ,'personal.id_personal')
                            ->join('posicion' , 'posicion.id_posicion','=','usuarios.id_posicion')
                            ->join('turnos' , 'turnos.id_turno','=','usuarios.id_turno')
                            ->orderBy('personal.no_empleado')
                            ->get();

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
            array(
                $request->session()->get('noEmpleado'),
                substr($request->session()->get('nombres'),0,20),
                'Actualizar usuario',
                'usuarios',
                'Actualizar usuario '.$request->no_empleado. ' usando el id_posicion "'.$posicion->id_posicion.'" y el email "'.$request->email.'"',
                'UPDATE',
                $request->ip(),
            ));

        return json_encode($usuarios);
    }




    public function destroyUser($id)
    {
        if(!(request()->session()->get('auth')=='1'))
        {
            return abort(404);
        }

        $idPersonal = DB::table('personal')
                        // ->select('id_personal')
                        ->where('no_empleado','=',$id)
                        ->first();

         DB::table('usuarios')
            ->where('id_personal' , '=' , $idPersonal->id_personal)
            ->delete();

         $usuarios = DB::table('usuarios')
                        ->select('personal.no_empleado' , 'usuarios.email', 'personal.nombres' , 'personal.apellidos','posicion.posicion', 'turnos.turno')
                        ->join('personal' , 'usuarios.id_personal' ,'=' ,'personal.id_personal')
                        ->join('posicion' , 'posicion.id_posicion','=','usuarios.id_posicion')
                        ->join('turnos' , 'turnos.id_turno','=','usuarios.id_turno')
                        ->orderBy('personal.no_empleado')
                        ->get();

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
            array(
                request()->session()->get('noEmpleado'),
                substr(request()->session()->get('nombres'),0,20),
                'Eliminar usuario',
                'usuarios',
                'Eliminar el usuario con no_empleado "'.$idPersonal->no_empleado.'"',
                'DELETE',
                request()->ip(),
            ));
            
        return json_encode($usuarios);
    }


    public function cambiarContrasena(Request $request)
    {
        if(!(request()->session()->get('auth')=='1') && !(request()->session()->get('auth')=='2'))
        {
            return abort(404);
        }

        // $RandomPassword = Crypt::encryptString($cadena);
        // $RandomPassword = Crypt::encryptString('2');

        $idPersonal = DB::table('personal')
                        ->select('id_personal')
                        ->where('no_empleado','=',$request->noEmp)
                        ->first();

        DB::table('usuarios')
            ->where('id_personal','=',$idPersonal->id_personal)
            ->update([
                'contrasena' => Crypt::encryptString($request->contrasena),
            ]);
        // return $request;
        return 'cambios realizados correctamente';
    }




    // Esta funcion envía los correos a los usuarios permitidos
    protected function enviarCorreo($noEmp,$parametros)
    {
        $controlador = DB::table('personal')
                        ->join('usuarios','usuarios.id_personal','=','personal.id_personal')
                        ->where('no_empleado',$noEmp)
                        ->first();

        $administrador = DB::table('personal')
                            ->join('usuarios','usuarios.id_personal','=','personal.id_personal')
                            ->where('id_posicion','1')
                            ->first();

        Mail::to([
                    $administrador->email, 
                    $controlador->email,
                ])
                ->send(new SendMailable(
                    $controlador->nombres." ".$controlador->apellidos,
                    $noEmp,
                    $parametros,
                ));

        return "Mensaje enviado";
    }




    // Si el controlador termino la encuesta entonces muestra una pagina de confirmacion
    public function finalizar(Request $request)
    {
        if($request->session()->get('auth')!='1' && $request->session()->get('auth')!='2')
        {
            return redirect()->route('sistema.inicio');
        }
        $query = DB::table('personal')
                        ->select('id_personal')
                        ->where('no_empleado',$request->session()->get('noEmpleado'))
                        ->first();

        $resultados = DB::table('log_usuarios')
                        ->select(DB::raw('areas_de_preguntas.nombre as area'),'log_usuarios.fecha_creacion',DB::raw('preguntas.contenido as pregunta'),DB::raw('respuestas.contenido as respuesta'))
                        ->join('respuestas','log_usuarios.id_respuesta','=','respuestas.id_respuesta')
                        ->join('preguntas','log_usuarios.id_pregunta','=','preguntas.id_pregunta')
                        ->join('areas_de_preguntas','log_usuarios.id_area','=','areas_de_preguntas.id_area')
                        ->where('log_usuarios.id_personal','=',$query->id_personal)
                        ->whereDate('log_usuarios.fecha_creacion','=',date('Y-m-d'))
                        ->get();

        $alert = 'correo enviado con exito';
        try {
            $this->enviarCorreo($request->session()->get('noEmpleado'),$resultados);

            DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
                array(
                    $request->session()->get('noEmpleado'),
                    substr($request->session()->get('nombres'),0,20),
                    'Enviar correos',
                    'usuarios',
                    'Se envió por correo los resultados de la encuesta del usuario con Numero de empleado '.$request->session()->get('noEmpleado'),
                    'SEND',
                    $request->ip(),
                ));
        } catch (\Throwable $th) {
            $alert = 'Correo no enviado, revise la conexion a internet';
        }

        $request->session()->forget('auth');
        $request->session()->forget('noEmpleado');
        $request->session()->forget('nombreCompleto');
        $request->session()->forget('nombres');
        return $alert;
    }




    //
    public function registroContrasena(Request $request)
    {
        DB::select('call perdidas_de_contrasena_guardar(?)',
        array(
            $request->ip(),
        ));
        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
            array(
                'Vacio',
                'Vacio',
                'Cuardar reporte',
                'perdidas_de_contrasena',
                'Un usuario reporto olvidar la contraseña.',
                'INSERT',
                request()->ip(),
            ));

        return 'guardado con exito';
    }
}






