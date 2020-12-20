<?php

namespace App\Http\Controllers;

use App\Personal;
use App\Usuarios;
use App\Charts\UsersChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;      // Importar DB
use Illuminate\Support\Facades\Crypt;   // Encriptar/Desencriptar contraseñas
use Illuminate\Support\Facades\Hash;    // Manejo de hashes
use Carbon\Carbon;

class ReportesController extends Controller
{




    // Permite obtener determinada cantidad de colores aleatorios
    protected function colores_aleatorios($cantidad)
    {
        $colores = collect();
        for ($i=0; $i < $cantidad; $i++) 
        { 
            $colores->push('#'.$this->random_color());
        }
        return $colores;
    }
    protected function random_color() 
    {
        return $this->random_color_part().$this->random_color_part().$this->random_color_part();
    }
    protected function random_color_part() 
    {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }




    // Muestra determinados reportes
    public function mostrar(Request $request)
    {
        $usr = $request->session()->get('auth');
        // dd($usr);
        if (($usr!='1') && ($usr!='4')) // si no esta autorizado
        {
            return redirect()->route('sistema.inicio');
        } 
        else if (!($request->fecha_inicio) && !($request->fecha_fin) /*|| (!($request->fecha_inicio) && $request->fecha_fin)*/) // si no lleva filtrado por fecha
        {
            // dd(2);
            $request->session()->forget('fecha_inicio');
            $request->session()->forget('fecha_fin');
            $contestaSI = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM log_usuarios
                            WHERE es_pregunta_filtro = 1 
                            AND id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "si"
                            )
                        ')))->count();
            
            $contestaNO = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM log_usuarios
                            WHERE es_pregunta_filtro = 1 
                            AND id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "no"
                            )
                        ')))->count();

            $PregResArea = collect(DB::select(DB::raw('
                            SELECT a.nombre, IFNULL(COUNT(*),0) as total
                            FROM areas_de_preguntas a INNER JOIN log_usuarios b
                            ON a.id_area = b.id_area
                            GROUP BY a.nombre
                        ')));

            $horasUso = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(created_at), "%H") as hora, IFNULL(COUNT(*),0) as total
                            FROM user
                            GROUP BY hora
                        ')));

            $usrMes = collect(DB::select(DB::raw('
                            SELECT username, IFNULL(COUNT(*),0) as total
                            FROM user
                            GROUP BY username
                        ')));

            $usoMes = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(created_at), "%m") as mes, IFNULL(COUNT(*),0) as total
                            FROM user
                            GROUP BY mes
                        ')));

            $PerConRep = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(id_perdida),0) as total
                            FROM perdidas_de_contrasena
                        ')))->first();

            $ModPreFil = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(id_pregunta),0) as total
                            FROM pregunta_filtro
                            WHERE id_pregunta > 1
                        ')))->first();

            $RazEncCon = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(id_razon),0) as total
                            FROM razones
                        ')))->first();
            
        }
        else if ($request->fecha_inicio && !($request->fecha_fin)) // si lleva solo la fecha de inicio
        {
            // dd($request);
            $request->session()->flash('fecha_inicio',$request->fecha_inicio);
            $contestaSI = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM (
                                SELECT *
                                FROM log_usuarios
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND NOW()
                            ) a
                            WHERE a.es_pregunta_filtro = 1 
                            AND a.id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "si"
                            )
                        ')))->count();

            $contestaNO = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM (
                                SELECT *
                                FROM log_usuarios
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND NOW()
                            ) a
                            WHERE a.es_pregunta_filtro = 1 
                            AND a.id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "no"
                            )
                        ')))->count();

            $PregResArea = collect(DB::select(DB::raw('
                            SELECT a.nombre, IFNULL(COUNT(*),0) as total
                            FROM areas_de_preguntas a INNER JOIN (
                                SELECT * 
                                FROM log_usuarios
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND NOW()
                            ) b
                            ON a.id_area = b.id_area
                            GROUP BY a.nombre
                        ')));

            $horasUso = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%H") as hora, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at
                                BETWEEN UNIX_TIMESTAMP("'.$request->fecha_inicio.'")
                                AND NOW()
                            ) a
                            GROUP BY hora
                        ')));

            $usrMes = collect(DB::select(DB::raw('
                            SELECT a.username, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at
                                BETWEEN UNIX_TIMESTAMP("'.$request->fecha_inicio.'")
                                AND NOW()
                            ) a
                            GROUP BY a.username
                        ')));

            $usoMes = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%m") as mes, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at
                                BETWEEN UNIX_TIMESTAMP("'.$request->fecha_inicio.'")
                                AND NOW()
                            ) a
                            GROUP BY mes
                        ')));

            $PerConRep = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(a.id_perdida),0) as total
                            FROM (
                                SELECT * 
                                FROM perdidas_de_contrasena
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND NOW()
                            ) a
                        ')))->first();

            $ModPreFil = collect(DB::select(DB::raw('
                            SELECT count(a.id_pregunta) as total
                            FROM (
                                SELECT *
                                FROM pregunta_filtro
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND NOW()
                                AND id_pregunta > 1 
                            ) a
                        ')))->first();

            $RazEncCon = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(a.id_razon),0) as total
                            FROM (
                                SELECT *
                                FROM razones
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND NOW()
                            ) a
                        ')))->first();
        }
        else if (!($request->fecha_inicio) && $request->fecha_fin) // si lleva solo la fecha fin
        {
            // dd($request);
            $request->session()->flash('fecha_fin',$request->fecha_fin);
            $contestaSI = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM (
                                SELECT *
                                FROM log_usuarios
                                WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                            ) a
                            WHERE a.es_pregunta_filtro = 1 
                            AND a.id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "si"
                            )
                        ')))->count();

            $contestaNO = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM (
                                SELECT *
                                FROM log_usuarios
                                WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                            ) a
                            WHERE a.es_pregunta_filtro = 1 
                            AND a.id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "no"
                            )
                        ')))->count();

            $PregResArea = collect(DB::select(DB::raw('
                            SELECT a.nombre, IFNULL(COUNT(*),0) as total
                            FROM areas_de_preguntas a INNER JOIN (
                                SELECT * 
                                FROM log_usuarios
                                WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                            ) b
                            ON a.id_area = b.id_area
                            GROUP BY a.nombre
                        ')));

            $horasUso = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%H") as hora, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at <= UNIX_TIMESTAMP("'.$request->fecha_fin.'")
                            ) a
                            GROUP BY hora
                        ')));

            $usrMes = collect(DB::select(DB::raw('
                            SELECT a.username, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at <= UNIX_TIMESTAMP("'.$request->fecha_fin.'")
                            ) a
                            GROUP BY a.username
                        ')));

            $usoMes = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%m") as mes, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at <= UNIX_TIMESTAMP("'.$request->fecha_fin.'")
                            ) a
                            GROUP BY mes
                        ')));

            $PerConRep = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(a.id_perdida),0) as total
                            FROM (
                                SELECT * 
                                FROM perdidas_de_contrasena
                                WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                            ) a
                        ')))->first();

            $ModPreFil = collect(DB::select(DB::raw('
                            SELECT count(a.id_pregunta) as total
                            FROM (
                                SELECT *
                                FROM pregunta_filtro
                                WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                                AND id_pregunta > 1 
                            ) a
                        ')))->first();

            $RazEncCon = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(a.id_razon),0) as total
                            FROM (
                                SELECT *
                                FROM razones
                                WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                            ) a
                        ')))->first();
        }
        else if ($request->fecha_inicio && $request->fecha_fin) // si lleva ambas fechas
        {
            $request->session()->flash('fecha_inicio',$request->fecha_inicio);
            $request->session()->flash('fecha_fin',$request->fecha_fin);
            $contestaSI = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM (
                                SELECT *
                                FROM log_usuarios
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND "'.$request->fecha_fin.'"
                            ) a
                            WHERE a.es_pregunta_filtro = 1 
                            AND a.id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "si"
                            )
                        ')))->count();
            $contestaNO = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM (
                                SELECT *
                                FROM log_usuarios
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND "'.$request->fecha_fin.'"
                            ) a
                            WHERE a.es_pregunta_filtro = 1 
                            AND a.id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "no"
                            )
                        ')))->count();

            $PregResArea = collect(DB::select(DB::raw('
                            SELECT a.nombre, IFNULL(COUNT(*),0) as total
                            FROM areas_de_preguntas a INNER JOIN (
                                SELECT * 
                                FROM log_usuarios
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND "'.$request->fecha_fin.'"
                            ) b
                            ON a.id_area = b.id_area
                            GROUP BY a.nombre
                        ')));

            $horasUso = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%H") as hora, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at
                                BETWEEN UNIX_TIMESTAMP("'.$request->fecha_inicio.'")
                                AND UNIX_TIMESTAMP("'.$request->fecha_fin.'")
                            ) a
                            GROUP BY hora
                        ')));

            $usrMes = collect(DB::select(DB::raw('
                            SELECT a.username, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at
                                BETWEEN UNIX_TIMESTAMP("'.$request->fecha_inicio.'")
                                AND UNIX_TIMESTAMP("'.$request->fecha_fin.'")
                            ) a
                            GROUP BY a.username
                        ')));

            $usoMes = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%m") as mes, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at
                                BETWEEN UNIX_TIMESTAMP("'.$request->fecha_inicio.'")
                                AND UNIX_TIMESTAMP("'.$request->fecha_fin.'")
                            ) a
                            GROUP BY mes
                        ')));

                        $PerConRep = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(a.id_perdida),0) as total
                            FROM (
                                SELECT * 
                                FROM perdidas_de_contrasena
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND "'.$request->fecha_fin.'"
                            ) a
                        ')))->first();

            $ModPreFil = collect(DB::select(DB::raw('
                            SELECT count(a.id_pregunta) as total
                            FROM (
                                SELECT *
                                FROM pregunta_filtro
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND "'.$request->fecha_fin.'"
                                AND id_pregunta > 1 
                            ) a
                        ')))->first();

            $RazEncCon = collect(DB::select(DB::raw('
                            SELECT IFNULL(count(a.id_razon),0) as total
                            FROM (
                                SELECT *
                                FROM razones
                                WHERE fecha_creacion
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND "'.$request->fecha_fin.'"
                            ) a
                        ')))->first();
        }
        else
        {
            return abort(404);
        }
        



        // Para el cuarto grafico se cuenta las veces que han respondido "si" y "no" en la 
        // pregunta filtro
        $etiquetas = ['Contestaron "SI"','Contestaron "NO"'];
        $datos1 = [$contestaSI, $contestaNO];
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chart1 = new UsersChart();
        $chart1->labels($etiquetas);
        $dataset = $chart1->dataset('Si/No','pie',$datos1);
        $dataset->backgroundColor($colores);
        $chart1->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => true,
                'text' => 'Cantidad de veces que han respondido "si" y "no" a la pregunta filtro'
            ]),
        ]);

        

        // para el segundo grafico se obtiene cuantas personas hay de cada posicion
        $posiciones = DB::table('posicion')
                        ->select('posicion')
                        ->get();
        $usrPos = collect(DB::select(DB::raw('
                        SELECT a.id_posicion, IFNULL(COUNT(b.id_posicion),0) as total
                        FROM (
                            SELECT *
                            FROM posicion
                        ) a LEFT JOIN usuarios b
                        ON a.id_posicion = b.id_posicion
                        GROUP BY id_posicion
                    ')));
        $posiciones = array_values($posiciones->pluck('posicion')->toArray());
        $usrPos = array_values($usrPos->pluck('total')->toArray());
        $colores = $this->colores_aleatorios(count($posiciones));
        $chart2 = new UsersChart();
        $chart2->labels($posiciones);
        $dataset = $chart2->dataset('','bar',$usrPos);
        $dataset->backgroundColor($colores);
        $chart2->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => 'true',
                'text' => 'Cantidad de usuarios por posición'
            ]),
        ]);



        // para el tercer grafico se obtiene cuantos usuarios hay de cada turno
        $turnos = DB::table('turnos')
                        ->select('turno')
                        ->get();
        $usrTur = collect(DB::select(DB::raw('
                                    SELECT a.id_turno, IFNULL(COUNT(b.id_turno),0) as total
                                    FROM turnos a LEFT JOIN usuarios b
                                    ON a.id_turno = b.id_turno
                                    GROUP BY id_turno
                                ')));
        $turnos = array_values($turnos->pluck('turno')->toArray());
        $usrTur = array_values($usrTur->pluck('total')->toArray());
        $colores = $this->colores_aleatorios(count($turnos));
        $chart3 = new UsersChart();
        $chart3->labels($turnos);
        $dataset = $chart3->dataset('','bar',$usrTur);
        $dataset->backgroundColor($colores);
        $chart3->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => 'true',
                'text' => 'Cantidad de usuarios por turno'
            ]),
        ]);



        // Para el cuarto grafico se cuenta las preguntas que tiene cada area 
        $PregArea = DB::table('preguntas')
                            ->select('areas_de_preguntas.nombre',DB::raw('count(*) as total'))
                            ->join('areas_de_preguntas','preguntas.id_area','=','areas_de_preguntas.id_area')
                            ->groupBy('areas_de_preguntas.id_area')
                            ->get();
        $etiquetas = array_values($PregArea->pluck('nombre')->toArray());
        $PregArea = array_values($PregArea->pluck('total')->toArray());
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chart4 = new UsersChart();
        $chart4->labels($etiquetas);
        $dataset = $chart4->dataset('df','polarArea',$PregArea);
        $dataset->backgroundColor($colores);
        $chart4->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => true,
                'text' => 'Cantidad de preguntas por area'
            ]),
        ]);



        // Para el quinto grafico se cuenta las preguntas respondidas por áreas 
        $etiquetas = array_values($PregResArea->pluck('nombre')->toArray());
        $PregResArea = array_values($PregResArea->pluck('total')->toArray());
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chart5 = new UsersChart();
        $chart5->labels($etiquetas);
        $dataset = $chart5->dataset('sd','doughnut',$PregResArea);
        $dataset->backgroundColor($colores);
        $dataset->color($colores);
        $chart5->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => true,
                'text' => 'Cantidad de preguntas respondidas por área'
            ]),
        ]);



        // Para el sexto grafico se muestra las horas que mas se usa el sistema 
        $etiquetas = array_values($horasUso->pluck('hora')->toArray());
        $horasUso = array_values($horasUso->pluck('total')->toArray());
        $chart6 = new UsersChart();
        $chart6->labels($etiquetas);
        $dataset = $chart6->dataset('Ingresos','line',$horasUso);
        $dataset->color($this->colores_aleatorios(1));
        $dataset->lineTension(0.1);
        if(count($horasUso)==0){ goto chart7;}
        $chart6->options([
            'legend' =>[
                'display' => false,
            ],
            'title' => collect([
                'display' => true,
                'text' => 'Horas de mayor uso del sistema'
            ]),
            'scales' => [
                'yAxes' => array(collect([
                    'ticks' => [ 
                        'suggestedMax' => max($horasUso) + ((max($horasUso))/100)*10,
                        'suggestedMin' => 0
                    ]
                ]))
            ],
        ]);



        // Para el septimo grafico se muestra las veces que cada usuario ha ingresado al sistema 
        chart7:
        $etiquetas = array_values($usrMes->pluck('username')->toArray());
        $usrMes = array_values($usrMes->pluck('total')->toArray());
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chart7 = new UsersChart();
        $chart7->labels($etiquetas);
        $dataset = $chart7->dataset('','pie',$usrMes);
        $dataset->backgroundColor($colores);
        $dataset->color($colores);
        $chart7->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => true,
                'text' => 'Ingresos al sistema por usuario'
            ]),
        ]);
        
        

        // Para el octavo grafico se muestra el uso del sistema por meses 
        $etiquetas = array_values($usoMes->pluck('mes')->toArray());
        $usoMes = array_values($usoMes->pluck('total')->toArray());
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chart8 = new UsersChart();
        $chart8->labels($etiquetas);
        $dataset = $chart8->dataset('Horas de entrada guardadas','bar',$usoMes);
        $dataset->backgroundColor($colores);
        $chart8->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => true,
                'text' => 'Uso del sistema por meses'
            ]),
        ]);

        // Para el noveno grafico se muestra las cantidades de registros de las tablas aisladas 
        $etiquetas = ['Perdidas de contraseñas reportadas','Veces que la pregunta filtro ha sido modificada','Razones para llenar la encuesta de un controlador'];
        $ejeY = [$PerConRep->total,$ModPreFil->total,$RazEncCon->total];
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chart9 = new UsersChart();
        $chart9->labels($etiquetas);
        $dataset = $chart9->dataset('','bar',$ejeY);
        $dataset->backgroundColor($colores);
        $chart9->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => true,
                'text' => 'Otros datos importantes'
            ]),
        ]);
        

        if ($request->session()->get('auth')=='1') {
            return view('reportesCreados',[
                'reportes' => compact(
                    'chart1','chart2',
                    'chart3','chart4',
                    'chart5','chart6',
                    'chart7','chart8',
                    'chart9'
                ) 
            ]);
        } else {
            return view('reportesParaRRHH',[
                'reportes' => compact(
                    'chart1','chart2',
                    'chart3','chart4',
                    'chart5','chart6',
                    'chart7','chart8',
                    'chart9'
                ) 
            ]);
        }
    }





    // Muestra reportes e informacion resumida sobre un usuario
    public function estadisticas(Request $request)
    {
        // dd($request);
        $usr = $request->session()->get('auth');
        if (($usr!='1')) // si no esta autorizado
        {
            return redirect()->route('sistema.inicio');
        }
        else
        {
            // Informacion resumida
            $user = DB::table('usuarios')
                    ->join('personal' , 'usuarios.id_personal' ,'=' ,'personal.id_personal')
                    ->join('posicion' , 'posicion.id_posicion','=','usuarios.id_posicion')
                    ->join('turnos' , 'turnos.id_turno','=','usuarios.id_turno')
                    ->where('personal.no_empleado','=',$request->estadisticaEmpleado)
                    ->orderBy('personal.no_empleado')
                    ->first();
            $idPersonal = DB::table('personal')
                    ->where('no_empleado','=',$request->estadisticaEmpleado)
                    ->first();
                    // dd($request->estadisticaEmpleado);
            $preRes = DB::table('log_usuarios')
                    ->where('id_personal','=',$idPersonal->id_personal)
                    ->count();
        }



        if (!($request->fecha_inicio) && !($request->fecha_fin)) // si no lleva filtrado por fecha
        {
            $request->session()->forget('fecha_inicio');
            $request->session()->forget('fecha_fin');

            $areasRes = collect(DB::select(DB::raw('
                        SELECT SUM(aa.total) as total_seleccionadas
                        from(
                            SELECT a.nombre, ROUND(IFNULL(IFNULL(COUNT(b.id_area),0)/c.total_preguntas,0),0) as total
                            FROM areas_de_preguntas a 
                            LEFT JOIN (SELECT * 
                                    FROM log_usuarios 
                                    WHERE id_personal = '.$idPersonal->id_personal.') b 
                            on a.id_area = b.id_area
                            LEFT JOIN (SELECT id_area, IFNULL(COUNT(*),0) as total_preguntas 
                                    FROM preguntas 
                                    GROUP BY id_area) c 
                            on a.id_area = c.id_area
                            GROUP BY a.id_area
                        ) aa
                    ')))->first();

            $correosEnviados = DB::table('seglog')
                                ->where('SegUsrKey','=',$idPersonal->no_empleado)
                                ->where('SegLogComando','=','SEND')
                                ->count();

            $contestaSI = DB::table('log_usuarios')
                            ->where('id_personal','=',$idPersonal->id_personal)
                            ->where('es_pregunta_filtro','=','1')
                            ->where('id_respuesta','=',DB::raw('(select id_respuesta from respuestas where contenido = "si")'))
                            ->count();
                            
            $contestaNO = DB::table('log_usuarios')
                            ->where('id_personal','=',$idPersonal->id_personal)
                            ->where('es_pregunta_filtro','=','1')
                            ->where('id_respuesta','=',DB::raw('(select id_respuesta from respuestas where contenido = "no")'))
                            ->count();

            $r2  = collect(DB::select(DB::raw('
                            SELECT a.nombre, ROUND(IFNULL(IFNULL(COUNT(b.id_area),0)/c.total_preguntas,0),0) as total
                            FROM areas_de_preguntas a 
                            LEFT JOIN (SELECT * 
                                    FROM log_usuarios 
                                    WHERE id_personal = '.$idPersonal->id_personal.') b 
                            on a.id_area = b.id_area
                            LEFT JOIN (SELECT id_area, IFNULL(COUNT(*),0) as total_preguntas 
                                    FROM preguntas 
                                    GROUP BY id_area) c 
                            on a.id_area = c.id_area
                            GROUP BY a.id_area
                        ')));

            $preResDia = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));

            $HorEnt = DB::table('user')
                            ->select(DB::raw('DATE_FORMAT(FROM_UNIXTIME(created_at), "%H") as hora'),DB::raw('count(*) as total'))
                            ->where('auth_key','=',$idPersonal->no_empleado)
                            ->groupBy('hora')
                            ->get();
            
            $PregDiaSi  = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        AND es_pregunta_filtro = 1
                                        AND id_respuesta = ( select id_respuesta from respuestas where contenido = "si" )
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));

            $PreDiaNo  = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        AND es_pregunta_filtro = 1
                                        AND id_respuesta = ( select id_respuesta from respuestas where contenido = "no" )
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));
        }
        else if ($request->fecha_inicio && !($request->fecha_fin)) // si lleva solo la fecha de inicio
        {
            $request->session()->flash('fecha_inicio',$request->fecha_inicio);

            $areasRes = collect(DB::select(DB::raw('
                        SELECT SUM(aa.total) as total_seleccionadas
                        from(
                            SELECT a.nombre, ROUND(IFNULL(IFNULL(COUNT(b.id_area),0)/c.total_preguntas,0),0) as total
                            FROM areas_de_preguntas a 
                            LEFT JOIN (SELECT * 
                                    FROM log_usuarios 
                                    WHERE id_personal = '.$idPersonal->id_personal.'
                                    AND fecha_creacion >= "'.$request->fecha_inicio.'"
                                ) b 
                            on a.id_area = b.id_area
                            LEFT JOIN (SELECT id_area, IFNULL(COUNT(*),0) as total_preguntas 
                                    FROM preguntas 
                                    GROUP BY id_area) c 
                            on a.id_area = c.id_area
                            GROUP BY a.id_area
                        ) aa
                    ')))->first();

            $correosEnviados = collect(DB::select(DB::raw('
                                SELECT *
                                FROM seglog
                                WHERE SegUsrKey = '.$idPersonal->no_empleado.'
                                AND SegLogComando = "SEND"
                                AND SegLogFecha >= "'.$request->fecha_inicio.'"
            ')))->count();

            $contestaSI = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM log_usuarios
                            WHERE es_pregunta_filtro = 1 
                            AND id_personal = '.$idPersonal->id_personal.'
                            AND fecha_creacion >= "'.$request->fecha_inicio.'"
                            AND id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "si"
                            )
                        ')))->count();
            
            $contestaNO = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM log_usuarios
                            WHERE es_pregunta_filtro = 1 
                            AND id_personal = '.$idPersonal->id_personal.'
                            AND fecha_creacion >= "'.$request->fecha_inicio.'"
                            AND id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "no"
                            )
                        ')))->count();

            $r2  = collect(DB::select(DB::raw('
                            SELECT a.nombre, ROUND(IFNULL(IFNULL(COUNT(b.id_area),0)/c.total_preguntas,0),0) as total
                            FROM areas_de_preguntas a 
                            LEFT JOIN (SELECT * 
                                    FROM log_usuarios 
                                    WHERE id_personal = '.$idPersonal->id_personal.'
                                    AND fecha_creacion >= "'.$request->fecha_inicio.'"
                                    ) b 
                            on a.id_area = b.id_area
                            LEFT JOIN (SELECT id_area, IFNULL(COUNT(*),0) as total_preguntas 
                                    FROM preguntas 
                                    GROUP BY id_area) c 
                            on a.id_area = c.id_area
                            GROUP BY a.id_area
                        ')));

            $preResDia = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion >= "'.$request->fecha_inicio.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));

            $HorEnt = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%H") as hora, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at >= UNIX_TIMESTAMP("'.$request->fecha_inicio.'")
                                AND auth_key = "'.$idPersonal->no_empleado.'"
                            ) a
                            GROUP BY hora
                        ')));

            $PregDiaSi  = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion >= "'.$request->fecha_inicio.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        AND es_pregunta_filtro = 1
                                        AND id_respuesta = ( select id_respuesta from respuestas where contenido = "si" )
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));

            $PreDiaNo  = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion >= "'.$request->fecha_inicio.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        AND es_pregunta_filtro = 1
                                        AND id_respuesta = ( select id_respuesta from respuestas where contenido = "no" )
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));
        }
        else if (!($request->fecha_inicio) && $request->fecha_fin) // si lleva solo la fecha fin
        {
            $request->session()->flash('fecha_fin',$request->fecha_fin);
            
            $areasRes = collect(DB::select(DB::raw('
                        SELECT SUM(aa.total) as total_seleccionadas
                        from(
                            SELECT a.nombre, ROUND(IFNULL(IFNULL(COUNT(b.id_area),0)/c.total_preguntas,0),0) as total
                            FROM areas_de_preguntas a 
                            LEFT JOIN (SELECT * 
                                    FROM log_usuarios 
                                    WHERE id_personal = '.$idPersonal->id_personal.'
                                    AND fecha_creacion <= "'.$request->fecha_fin.'"
                                ) b 
                            on a.id_area = b.id_area
                            LEFT JOIN (SELECT id_area, IFNULL(COUNT(*),0) as total_preguntas 
                                    FROM preguntas 
                                    GROUP BY id_area) c 
                            on a.id_area = c.id_area
                            GROUP BY a.id_area
                        ) aa
                    ')))->first();

            $correosEnviados = collect(DB::select(DB::raw('
                                SELECT *
                                FROM seglog
                                WHERE SegUsrKey = '.$idPersonal->no_empleado.'
                                AND SegLogComando = "SEND"
                                AND SegLogFecha <= "'.$request->fecha_fin.'"
            ')))->count();

            $contestaSI = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM log_usuarios
                            WHERE es_pregunta_filtro = 1 
                            AND id_personal = '.$idPersonal->id_personal.'
                            AND fecha_creacion <= "'.$request->fecha_fin.'"
                            AND id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "si"
                            )
                        ')))->count();
            
            $contestaNO = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM log_usuarios
                            WHERE es_pregunta_filtro = 1 
                            AND id_personal = '.$idPersonal->id_personal.'
                            AND fecha_creacion <= "'.$request->fecha_fin.'"
                            AND id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "no"
                            )
                        ')))->count();

            $r2  = collect(DB::select(DB::raw('
                            SELECT a.nombre, ROUND(IFNULL(IFNULL(COUNT(b.id_area),0)/c.total_preguntas,0),0) as total
                            FROM areas_de_preguntas a 
                            LEFT JOIN (SELECT * 
                                    FROM log_usuarios 
                                    WHERE id_personal = '.$idPersonal->id_personal.'
                                    AND fecha_creacion <= "'.$request->fecha_fin.'"
                                    ) b 
                            on a.id_area = b.id_area
                            LEFT JOIN (SELECT id_area, IFNULL(COUNT(*),0) as total_preguntas 
                                    FROM preguntas 
                                    GROUP BY id_area) c 
                            on a.id_area = c.id_area
                            GROUP BY a.id_area
                        ')));

            $preResDia = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));

            $HorEnt = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%H") as hora, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at <= UNIX_TIMESTAMP("'.$request->fecha_fin.'")
                                AND auth_key = "'.$idPersonal->no_empleado.'"
                            ) a
                            GROUP BY hora
                        ')));

            $PregDiaSi  = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        AND es_pregunta_filtro = 1
                                        AND id_respuesta = ( select id_respuesta from respuestas where contenido = "si" )
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));

            $PreDiaNo  = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion <= "'.$request->fecha_fin.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        AND es_pregunta_filtro = 1
                                        AND id_respuesta = ( select id_respuesta from respuestas where contenido = "no" )
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));
        }
        else if ($request->fecha_inicio && $request->fecha_fin) // si lleva ambas fechas
        {
            $request->session()->flash('fecha_inicio',$request->fecha_inicio);
            $request->session()->flash('fecha_fin',$request->fecha_fin);

            $areasRes = collect(DB::select(DB::raw('
                        SELECT SUM(aa.total) as total_seleccionadas
                        from(
                            SELECT a.nombre, ROUND(IFNULL(IFNULL(COUNT(b.id_area),0)/c.total_preguntas,0),0) as total
                            FROM areas_de_preguntas a 
                            LEFT JOIN (SELECT * 
                                    FROM log_usuarios 
                                    WHERE id_personal = '.$idPersonal->id_personal.'
                                    AND fecha_creacion 
                                    BETWEEN "'.$request->fecha_inicio.'"
                                    AND "'.$request->fecha_fin.'"
                                ) b 
                            on a.id_area = b.id_area
                            LEFT JOIN (SELECT id_area, IFNULL(COUNT(*),0) as total_preguntas 
                                    FROM preguntas 
                                    GROUP BY id_area) c 
                            on a.id_area = c.id_area
                            GROUP BY a.id_area
                        ) aa
                    ')))->first();

            $correosEnviados = collect(DB::select(DB::raw('
                                SELECT *
                                FROM seglog
                                WHERE SegUsrKey = '.$idPersonal->no_empleado.'
                                AND SegLogComando = "SEND"
                                AND SegLogFecha 
                                BETWEEN "'.$request->fecha_inicio.'"
                                AND "'.$request->fecha_fin.'"
            ')))->count();

            $contestaSI = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM log_usuarios
                            WHERE es_pregunta_filtro = 1 
                            AND id_personal = '.$idPersonal->id_personal.'
                            AND fecha_creacion BETWEEN "'.$request->fecha_inicio.'" AND "'.$request->fecha_fin.'"
                            AND id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "si"
                            )
                        ')))->count();
            
            $contestaNO = collect(DB::select(DB::raw('
                            SELECT * 
                            FROM log_usuarios
                            WHERE es_pregunta_filtro = 1 
                            AND id_personal = '.$idPersonal->id_personal.'
                            AND fecha_creacion BETWEEN "'.$request->fecha_inicio.'" AND "'.$request->fecha_fin.'"
                            AND id_respuesta = (
                                SELECT id_respuesta 
                                FROM respuestas 
                                WHERE contenido = "no"
                            )
                        ')))->count();

            $r2  = collect(DB::select(DB::raw('
                            SELECT a.nombre, ROUND(IFNULL(IFNULL(COUNT(b.id_area),0)/c.total_preguntas,0),0) as total
                            FROM areas_de_preguntas a 
                            LEFT JOIN (SELECT * 
                                    FROM log_usuarios 
                                    WHERE id_personal = '.$idPersonal->id_personal.'
                                    AND fecha_creacion BETWEEN "'.$request->fecha_inicio.'" AND "'.$request->fecha_fin.'"
                                    ) b 
                            on a.id_area = b.id_area
                            LEFT JOIN (SELECT id_area, IFNULL(COUNT(*),0) as total_preguntas 
                                    FROM preguntas 
                                    GROUP BY id_area) c 
                            on a.id_area = c.id_area
                            GROUP BY a.id_area
                        ')));

            $preResDia = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion BETWEEN "'.$request->fecha_inicio.'" AND "'.$request->fecha_fin.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));

            $HorEnt = collect(DB::select(DB::raw('
                            SELECT DATE_FORMAT(FROM_UNIXTIME(a.created_at), "%H") as hora, IFNULL(COUNT(*),0) as total
                            FROM (
                                SELECT * 
                                FROM user
                                WHERE created_at BETWEEN UNIX_TIMESTAMP("'.$request->fecha_inicio.'") AND UNIX_TIMESTAMP("'.$request->fecha_fin.'")
                                AND auth_key = "'.$idPersonal->no_empleado.'"
                            ) a
                            GROUP BY hora
                        ')));

            $PregDiaSi  = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion BETWEEN "'.$request->fecha_inicio.'" AND "'.$request->fecha_fin.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        AND es_pregunta_filtro = 1
                                        AND id_respuesta = ( select id_respuesta from respuestas where contenido = "si" )
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));

            $PreDiaNo  = collect(DB::select(DB::raw('
                                SELECT a.dia, IFNULL(COUNT(b.id_personal), 0) as preguntas_por_dia
                                FROM (
                                    SELECT date_format(x.fecha_creacion,"%Y-%m-%d") as dia
                                    FROM (SELECT * 
                                            FROM `log_usuarios`
                                            WHERE fecha_creacion BETWEEN "'.$request->fecha_inicio.'" AND "'.$request->fecha_fin.'"
                                            ) x
                                    GROUP BY dia
                                    ) a 
                                LEFT JOIN (SELECT * 
                                        FROM log_usuarios
                                        WHERE id_personal = '.$idPersonal->id_personal.'
                                        AND es_pregunta_filtro = 1
                                        AND id_respuesta = ( select id_respuesta from respuestas where contenido = "no" )
                                        ) b 
                                on a.dia = date_format(b.fecha_creacion,"%Y-%m-%d")
                                GROUP BY a.dia
                            ')));
        }
        else
        {
            return abort(404);
        }
        


        // Reportes 
        $etiquetas = ['Contestó "SI"','Contestó "NO"'];
        $r1 = [$contestaSI, $contestaNO];
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chartA = new UsersChart();
        $chartA->labels($etiquetas);
        $dataset = $chartA->dataset('ed','pie',$r1);
        $dataset->backgroundColor($colores);
        $chartA->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => true,
                'text' => 'Cantidad de veces que ha respondido "si" y "no" a la pregunta filtro'
            ]),
        ]);

        $etiquetas = array_values($r2->pluck('nombre')->toArray());
        $r2 = array_values($r2->pluck('total')->toArray());
        if(!$r2){ $r2 = ['0']; $etiquetas = []; }
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chartB = new UsersChart();
        $chartB->labels($etiquetas);
        $dataset = $chartB->dataset('Veces seleccionada','bar',$r2);
        $dataset->backgroundColor($colores);
        $dataset->color($colores);
        $chartB->options([
            'legend' => collect([
                'display' => false,
            ]),
            'title' => collect([
                'display' => true,
                'text' => 'Veces que ha seleccionado cada área'
            ]),
        ]);
 
        $etiquetas = array_values($preResDia->pluck('dia')->toArray());
        $preResDia = array_values($preResDia->pluck('preguntas_por_dia')->toArray());
        $colores = $this->colores_aleatorios(count($etiquetas));
        $chartC = new UsersChart();
        $chartC->labels($etiquetas);
        $dataset = $chartC->dataset('Preguntas respondidas','line',$preResDia);
        $dataset->color($colores[0]);
        $dataset->fill(false);
        $dataset->lineTension(0.1);
        $chartC->options([
            'legend' => [
                'display' => false,
            ],
            'title' => [
                'display' => true,
                'text' => 'Preguntas respondidas por dia'
            ],
            'scales' => [
                'yAxes' => array(collect([
                    'ticks' => [
                        'suggestedMax' => max($preResDia) + ((max($preResDia))/100)*10,
                        'suggestedMin' => 0, 
                    ]
                ]))
            ],
        ]);

        $etiquetas = array_values($HorEnt->pluck('hora')->toArray());
        $HorEnt = array_values($HorEnt->pluck('total')->toArray());
        if(!$HorEnt){ $HorEnt = ['0']; $etiquetas = []; }
        $chartD = new UsersChart();
        $chartD->labels($etiquetas);
        $dataset = $chartD->dataset('Ingresos','line',$HorEnt);
        $dataset->color($this->colores_aleatorios(1));
        $dataset->fill(false);
        $dataset->lineTension(0.1);
        $chartD->options([
            'legend' =>[
                'display' => false,
            ],
            'title' => collect([
                'display' => true,
                'text' => 'Horas de entrada'
            ]),
            'scales' => [
                'yAxes' => array(collect([
                    'ticks' => [ 
                        'suggestedMax' => max($HorEnt) + ((max($HorEnt))/100)*10,
                        'suggestedMin' => 0
                    ]
                ]))
            ],
        ]);

        $etiquetas = array_values($PregDiaSi->pluck('dia')->toArray());
        $PregDiaSi = array_values($PregDiaSi->pluck('preguntas_por_dia')->toArray());
        $PreDiaNo = array_values($PreDiaNo->pluck('preguntas_por_dia')->toArray());
        $chartE = new UsersChart();
        $chartE->labels($etiquetas);
        $dataset = $chartE->dataset('Contesto Si','line',$PregDiaSi);
        $dataset2 = $chartE->dataset('Contesto No','line',$PreDiaNo);
        $dataset->fill(false);
        $dataset->color($this->colores_aleatorios(1));
        $dataset->lineTension(0.1);
        $dataset2->fill(false);
        $dataset2->color($this->colores_aleatorios(1));
        $dataset2->lineTension(0.1);
        $chartE->options([
            // 'legend' => [
            //     'display' => false,
            // ],
            'title' => [
                'display' => true,
                'text' => 'Respuestas a la pregunta filtro por dia'
            ],
            'scales' => [
                'yAxes' => array(collect([
                    'ticks' => [
                        'suggestedMax' => max([max($PregDiaSi),max($PreDiaNo)]) + ((max([max($PregDiaSi),max($PreDiaNo)]))/100)*10,
                        'suggestedMin' => 0, 
                    ]
                ]))
            ],
        ]);



        return view('estadisticasUsuario',[
            'reportes' => compact(
                'chartB',
                'chartA',
                'chartC',
                'chartD',
                'chartE'
            ),
            'estadisticaEmpleado' => $request->estadisticaEmpleado,
            'usuario' => $user,
            'edad' => Carbon::parse($user->fecha_nacimiento)->age,
            'tiempoTrabajando' => Carbon::parse($user->fecha_ingreso)->age,
            'preguntasRespondidas' => $preRes,
            'areasRespondidas' => $areasRes->total_seleccionadas,
            'correosEnviados' => $correosEnviados
        ]);
    }
}




