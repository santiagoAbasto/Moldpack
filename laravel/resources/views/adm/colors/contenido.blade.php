@extends('adm.layouts')

@section('content')
<a href="{{route('nuevocolor')}}" class="btn btn-success mb-5" >Nuevo Color</a>
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<table class="table">
  <thead>
    <tr>
      <th scope="col">Color</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
 
  <tbody>
  	@foreach($colors as $p)
	    <tr>
	      <th scope="row">{{$p->nombre}} <div style="width: 30px;height:30px;background:{{$p->color}};"></div></th>	     
	      <td>
	      	<a class="btn btn-warning" href="{{route('editarcolor',$p->id)}}" role="button">editar</a>
	      	<a class="btn btn-danger" href="{{route('eliminarcolor',$p->id)}}" role="button">borrar</a>

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