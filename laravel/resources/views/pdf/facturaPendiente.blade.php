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
                </td>
                <td style="text-align: center;vertical-align:top;padding-top: 35px;width: 10%;">                    
                </td>
                <td style="text-align: end;vertical-align:top;padding-top: 35px;width: 45%;">
                    <div><b>NOTA DE PEDIDO</b></div> 
                    <div>Pedido N° {{$nPedido}}</div> 
                    <div>Fecha: {{$fecha}}</div>
                </td>
            </tr>
            <tr style="margin-top:15px;">
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
    <table style="width: 100%;font-size:13px;">
        <tbody>
            @php
                $total = 0;
            @endphp
            <tr>
                <td style="width: 5%;">CANTIDAD</td>
                <td style="width: 75%">DETALLE</td>                
            </tr>
            @forelse ($productosPendiente as $producto)
            <tr>
                <td style="width: 5%;"> {{$producto->cantidad}}</td>
                <td style="width: 75%">{{$producto->nombre}}</td>
            </tr>
            @empty
            
            @endforelse
            
        </tbody>        
    </table>
    <hr style="width: 100%;">        
    <div style="width:100%;padding:30px 0px;">
        *Comprobante no valido como factura
    </div>
</body>
</html>