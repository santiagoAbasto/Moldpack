@extends('adm.layouts')

@section('content')
<a href="{{route('nuevoproducto')}}" class="btn btn-success mb-5" >Nuevo Producto</a>
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<form method="GET" action="{{route('Productos')}}" class="card mb-4 p-3">
  <div class="row align-items-end">
    <div class="form-group col-md-4 mb-2">
      <label for="productos_q" class="mb-1">Buscar</label>
      <input id="productos_q" class="form-control" placeholder="Producto, codigo, presentacion o familia" name="q" value="{{request('q')}}">
    </div>
    <div class="form-group col-md-2 mb-2">
      <label for="productos_activa" class="mb-1">Estado</label>
      <select id="productos_activa" name="activa" class="form-control">
        <option value="">Todos</option>
        <option value="1" {{request('activa') === '1' ? 'selected' : ''}}>Activo</option>
        <option value="0" {{request('activa') === '0' ? 'selected' : ''}}>Desactivado</option>
      </select>
    </div>
    <div class="form-group col-md-3 mb-2">
      <label for="productos_categoria" class="mb-1">Familia</label>
      <select id="productos_categoria" name="categoria_id" class="form-control">
        <option value="">Todas</option>
        @foreach(($categorias ?? []) as $categoria)
          <option value="{{$categoria->id}}" {{(string) request('categoria_id') === (string) $categoria->id ? 'selected' : ''}}>{{$categoria->nombre}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-3 mb-2">
      <label for="productos_subcategoria" class="mb-1">Subfamilia</label>
      <select id="productos_subcategoria" name="subcategoria_id" class="form-control">
        <option value="">Todas</option>
        @foreach(($subcategorias ?? []) as $subcategoria)
          <option value="{{$subcategoria->id}}" {{(string) request('subcategoria_id') === (string) $subcategoria->id ? 'selected' : ''}}>{{$subcategoria->nombre}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-12 mb-2 d-flex justify-content-end">
      <button type="submit" class="btn btn-success mr-2">Buscar</button>
      <a href="{{route('Productos')}}" class="btn btn-outline-secondary">Limpiar</a>
    </div>
  </div>
</form>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Producto</th>
      <th scope="col">Familia</th>
      <th scope="col">Codigo</th>
      <th scope="col">Precio</th>
      <th scope="col">Activo</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
 
  <tbody>
      
  	@forelse($productos as $p)
      @php
        $primeraPresentacion = $p->obtenerPresentacionRelacionados->first();
      @endphp
	    <tr>        
	      <th scope="row">
			  <img src="{{asset(Storage::url($p->imagen))}}" width="100px" height="auto" onerror="this.src='{{asset('img/logo2.jpg')}}';">
			  @isset($p->obtenerCategoria()->nombre){{$p->obtenerCategoria()->nombre}}@endisset {{$p->nombre}}</th>	      
        <td>{{optional($p->obtenerFamilia)->nombre}} @if(optional($p->obtenerSubCategoria)->nombre) / {{optional($p->obtenerSubCategoria)->nombre}} @endif</td>
	     {{--  <td scope="row"><img src="{{asset(Storage::url($p->imagen))}}" class="img-thumbnail w-25"></td> --}}
	      <td>@if($primeraPresentacion) {{$primeraPresentacion->codigo}} @endif</td>
	      <td>@if($primeraPresentacion) $ {{ number_format($primeraPresentacion->precio, 2, ",", ".") }} @endif</td>
	      <td>@if($p->activa != 1 ) Desactivado @else Activo @endif</td>
	      <td>
	      	<a class="btn btn-warning" href="{{route('editarproducto',$p->id)}}" role="button">editar</a>
	      	<a class="btn btn-danger" href="{{route('eliminarproducto',$p->id)}}" role="button">borrar</a>

	      </td>
	    </tr>
    @empty
	@endforelse
  </tbody>
</table>

@if(method_exists($productos, 'links') && $productos->links() !== null)
<div class="w-100 d-flex justify-content-center">
    {!! $productos->links() !!}
</div>
@endif

<script>
  if (document.getElementById("file")) {
    document.getElementById("file").onchange = function(e) {

      if(this.value != null){
        $('#file_submint').removeAttr("disabled")
      }
    }
  }
</script>

@endsection
