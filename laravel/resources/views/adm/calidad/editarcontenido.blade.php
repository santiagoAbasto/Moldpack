@extends('adm.layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif


<form method="post" action="{{route('updatecalidad',$contenido->id)}}" enctype="multipart/form-data">
	@csrf
	@method('put')  
 <div class="d-flex row justify-content-between align-items-start flex-wrap">

  <div class="d-flex flex-row flex-wrap justify-content-between align-items-start col-12">
      <div class="col-6 my-3">
        <label for="img">imagen principal</label><br>
        <img src="{{asset(Storage::url($contenido->imagen))}}" width="50%" height="auto" class="my-3" style="filter: brightness(0.5);">
        <input type="file" class="form-control-file" id="img" name="imagen">
        <span class="">Seleccione (Tamaño recomendado: 618 X 452 px)</span>
      </div>

    <div class="col-6 my-3">
      <label for="descripcion">Texto</label>
      <textarea class="form-control" name="texto" id="texto" cols="30" rows="10" value="" >{{$contenido->texto}}</textarea>    
    </div>

    <div class="col-6 ps-3 my-3">
      <label for="certificado">Certificado de calidad </label>
      <a target="_blank" href="{{asset(Storage::url($contenido->certificado))}}">Ver certificado</a>
      <input type="file" class="form-control-file" id="certificado" name="certificado">
    </div>

    <div class="col-6 ps-3 my-3">
      <label for="politicas">Pol&iacute;ticas de calidad</label>
      <a target="_blank" href="{{asset(Storage::url($contenido->politicas))}}">Ver politicas</a>
      <input type="file" class="form-control-file" id="politicas" name="politicas">
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