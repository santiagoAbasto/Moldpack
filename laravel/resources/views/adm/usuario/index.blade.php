@extends('adm.layouts')

@section('content')
<div class="admin-page-header">
  <div>
    <span class="admin-eyebrow">Usuarios internos</span>
    <h1>Permisos del panel</h1>
  </div>
  <a href="{{route('nuevousuarios')}}" class="admin-btn admin-btn-primary">Nuevo usuario</a>
</div>

@if(session()->has('success'))
  <div class="alert alert-success">{{ session()->get('success') }}</div>
@endif
@if(session()->has('danger'))
  <div class="alert alert-danger">{{ session()->get('danger') }}</div>
@endif

<form method="GET" action="{{route('usuarios')}}" class="admin-filter-bar">
  <div class="admin-field">
    <label for="usuarios_q">Buscar</label>
    <input id="usuarios_q" type="text" name="q" value="{{request('q')}}" class="form-control" placeholder="Usuario, nombre o correo">
  </div>
  <div class="admin-field">
    <label for="usuarios_role">Rol</label>
    <select id="usuarios_role" name="role" class="form-control">
      <option value="">Todos</option>
      @foreach($roles as $roleId => $roleName)
        <option value="{{$roleId}}" {{(string) request('role') === (string) $roleId ? 'selected' : ''}}>{{$roleName}}</option>
      @endforeach
    </select>
  </div>
  <div class="admin-filter-actions">
    <button type="submit" class="admin-btn admin-btn-primary">Buscar</button>
    <a href="{{route('usuarios')}}" class="admin-btn admin-btn-secondary">Limpiar</a>
  </div>
</form>

<div class="admin-table-wrap">
  <table class="table admin-table">
    <thead>
      <tr>
        <th>Usuario</th>
        <th>Nombre</th>
        <th>Rol</th>
        <th>Modulos visibles</th>
        <th>Accion</th>
      </tr>
    </thead>
    <tbody>
      @forelse($usuarios as $u)
        @php($userModules = \App\Support\AdminModules::modulesForUser($u))
        <tr>
          <td>
            <strong>{{ $u->username }}</strong>
            <span class="admin-muted d-block">{{ $u->email }}</span>
          </td>
          <td>{{ $u->name }}</td>
          <td><span class="admin-chip">{{ \App\Support\AdminModules::roleLabel($u->role) }}</span></td>
          <td>
            <div class="module-badges">
              @foreach($userModules as $module)
                <span>{{$module['label']}}</span>
              @endforeach
            </div>
          </td>
          <td class="admin-actions">
            <a class="admin-btn admin-btn-secondary" href="{{route('editarusuarios', $u->id)}}">Editar</a>
            <a class="admin-btn admin-btn-danger" href="{{route('eliminarusuarios', $u->id)}}" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Borrar</a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5">No hay usuarios para los filtros seleccionados.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if(method_exists($usuarios, 'links') && $usuarios->links() !== null)
  <div class="w-100 d-flex justify-content-center">
    {!! $usuarios->links() !!}
  </div>
@endif
@endsection
