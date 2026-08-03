@extends('adm.layouts')

@section('content')

<form method="post" action="{{route('updateslider',[$seccion,'id'=>$slider->id])}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="form-group col-md-6">
    <label for="orden">orden</label>
    <input type="text" class="form-control" id="orden" name="orden" value="{{$slider->orden}}">   
  </div>
  
  <div class="form-group col-md-8">
    <label for="descripcion">Descripcion</label>    
    <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="10" value="{{$slider->descripcion}}" >{!!$slider->descripcion!!}</textarea>
  </div>
  <div class="form-group">
    <label for="imagen">Imagen</label>
    <input type="file" class="form-control-file" id="imagen" name="imagen" value="">
    <span class="">Seleccione (Tamaño recomendado: 1366x623px)</span>
    <img src="{{asset(Storage::url($slider->imagen))}}" class="img-thumbnail mt-4">
  </div>

  @if ($seccion == 'inicio')
  <div class="form-group col-md-8">
    <label for="boton">Titulo del boton</label>
    <input type="text" class="form-control" id="boton" name="boton" value="{{$slider->boton}}">
    <br>
    <label for="link">Link</label>
    <input type="text" class="form-control" id="link" name="link" value="{{$slider->link}}">
  </div> 
  @endif
  
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="activo" name="activo" @if ($slider->activo == "1") checked @endif>
        <label class="form-check-label" for="activo">
          Activo
        </label>
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