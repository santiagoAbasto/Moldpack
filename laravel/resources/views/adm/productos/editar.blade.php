@extends('adm.layouts')

<script src="{{asset('js/producto.js')}}"></script>

@section('content')
<form method="post" action="{{route('updateproducto',$productos->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="row">

    <div class="form-group col-md-6">
      <label for="orden">Orden</label>
      <input type="text" class="form-control" id="orden" name="orden" value="{{$productos->orden}}">
    </div>
    <div class="form-group col-md-12">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" value="{{$productos->nombre}}" required>
    </div>

    <div class="form-group col-md-12">
      <label for="descripcion">Descripcion</label>
      <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="10">{!!$productos->descripcion!!}</textarea>
    </div>

    <div class="row">
      <div class="form-group col-md-6">
        <label for="imagen">Imagen Familia</label>
        <input type="file" class="form-control-file" id="imagen" name="imagen" >
          <small>Resolucion recomendada: 285px X 273px</small>
        <img src="{{asset(Storage::url($productos->imagen))}}" class="img-thumbnail mt-4">
      </div>
    </div>

    <div class="form-group col-md-6">
      <label for="galeria">galeria</label>
      <input type="file" class="form-control-file" id="galeria" name="galeria[]" value="" multiple="">
      <small>Resolucion recomendada: 285px X 273px</small>
      <?php $galerias = explode(',', $productos->galeria); ?>
      <div class="d-flex">
        @foreach($galerias as $key => $galeria)
          <div>
            <a  href="{{route('borrarimagen',[$productos->id, $key])}}" class="" style=" line-height: 30px; position: absolute; float: right; border: 1px solid grey; height: 30px; width: 30px; border-radius: 50px; text-align: center;" > <i class="fas fa-times " style="color: red;"></i> </a>
            <img src="{{asset(Storage::url($galeria))}}" class="" style="width:200px;  margin-right: 13px;">
          </div>
        
        @endforeach
      </div>
    </div>

  </div>

  <hr class="w-100">

  <div class="form-group w-100">            
    <label>Colores</label><br>
    <select class="custom-select w-100" id="select2" multiple  aria-label="select example" name="colores[]">      
        @if( isset($productos->obtenerColores))
          @foreach ($colores as $color)
          <?php $selected = '' ?>
            @foreach ( $productos->obtenerColores as $relacion)
                @if($relacion->relacion_id == $color->id)
                  <?php $selected = 'selected' ?>
                  @break
                @else
                @endif
            @endforeach
            <option {{$selected}} value="{{$color->id}}">{{$color->nombre}}</option>
          @endforeach
        @else
          @forelse ($colores as $color)          
            <option value="{{$color->id}}">{{$color->nombre}}</option>                
          @empty
          @endforelse
        @endif
    </select>
  </div>
  <hr class="w-100">
  <span class="my-3">Familia del producto</span>
  <div class="row">    
    <div class="input-group mb-3 col-md-6">
      <select class="custom-select" id="inputGroupSelect01" name="categorias_id">
        <option value="0">Seleccione una familia</option>
        @forelse ($categorias as $categoria)
          <option value="{{$categoria->id}}" 
              @if ($categoria->id == $productos->categorias_id)
                selected
              @endif>
                {{$categoria->nombre}}
          </option>
        @empty
          
        @endforelse
      </select>
    </div>
  </div>

  <hr class="w-100">
  <span class="my-3">SubFamilia del producto</span>
  <div class="row">    
    <div class="input-group mb-3 col-md-6">
      <select class="custom-select"  name="subcategorias_id">
        <option value="0">Seleccione una subfamilia</option>
        @forelse ($subcategorias as $categoria)
          <option value="{{$categoria->id}}" 
              @if ($categoria->id == $productos->subcategorias_id)
                selected
              @endif>
                {{$categoria->nombre}}
          </option>
        @empty
          
        @endforelse
      </select>
    </div>
  </div>

  <hr class="w-100">

  <div class="form-group w-100">            
    <label>Seleccione producto relacionado</label><br>
    <select class="custom-select w-100" id="select2" multiple  aria-label="select example" name="relacionado[]">
        @if( isset($productos->obtenerRelacionados))        
          @foreach ($productosall as $producto)
          <?php $selected = '' ?>
            @if ($producto->id != $productos->id)
                @foreach ( $productos->obtenerRelacionados as $relacion)
                    @if($relacion->relacion_id == $producto->id)
                        <?php $selected = 'selected' ?>                                                   
                        @break
                    @else
                    @endif
                @endforeach
                <option {{$selected}} value="{{$producto->id}}">{{$producto->nombre}}</option>
            @endif
          @endforeach
        @else
            @forelse ($productosall as $producto)            
              @if ($producto->id != $productos->id)                    
                <option value="{{$producto->id}}">{{$producto->nombre}}</option>
              @endif
              @empty
            @endforelse
        @endif        
    </select>
</div>

<hr class="w-100">
<div class="w-100 d-flex justify-content-between align-items-center">
  <div class="col-2">Codigo</div>
  <div class="col-5">Presentaciones</div>
  <div class="col-2">Precio</div>
  <div class="col-2">Stock</div>
</div>
<div class="col-12 mt-2 d-flex justify-content-between align-items-start flex-wrap" id="presentacion">  
  @forelse ( $productos->obtenerPresentacionRelacionados as $relacion)
  <div class="w-100 d-flex justify-content-between align-items-center">
	<input type="hidden" name="idrelacion[]" value="{{$relacion->id}}">
    <input type="text" class="form-control my-2 col-2" name="codigoP[]" value="{{$relacion->codigo}}" placeholder="codigo">
    <input type="text" class="form-control my-2 col-5" name="presentacion[]" value="{{$relacion->presentacion}}" placeholder="Presentaci&oacute;n/Formato">
    <input type="number" step=".001" class="form-control my-2 col-2" name="precio[]" value="{{$relacion->precio}}" placeholder="Precio">
    <input type="number" class="form-control my-2 col-2" name="stock[]" value="{{$relacion->stock}}" placeholder="Stock">
	  <button id="boton" type="button" class="btn btn-danger">X</button>
  </div>
  @empty
  <div class="w-100 d-flex justify-content-between align-items-center">
    <input type="text" class="form-control my-2 col-2" name="codigoP[]" placeholder="codigo">
    <input type="text" class="form-control my-2 col-2" name="presentacion[]" placeholder="Presentaci&oacute;n/Formato">
    <input type="number" step=".001" class="form-control my-2 col-2" name="precio[]" placeholder="Precio">
    <input type="number" class="form-control my-2 col-2" name="stock[]" placeholder="Stock">
  </div>
    @endforelse
    <br><div class="w-100 d-flex justify-content-end pr-2 pl-2"><button id="boton" type="button" class="btn btn-primary" onclick="crear_fila()">+ FILA</button></div>
</div>
<div class="col-12 mt-4" id="clone"></div>
<hr class="w-100">

<div class="form-check">
  <input class="form-check-input" @if ($productos->destacado == "1") checked @endif type="checkbox" id="destacado" name="destacado">
  <label class="form-check-label" for="destacado">
    Destacar en la home
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" @if ($productos->activa == "1") checked @endif type="checkbox" id="activa" name="activa">
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

function eliminar(id){  
  $(id).parent().remove();
}
$("#select2").select2({
  placeholder: "Seleccione un opcion",
  allowClear: true,
  minimumInputLength: 0
});
function insertAfter(newNode, existingNode) {
  existingNode.parentNode.insertBefore(newNode, existingNode.nextSibling);
}
	function eliminarFila() {
		console.log("eliminar");
		var fila = $(this).closest('.w-100');
		fila.find('input[name="precio[]"]').val(0);
		fila.find('input[name="stock[]"]').val(0);
  		//fila.find('input').prop('disabled', true); // Deshabilitar los campos de entrada
  		//fila.remove();
  
}
$(document).on('click', '.btn-danger', eliminarFila);	
function crear_fila(){
  $("#boton").remove();
  var fila = $("#presentacion div:first").clone();
  $(fila).find('input').val("");
  $("#clone").append(fila);
  $("#clone").append(`<br><div class="w-100 d-flex justify-content-end pr-2 pl-2"><button id="boton" type="button" class="btn btn-primary" onclick="crear_fila()">+ FILA</button></div>`)
}
</script>

@endsection