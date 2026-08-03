<!DOCTYPE html>
<html lang="en">
<head style="padding: 0px;margin:0px;">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>factura</title>
</head>
<body style="padding: 0px;margin:5px;">
    <table style="width: 100%;font-size:11px;">
        <tbody>
            <tr>
                <td style="width: 45%;">
                    <div><img src="{{asset('img/logo2.jpg')}}" width="150px" height="auto"> SRL</div>
                    <div style="margin-top:15px;">Domicilio Comercial: Dante Alighieri 1377</div>
                    <div>(1611) - Don Torcuato - Pcia de Bs As</div>
                    <div>Tel. {{$contacto->celular}}</div>
                    <div>Mail: {{$contacto->correF}}</div>
                    <b>Iva Responsable Inscripto</b>                    
                </td>
                <td style="text-align: center;vertical-align:top;padding-top: 35px;width: 10%;">
                    <div style="border: 1px solid #000;padding:10px;">
                        <span style="font-size:7px;padding: 5px 2px">ORIGINAL</span>
                        <div><b style="font-zise:15px;">{{$tipoFactura}}</b></div>
						@if($tipoFactura == "A")
                        <span style="font-size:6px;padding: 5px 2px">COD N°001</span>
						@else
						<span style="font-size:6px;padding: 5px 2px">COD N°006</span>
						@endif
                    </div>
                </td>
                <td style="text-align: end;vertical-align:top;padding-top: 35px;width: 45%;padding:left:10px;">
					<div><b>FACTURA</b></div>
					<div>N° 0006-{{str_pad($num_factura,8,"0",STR_PAD_LEFT)}}</div> 
					<div>Pedido N° {{$pedido->id}}</div>
                    <div>Fecha: {{$fecha}}</div>
                    <div>CUIT: 30714394874</div>
                    <div>Ingresos Brutos C.M. 901-612100-0</div>
                    <div>Fecha de Inicio de Actividades: 22/11/2013</div>
                    <div>Vencimiento: {{$res['CAEFchVto']}}</div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="border-bottom: 1px dashed #000">Señores {{$cliente->razonSocial}}</div>
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
                    @if ($tipoFactura == "A")
                    <div style="border-bottom: 1px dashed #000">IVA RESP. INSCRIPTO</div>
                    @else
                    <div style="border-bottom: 1px dashed #000">IVA </div>
                    @endif
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
    <table style="width: 100%;font-size:10px;">        
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
            @forelse ($productos as $producto)
            <tr>
                <td style="width: 5%;"> {{$producto->cantidad}}</td>
                @php
                
                $cantidadTotal +=intval($producto->cantidad);
                @endphp
                <td style="width: 75%">{{$producto->nombre}}</td>
                <td style="width: 10%;text-align: end;padding-right:20px;"> {{number_format($producto->precio,2,',', '.')}}</td>
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
                <td><b>{{$cantidadTotal}}</b></td>
                <td>Total mercaderia</td>
                <td></td>
                <td></td>                
            </tr>
        </tbody>        
    </table>
    <hr style="width: 100%;">
    <table style="width: 100%;font-size:10px;">
        <tbody>
            <tr>
                <td colspan="4" style="text-align: end"><div><b>Subtotal</b></div></td>
                <td colspan="1" style="text-align: end"><div><b>{{number_format($total,2,',', '.')}}</b></div></td>
            </tr>
            <tr>
                @if($descuento != 1)
                    <td colspan="4" style="text-align: end;"><b>Bonificacion {{$cliente->descuento}}%</b> </td>
                    <td colspan="1" style="text-align: end;"><b> -{{number_format(round($descuentoPrecio,2),2,',', '.')}}</b></td>
                @else
                    <td colspan="4" style="text-align: end;"><b>Bonificacion</b> </td>
                    <td colspan="1"></td>
                @endif
            </tr>
            <tr>
                <td colspan="4" style="text-align: end;"><b>Subtotal</b></td>
                <td colspan="1" style="text-align: end;"><b style="font-size:11px;"> {{number_format(round($totalDescuento,2),2,',', '.')}}</b></td>
            </tr>
            <tr>
                <td style="padding-bottom: 8px;"></td>
            </tr>
            @if ($tipoFactura == "A")                
            <tr>
                <td>
                    <b>SUBTOTAL</b>
                </td>
                <td>
                    <b>GRAVADO</b>
                </td>
                <td>
                    <b>EXENTO</b>
                </td>
                <td>
                    <b>IVA 21%</b>
                </td>
                <td style="text-align: end">
                    <b>TOTAL</b>
                </td>
            </tr>
            <tr>
                <td>
                    <b style="font-size:11px;">{{number_format(round($ImpNeto,2),2,',', '.')}}</b>
                </td>
                <td>
                    
                </td>
                <td>
                    
                </td>
                <td>
					<b style="font-size:11px;">{{number_format(round($ImpIVA,2),2,',', '.')}}</b>                    
                </td>
                <td style="text-align: end;font-size:11px;">
                    @php                    
                        $totalIva = $totalDescuento+$ImpIVA;
                    @endphp
                    <b>{{number_format(round($totalIva,2),2,',', '.')}}</b>
                </td>
            </tr>
            @else
            <tr>                
                <td style="text-align: end;width:100%;">
                    <b>TOTAL</b>
                </td>
            </tr>
            <tr>                
                <td  style="text-align: end;width:100%;font-size:11px;">
					<b>{{number_format(round($total,2),2,',', '.')}}</b>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    <hr style="width: 100%;">
    <table style="width: 100%;">        
        <tbody>
            <tr>
                <td style="width:80px;">
                    <img src="{{asset('img/afiplogo.png')}}" width="80px" height="auto">
                </td>
                <td  align="left" style="font-size:10px;">
                    <b>Comprobante Autorizado</b><br>
                    <span>CAE N°: {{$res['CAE']}}</span><br>
                    <span>Fecha de Vto. de CAE: {{$res['CAEFchVto']}}</span>
                </td>
            </tr>
        </tbody>        
    </table>
    <div style="width:35px!important;">
        {!!  DNS2D::getBarcodeHTML( $res['CAE'], 'QRCODE',5,5) !!}                    
    </div>
</body>
</html>