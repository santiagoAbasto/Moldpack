<div>Realizado el @php $date = date('d/m/o') @endphp {{$date}}</div>
<table class="table" >
    <th>Datos del pedido de presupuesto</th>
    <tr><td>Nombre</td><td>{{$dataRequest['nombre']}}</td></tr>
    <tr><td>Correo</td><td>{{$dataRequest['email']}}</td></tr>
    <tr><td>Telefono</td><td>{{$dataRequest['telefono']}}</td></tr>
    @if(isset($dataRequest['empresa'] ))
        <tr><td>Empresa</td><td>{{$dataRequest['empresa']}}</td></tr>
    @endif
    @if(isset($dataRequest['mensaje'] ))
        <tr><td>Mensaje</td><td>{{$dataRequest['mensaje']}}</td></tr>
    @endif    
</table>
