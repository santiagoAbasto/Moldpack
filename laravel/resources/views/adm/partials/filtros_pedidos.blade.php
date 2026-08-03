<form method="GET" action="{{ route($routeName) }}" class="card mb-3 p-3">
  <div class="row align-items-end">
    <div class="form-group col-md-6 mb-2">
      <label for="{{ $routeName }}_q" class="mb-1">Buscar</label>
      <input
        type="text"
        id="{{ $routeName }}_q"
        name="q"
        value="{{ request('q') }}"
        class="form-control"
        placeholder="Pedido, cliente, fecha, producto o codigo"
      >
    </div>
    <div class="form-group col-md-3 mb-2">
      <label for="{{ $routeName }}_estado" class="mb-1">Estado</label>
      <select id="{{ $routeName }}_estado" name="estado" class="form-control">
        <option value="">Todos</option>
        @foreach(($estados ?? []) as $estado)
          <option value="{{ $estado }}" {{ request('estado') === $estado ? 'selected' : '' }}>{{ $estado }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group col-md-3 mb-2 d-flex">
      <button type="submit" class="btn btn-primary mr-2">Buscar</button>
      <a href="{{ route($routeName) }}" class="btn btn-outline-secondary">Limpiar</a>
    </div>
  </div>
</form>
