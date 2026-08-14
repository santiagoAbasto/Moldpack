@php
    $comentarioPedido = trim((string) ($item->mensaje ?? ''));
@endphp

@if($comentarioPedido !== '')
    <div class="alert alert-warning my-3" style="border-left: 5px solid #EC458B;background:#fff8fb;color:#303342;">
        <div style="font-size:12px;font-weight:800;text-transform:uppercase;color:#EC458B;letter-spacing:.02em;">
            Comentario del pedido
        </div>
        <div style="font-size:15px;font-weight:600;white-space:pre-wrap;">{{ $comentarioPedido }}</div>
    </div>
@endif
