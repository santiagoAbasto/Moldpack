@extends('adm.layouts')


<script src="{{asset('js/producto.js')}}"></script>


@section('content')
<form method="post" action="{{route('crearfamiliaProducto')}}" enctype="multipart/form-data">
	@csrf
  <div class="row flex-wrap">

    <div class="form-group col-md-6">
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >
      
    </div>

    <div class="form-group w-100">
      <label for="nombre">Nombre</label>
      <input  type="text" class="form-control" id="nombre" name="nombre" >
    </div>

    <div class="form-group col-md-12">
      <label for="descripcion">Categoria de producto</label>
      <select class="form-select form-control" required name="producto">
      @forelse ($Categoria as $item)          
        <option value="{{$item->id}}">{{$item->nombre}}</option>
      @empty
          
      @endforelse

      </select>
    </div>
  </div>
  


  <div class="row">
    <div class="form-group">
      <label for="imagen">Imagen de producto</label>
      <input type="file" class="form-control-file" required id="imagen" name="imagen" >
      <small>Resolucion recomendada: 390px X 390px</small>
    </div>    
  </div>  
  <div class="form-check">
    <input class="form-check-input" type="checkbox" id="activa" name="activa">
    <label class="form-check-label" for="activa">
      Activo
    </label>
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