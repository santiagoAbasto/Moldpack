@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('updatenovedad',$novedades->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="form-group">
    <label for="orden">orden</label>
    <input type="text" class="form-control" id="orden" name="orden" value="{{$novedades->orden}}">   
  </div>
  <div class="form-group">
    <label for="nombre">nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="{{$novedades->nombre}}">   
  </div>
  <div class="form-group col-12">
    <label for="descripcion2">Breve descripcion</label>
    <input type="text" class="form-control" id="descripcion2" name="descripcion2" value="{{$novedades->descripcion2}}">
  </div>

  <div class="form-group">
    <label for="imagen">Imagen</label>
    <input type="file" class="form-control-file" id="imagen" name="imagen" value="" >
    
    <img src="{{asset(Storage::url($novedades->imagen))}}" class="img-thumbnail mt-4">
  </div>

  <div class="row">
    <div class="form-group col-md-12">
      <label for="descripcion">Descripcion</label>
      <textarea required class="form-control" name="descripcion" id="descripcion" cols="30" rows="10" value="" >{{$novedades->descripcion}}</textarea>
    </div>
  </div>  

  <label>Categoria</label>
  <div class="row">
    <select class="form-group w-100" name="categoria">
      @forelse ($cat as $item)
        <option @if ($novedades->categoria == $item->id) selected @endif value="{{$item->id}}">{{$item->nombre}}</option>
      @empty
        
      @endforelse
    </select>
  </div>

  <div class="form-check">
    <input class="form-check-input" name="destacar" type="checkbox" id="destacar" @if ($novedades->destacar == 1) checked @endif value="1">
    <label class="form-check-label" for="destacar">
      Destacar en la home
    </label>
  </div>

 <button type="submit" class="btn btn-success my-3">Editar</button>
</form>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
        $(document).ready(function() {
             $('textarea').summernote({
                
                 height: 250,
                     fontNames: ['Montserrat-Bold', 'Montserrat-Light', 'Montserrat-Medium', 'Montserrat-Regular', 'Montserrat-SemiBold', 'Roboto-Regular'],
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
@endsection
