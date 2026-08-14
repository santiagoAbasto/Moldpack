@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@include('adm.partials.filtros_pedidos', ['routeName' => 'adm.facturas', 'estados' => $estados ?? []])
<div class="card esconder mb-1 d-flex flex-column flex-md-row justify-content-between align-items-center" >
<table class="table table-striped box_table_contain " style="">
        <thead style="background:#EC458B;color:#fff" class="border-0">
          <tr>                        
	            <th scope="col" class="pedido-col">N&deg; de pedido</th>
            <th scope="col">CLIENTE</th>
            <th scope="col">FECHA DE EMISI&Oacute;N</th>
            <th scope="col">COMENTARIO</th>
            <th scope="col">IMPORTE</th>            
            <th scope="col">COMPROBANTES</th>
            <th scope="col">ESTADO</th>
          </tr>
        </thead>
        <tbody class="">
            @forelse ($pedido as $item)
            @php
              $comprobantePagado = $item->obtenerRelacionados->firstWhere('estado', 'PAGADO');
              $ultimoComprobante = $comprobantePagado ?: $item->obtenerRelacionados->last();
            @endphp
            <tr>
	                <th scope="col" class="pedido-col" style="padding: 8px 29px!important;"><span class="pedido-numero">#{{$item->id}}</span></th>
                <th scope="col" style="padding: 8px 29px!important;">{{optional($item->cliente)->razonSocial ?? optional($item->cliente)->nombre ?? 'Cliente eliminado'}}</th>
                <th scope="col" style="padding: 8px 29px!important;">{{$item->fecha}}</th>
                <th scope="col" style="padding: 8px 29px!important;max-width:260px;">
                  @php $comentarioPedido = trim((string) ($item->mensaje ?? '')); @endphp
                  @if($comentarioPedido !== '')
                    <span style="display:block;color:#EC458B;font-weight:800;font-size:12px;text-transform:uppercase;">Comentario</span>
                    <span style="white-space:pre-wrap;">{{ $comentarioPedido }}</span>
                  @else
                    -
                  @endif
                </th>
                <th scope="col" style="padding: 8px 29px!important;">$ {{$item->facturaTotal}}</th>                
                <th scope="col" class="container_button" style="padding: 8px 29px!important;display:flex;flex-direction:column;gap:8px;align-items:flex-start;">
                @forelse ($item->obtenerRelacionados as $factura)
                
                  @if ($factura->factura == "A")
                    <a target="_blank" href="{{asset('pdf/'.$factura->relacion_id.'.pdf')}}" style="text-decoration:none;font-size: 11px;font-weight: bold;width: 140px;color:#EC458B;border:1px solid #EC458B;background:#fff;" class="btn btn-danger">Factura</a>
                  @endif
                  @if ($factura->factura == "N")
                    <a target="_blank" href="{{asset('pdf/'.$factura->relacion_id.'.pdf')}}" style="text-decoration:none;font-size: 11px;font-weight: bold;width: 140px;color:#EC458B;border:1px solid #EC458B;background:#fff;" class="btn btn-danger">Factura x</a>
                  @endif
                  @if ($factura->factura == "T")
                    <a target="_blank" href="{{asset('pdf/'.$factura->relacion_id.'.pdf')}}" style="text-decoration:none;font-size: 11px;font-weight: bold;width: 140px;color:#EC458B;border:1px solid #EC458B;background:#fff;" class="btn btn-danger">Remito Pedido</a>
                  @endif
                @empty
                  Sin comprobantes
                @endforelse
                </th>
                <th scope="col">
                  {{optional($ultimoComprobante)->estado === 'PAGADO' ? 'PAGADO' : 'PENDIENTE'}}
                </th>
            </tr>
            @empty
                
            @endforelse
        </tbody>
    </table>
</div>


@if($pedido->links() !== null)
<div class="w-100 d-flex justify-content-center">
    {!! $pedido->links() !!}
</div>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!--Alertify-->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
  $(document).on('keyup mouseup', '#editar', function() {
      let contenedor = this.parentElement;
      let fila = contenedor.parentElement
      let inputs = fila.querySelectorAll('input')
      inputs.forEach(element => {
        element.disabled = false;
      });
  });
  function enviar(id){    
    let formulario = document.getElementById(id)        
      console.log(formulario)
      console.log(formulario.querySelector("#codigo").value)       
      data = new FormData();
      data.append( 'codigo', formulario.querySelector("#codigo").value);
      data.append( 'cantidad', formulario.querySelector("#cantidad").value);
      data.append( 'precio', formulario.querySelector("#precio").value);
      data.append( 'nombre', formulario.querySelector("#nombre").value);
      data.append( 'total', formulario.querySelector("#total").value);
      data.append( 'id', formulario.querySelector("#idPedido").value);
      data.append( 'idItem', formulario.querySelector("#idItem").value);
      data.append( '_token', formulario.querySelector('[name="_token"]').value);
      $.ajax({
        url: '{{route('adm.update.pedido')}}',
        data: data,
        type: "post",
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType      
        success: function (response) {                  
          console.log(response);
          swal(response,"","success");        
        },
        error: function(response){
          console.log(response);
          swal("Algo salió mal","","error");
        }
      });
  }
    function mostrar_producto(item){
        $('.box_toggle').css('display','none')
        $('#'+item).toggle('esconder')        
    }    

    function aprobar(id){
        $.ajax({
          url:'{{route('adm.facturado.estado')}}',
          data: {'id': id, '_token': '{{ csrf_token() }}' },
          type:'post',
          success: function (response) {            
            $('#estadoFactura'+id).text('PAGADO')
          },
          statusCode: {
             404: function() {
                alert('web not found');
             }
          },
          error:function(x,xs,xt){              
              alert(JSON.stringify(x));              
          }
        });
    }    

    function armado(id){
        $.ajax({
          url:'{{route('adm.facturacion_put')}}',
          data: {'id': id, '_token': '{{ csrf_token() }}' },
          type:'post',
          success: function (response) {
            $('#tabla').hide();
            $('#msj').text(response['msj'])
            $('#msj').css('display','block')
            $('.estado_'+id).text('ARMANDO')
            console.log(response['msj'])
          },
          statusCode: {
             404: function() {
                alert('web not found');
             }
          },
          error:function(x,xs,xt){              
              alert(JSON.stringify(x));              
          }
        });
    }    
</script> 

@endsection
