@extends('adm.layouts')


<script src="{{asset('js/producto.js')}}"></script>


@section('content')
<form method="post" action="{{route('crearproducto')}}" enctype="multipart/form-data">
	@csrf
  <div class="row">

    <div class="form-group col-md-6">
      <label for="orden">Orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >
    </div>

    <div class="form-group col-md-12">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" >
    </div>

    <div class="form-group col-md-12">
      <label for="descripcion">Descripcion</label>
      <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
    </div>

    <div class="row">
      <div class="form-group">
        <label for="imagen">Imagen de producto</label>
        <input type="file" class="form-control-file" required id="imagen" name="imagen" >
       <small>Resolucion recomendada: 285px X 273px</small>
      </div>    
    </div>

    <div class="form-group my-2">
      <label for="galeria">Galeria de Imagenes</label>
      <input type="file" class="form-control-file" id="galeria" name="galeria[]" multiple="">
      <small>Resolucion recomendada: 285px X 273px</small>  
    </div>
    
  </div>

  <hr class="w-100">

  <span class="my-3">Color</span>
  <div class="row mb-4">    
    <div class="input-group  col-md-6">
      <select class="custom-select" id="inputGroupSelect01" name="categorias_id" multiple> 
        @forelse ($colors as $color)
          <option value="{{$color->id}}">{{$color->nombre}}</option>          
        @empty          
          <option value="0">No hay colores creadas</option>  
        @endforelse
      </select>
    </div>
  </div>

  <hr class="w-100">

  <span class="my-3">Familia del producto</span>
  <div class="row mb-4">    
    <div class="input-group  col-md-6">
      <select class="custom-select" id="inputGroupSelect01" name="categorias_id">
        <option value="0">Seleccione una familia</option>
        @forelse ($categorias as $categoria)
          <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>          
        @empty          
          <option value="0">No hay categorias creadas</option>  
        @endforelse
      </select>
    </div>
  </div>

  <hr class="w-100">

  <span class="my-3">SubFamilia del producto</span>
  <div class="row mb-4">    
    <div class="input-group  col-md-6">
      <select class="custom-select" id="inputGroupSelect01" name="subcategorias_id"> 
        <option value="0">Seleccione una subfamilia</option>
        @forelse ($subcategorias as $categoria)
          <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>          
        @empty          
          <option value="0">No hay categorias creadas</option>  
        @endforelse
      </select>
    </div>
  </div>

  <hr class="w-100">
  <div class="w-100 d-flex justify-content-between align-items-center">
    <div class="col-2">Codigo</div>
    <div class="col-5">Presentaciones</div>
    <div class="col-2">Precio</div>
    <div class="col-2">Stock</div>
  </div>
  <div class="col-12 mt-2 d-flex justify-content-between align-items-start flex-wrap" id="presentacion">
    <div class="w-100 d-flex justify-content-between align-items-center">
      <input type="text" class="form-control my-2 col-2" name="codigoP[]" placeholder="codigo">
      <input type="text" class="form-control col-5" name="presentacion[]" placeholder="Presentaci&oacute;n/Formato">
      <input type="number" step=".01" class="form-control col-2" name="precio[]" placeholder="Precio">
      <input type="number" class="form-control my-2 col-2" name="stock[]" placeholder="Stock">
    </div>
      <br><div class="w-100 d-flex justify-content-end pr-2 pl-2"><button id="boton" type="button" class="btn btn-primary" onclick="crear_fila()">+ FILA</button></div>
  </div>
  <div class="col-12 mt-4" id="clone"></div>
  <hr class="w-100">

  <div class="form-check">
    <input class="form-check-input" type="checkbox" id="destacado" name="destacado">
    <label class="form-check-label" for="destacado">
      Destacar en la home
    </label>
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
<script>
  $("#select2").select2({
    placeholder: "Seleccione un opcion",
    allowClear: true,
    minimumInputLength: 0
  });
  function eliminar(id){      
    $(id).parent().remove();
  }
  function crear_fila(){            
      $("#boton").remove();
      var fila = $("#presentacion:first").clone();
      $(fila).find('input').val("");
      $("#clone").append(fila);
      $("#clone").append(`<br><div class="w-100 d-flex justify-content-end pr-2 pl-2"><button id="boton" type="button" class="btn btn-primary" onclick="crear_fila()">+ FILA</button></div>`)
  }
</script>
@endsection