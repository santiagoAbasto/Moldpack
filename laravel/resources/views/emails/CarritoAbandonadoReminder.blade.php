<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:24px;background:#f6f7fb;font-family:Arial,sans-serif;color:#1f2430;">
    @php
        $cliente = $carritoAbandonado->cliente;
        $items = collect($carritoAbandonado->items ?: []);
        $baseUrl = config('app.url');

        if (!$baseUrl || preg_match('/localhost|127\.0\.0\.1/i', $baseUrl)) {
            $baseUrl = 'https://www.moldpack.com.ar';
        }

        $carritoUrl = rtrim($baseUrl, '/').'/carrito';
    @endphp

    <div style="max-width:720px;margin:0 auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e5e8f0;">
        <div style="padding:28px 30px;border-bottom:3px solid #EB468B;text-align:center;">
            <img src="{{ url('img/logo2.jpg') }}" alt="Moldpack" style="max-width:180px;height:auto;">
        </div>

        <div style="padding:30px;">
            <h1 style="margin:0 0 12px;font-size:24px;line-height:1.25;color:#1f2430;">Tu carrito quedo pendiente</h1>
            <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#596273;">
                @if($cliente)
                    Hola {{ trim(($cliente->nombre ?? '').' '.($cliente->apellido ?? '')) ?: ($cliente->email ?? 'cliente') }},
                @else
                    Hola,
                @endif
                vimos que dejaste productos cargados en tu carrito. Si necesitas ayuda para cerrar el pedido, respondé este correo y el equipo de Moldpack te acompaña.
            </p>

            <div style="background:#fff7fb;border:1px solid #f7c8dd;border-radius:8px;padding:16px 18px;margin-bottom:22px;">
                <strong style="display:block;font-size:13px;text-transform:uppercase;color:#EB468B;margin-bottom:6px;">Resumen</strong>
                <div style="font-size:15px;color:#303342;">
                    Productos: <strong>{{ $carritoAbandonado->items_count }}</strong><br>
                    Total estimado: <strong>$ {{ number_format((float) $carritoAbandonado->total_estimado, 2, ',', '.') }}</strong>
                </div>
            </div>

            <table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
                <thead>
                    <tr style="background:#EB468B;color:#ffffff;">
                        <th style="padding:10px;text-align:left;font-size:13px;">Codigo</th>
                        <th style="padding:10px;text-align:left;font-size:13px;">Producto</th>
                        <th style="padding:10px;text-align:center;font-size:13px;">Cant.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items->take(12) as $item)
                        <tr>
                            <td style="padding:10px;border-bottom:1px solid #edf0f5;font-size:13px;color:#303342;">{{ data_get($item, 'codigo', '-') }}</td>
                            <td style="padding:10px;border-bottom:1px solid #edf0f5;font-size:13px;color:#303342;">
                                {{ data_get($item, 'nombre', 'Producto') }}
                                @if(data_get($item, 'presentacion'))
                                    <br><span style="color:#6b7280;">{{ data_get($item, 'presentacion') }}</span>
                                @endif
                            </td>
                            <td style="padding:10px;border-bottom:1px solid #edf0f5;text-align:center;font-size:13px;color:#303342;">{{ (int) data_get($item, 'cantidad', 1) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding:16px;text-align:center;color:#777;">No hay productos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p style="margin:0 0 22px;text-align:center;">
                <a href="{{ $carritoUrl }}" style="display:inline-block;background:#EB468B;color:#ffffff;text-decoration:none;font-weight:bold;border-radius:6px;padding:13px 22px;">Continuar pedido</a>
            </p>

            <p style="margin:0;color:#8a90a0;font-size:12px;line-height:1.5;text-align:center;">
                Este correo se envia una sola vez por carrito pendiente. Si ya hiciste el pedido, podes ignorarlo.
            </p>
        </div>
    </div>
</body>
</html>
