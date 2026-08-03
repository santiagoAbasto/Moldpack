@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('updatelista',$listas->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="row">
    <div class="form-group col-md-2">
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" value="{{$listas->orden}}">      
    </div>
    <div class="form-group col-md-10">
      <label for="titulo">Nombre</label>
      <input type="text" class="form-control" id="titulo" name="titulo" value="{{$listas->titulo}}">      
    </div>
  </div>

  <div class="row">
    <div class="form-group">
      <label for="plano">Imagen</label>
      <img src="{{asset(Storage::url($listas->imagen))}}" class="img-thumbnail mt-4">
      <input type="file" class="form-control-file" id="imagen" name="imagen" >
    </div>
  </div>

  <div class="row">
    <div class="form-group">
      <label for="plano">Archivo</label>
      <a target="_blank" href="{{asset(Storage::url($listas->archivo))}}">Ver archivo</a>
      <input type="file" class="form-control-file" id="archivo" name="archivo" accept=".pdf">
    </div>
  </div>
  

 <button type="submit" class="btn btn-success my-3">Agregar</button>
</form>

@endsection
@section('js')
 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
        $(document).ready(function() {
             $('textarea').summernote({
                
              height: 250,
                     fontNames: ['Montserrat-Bold', 'Montserrat-Light', 'Montserrat-Medium', 'Montserrat-Regular', 'Montserrat-SemiBold', 'Roboto-Regular'],
             });
         });
    
</script>


  

@endsection