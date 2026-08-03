<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>factura</title>
</head>
<body>
    <table style="width: 100%;font-size:11px">
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
                    <div style="border-bottom: 1px dashed #000">Domicilio de entrega{{$cliente->direccionEntrega}}</div>
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
                <td style="width: 10%;text-align: end;padding-right:20px;">PRECIO UNITARIO</td>
                <td style="width: 10%;text-align: end;">IMPORTE</td>
            </tr>
                 @php
                $cantidadTotal = 0;
                @endphp
            @forelse ($productosNegro as $producto)
            <tr>
                <td style="width: 5%;"> {{$producto->cantidad}}</td>
                @php
                $cantidadTotal +=intval($producto->cantidad);
                @endphp
                <td style="width: 75%">{{$producto->nombre}}</td>
                <td style="width: 10%;text-align: end;padding-right:20px;">  {{number_format($producto->precio, 2, ',', '.')}}</td>
                <td style="width: 10%;text-align: end;"> {{number_format(round(floatval($producto->precio)*intval($producto->cantidad),2),2,',', '.')}}</td>
                @php
                    $total +=floatval($producto->precio)*intval($producto->cantidad);                    
                @endphp
                @if ($descuento != 1)
                    @php
                        $totalDescuento = $total*$descuento;
                        $descuentoPrecio = $total-$totalDescuento;
                    @endphp
                @else
                    @php                    
                        $totalDescuento = $total;
                    @endphp
                @endif
                
            </tr>
            @empty
            
            @endforelse
            <tr>
                <td>
                    {{$cantidadTotal}}
                </td>
            </tr>
        </tbody>        
    </table>
    <hr style="width: 100%;">
    <table style="width: 100%;font-size:11px">
        <tbody>
            <tr>
                <td colspan="4" style="text-align: end"><div>Subtotal</div></td>
                <td colspan="1" style="text-align: end"><div>{{number_format($total,2,',', '.')}}</div></td>
            </tr>
            <tr>
                @if($descuento != 1)
                    <td colspan="4" style="text-align: end;">Bonificacion {{$cliente->descuento}}% </td>
                    <td colspan="1" style="text-align: end;"> -{{number_format(round($descuentoPrecio,2),2,',', '.')}}</td>
                @else
                    <td colspan="4" style="text-align: end;">Bonificacion </td>
                    <td colspan="1"></td>
                @endif
            </tr>
            <tr>
                <td colspan="4" style="text-align: end;">Subtotal</td>
                <td colspan="1" style="text-align: end;"> {{number_format(round($totalDescuento,2),2,',', '.')}}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 35px;"></td>
            </tr>           
            <tr>                
                <td style="text-align: end;width:100%;">
                    TOTAL
                </td>
            </tr>
            <tr>                
                <td  style="text-align: end;width:100%;">
					<b>{{number_format(round($totalDescuento,2),2,',', '.')}}</b>
                </td>
            </tr>            
        </tbody>
    </table>
    <hr style="width: 100%;">    	
    <div style="width:100%;padding:30px 0px;">
        *Comprobante no valido como factura
    </div>
</body>
</html>