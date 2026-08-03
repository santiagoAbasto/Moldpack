@extends('adm.layouts')
@section('content')
<form method="post" action="{{route('crearcolor')}}" enctype="multipart/form-data">
	@csrf
  <div class="row">

    <div class="form-group col-md-12">
      <label for="orden">Orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >
    </div>

    <div class="form-group col-md-12">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" >
    </div>

    <label for="color" class="form-label">Color</label>
    <input type="color" class="form-control form-control-color" name="color" id="color" value="#000">

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
         
    function eliminar(id){
      
      $(id).parent().remove();
    }
</script>


  

@endsection