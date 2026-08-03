@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('updatevideo',$video->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="form-group">
    <label for="orden">orden</label>
    <input type="text" class="form-control" id="orden" name="orden" value="{{$video->orden}}">   
  </div>
  <div class="form-group">
    <label for="nombre">nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="{{$video->nombre}}">   
  </div>

  <div class="row">
    <div class="form-group col-md-12">
      <label for="descripcion">Descripcion</label>
      <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{$video->descripcion}}">         
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-12">
      <label for="link">Link</label>
      <input type="text" class="form-control" id="link" name="link" value="{{$video->link}}">         
    </div>
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
