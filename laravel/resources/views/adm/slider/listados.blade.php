@extends('adm.layouts')

@section('content')
<div class="d-flex justify-content-end ">

  <a href="{{route('nuevoslider', [ 'seccion' => $seccion ])}}" class="btn btn-success rounded-pill" >
   <i class="fas fa-plus"></i>
 </a> 
</div>

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
      <th scope="col">Imagen</th>
      <th scope="col">Descripcion</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
 
  <tbody>
  	@foreach($slider as $s)
	    <tr>
	      <th scope="row">{{$s->orden}}</th>
	      <td scope="row"><img src="{{asset(Storage::url($s->imagen))}}" class="img-thumbnail w-50"></td>
	      <td>{!!$s->descripcion!!}</td>
	      <td>
	      	<a class="btn btn-warning rounded-pill" href="{{route('editslider',[$seccion,'id'=>$s->id])}}" role="button">
            <i class="fas fa-edit"></i>
          </a>
	      	<a class="btn btn-danger rounded-pill" href="{{route('eliminarslider',$s->id)}}" role="button">
            <i class="far fa-trash-alt"></i>
          </a>

	      </td>
	    </tr>
    
	@endforeach
  </tbody>
</table>


@endsection