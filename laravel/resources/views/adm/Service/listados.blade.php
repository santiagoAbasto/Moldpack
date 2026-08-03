@extends('adm.layouts')

@section('content')

<div class="d-flex justify-content-end ">

  <a href="{{route('nuevoservice', [ 'seccion' => $seccion ])}}" class="btn btn-success rounded-pill" >
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
      <th scope="col">Nombre</th>      
      <th scope="col">Accion</th>
    </tr>
  </thead>
 
  <tbody>
  	@foreach($service as $s)
	    <tr>
	      <th scope="row">{{$s->orden}}</th>	      
	      <td>{!!$s->nombre!!}</td>
	      <td>
	      	<a class="btn btn-warning rounded-pill" href="{{route('editservice',[$seccion,'id'=>$s->id])}}" role="button">
            <i class="fas fa-edit"></i>
          </a>
	      	<a class="btn btn-danger rounded-pill" href="{{route('eliminarservice',$s->id)}}" role="button">
            <i class="far fa-trash-alt"></i>
          </a>

	      </td>
	    </tr>
    
	@endforeach
  </tbody>
</table>

<script>
  document.getElementById("file").onchange = function(e) {

if(this.value != null){
$('#file_submint').removeAttr("disabled")
}      
}
</script>
@endsection