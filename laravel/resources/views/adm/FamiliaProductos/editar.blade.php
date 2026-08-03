@extends('adm.layouts')

<script src="{{asset('js/producto.js')}}"></script>

@section('content')
<form method="post" action="{{route('updatefamiliaProducto',$Familiaproductos->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="row flex-wrap">


    <div class="form-group col-md-6" >
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" value="{{$Familiaproductos->orden}}">   
    </div>    
    <div class="form-group w-100">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" value="{{$Familiaproductos->nombre}}">
    </div>
    
    <div class="form-group col-md-12">
      <label for="descripcion">Categoria de producto</label>
      <select class="form-select form-control" name="producto"  required>
        @forelse ($Categoria as $item)
          <option {{$Familiaproductos->producto == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->nombre}}</option>
          
        @empty
          
        @endforelse        
      </select>
    </div>
  </div>

  
  
  <div class="row">
    <div class="form-group col-md-6">
      <label for="imagen">Imagen Familia</label>
      <input type="file" class="form-control-file" id="imagen" name="imagen" >
      <small>Resolucion recomendada: 390px X 390px</small>
      <img src="{{asset(Storage::url($Familiaproductos->imagen))}}" class="img-thumbnail mt-4">
    </div>
  </div>

  <div class="form-check">
    <input class="form-check-input" @if ($Familiaproductos->activa == "1") checked @endif type="checkbox" id="activa" name="activa">
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
             });
         });
    
</script>
    <script src="{{asset('js/select2.js')}}"></script>
    <script>
        $("select").select2({
            placeholder: "Seleccione un opcion",
            allowClear: true,
            minimumInputLength: 0
        });
        function insertAfter(newNode, existingNode) {
      existingNode.parentNode.insertBefore(newNode, existingNode.nextSibling);
    }
    function clone(){
      var p = document.getElementById("video_conteiner");
      var p_prime = p.querySelector('input').cloneNode(true); 
      p_prime.value = "";
      insertAfter(p_prime,  p.lastElementChild);
    }
</script>

@endsection