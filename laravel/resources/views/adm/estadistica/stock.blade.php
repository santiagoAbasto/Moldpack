@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<div class="container">
    <h1>Stock </h1>
    <!-- <form method="get" action="{{ route('estat.clientes') }}">
        <div class="w-100 d-flex justify-content-center align-items-center">
            <label class="mx-2">Fecha de Inicio: 
                <input type="date" name="fecha_inicio" class="form-control form-control-sm mx-2">
            </label>
            <label class="mx-2">Fecha de Fin: 
                <input type="date" name="fecha_fin" class="form-control form-control-sm mx-2">
            </label>
        </div>
        <div class="w-100 d-flex justify-content-center align-items-center mb-2">
            <button id="consultar" type="submit" class="btn btn-primary mx-2 flex-grow-1">Consultar</button>
            <a href="{{ route('export.clientesVentas') }}?fecha_inicio={{ request()->input('fecha_inicio') }}&fecha_fin={{ request()->input('fecha_fin') }}" class="btn btn-success mx-2 flex-grow-1">Exportar a Excel</a>
        </div>

    </form> -->
    <div class="w-100 d-flex justify-content-center align-items-center mb-2">
        <h3>Stock Actual</h3>
    </div>
    <div class="w-100 d-flex justify-content-center align-items-center mb-2">
        <a href="{{ route('export.stockexcel') }}" class="btn btn-success">Exportar a Excel</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Presentación</th>
                <th>Codigo</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                @foreach ($producto->presentaciones as $presentacion)
                    <tr>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $presentacion->presentacion }}</td>
                        <td>{{ $presentacion->codigo }}</td>
                        <td class="text-right">{{ number_format($presentacion->stock, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
