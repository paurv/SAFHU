<?php

namespace App\Http\Controllers;

use App\Personal;                       // Eloquent
use App\Usuarios;                       // Eloquent
use Illuminate\Http\Request;            // Request()
use Illuminate\Support\Facades\DB;      // Importar DB
use Illuminate\Support\Facades\Crypt;   // Encriptar/Desencriptar contraseñas
use Illuminate\Support\Facades\Hash;    // Manejo de hashes
use Mail;                               // Clase Mail
use App\Mail\SendMailable;
use View;

class MainController extends Controller
{



    public function index(Request $request)
    {
        $request->session()->forget('auth');
        $request->session()->forget('noEmpleado');
        $request->session()->forget('nombreCompleto');
        $request->session()->forget('nombres');
        return view('iniciarSesion');
    }




    public function verificar(Request $request)
    {
        // Valida la informacion entrante, de no cumplirse las reglas
        // Regresa a la pagina de iniciar sesion con los errores
        $data = $request->validate([
            'numeroEmpleado' => ['required','exists:personal,no_empleado'], //verifica si existe el numero de empleado en la tabla personal, en la columna no_empleado
            'contrasena' => ['required']
        ],[
            'numeroEmpleado.required' => 'El número de empleado es obligatorio',
            'numeroEmpleado.exists' => 'El número de empleado es inválido',
            'contrasena.required' => 'La contraseña es obligatoria',
        ]);

        /**
         * Metodo 1: Usando cifrado (encrypt/decrypt)
         *  */
        // Busca la contraseña perteneciente al numero de empleado y la desencripta
        // se almacena en la variable $pass
        $usuario = DB::table('usuarios')
                    ->join('personal','usuarios.id_personal','=','personal.id_personal')
                    ->where('no_empleado',$data['numeroEmpleado'])
                    ->first();

        if(!$usuario)
        {
            return back()->withErrors([
                'numeroEmpleado'=>'El número de empleado es inválido',
            ])->withInput();
        }
        $pass = Crypt::decryptString($usuario->contrasena);
        
        // Compara la contraseña ingresada con la obtenida en la consulta, si son iguales
        // entonces ingresa al sistema, sino entonces regresa a iniciar sesion
        // mostrando el error de contraseña incorrecta
        if($pass != $data['contrasena'])
        {
            return back()->withErrors([
                'contrasena'=>'La contraseña es incorrecta',
            ])->withInput();
        }
        else
        {
            $actualDia = date('d/m/Y', time());
            $encuestaCompletada = DB::table('user')
                                    ->where('auth_key','=',$data['numeroEmpleado'])
                                    ->where(DB::raw('DATE_FORMAT(FROM_UNIXTIME(created_at), "%d/%m/%Y")'),'=',$actualDia)
                                    ->first();
            // dd($usuario);
            // si el controlador ya lleno la encuesta y no se le ha permitido una nueva oportunidad entonces no iniciar la encuesta                        
            if($encuestaCompletada != NULL && $usuario->id_posicion == 2 && $usuario->nuevo_intento == 0)
            {
                return view('finEncuesta',[
                    'mensaje0' => 'Encuesta terminada por hoy',
                    'mensaje1' => 'Para nuevos intentos en el dia',
                    'mensaje2' => 'contacte con el administrador del sistema.',
                    'retardo' => '4000',
                ]);
            }
            else
            {
                // si el cualquier usuario tiene permitido una nueva oportunidad entonces reiniciar el atributo y guardar el registro
                if($usuario->nuevo_intento == 1)
                {
                    $idPersonal = DB::table('personal')
                        ->select('id_personal')
                        ->where('no_empleado','=',$data['numeroEmpleado'])
                        ->first();
                        DB::table('usuarios')
                            ->where('id_personal','=',$idPersonal->id_personal)
                            ->update([
                                'nuevo_intento' => 0,
                            ]);
                    DB::select('call reg_entrada_guardar(?,?,?,?,?,?)',
                    array(
                        $usuario->nombres." ".$usuario->apellidos,
                        $data['numeroEmpleado'],
                        Hash::make($usuario->contrasena),
                        $usuario->email,
                        $usuario->activo,
                        $request->_token,
                    ));
                }
                // si es la primera vez en el dia entonces almacenar, es para todos los usuarios
                else if($encuestaCompletada == NULL)
                {
                    DB::select('call reg_entrada_guardar(?,?,?,?,?,?)',
                    array(
                        $usuario->nombres." ".$usuario->apellidos,
                        $data['numeroEmpleado'],
                        Hash::make($usuario->contrasena),
                        $usuario->email,
                        $usuario->activo,
                        $request->_token,
                    ));
                }
            }

            $this->permitirNoEmpleado = '';
            $posicion = $usuario->id_posicion;
            $request->session()->put('noEmpleado',$usuario->no_empleado);
            $request->session()->put('nombres',$usuario->nombres);
            $request->session()->put('nombreCompleto',$usuario->nombres." ".$usuario->apellidos);
            
            $pregFiltro = DB::table('pregunta_filtro')
                                ->latest('id_pregunta')
                                ->first();

            if($posicion == 1)  // Es administrador
            {
                $areas  = array();
                $areas = DB::table('areas_de_preguntas')->get();

                $request->session()->put('auth','1');

                return view('principalAdmin', [
                    'areas'=> $areas,
                    'PreguntaF' => $pregFiltro->pregunta,
                ]);
            }
            else if($posicion == 2) // Es controlador
            {
                $request->session()->put('auth','2');

                return view('preguntaFiltro')->with([
                        'datos' => $data['numeroEmpleado'],
                        'preguntaFiltro' => $pregFiltro->pregunta,
                    ]);   
            }
            else if($posicion == 3) // Es supervisor
            {   
                $request->session()->put('auth','3');

                $user = DB::table('usuarios')
                        ->join('personal' , 'usuarios.id_personal' ,'=' ,'personal.id_personal')
                        ->orderBy('personal.no_empleado')
                        ->where('usuarios.id_posicion','=','2')
                        ->get();


                return view('supervisor')->with([
                    'numerosDeEmpleados' => $user,
                ]);   
            }  
            else if($posicion == 4) // Es RRHH
            {
                $request->session()->put('auth','4');
                return redirect()->route('reportes.mostrar');
            }  
            else{
                return abort(404);
            }
        } 
        /** 
         * Metodo 2: Usando hashes
         *  */
        /*
        $usuario = DB::table('personal')
                    ->where('no_empleado',$data['numeroEmpleado'])
                    ->first();
        // Crea un hash con los datos ingresados 
        // si los hashes coinciden entonces la contraseña es correcta
        if(Hash::check($data['contrasena'],$usuario->contrasena))
        {
            $posicion = $usuario->id_posicion;
            if($posicion == 1)
            {
                return redirect()->route('administrador.principal');
            }
            return redirect()->route('encuesta.preguntaFiltro');   
        }else
        {
            return back()->withErrors([
                'contrasena'=>'La contraseña es incorrecta',
            ])->withInput();
        }    
        */
    }




    // Si es el administrador quien ingreso, entonces cargar las areas de preguntas
    public function ingresarComoAdmin(Request $request)
    {
        if(request()->session()->get('auth')!='1')
        {
            return redirect()->route('sistema.inicio');
        }

        $areas  = array();
        $areas = DB::table('areas_de_preguntas')->get();

        $pregFiltro = DB::table('pregunta_filtro')
                                ->latest('id_pregunta')
                                ->first();

        return view('principalAdmin', [
            'areas'=> $areas,
            'PreguntaF' => $pregFiltro->pregunta,
        ]);
    }
    
    


    // Define que ruta se tomará de acuerdo a la respuesta de la pregunta filtro 
    public function seleccionarEstado()
    {
        if(request()->noEmpleado)
        {
            $query = DB::table('personal')
                        ->select('id_personal')
                        ->where('no_empleado',request()->noEmpleado)
                        ->first();

            $respuesta = request()->btn;

            if ($respuesta)
            {
                $idRespuesta = DB::table('respuestas')
                                ->select('id_respuesta')
                                ->where('contenido','=','si')
                                ->first();
                // dd($idRespuesta->id_respuesta);                
                DB::select('call log_usuarios_guardar(?,?,?,?,?,?,?)',
                array(
                    $query->id_personal,
                    $idRespuesta->id_respuesta,
                    NULL,
                    NULL,
                    NULL,
                    request()->ip(),
                    '1',
                ));
                request()->session()->forget('auth');
                request()->session()->forget('noEmpleado');
                request()->session()->forget('nombreCompleto');
                request()->session()->forget('nombres');
                return view('finEncuesta',[
                    'mensaje0' => 'Fin de encuesta',
                    'mensaje1' => 'Ha finalizado la encuesta',
                    'mensaje2' => 'Gracias por su participación.',
                    'retardo' => '2000',
                ]);
            }
            else
            {
                $idRespuesta = DB::table('respuestas')
                                ->select('id_respuesta')
                                ->where('contenido','=','no')
                                ->first();
                DB::select('call log_usuarios_guardar(?,?,?,?,?,?,?)',
                array(
                    $query->id_personal,
                    $idRespuesta->id_respuesta,
                    NULL,
                    NULL,
                    NULL,
                    request()->ip(),
                    '1',
                ));
                return redirect()->route('encuesta.mostrarAreas');
            }
        } else 
        {
            return abort(404);
        }
    }




    // Almacena las respuestas de los controladores en la base de datos
    public function guardarRespuestas(Request $request)
    {
        if($request->session()->get('auth')!='1' && $request->session()->get('auth')!='2')
        {
            return redirect()->route('sistema.inicio');
        }
        
        $idRespuesta = DB::table('respuestas')
        ->select('id_respuesta')
        ->where('contenido','=', $request->respuesta)
        ->first();

        $idPregunta = DB::table('preguntas')
        ->select('id_pregunta')
        ->where('contenido','=', $request->pregunta)
        ->first();

        $idPersonal = DB::table('personal')
                        ->select('id_personal')
                        ->where('no_empleado',$request->session()->get('noEmpleado'))
                        ->first();


        DB::select('call log_usuarios_guardar(?,?,?,?,?,?,?)',
            array(
                // ,
                $idPersonal->id_personal,
                $idRespuesta->id_respuesta,
                $request->idArea,
                $idPregunta->id_pregunta,
                NULL,
                request()->ip(),
                '0',
            ));

        return json_encode($idPregunta);
    }




    // actualiza la pregunta filtro
    public function actualizarPF(Request $request)
    {
        DB::select('call preg_filtro_crear(?)',
        array(
            $request->pregunta,
        ));
        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
        array(
            request()->session()->get('noEmpleado'),
            substr(request()->session()->get('nombres'),0,20),
            'Actualizar pregunta filtro',
            'pregunta_filtro',
            'Actualizar pregunta filtro a: '.$request->pregunta,
            'UPDATE',
            request()->ip(),
        ));
        return 1;
    }




    // Permite una nueva oportunidad de llenar una encuesta
    public function nuevaOportunidad(Request $request)
    {
        $idPersonal = DB::table('personal')
                        ->select('id_personal')
                        ->where('no_empleado','=',$request->no_empleado)
                        ->first();

        DB::table('usuarios')
            ->where('id_personal','=',$idPersonal->id_personal)
            ->update([
                'nuevo_intento' => 1,
            ]);
        
        return 1;
    }



    // Muestra todas las acciones realizadas en el sistema
    public function verLogs()
    {
        $registros = DB::table('seglog')
                        ->orderBy('segLogFecha','DESC')
                        ->orderBy('segLogHora','DESC')
                        ->get();

        return view('logUsuarios' ,[
            'registros'=> $registros
        ] 
       );
   }



   // LLena la encuesta de un controlador
   public function llenarEncuesta(Request $request)
   {
        $request->noEmpleado = substr($request->noEmpleado,0,3);
        $idPersonal = DB::table('personal')
                        ->select('id_personal')
                        ->where('no_empleado','=',$request->noEmpleado)
                        ->first();

        DB::table('usuarios')
            ->where('id_personal','=',$idPersonal->id_personal)
            ->update([
                'nuevo_intento' => 0,
            ]);
        
        DB::table('razones')
            ->insert(
                array(
                    'razon' => $request->razon,
                    'fecha_creacion' => date("Y-m-d H:i:s"),
                )
            );

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
            array(
                request()->session()->get('noEmpleado'),
                substr(request()->session()->get('nombres'),0,20),
                'Ingresa razon de llenar encuesta',
                'razones',
                'Razon: '.$request->razon,
                'INSERT',
                request()->ip(),
            ));
                
        $usuario = DB::table('usuarios')
                ->join('personal','usuarios.id_personal','=','personal.id_personal')
                ->where('no_empleado',$request->noEmpleado)
                ->first();

        $pregFiltro = DB::table('pregunta_filtro')
                ->latest('id_pregunta')
                ->first();

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
        array(
            request()->session()->get('noEmpleado'),
            substr(request()->session()->get('nombres'),0,20),
            'Llenar encuesta de un controlador',
            'razones',
            'Se llena la encuesta del controlador: '.$usuario->no_empleado,
            'INSERT',
            request()->ip(),
        ));

        $request->session()->put('noEmpleado',$usuario->no_empleado);
        $request->session()->put('nombres',$usuario->nombres);
        $request->session()->put('nombreCompleto',$usuario->nombres." ".$usuario->apellidos);
        $request->session()->put('auth','2');

        return view('preguntaFiltro')->with([
                'datos' => $request->noEmpleado,
                'preguntaFiltro' => $pregFiltro->pregunta,
            ]);
   }



   // Acerca de
   public function acerca_de()
   {
       return view('acercaDe');
   }
}
