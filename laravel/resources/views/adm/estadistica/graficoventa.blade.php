@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<div class="container">
    <h1>Ventas</h1>
    <form method="GET" action="{{ route('estat.grafventas') }}">
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
        </div>
    </form>

<canvas id="ventasChart" width="400" height="200"></canvas>
<h1>Clientes</h1>

<canvas id="ventasClienteChart" width="400" height="200"></canvas>

<script>
    const ctx = document.getElementById('ventasChart').getContext('2d');
    
    const ventasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Cantidad Vendida',
                    data: @json($cantidadVendida),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Cantidad Solicitada',
                    data: @json($cantidadSolicitada),
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Productos'
                    }
                }
            }
        }
    });
</script>
<script>
    const ctx1 = document.getElementById('ventasClienteChart').getContext('2d');
    
    const ventasClienteChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json($clienteLabels), // Etiquetas de clientes
            datasets: [{
                label: 'Total Ventas',
                data: @json($clienteTotals), // Totales de ventas por cliente
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Ventas'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Clientes'
                    }
                }
            }
        }
    });
</script>
</div>
@endsection