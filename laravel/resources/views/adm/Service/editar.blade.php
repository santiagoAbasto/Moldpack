@extends('adm.layouts')

@section('content')

<form method="post" action="{{route('updateservice',[$seccion,'id'=>$service->id])}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="form-group col-md-6">
    <label for="orden">orden</label>
    <input type="text" class="form-control" id="orden" name="orden" value="{{$service->orden}}">
  </div>
  
  <div class="form-group col-md-6">
    <label for="nombre">Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="{{$service->nombre}}">
  </div>

  <div class="form-group col-12">
    <label for="nombre">Nombre</label>
    <select class="form-select w-100" aria-label="Default select example" name=provincia>
      <option {{$service->provincia == 'Buenos Aires' ? 'selected': ''}} value="Buenos Aires">Buenos Aires</option>
      <option {{$service->provincia == 'Buenos Aires Capital' ? 'selected': ''}} value="Buenos Aires Capital">Buenos Aires Capital</option>
      <option {{$service->provincia == 'Catamarca' ? 'selected': ''}} value="Catamarca">Catamarca</option>
      <option {{$service->provincia == 'Chaco' ? 'selected': ''}} value="Chaco">Chaco</option>
      <option {{$service->provincia == 'Chubut' ? 'selected': ''}} value="Chubut">Chubut</option>
      <option {{$service->provincia == 'Cordoba' ? 'selected': ''}} value="Cordoba">Cordoba</option>
      <option {{$service->provincia == 'Corrientes' ? 'selected': ''}} value="Corrientes">Corrientes</option>
      <option {{$service->provincia == 'Entre Rios' ? 'selected': ''}} value="Entre Rios">Entre Rios</option>
      <option {{$service->provincia == 'Formosa' ? 'selected': ''}} value="Formosa">Formosa</option>
      <option {{$service->provincia == 'Jujuy' ? 'selected': ''}} value="Jujuy">Jujuy</option>
      <option {{$service->provincia == 'La Pampa' ? 'selected': ''}} value="La Pampa">La Pampa</option>
      <option {{$service->provincia == 'La Rioja' ? 'selected': ''}} value="La Rioja">La Rioja</option>
      <option {{$service->provincia == 'Mendoza' ? 'selected': ''}} value="Mendoza">Mendoza</option>
      <option {{$service->provincia == 'Misiones' ? 'selected': ''}} value="Misiones">Misiones</option>
      <option {{$service->provincia == 'Neuquen' ? 'selected': ''}} value="Neuquen">Neuquen</option>
      <option {{$service->provincia == 'Rio Negro' ? 'selected': ''}} value="Rio Negro">Rio Negro</option>
      <option {{$service->provincia == 'Salta' ? 'selected': ''}} value="Salta">Salta</option>
      <option {{$service->provincia == 'San Juan' ? 'selected': ''}} value="San Juan">San Juan</option>
      <option {{$service->provincia == 'San Luis' ? 'selected': ''}} value="San Luis">San Luis</option>
      <option {{$service->provincia == 'Santa Cruz' ? 'selected': ''}} value="Santa Cruz">Santa Cruz</option>
      <option {{$service->provincia == 'Santa Fe' ? 'selected': ''}} value="Santa Fe">Santa Fe</option>
      <option {{$service->provincia == 'Santiago del Estero' ? 'selected': ''}} value="Santiago del Estero">Santiago del Estero</option>
      <option {{$service->provincia == 'Tierra del Fuego' ? 'selected': ''}} value="Tierra del Fuego">Tierra del Fuego</option>
      <option {{$service->provincia == 'Tucuman' ? 'selected': ''}} value="Tucuman">Tucuman</option>
      </select>
  </div>

  <div class="form-group col-12">
    <label for="localidad">Localidad</label>
    <input type="text" class="form-control" id="localidad" name="localidad" value="{{$service->localidad}}">
  </div>

  <div class="form-group col-12">
    <label for="correo">Correo</label>
    <input type="text" class="form-control" id="correo" name="correo" value="{{$service->correo}}">
  </div>

  <div class="form-group col-12">
    <label for="telefono">Telefono</label>
    <input type="text" class="form-control" id="telefono" name="telefono" value="{{$service->telefono}}">
  </div>

  <div class="form-group col-md-6">
    <label for="latitud">Latitud</label>
    <input type="text" class="form-control" id="latitud" name="latitud" value="{{$service->latitud}}">
  </div>

  <div class="form-group col-md-6">
    <label for="longitud">Longitud</label>
    <input type="text" class="form-control" id="longitud" name="longitud" value="{{$service->longitud}}">
  </div>

  <div class="form-group col-12">
    <label for="horario">Horario</label>
    <input type="text" class="form-control" id="horario" name="horario" value="{{$service->horario}}">
  </div>

  <div class="form-group col-12">
    <label for="direccion">Direccion</label>
    <input type="text" class="form-control" id="direccion" name="direccion" value="{{$service->direccion}}">
  </div>

 <button type="submit" class="btn btn-success">Editar</button>
</form>


@endsection
@section('js')
 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
 <script>
  $(document).ready(function() {
       $('textarea').summernote({
          
           height: 250,
               fontNames: ['Montserrat', 'Comic Sans MS'],
               fontNamesIgnoreCheck: ['Arial', 'Segoe UI']
              //  toolbar: [
              //  ['style', ['style']],
              //  ['font', ['bold', 'underline', 'clear']],
              // // ['fontNames', ['fontname']],
              //  ['color', ['color']],
              //  ['para', ['ul', 'ol', 'paragraph']]
               
              //  ]
       });
   });

</script> 

@endsection