@extends('adm.layouts')

@section('content')
<h3>Nuevo Slider</h3>
<form method="post" action="{{route('crearservice', $seccion)}}" enctype="multipart/form-data">
	@csrf
  <div class="form-group col-md-6">
    <label for="orden">orden</label>
    <input type="text" class="form-control" id="orden" name="orden" >    
  </div>
  
  <div class="form-group col-md-6">
    <label for="nombre">Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre">
  </div>

  <div class="form-group col-12">
    <label for="nombre">Nombre</label>
    <select class="form-select w-100" aria-label="Default select example" name=provincia>
      <option value="Buenos Aires">Buenos Aires</option>
      <option value="Buenos Aires Capital">Buenos Aires Capital</option>
      <option value="Catamarca">Catamarca</option>
      <option value="Chaco">Chaco</option>
      <option value="Chubut">Chubut</option>
      <option value="Cordoba">Cordoba</option>
      <option value="Corrientes">Corrientes</option>
      <option value="Entre Rios">Entre Rios</option>
      <option value="Formosa">Formosa</option>
      <option value="Jujuy">Jujuy</option>
      <option value="La Pampa">La Pampa</option>
      <option value="La Rioja">La Rioja</option>
      <option value="Mendoza">Mendoza</option>
      <option value="Misiones">Misiones</option>
      <option value="Neuquen">Neuquen</option>
      <option value="Rio Negro">Rio Negro</option>
      <option value="Salta">Salta</option>
      <option value="San Juan">San Juan</option>
      <option value="San Luis">San Luis</option>
      <option value="Santa Cruz">Santa Cruz</option>
      <option value="Santa Fe">Santa Fe</option>
      <option value="Santiago del Estero">Santiago del Estero</option>
      <option value="Tierra del Fuego">Tierra del Fuego</option>
      <option value="Tucuman">Tucuman</option>
      </select>
  </div>

  <div class="form-group col-12">
    <label for="localidad">Localidad</label>
    <input type="text" class="form-control" id="localidad" name="localidad">
  </div>

  <div class="form-group col-12">
    <label for="correo">Correo</label>
    <input type="text" class="form-control" id="correo" name="correo">
  </div>

  <div class="form-group col-12">
    <label for="telefono">Telefono</label>
    <input type="text" class="form-control" id="telefono" name="telefono">
  </div>

  <div class="form-group col-md-6">
    <label for="latitud">Latitud</label>
    <input type="text" class="form-control" id="latitud" name="latitud">
  </div>

  <div class="form-group col-md-6">
    <label for="longitud">Longitud</label>
    <input type="text" class="form-control" id="longitud" name="longitud">
  </div>

  <div class="form-group col-12">
    <label for="horario">Horario</label>
    <input type="text" class="form-control" id="horario" name="horario">
  </div>

  <div class="form-group col-12">
    <label for="direccion">Direccion</label>
    <input type="text" class="form-control" id="direccion" name="direccion">
  </div>

 <button type="submit" class="btn btn-success">Agregar</button>
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