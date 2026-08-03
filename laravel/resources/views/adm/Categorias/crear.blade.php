@extends('adm.layouts')

<script src="{{asset('js/producto.js')}}"></script>

@section('content')
<form method="post" action="{{route('crearCategoria')}}" enctype="multipart/form-data">
	@csrf
  
  <div class="row flex-wrap">


    <div class="form-group col-md-6" >
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >
    </div>
    
    <div class="form-group col-md-6">
      <label for="nombre">Nombre</label>
      <input type="text" name="nombre" class="form-control" >      
    </div>
    
  </div>
  
  <div class="row">
    <div class="form-group col-md-6">
      <label for="imagen">Imagen Familia</label>
      <input type="file" class="form-control-file" id="imagen" name="imagen" required>
      <small>Resolucion recomendada: 390px X 390px</small>
    </div>
  </div>

  <div class="form-check">
    <input class="form-check-input" type="checkbox" id="destacado" name="destacado">
    <label class="form-check-label" for="destacado">
      Destacar en la home
    </label>
  </div>

  <div class="form-check">
    <input class="form-check-input" type="checkbox" id="activa" name="activa">
    <label class="form-check-label" for="activa">
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
                     fontNames: ['Montserrat-Bold', 'Montserrat-Light', 'Montserrat-Medium', 'Montserrat-Regular', 'Montserrat-SemiBold'],
                     toolbar: [
                     ['style', ['style']],
                     ['font', ['bold', 'underline', 'clear']],
                     ['fontNames', ['fontname']],
                     ['color', ['color']],
                     ['para', ['ul', 'ol', 'paragraph']]
                     
                     ]
             });
         });
    
</script>

@endsection