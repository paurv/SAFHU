<div>
  El controlador "{{$nombre}}" con número de empleado "{{$noEmp}}" reportó no estar
  en forma para realizar el turno, llenando la encuesta de la siguiente manera:
  <br><br>
  <table>
    <thead>
      <tr>
        <th>Área</th>
        <th></th>
        <th>Pregunta</th>
        <th></th>
        <th>Respuesta</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($parametros as $parametro)
        <tr>
          <td>{{$parametro->area}}</td>
          <td></td>
          <td>{{$parametro->pregunta}}</td>
          <td></td>
          <td>{{$parametro->respuesta}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <br>
  Fecha y hora de la encuesta: {{$parametros[count($parametros)-1]->fecha_creacion}} 
</div>