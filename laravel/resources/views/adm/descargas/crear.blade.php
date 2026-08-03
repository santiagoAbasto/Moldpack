@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('creardescarga')}}" enctype="multipart/form-data">
	@csrf
  <div class="row">

    <div class="form-group col-md-2">
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >      
    </div>
    <div class="form-group col-md-10">
      <label for="titulo">Nombre</label>
      <input type="text" class="form-control" id="titulo" name="titulo" >      
    </div>
  </div>

  <div class="row">
    <div class="form-group">
      <label for="plano">Imagen</label>
      <input type="file" class="form-control-file" id="imagen" name="imagen" >
    </div>
  </div>

  <div class="row">
    <div class="form-group">
      <label for="plano">Archivo</label>
      <input required type="file" class="form-control-file" id="archivo" name="archivo" accept=".pdf">
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