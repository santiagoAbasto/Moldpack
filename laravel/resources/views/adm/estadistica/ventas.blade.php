@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<div class="container">
    <h1>Estadísticas de Ventas</h1>
    <form method="get" action="{{ route('estat.ventas') }}">
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
        <a href="{{ route('export.productosVendidos') }}?fecha_inicio={{ request()->input('fecha_inicio') }}&fecha_fin={{ request()->input('fecha_fin') }}" class="btn btn-success mx-2 flex-grow-1">Exportar a Excel</a>
    </div>


    </form>
    <div class="w-100 d-flex justify-content-center align-items-center mb-2">
        <h3>Listado de productos vendidos desde: <strong>{{ $fechaInicio }}</strong> hasta: <strong>{{ $fechaFin }}</strong> </h3>
    </div>
    <table class="table">
        <thead> 
            <tr> 
                <th>Código</th> 
                <th>Nombre</th> 
                <th class="text-right">Precio</th> 
                <th class="text-right">Cantidad Solicitada</th> 
                <th class="text-right">Cantidad Vendida</th> 
            </tr> 
        </thead>
        <tbody>
            @foreach($productosVendidos as $codigoProducto => $producto) 
            <tr>
                <td>{{ @$codigoProducto }}</td>
                <td>{{ @$producto['nombre_producto'] }}</td>
                <td class="text-right">$ {{ @number_format($producto['precio_producto'], 2) }}</td>
                <td class="text-right">{{ @$producto['cantidad_solicitada'] }}</td>
                <td class="text-right">{{ @$producto['cantidad_vendida'] }}</td>
            </tr>
            @endforeach
        </tbody> 
    </table> 
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!--Alertify-->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>


@endsection