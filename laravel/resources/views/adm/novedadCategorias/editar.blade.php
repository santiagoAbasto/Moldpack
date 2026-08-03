@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('updatenovedadCategoria',$novedadCategoria->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="form-group">
    <label for="orden">orden</label>
    <input type="text" class="form-control" id="orden" name="orden" value="{{$novedadCategoria->orden}}">   
  </div>
  <div class="form-group">
    <label for="nombre">nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="{{$novedadCategoria->nombre}}">   
  </div> 
 <button type="submit" class="btn btn-success">Editar</button>
</form>

@endsection
