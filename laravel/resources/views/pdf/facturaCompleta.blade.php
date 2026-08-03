<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>factura</title>
</head>
<body>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="width: 45%;">
                    <img src="{{asset('img/logo2.jpg')}}" width="auto" height="auto">
                    <div>Domicilio Comercial: Dante Alighieri 1377</div>
                    <div>(1611) - Don Torcuato - Pcia de Bs As</div>                    
                </td>
                <td style="text-align: center;vertical-align:top;padding-top: 35px;width: 10%;">
                    <div style="border: 1px solid #000;padding:30px;"><b>R</b></div>
                </td>
                <td style="text-align: end;vertical-align:top;padding-top: 35px;width: 45%;">
                    <div>Pedido N° {{$nPedido}}</div>                     
                    <div>Fecha: {{$fecha}}</div>
                </td>
            </tr>
            <tr style="padding-top:15px;">
                <td colspan="3">
                    <div style="border-bottom: 1px dashed #000">Señores {{$cliente->razonSocial}}</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="border-bottom: 1px dashed #000">Nombre de fantasia {{$cliente->nombre}}</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="border-bottom: 1px dashed #000">Domicilio {{$cliente->direccion}}</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="border-bottom: 1px dashed #000">Domicilio de entrega {{$cliente->direccionEntrega}}</div>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td></td>
                <td>
                    <div style="border-bottom: 1px dashed #000">CUIT {{$cliente->cuit}}</div>               
                </td>
            </tr>
            <tr>
                <td>
                    <div style="border-bottom: 1px dashed #000">Cond. Venta</div>
                </td>
                <td></td>
                <td>
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="width: 100%;">
	<div class="width:100%">
	Bultos: {{$pedido->bultos}}
	</div>
	<hr style="width: 100%;">
    <table style="width: 100%;font-size:13px;">        
        <tbody>
            @php
                $total = 0;
            @endphp
            <tr>
                <td style="width: 5%;">CANTIDAD</td>
                <td style="width: 95%">DETALLE</td>
            </tr>
            @php
                $cantidadTotal = 0;
            @endphp
            @forelse ($productosCompleto as $producto)
                @if($producto->cantidad != "0" || $producto->cantidad > 0)
                <tr>
                    <td style="width: 5%;"> {{$producto->cantidad}}</td>
                    @php
                    $cantidadTotal +=intval($producto->cantidad);
                    @endphp
                    <td style="width: 95%">{{$producto->nombre}}</td>
                </tr>
                @endif
            @empty
            
            @endforelse
            <tr>
                <td>
                    TOTAL: 
                </td>
                <td>
                    {{$cantidadTotal}}
                </td>
            </tr>
        </tbody>        
    </table>
    <hr style="width: 100%;">
</body>
</html>