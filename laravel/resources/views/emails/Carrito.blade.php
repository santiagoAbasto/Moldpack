<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 20px; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <div style="max-width: 800px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">

        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e0e0e0;">
            <img src="{{ url('img/logo2.jpg') }}" alt="MOLDPACK" style="max-width: 200px; height: auto; display: block; margin: 0 auto;">
</div>
        
        <!-- Encabezado -->
        <div style="margin-bottom: 30px; border-bottom: 2px solid #e0e0e0; padding-bottom: 20px;">
            <h2 style="margin: 0; color: #333; font-size: 24px;">Nueva Venta</h2>
            <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">
                Realizado el @php $date = date('d/m/Y') @endphp {{$date}}
            </p>
            <p style="margin: 15px 0 0 0; color: #333; font-size: 16px;">
                <strong>Pedido #{{$pedido_carrito->numeroPedido}}</strong>
            </p>
            <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">
                Se ha registrado una nueva venta. A continuación se detallan los datos del comprador y los productos adquiridos.
            </p>
            <p style="margin: 15px 0 0 0; color: #666; font-size: 14px; font-style: italic;">
                La entrega se realizará dentro de los 7 y 15 días hábiles. A confirmar con MOLDPACK.
            </p>
        </div>

        <!-- Información del Cliente -->
        <div style="margin-bottom: 30px;">
            <h3 style="margin: 0 0 15px 0; color: #333; font-size: 18px; border-bottom: 2px solid #EB468B; padding-bottom: 8px;">Datos del Cliente</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9; font-weight: bold; width: 200px; color: #333;">Nombre</td>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; color: #666;">{{$pedido_carrito->usario->nombre}} {{$pedido_carrito->usario->apellido}}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9; font-weight: bold; color: #333;">Email</td>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; color: #666;">{{$pedido_carrito->usario->email}}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9; font-weight: bold; color: #333;">Empresa</td>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; color: #666;">{{$pedido_carrito->usario->razonSocial ?? 'N/A'}}</td>
                </tr>
                @if(isset($pedido_carrito->usario->direccionEntrega) && $pedido_carrito->usario->direccionEntrega)
                <tr>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9; font-weight: bold; color: #333;">Dirección de envío</td>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; color: #666;">{{$pedido_carrito->usario->direccionEntrega}}</td>
                </tr>
                @endif
                @if($pedido_carrito->entregatext)
                <tr>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9; font-weight: bold; color: #333;">Envío</td>
                    <td style="padding: 10px; border: 1px solid #e0e0e0; color: #666;">{{$pedido_carrito->entregatext}}</td>
                </tr>
                @endif
</table>
        </div>

        <!-- Detalle de Compra -->
        <div style="margin-bottom: 30px;">
            <h3 style="margin: 0 0 15px 0; color: #333; font-size: 18px; border-bottom: 2px solid #EB468B; padding-bottom: 8px;">Detalle de Compra</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #EB468B; color: #ffffff;">
                        <th style="padding: 12px; border: 1px solid #d42a7a; text-align: left; font-weight: bold;">Código</th>
                        <th style="padding: 12px; border: 1px solid #d42a7a; text-align: left; font-weight: bold;">Descripción</th>
                        <th style="padding: 12px; border: 1px solid #d42a7a; text-align: center; font-weight: bold;">Cantidad</th>
                        <th style="padding: 12px; border: 1px solid #d42a7a; text-align: right; font-weight: bold;">Precio</th>
    </tr>
                </thead>
                <tbody>
    @forelse ($pedido_carrito->pedido as $item)
                    <tr style="background-color: {{ $loop->even ? '#f9f9f9' : '#ffffff' }};">
                        <td style="padding: 10px; border: 1px solid #e0e0e0; color: #333; font-weight: 500;">{{$item->codigo}}</td>
                        <td style="padding: 10px; border: 1px solid #e0e0e0; color: #666;">{{$item->nombre}}</td>
                        <td style="padding: 10px; border: 1px solid #e0e0e0; text-align: center; color: #333;">{{$item->cantidad}}</td>
                        <td style="padding: 10px; border: 1px solid #e0e0e0; text-align: right; color: #333; font-weight: 500;">$ {{number_format($item->precio, 2, ',', '.')}}</td>
    </tr>
    @empty
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center; color: #999; border: 1px solid #e0e0e0;">No hay productos en el pedido</td>
                    </tr>
    @endforelse
                </tbody>
            </table>

            <!-- Resumen de Totales -->
            <div style="margin-top: 20px; padding: 20px; background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Subtotal:</td>
                        <td style="padding: 8px 0; text-align: right; color: #333; font-size: 14px; font-weight: 500;">$ {{number_format(isset($pedido_carrito->total_sin_descuento) ? $pedido_carrito->total_sin_descuento : $pedido_carrito->total_pedido, 2, ',', '.')}} ARS</td>
                    </tr>
                    @if(isset($pedido_carrito->descuentoCarrito) && $pedido_carrito->descuentoCarrito != 0 && isset($pedido_carrito->total_sin_descuento))
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Descuento ({{$pedido_carrito->descuentoCarrito}}%):</td>
                        <td style="padding: 8px 0; text-align: right; color: #d32f2f; font-size: 14px; font-weight: 500;">- $ {{number_format($pedido_carrito->total_sin_descuento - $pedido_carrito->total_con_descuento, 2, ',', '.')}} ARS</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Total con descuento:</td>
                        <td style="padding: 8px 0; text-align: right; color: #333; font-size: 14px; font-weight: 500;">$ {{number_format($pedido_carrito->total_con_descuento, 2, ',', '.')}} ARS</td>
                    </tr>
                    @endif
                    @if(isset($pedido_carrito->porcentaje_iva) && $pedido_carrito->porcentaje_iva > 0 && isset($pedido_carrito->monto_iva))
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">IVA ({{$pedido_carrito->porcentaje_iva}}%):</td>
                        <td style="padding: 8px 0; text-align: right; color: #333; font-size: 14px; font-weight: 500;">$ {{number_format($pedido_carrito->monto_iva, 2, ',', '.')}} ARS</td>
                    </tr>
                    @endif
                    <tr style="border-top: 2px solid #EB468B;">
                        <td style="padding: 12px 0 0 0; color: #333; font-size: 18px; font-weight: bold;">TOTAL:</td>
                        <td style="padding: 12px 0 0 0; text-align: right; color: #EB468B; font-size: 18px; font-weight: bold;">$ {{number_format($pedido_carrito->total_pedido, 2, ',', '.')}} ARS</td>
                    </tr>
                </table>
            </div>

            <!-- Mensaje/Observaciones -->
    @isset($pedido_carrito->mensaje)
            <div style="margin-top: 20px; padding: 15px; background-color: #f5f5f5; border-left: 4px solid #757575; border-radius: 4px;">
                <p style="margin: 0 0 8px 0; font-weight: bold; color: #333;">Observaciones:</p>
                <p style="margin: 0; color: #666; font-style: italic;">{{$pedido_carrito->mensaje}}</p>
            </div>
    @endisset
        </div>

        <!-- Pie de página -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: center; color: #999; font-size: 12px;">
            <p style="margin: 0;">Este es un correo automático generado por el sistema MOLDPACK</p>
        </div>
    </div>
</body>
</html>