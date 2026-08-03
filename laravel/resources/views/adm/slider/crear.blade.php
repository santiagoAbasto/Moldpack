@extends('adm.layouts')

@section('content')
<h3>Nuevo Slider</h3>
<form method="post" action="{{route('crearslider', $seccion)}}" enctype="multipart/form-data">
	@csrf
  <div class="form-group col-md-6">
    <label for="orden">orden</label>
    <input type="text" class="form-control" id="orden" name="orden" >
    
  </div>
  
  <div class="form-group col-md-6">
    <label for="titulo">Titulo</label>
    <input type="text" class="form-control" id="titulo" name="titulo">
  </div> 
  <div class="form-group col-md-8">
    <label for="descripcion">Contenido</label>
    <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="10" value="" ></textarea>
    
  </div> 
  <div class="form-group col-md-6">
    <label for="imagen">Imagen</label>
    <input type="file" class="form-control-file" required id="imagen" name="imagen">
    <span class="">Seleccione (Tamaño recomendado: 1366x623px)</span>
  </div>

  @if ($seccion == 'inicio')
  <div class="form-group col-md-8">
    <label for="boton">Titulo del boton</label>
    <input type="text" class="form-control" id="boton" name="boton">
    <br>
    <label for="link">Link</label>
    <input type="text" class="form-control" id="link" name="link">
  </div> 
  @endif
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="activo" name="activo">
        <label class="form-check-label" for="activo">
          Activo
        </label>
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