@extends('adm.layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif


<form method="post" action="{{route('updateempresa',$contenido->id)}}" enctype="multipart/form-data">
	@csrf
	@method('put')  
 <div class="d-flex row justify-content-between align-items-start flex-wrap">

  <div class="d-flex flex-row flex-wrap justify-content-between align-items-start col-12">
    <div class="row col-6">
      <label for="descripcion">Texto</label>
      <textarea class="form-control" name="texto" id="texto" cols="30" rows="10" value="" >{{$contenido->texto}}</textarea>    
    </div>
    <div class="row col-6 ps-3">
      <div class="form-group">
        <label for="img">imagen</label>
        <img src="{{asset(Storage::url($contenido->imagen))}}" width="50%" height="auto" class="mt-4" style="filter: brightness(0.5);">
        <input type="file" class="form-control-file" id="img2" name="imagen">
        <span class="">Seleccione (Tamaño recomendado: )</span>
      </div>
    </div>    
    <hr class="w-100">
    <div class="row col-6 ps-3">
      <div class="form-group">
        <label for="img">imagen</label>
        <img src="{{asset(Storage::url($contenido->imagen2))}}" width="50%" height="auto" class="mt-4" style="filter: brightness(0.5);">
        <input type="file" class="form-control-file" id="img2" name="imagen2">
        <span class="">Seleccione (Tamaño recomendado: )</span>
      </div>
    </div>
    <div class="row col-6">
      <label for="descripcion">Texto</label>
      <textarea class="form-control" name="texto2" id="texto2" cols="30" rows="10" value="" >{{$contenido->texto2}}</textarea>
    </div>
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