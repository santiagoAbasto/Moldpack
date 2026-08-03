@extends('adm.layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@if(session()->has('danger'))
    <div class="alert alert-danger">
        {{ session()->get('danger') }}
    </div>
@endif
<table class="table">
  <thead>
    <tr>
      <th scope="col">Orden</th>
      <th scope="col">Direccion</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <tr>
       @foreach($contacto as $c)
      <tr>
        <th scope="row" class="text-uppercase">{{$c->orden}}</th>
         <td scope="row" class="" >{{$c->direccion}}</td>
        <td>
            <a class="btn btn-warning" href="{{route('editarcontacto', $c->id)}}" role="button">editar</a>
           {{--  <a class="btn btn-danger" href="{{route('eliminarrepresentantes', $r->id)}}" role="button">borrar</a> --}}   

        </td> 
      </tr>
    
  @endforeach 
    </tr>
   
  </tbody>
</table>





@endsection