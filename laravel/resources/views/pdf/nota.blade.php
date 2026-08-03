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
                    <div><img src="{{asset('img/logo2.jpg')}}" width="150px" height="auto"> SRL</div>
                    <div>Domicilio Comercial: Dante Alighieri 1377</div>
                    <div>(1611) - Don Torcuato - Pcia de Bs As</div>
                    <div>Tel. (011) 4272-2836</div>
                    <div>Mail: info@moldpack.com.ar</div>                    
                </td>
                <td style="text-align: center;vertical-align:top;padding-top: 35px;width: 10%;">
                    		@if($nota == "debito")
						<div><b style="font-zise:15px;">Nota de debito</b></div>                        
						@else
						<span style="font-size:7px;padding: 5px 2px">Nota de credito</span>							
						@endif
                </td>
                <td style="text-align: end;vertical-align:top;padding-top: 35px;width: 45%;">
                    <div>FACTURA N° {{$new_factura}}</div> 
                    <div>Fecha: {{$fecha}}</div>
                    <div>CUIT: {{$cuit}}</div>
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
    <table>
        <tr>
            <td style="width: 50%">
                Nota de {{$nota}}
            </td>
            <td style="width: 50%">
                Descripcion {{$descripcion}}
            </td>
            <td style="width: 50%">$ {{$total}}</td>
        </tr>
    </table>    
    <hr style="width: 100%;">
    <table style="width: 100%;">        
        <tbody>
            <tr>
                <td>
                    <div>CAE N°: {{$res['CAE']}}</div>
                    <div>Fecha de Vto. de CAE: {{$res['CAEFchVto']}}</div>
                </td>
            </tr>
        </tbody>        
    </table>
    <div style="width:35px;">
        {!!  DNS2D::getBarcodeHTML( $res['CAE'], 'QRCODE') !!}                    
    </div>
</body>
</html>