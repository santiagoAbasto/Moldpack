@extends('adm.layouts')

@section('content')

<a href="{{route('nuevonovedadCategoria')}}" class="btn btn-success mb-5" >Nueva Categoria</a>
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

<table class="table">
  <thead>
    <tr>
      <th scope="col">Orden</th>
      <th scope="col-md-4">Nombre</th>      
      <th scope="col">Accion</th>
    </tr>
  </thead>
  <tbody>
    <tr>
       @foreach($novedadCategoria as $a)
      <tr>
        <th scope="row" class="text-uppercase">{{$a->orden}}</th>
          <td scope="row">{{$a->nombre}}</td>
        <td>
           <a class="btn btn-warning" href="{{route('editarnovedadCategoria', $a->id)}}" role="button">edit</a>
           <a class="btn btn-danger" href="{{route('eliminarnovedadCategoria', $a->id)}}" role="button">borrar</a>  

        </td>
      </tr>
    
  @endforeach 
    </tr>
   
  </tbody>
</table>











@endsection