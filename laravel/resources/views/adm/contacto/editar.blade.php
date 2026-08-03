@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('updatecontacto',$contacto->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')  

  <div class="form-group">
    <label for="direccion">Direccion </label>
    <input type="text" class="form-control" id="direccion" name="direccion" value="{{$contacto->direccion}}">
  </div>  
   <div class="form-group">
    <label for="telefono">Telefono </label>
    <input type="tel" class="form-control" id="telefono" name="telefono" value="{{$contacto->telefono}}">
  </div>
   <div class="form-group">
    <label for="celular">celular</label>
    <input type="tel" class="form-control" id="celular" name="celular" value="{{$contacto->celular}}">
  </div>
   <div class="form-group">
    <label for="correo">Correo</label>
    <input type="email" class="form-control" id="correo" name="correo" value="{{$contacto->correo}}">
  </div>
   <div class="form-group">
    <label for="correoF">Correo de la factura</label>
    <input type="email" class="form-control" id="correoF" name="correoF" value="{{$contacto->correoF}}">
  </div>
 <button type="submit" class="btn btn-success">Editar</button>
</form>


@endsection