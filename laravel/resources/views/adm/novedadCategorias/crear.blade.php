@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('crearnovedadCategoria')}}" enctype="multipart/form-data">
	@csrf
  <div class="row">

    <div class="form-group col-md-2">
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >
      
    </div>

    <div class="form-group col-md-4">
      <label for="nombre">nombre</label>
      <input required type="text" class="form-control" id="nombre" name="nombre" >
      
    </div>        
    
  </div> 
  
 
 <button type="submit" class="btn btn-success my-3">Agregar</button>
</form>

@endsection