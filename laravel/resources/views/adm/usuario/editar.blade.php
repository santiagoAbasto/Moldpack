@extends('adm.layouts')

@section('content')
<div class="admin-page-header">
  <div>
    <span class="admin-eyebrow">Editar usuario</span>
    <h1>{{ $usuarios->name }}</h1>
  </div>
  <a href="{{route('usuarios')}}" class="admin-btn admin-btn-secondary">Volver</a>
</div>

<form method="post" action="{{route('updateusuarios', $usuarios->id)}}" enctype="multipart/form-data" class="admin-form-grid">
  @csrf
  @method('put')

  <section class="admin-form-panel">
    <h2>Datos de acceso</h2>
    <div class="row">
      <div class="form-group col-md-6">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required value="{{old('name', $usuarios->name)}}">
      </div>
      <div class="form-group col-md-6">
        <label for="username">Usuario</label>
        <input type="text" class="form-control" id="username" name="username" required value="{{old('username', $usuarios->username)}}">
      </div>
      <div class="form-group col-md-6">
        <label for="email">Correo</label>
        <input type="email" class="form-control" required id="email" name="email" value="{{old('email', $usuarios->email)}}">
      </div>
      <div class="form-group col-md-6">
        <label for="password">Nueva contraseña</label>
        <div class="input-group">
          <input type="password" class="form-control" id="password" name="password" value="">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="mostrarContrasena()">Mostrar</button>
          </div>
        </div>
      </div>
      <div class="form-group col-md-6">
        <label for="role">Rol</label>
        <select class="form-control js-role-select" id="role" name="role" required>
          @foreach($roles as $roleId => $roleName)
            <option value="{{$roleId}}" {{(string) old('role', $usuarios->role) === (string) $roleId ? 'selected' : ''}}>{{$roleName}}</option>
          @endforeach
        </select>
      </div>
    </div>
  </section>

  <section class="admin-form-panel">
    <h2>Modulos habilitados</h2>
    <div class="permission-grid">
      @foreach($moduleCatalog as $moduleKey => $module)
        <label class="permission-item">
          <input
            type="checkbox"
            data-module="{{$moduleKey}}"
            disabled
            {{array_key_exists($moduleKey, $userModules) ? 'checked' : ''}}
          >
          <span>
            <strong>{{$module['label']}}</strong>
            <small>{{$module['description']}}</small>
          </span>
        </label>
      @endforeach
    </div>
  </section>

  @if($canManageDashboardAccess)
    <section class="admin-form-panel">
      <h2>Dashboard del usuario</h2>
      @if(\App\Support\AdminDashboardAccess::isPrimarySalesUser($usuarios))
        <input type="hidden" name="dashboard_type" value="{{\App\Support\AdminDashboardAccess::SALES}}">
        <div class="admin-readonly-box">
          <strong>Metricas de ventas y pedidos</strong>
          <span>Este acceso queda fijo para pmathey.</span>
        </div>
      @else
        <div class="form-group mb-0">
          <label for="dashboard_type">Vista inicial del panel</label>
          <select class="form-control" id="dashboard_type" name="dashboard_type">
            @foreach($dashboardOptions as $optionValue => $optionLabel)
              <option value="{{$optionValue}}" {{(string) $dashboardType === (string) $optionValue ? 'selected' : ''}}>{{$optionLabel}}</option>
            @endforeach
          </select>
        </div>
      @endif
    </section>
  @endif

  <div class="admin-form-footer">
    <button type="submit" class="admin-btn admin-btn-primary">Guardar cambios</button>
  </div>
</form>

<script>
  function mostrarContrasena(){
    var tipo = document.getElementById("password");
    tipo.type = tipo.type === "password" ? "text" : "password";
  }

  (function () {
    var roleModules = @json($roleModules);
    var roleSelect = document.querySelector('.js-role-select');

    function updateModules() {
      var activeModules = roleModules[roleSelect.value] || [];
      document.querySelectorAll('[data-module]').forEach(function (input) {
        input.checked = activeModules.indexOf(input.dataset.module) !== -1;
      });
    }

    roleSelect.addEventListener('change', updateModules);
    updateModules();
  })();
</script>
@endsection
