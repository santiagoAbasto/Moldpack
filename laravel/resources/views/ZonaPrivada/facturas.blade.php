@extends('layouts.plantilla')

@section('content')

<style>
   tbody th {
        font-size:16px;
        color:#77787B;
        padding: unset!important;
    }
    .tabla_container{
        padding: 4vh 8vh!important;
    }
    @media (max-width: 600px) {
        .box_titulo_btn {
            display:flex!important;
            flex-flow:column!important;
        }
        .box_table_contain{
            display:flex!important;
            flex-flow:column!important
        }
        .box_table_contain thead tr{
            display:flex!important;
            flex-flow:column!important
        }
        .box_table_contain tbody tr{
            display:flex!important;
            flex-flow:column!important
        }
        .box_detalle{
            font-size:10px;
            color:#000;
            font-weight: bold;
        }
        .container_button{
            flex-flow:column!important;
            align-items: start!important;
        }
        .container_button button{
            margin:unset!important;
            margin-bottom:10px!important;            
        }
        .tabla_container{
            padding: 4vh 2vh!important;
        }
    }
</style>
<?php
    $email = Auth::guard('cliente')->user()->email;
    $username = Auth::guard('cliente')->user()->username;
    $id = Auth::guard('cliente')->user()->id;
?>



<div class="col-12 ps-4 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
    <div class="col-12 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
        <div class="box_container">
        <a style="text-decoration: none;color:#000;" href="{{route('page.pedido')}}">Pedidos</a>
        > Facturas
        </div>
    </div>

</div>
<div class="col-12 ps-4 py-2 d-flex justify-content-center">
	    <div class="box_container">        
    <form method="GET" action="{{route('zp.factura')}}" class="mb-3 d-flex flex-column flex-md-row gap-2">
        <input type="text" name="q" value="{{request('q')}}" class="form-control" placeholder="Buscar por pedido, fecha, comprobante o estado">
        <div class="d-flex gap-2">
            <button type="submit" class="btn" style="background:#EC458B;color:#fff;">Buscar</button>
            <a href="{{route('zp.factura')}}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>
	<div class="row w-100 mt-4 border-0" >
	    
	    <table class="table table-striped box_table_contain " style="">
        <thead style="background:#EC458B;color:#fff" class="border-0">
          <tr>                        
	            <th scope="col" class="pedido-col">N&deg; de pedido</th>
            <th scope="col">FECHA DE EMISI&Oacute;N</th>
            <th scope="col">IMPORTE</th>            
            <th scope="col">COMPROBANTES</th>
            <th scope="col">ESTADO</th>
          </tr>
        </thead>
        <tbody class="" style="background:#fff;">
            @forelse ($pedido as $item)            
            @php
              $comprobantePagado = $item->obtenerRelacionados->firstWhere('estado', 'PAGADO');
              $ultimoComprobante = $comprobantePagado ?: $item->obtenerRelacionados->last();
            @endphp
            <tr>
	                <th scope="col" class="pedido-col" style="padding: 8px 29px!important;"><span class="pedido-numero">#{{$item->id}}</span></th>
                <th scope="col" style="padding: 8px 29px!important;">{{$item->fecha}}</th>
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
    @if(method_exists($pedido, 'links') && $pedido->links() !== null)
    <div class="w-100 d-flex justify-content-center">
        {!! $pedido->links() !!}
    </div>
    @endif
	    </div>
	</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
    function enviar(pedido){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        var email = '{{ $email }}';
        var user = '{{ $id }}';
        var username = '{{ $username }}';
        var form_data = new FormData();

        form_data.append("id", pedido);        
        form_data.append("usuario_id", user);
        form_data.append("email", email);
        form_data.append("username", username);        

        $.ajax({           
           url: '{{route('recomprar')}}',
           data: form_data,
           type: "post",           
           processData: false,  // tell jQuery not to process the data
           contentType: false,   // tell jQuery not to set contentType                 
           success: function (response) {
               swal("Pedido realizado","","success");               
               console.log(response)
               //setTimeout(function(){ location.reload(); }, 1500);
           },
           error: function(response){
               console.log(response);
               swal("Algo salió mal reintentar mas tarde","","error");
           }
         });



    }
    </script>
@endsection
