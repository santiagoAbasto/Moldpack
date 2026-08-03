@extends('adm.layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

<form method="post" action="{{route('updateinicio',$contenido->id)}}" enctype="multipart/form-data">
	@csrf
	@method('put')
  <div class="form-group">
    <h3>Banner </h3>
    <div class="form-group col-4">
      <label for="imagen">Imagen</label>
      <input type="file" class="form-control-file" id="imagen" name="imagen" value="">
      <img src="{{asset(Storage::url($contenido->imagen))}}" width="100%" height="auto" class="img-thumbnail mt-4 ">
      <small>Tamaño recomendado 683px X 500px</small>
    </div>    

    <div class="form-group col-10">
      <label for="texto">Texto</label>      
      <textarea class="form-control" name="texto" cols="30" rows="10">{{$contenido->texto}}</textarea>
    </div>
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
                 fontNames: ['Montserrat'],
                 fontNamesIgnoreCheck: ['Segoe UI']
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