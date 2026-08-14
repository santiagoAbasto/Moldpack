@extends('adm.layouts')

@section('content')
<style>
  .confirmacion-borrado-backdrop {
    position: fixed;
    inset: 0;
    z-index: 2050;
    display: none;
    align-items: center;
    justify-content: center;
    background: rgba(12, 18, 32, 0.42);
    backdrop-filter: blur(6px);
  }

  .confirmacion-borrado-backdrop.is-visible {
    display: flex;
  }

  .confirmacion-borrado-modal {
    width: min(420px, calc(100vw - 32px));
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 24px 70px rgba(15, 23, 42, 0.24);
    padding: 24px;
    color: #111827;
    text-align: center;
  }

  .confirmacion-borrado-icono {
    width: 54px;
    height: 54px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff1f1;
    color: #dc2626;
    font-size: 24px;
    margin-bottom: 14px;
  }

  .confirmacion-borrado-modal h3 {
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 8px;
  }

  .confirmacion-borrado-modal p {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 22px;
  }

  .confirmacion-borrado-acciones {
    display: flex;
    gap: 10px;
    justify-content: center;
  }
</style>
<a href="{{route('nuevoproducto')}}" class="btn btn-success mb-5" >Nuevo Producto</a>
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@if(session()->has('warning'))
    <div class="alert alert-warning">
        {{ session()->get('warning') }}
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
          <button type="button" class="btn btn-danger btn-delete-producto" data-url="{{route('eliminarproducto',$p->id)}}" data-nombre="{{$p->nombre}}">borrar</button>

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

<div class="confirmacion-borrado-backdrop" id="confirmacionBorradoProducto" aria-hidden="true">
  <div class="confirmacion-borrado-modal" role="dialog" aria-modal="true" aria-labelledby="confirmacionBorradoTitulo">
    <div class="confirmacion-borrado-icono">
      <i class="fas fa-trash-alt"></i>
    </div>
    <h3 id="confirmacionBorradoTitulo">Eliminar producto</h3>
    <p id="confirmacionBorradoTexto">Esta accion no se puede deshacer.</p>
    <div class="confirmacion-borrado-acciones">
      <button type="button" class="btn btn-outline-secondary" id="cancelarBorradoProducto">Cancelar</button>
      <a href="#" class="btn btn-danger" id="confirmarBorradoProducto">Eliminar</a>
    </div>
  </div>
</div>

<script>
  (function () {
    const modal = document.getElementById('confirmacionBorradoProducto');
    const confirmar = document.getElementById('confirmarBorradoProducto');
    const cancelar = document.getElementById('cancelarBorradoProducto');
    const texto = document.getElementById('confirmacionBorradoTexto');

    document.querySelectorAll('.btn-delete-producto').forEach(function (boton) {
      boton.addEventListener('click', function () {
        const separador = this.dataset.url.indexOf('?') === -1 ? '?' : '&';
        confirmar.setAttribute('href', this.dataset.url + separador + 'confirm=1');
        texto.textContent = 'Vas a eliminar "' + (this.dataset.nombre || 'este producto') + '". Esta accion no se puede deshacer.';
        modal.classList.add('is-visible');
        modal.setAttribute('aria-hidden', 'false');
      });
    });

    function cerrarConfirmacion() {
      modal.classList.remove('is-visible');
      modal.setAttribute('aria-hidden', 'true');
      confirmar.setAttribute('href', '#');
    }

    cancelar.addEventListener('click', cerrarConfirmacion);
    modal.addEventListener('click', function (event) {
      if (event.target === modal) {
        cerrarConfirmacion();
      }
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && modal.classList.contains('is-visible')) {
        cerrarConfirmacion();
      }
    });
  })();

  if (document.getElementById("file")) {
    document.getElementById("file").onchange = function(e) {

      if(this.value != null){
        $('#file_submint').removeAttr("disabled")
      }
    }
  }
</script>

@endsection
