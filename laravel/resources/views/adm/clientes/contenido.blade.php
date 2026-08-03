@extends('adm.layouts')

@section('content')
<div class="admin-page-header">
  <div>
    <span class="admin-eyebrow">Zona privada</span>
    <h1>Clientes registrados</h1>
    <p class="admin-muted mb-0">Listado ordenado por fecha y hora de registro, del mas reciente al mas antiguo.</p>
  </div>
  <div class="admin-actions">
    <a href="{{route('clientes_export_excel')}}" class="admin-btn admin-btn-secondary">Exportar</a>
    <a href="{{route('cliente.create')}}" class="admin-btn admin-btn-primary">Nuevo cliente</a>
  </div>
</div>

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<form method="GET" action="{{route('clientes.view')}}" class="admin-filter-bar">
    <div class="admin-field">
      <label for="clientes_q" class="mb-1">Buscar</label>
      <input id="clientes_q" class="form-control" placeholder="Empresa, usuario, email, CUIT o telefono" name="q" value="{{request('q')}}">
    </div>
    <div class="admin-field">
      <label for="clientes_estado" class="mb-1">Estado</label>
      <select id="clientes_estado" name="estado" class="form-control">
        <option value="">Todos</option>
        <option value="1" {{request('estado') === '1' ? 'selected' : ''}}>Activo</option>
        <option value="0" {{request('estado') === '0' ? 'selected' : ''}}>Inactivo</option>
      </select>
    </div>
    <div class="admin-filter-actions">
      <button type="submit" class="admin-btn admin-btn-primary">Buscar</button>
      <a href="{{route('clientes.view')}}" class="admin-btn admin-btn-secondary">Limpiar</a>
    </div>
 </form>
<div class="admin-table-wrap">
<table class="table admin-table">
  <thead>
    <tr>
      <th scope="col">Usuario</th>
      <th scope="col">Empresa</th>
      <th scope="col">Fecha y hora de registro</th>
      <th scope="col">Estado</th>
      
      <th scope="col">Accion</th>
    </tr>
  </thead>
 
  <tbody>
  	@foreach($Clientes as $cliente)
	    <tr>
	      <th scope="row">
          {{$cliente->username}}
          <small class="d-block admin-muted">{{$cliente->email}}</small>
          @if($cliente->emailAux)
            <small class="d-block admin-muted">{{$cliente->emailAux}}</small>
          @endif
        </th>
	      <td>
          <strong>{{$cliente->razonSocial ?: 'Sin empresa'}}</strong>
          @if($cliente->nombre || $cliente->apellido)
            <small class="d-block admin-muted">{{trim($cliente->nombre.' '.$cliente->apellido)}}</small>
          @endif
        </td>
        <td>
          @if($cliente->created_at)
            {{$cliente->created_at->copy()->timezone(config('app.display_timezone', 'America/Argentina/Buenos_Aires'))->format('d/m/Y H:i')}}
          @else
            {{$cliente->fechaInicio ?: 'Sin fecha registrada'}}
          @endif
        </td>
        <td>
          <span class="state-pill">{{$cliente->estado == 1 ? 'Activo' : 'Inactivo'}}</span>
        </td>
	     {{--  <td scope="row"><img src="{{asset(Storage::url($cliente->imagen))}}" class="img-thumbnail w-25"></td> --}}
	      {{-- <td>{!!$cliente->descripcion!!}</td> --}}
	      <td>
	      	<a class="admin-btn admin-btn-secondary" href="{{route('editarcliente',$cliente->id)}}" role="button">Editar</a>
			    <a class="admin-btn admin-btn-danger" href="{{route('eliminarcliente',$cliente->id)}}" onclick="return confirm('¿Estás seguro de que deseas eliminar este Cliente?');" role="button">Desactivar</a>

	      </td>
	    </tr>
    
	@endforeach
  </tbody>
</table>
</div>

@if($Clientes->links() !== null)
<div class="w-100 d-flex justify-content-center">
    {!! $Clientes->links() !!}
</div>
@endif

@endsection
