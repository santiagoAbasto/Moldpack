@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<div class="container">
    <h1>Estadísticas de Ventas por Cliente</h1>
    <form method="get" action="{{ route('estat.clientes') }}">
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

    </form>
    <div class="w-100 d-flex justify-content-center align-items-center mb-2">
        <h3>Listado de clientes por ventas desde: <strong>{{ $fechaInicio }}</strong> hasta: <strong>{{ $fechaFin }}</strong> </h3>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Total Ventas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente['cliente']->nombre }}</td>
                    <td class="text-right">{{ number_format($cliente['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
