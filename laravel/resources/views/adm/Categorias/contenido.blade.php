@extends('adm.layouts')

@section('content')
<a href="{{route('nuevoCategoria')}}" class="btn btn-success mb-5" >Nueva Categoria</a>
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<table class="table">
  <thead>
    <tr>
      <th scope="col">Orden</th>
      <th scope="col">nombre</th>
      
      <th scope="col">Accion</th>
    </tr>
  </thead>
 
  <tbody>
  	@foreach($Categorias as $p)
	    <tr>
	      <th scope="row">{{$p->orden}}</th>
	      <th scope="row">{{$p->nombre}}</th>
	     {{--  <td scope="row"><img src="{{asset(Storage::url($p->imagen))}}" class="img-thumbnail w-25"></td> --}}
	      {{-- <td>{!!$p->descripcion!!}</td> --}}
	      <td>
	      	<a class="btn btn-warning" href="{{route('editarCategoria',$p->id)}}" role="button">editar</a>
	      	<a class="btn btn-danger " href="{{route('eliminarCategoria',$p->id)}}" role="button">borrar</a>

	      </td>
	    </tr>
    
	@endforeach
  </tbody>
</table>
@endsection