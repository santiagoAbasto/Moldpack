@extends('adm.layouts')

<script src="{{asset('js/producto.js')}}"></script>

@section('content')
@php
  $categoryImageUrl = function ($path) {
      $path = trim((string) $path);
      if ($path === '') {
          return null;
      }
      if (preg_match('/^https?:\/\//', $path)) {
          return $path;
      }
      $path = ltrim($path, '/');
      if (strpos($path, 'storage/') === 0 || strpos($path, 'img/') === 0) {
          return asset($path);
      }
      if (strpos($path, 'public/') === 0) {
          return asset(Storage::url($path));
      }
      return asset('storage/'.$path);
  };
  $imagenActual = $categoryImageUrl($Categorias->imagen);
@endphp
<form method="post" action="{{route('updateCategoria',$Categorias->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="row flex-wrap">


    <div class="form-group col-md-6" >
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" value="{{$Categorias->orden}}">
    </div>
    
    <div class="form-group col-md-6">
      <label for="nombre">Nombre</label>
      <input type="text" name="nombre" class="form-control" value="{{$Categorias->nombre}}">
      
    </div>
  </div>
  
  <div class="row">
    <div class="form-group col-md-6">
      <label for="imagen">Imagen categoria / portada home</label>
      <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*">
      <small>Resolucion recomendada: 390px X 390px. Si la categoria esta destacada, esta foto tambien se ve en la pagina principal.</small>
      <div class="mt-4" id="previewWrap" style="{{$imagenActual ? '' : 'display:none;'}}">
        <img id="categoriaImagenPreview" src="{{$imagenActual}}" class="img-thumbnail" style="max-width:390px;width:100%;height:auto;object-fit:cover;" onerror="this.src='{{asset('img/logo2.jpg')}}';">
        <div class="mt-2 text-muted" style="font-size:13px;">Vista previa de la imagen que se vera en categoria/home.</div>
      </div>
    </div>
  </div>

  <div class="form-check">
    <input class="form-check-input" @if ($Categorias->destacado == "1") checked @endif type="checkbox" id="destacado" name="destacado">
    <label class="form-check-label" for="destacado">
      Destacar en la home
    </label>
  </div>

  <div class="form-check">
    <input class="form-check-input" @if ($Categorias->activa == "1") checked @endif type="checkbox" id="activa" name="activa">
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

        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('imagen');
            var preview = document.getElementById('categoriaImagenPreview');
            var previewWrap = document.getElementById('previewWrap');

            if (!input || !preview || !previewWrap) {
                return;
            }

            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) {
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                    previewWrap.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        });
    
</script>

@endsection
