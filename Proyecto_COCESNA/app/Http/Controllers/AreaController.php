<?php

namespace App\Http\Controllers;

use App\Personal;
use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;      // Importar DB
use Illuminate\Support\Facades\Crypt;   // Encriptar/Desencriptar contraseñas
use Illuminate\Support\Facades\Hash;    // Manejo de hashes
use Mail;
use App\Mail\SendMailable;

class AreaController extends Controller
{




    // Se encarga de mostrar las areas a los controladores
    public function mostrarAreas()
    {
        if(!(request()->session()->get('auth')=='2'))
        {
            return redirect()->route('sistema.inicio');   
        }

        $areas  = array();
        $areas = DB::table('areas_de_preguntas')->get();
        return view('areasPreguntasControlador', [
            'areas'=> $areas
        ]);
    }




    // Guarda una nueva area hecha por el administrador 
    public function agregarArea(Request $request)
    {
        if(!($request->nombre && $request->descripcion))
        {
            // si alguien intenta ingresar usando la ruta directamente, entonces, retorna 404 pagina no encontrada
            return abort(404);
        }

        DB::select('call area_guardar(?,?)',
        array(
            $request->nombre,
            $request->descripcion
        ));

        $areas = DB::table('areas_de_preguntas')->get();

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
        array(
            $request->session()->get('noEmpleado'),
            substr($request->session()->get('nombres'),0,20),
            'Nueva area',
            'areas_de_preguntas',
            'area_guardar',
            'INSERT',
            $request->ip(),
        ));

        return json_encode($areas);
    }




    // Actualiza un area de preguntas
    public function actualizarArea()
    {
        if(!(request()->session()->get('auth')=='1'))
        {
            return abort(404);
        }

        DB::table('areas_de_preguntas')
            ->where('id_area','=',request()->id)
            ->update([
                'nombre' => request()->nombre,
                'descripcion' => request()->descripcion,
            ]);

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
        array(
            request()->session()->get('noEmpleado'),
            substr(request()->session()->get('nombres'),0,20),
            'Actualizar area',
            'areas_de_preguntas',
            'Actualizar area '.request()->id. ' usando el nombre "'.request()->nombre.'" y la descripcion "'.request()->descripcion.'"',
            'UPDATE',
            request()->ip(),
        ));

        $areas = DB::table('areas_de_preguntas')->get();

        return json_encode($areas);
    }




    // Borra un area especifica
    public function borrarArea($id)
    {
        if(!(request()->session()->get('auth')=='1'))
        {
            return abort(404);
        }

        DB::table('log_usuarios')
            ->where('id_area', '=', $id)
            ->delete();
        DB::table('preguntas')
            ->where('id_area', '=', $id)
            ->delete();
        DB::table('areas_de_preguntas')
            ->where('id_area', '=', $id)
            ->delete();
        $areas = DB::table('areas_de_preguntas')
                    ->get();

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
        array(
            request()->session()->get('noEmpleado'),
            substr(request()->session()->get('nombres'),0,20),
            'Borrar area',
            'areas_de_preguntas',
            'Borra el area '.$id,
            'DELETE',
            request()->ip(),
        ));

        return json_encode($areas);
    }




    // Muestra las preguntas de un area, de forma sincrona
    public function paginaPreguntas()
    {
        if(request()->session()->get('auth')=='1')
        {
            $nombreArea = DB::table('areas_de_preguntas')
                            ->select('nombre')
                            ->where('id_area',request()->id)
                            ->first();

            return view('preguntasArea',[
                'id' => request()->id,
                'nombreArea' => $nombreArea->nombre,
            ]);
        } elseif (request()->session()->get('auth')=='2')
        {
            $nombreArea = DB::table('areas_de_preguntas')
                            ->select('nombre')
                            ->where('id_area',request()->id)
                            ->first();

            $preguntas = DB::table('preguntas')
                            ->select(DB::raw('preguntas.contenido as cuest'),'preguntas.id_tipo')
                            ->where('id_area',request()->id)
                            ->get();
            
            $FormatoPreguntas = array();
            foreach ($preguntas as $pregunta) 
            {
                $temp = array();
                $temp['cuest'] = $pregunta->cuest;
                $respuestas = DB::table('respuestas')
                                    ->select('contenido')
                                    ->where('id_tipo',$pregunta->id_tipo)
                                    ->get();
                $respuestas = array_values($respuestas->pluck('contenido')->toArray());
                $temp['respuestas'] = $respuestas;
                array_push($FormatoPreguntas,$temp);
            }
            return view('encuesta',[
                'id' => request()->id,
                'nombreArea' => $nombreArea->nombre,
            ]);
        }
    }




    // Muestra las preguntas de un area, de forma asincrona
    public function verPreguntasAJAX()
    {
        if(request()->session()->get('auth')=='1')
        {
            $preguntas = DB::table('preguntas')
            ->where('id_area',request()->area)->get();
            return $preguntas;

        } else if (request()->session()->get('auth')=='2')
        {
            $nombreArea = DB::table('areas_de_preguntas')
                            ->select('nombre')
                            ->where('id_area',request()->id)
                            ->first();

            $preguntas = DB::table('preguntas')
                            ->select(DB::raw('preguntas.contenido as cuest'),'preguntas.id_tipo')
                            ->where('id_area',request()->id)
                            ->get();
            
            $FormatoPreguntas = array();
            foreach ($preguntas as $pregunta) 
            {
                $temp = array();
                $temp['cuest'] = $pregunta->cuest;
                $respuestas = DB::table('respuestas')
                                    ->select('contenido')
                                    ->where('id_tipo',$pregunta->id_tipo)
                                    ->get();
                $respuestas = array_values($respuestas->pluck('contenido')->toArray());
                $temp['respuestas'] = $respuestas;
                array_push($FormatoPreguntas,$temp);
            }
            return [
                'id' => request()->id,
                'nombreArea' => $nombreArea->nombre,
                'preguntas' => $FormatoPreguntas,
            ];
        }
    }




    // Se guarda una nueva pregunta de un area especifica
    public function agregarPreguntaAJAX()
    {
        if (!(request()->area && request()->tipo && request()->contenido)) 
        {
            return abort(404);
        }

        DB::select('call pregunta_crear(?,?,?)',
        array(
            request()->area,
            request()->tipo,
            request()->contenido,
        ));

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
        array(
            request()->session()->get('noEmpleado'),
            substr(request()->session()->get('nombres'),0,20),
            'Nueva pregunta',
            'preguntas',
            'pregunta_guardar(?,?)',
            'INSERT',
            request()->ip(),
        ));
        return "Agregado con exito";
    }




    // Edita una pregunta especifica
    public function actualizarPregunta()
    {
        if(!(request()->session()->get('auth')=='1'))
        {
            return abort(404);
        }

        DB::table('preguntas')
            ->where('id_pregunta', request()->id)
            ->update([
                'contenido' => request()->contenido,
                'id_tipo' => request()->tipo,
            ]);

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
        array(
            request()->session()->get('noEmpleado'),
            substr(request()->session()->get('nombres'),0,20),
            'Actualizar pregunta',
            'preguntas',
            'Actualizar pregunta '.request()->id. ' usando el contenido "'.request()->contenido.'" y el tipo "'.request()->tipo.'"',
            'UPDATE',
            request()->ip(),
        ));

        return request();
    }




    // Borra una determinada pregunta
    public function borrarPregunta()
    {
        if(!request()->id)
        {
            return abort(404);
        }

        DB::table('preguntas')
            ->where('id_pregunta', request()->id)
            ->delete();

        DB::select('call seglog_guardar(?,?,?,?,?,?,?)',
        array(
            request()->session()->get('noEmpleado'),
            substr(request()->session()->get('nombres'),0,20),
            'Borrar pregunta',
            'preguntas',
            'Borra la pregunta '.request()->id,
            'DELETE',
            request()->ip(),
        ));

        return request();
    }




    // Retorna los tipos de respuesta existentes en la base de datos
    public function obtenerTiposRespuesta()
    {
        if(!(request()->session()->get('auth')=='1'))
        {
            return abort(404);
        }

        $tipos = DB::table('tipos_de_respuesta')
                    ->get();
        return $tipos;
    }




    // Muestra las respuestas de una pregunta
    public function mostrarRespuestasDelTipo()
    {
        if (!request()->id_tipo) 
        {
            return abort(404);
        }

        $elementos = DB::table('respuestas')
                    ->where('id_tipo',request()->id_tipo)
                    ->get();
        return $elementos;
    }




}
