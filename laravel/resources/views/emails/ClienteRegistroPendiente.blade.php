<table style="width:100%;max-width:620px;border-collapse:collapse;font-family:Arial,sans-serif;color:#333;">
    <tr>
        <td style="padding:24px;background:#EC458B;color:#fff;font-size:24px;font-weight:bold;">
            Moldpack
        </td>
    </tr>
    <tr>
        <td style="padding:24px;background:#fff;border:1px solid #eee;">
            <p style="font-size:18px;margin-top:0;">Hola {{ $cliente->nombre ?: $cliente->username }},</p>
            <p>Tu registro se ha completado con exito.</p>
            <p>En las proximas 24 hs nos contactaremos contigo via mail para la activacion de la pagina.</p>
            <p style="margin-bottom:0;">Gracias por comunicarte con nosotros.</p>
        </td>
    </tr>
    @if($contacto && ($contacto->correo || $contacto->telefono))
        <tr>
            <td style="padding:16px 24px;background:#f7f7f7;border:1px solid #eee;border-top:0;font-size:13px;">
                @if($contacto->correo)
                    <div>Correo: {{ $contacto->correo }}</div>
                @endif
                @if($contacto->telefono)
                    <div>Telefono: {{ $contacto->telefono }}</div>
                @endif
            </td>
        </tr>
    @endif
</table>
