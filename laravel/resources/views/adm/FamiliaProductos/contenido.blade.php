@extends('adm.layouts')

@section('content')
<div class="mb-4 d-none">
  {{-- <span>Exportar productos excel</span><br>
  <a href="{{route('exportar_productos_excel')}}">Exportar excel</a> --}}

  <form action="{{route('productos_import_excel')}}" accept-charset="UTF-8" enctype="multipart/form-data" method="POST">
      @csrf
      <h4><b>Actualizar precios por excel</b></h4>
      <input class="btn" type="file" name="file" id="file">
      <button disabled class="btn btn-primary" id="file_submint">Importar</button>
  </form>
</div>

<a href="{{route('nuevofamiliaProducto')}}" class="btn btn-success mb-5" >Nuevo producto</a>
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
  	@foreach($Familiaproductos as $p)
	    <tr>
	      <th scope="row">{{$p->orden}}</th>
	      <th scope="row">{{$p->nombre}}</th>
	     {{--  <td scope="row"><img src="{{asset(Storage::url($p->imagen))}}" class="img-thumbnail w-25"></td> --}}
	      {{-- <td>{!!$p->descripcion!!}</td> --}}
	      <td>
	      	<a class="btn btn-warning" href="{{route('editarfamiliaProducto',$p->id)}}" role="button">editar</a>
	      	<a class="btn btn-danger" href="{{route('eliminarfamiliaProducto',$p->id)}}" role="button">borrar</a>

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