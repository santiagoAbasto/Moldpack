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
        > Historico
        </div>
    </div>

</div>
<div class="col-12 ps-4 py-2 d-flex justify-content-center" style="color:#EC458B;height: 35px;font-size:36px;font-weight: 600;">
    <div class="box_container">        
    <span>HISTORIAL DE PEDIDOS</span>
    </div>
</div>
<div class="col-12 ps-4 py-2 d-flex justify-content-center">
    <div class="box_container">        
<div class="row w-100 mt-4 border-0" >
    <table class="table table-striped box_table_contain " style="">
        <thead style="background:#EC458B;color:#fff" class="border-0">
          <tr>                        
	            <th class="col-1 pedido-col">PEDIDO</th>
            <th class="col-1">FECHA DEL PEDIDO</th>
            <th class="col-1">FACTURA</th>
            <th class="col-5">DETALLES DEL PEDIDO</th>
            <th class="text-end col-2">TOTAL</th>
            <th class="col-2"></th>
          </tr>
        </thead>
        <tbody class="" style="background:#fff;">
            
            @forelse ($pedido as $item)
            <tr>
	                <th class="col-1 pedido-col" style="padding: 8px 29px!important;"><span class="pedido-numero">#{{$item->id}}</span></th>
                <th class="col-1" style="padding: 8px 29px!important;">{{$item->fecha}}</th>
                <th class="col-1" style="padding: 8px 29px!important;">
                    {{$item->facturaA}}<br>
                    {{$item->facturaB}}
                </th>
                <th class="col-5" style="padding: 8px 29px!important;">
                @forelse ($item->pedido as $value)
                {{$value->cantidad}} {{$value->nombre}}
                @if (!$loop->last)
                ,
                @endif
                @empty            
                @endforelse
                </th>
                <th class="text-end col-2" style="padding: 8px 8px 8px 0px!important;">$ {{round($item->total,2)}}</th>
                <th class="col-2" style="padding: 8px 29px!important;">
                    <button style="font-size: 11px;font-weight: bold;width: 140px;color:#EC458B;border:1px solid #EC458B;background:#fff;" onclick="enviar({{$item->id}})" class="btn btn-danger me-4">RECOMPRAR</button>
                </th>
            </tr>
            @empty
                
            @endforelse          
        </tbody>
      </table>  
</div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
@include('ZonaPrivada.partials.carrito_storage')
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
                var obj_fila = [];
                response.forEach(function(item) {
                    var fila = {
                        cantidad: item.cantidad,
                        codigo: item.codigo,
                        imagen: item.imagen,
                        nombre: item.nombre,
                        precio: item.precio,
                        presentacion: item.presentacion,
                        presentacionid: item.presentacionid,
                        productoid: item.productoid,
                        stock: item.stock,
                        subtotal: item.subtotal
                    };
                    obj_fila.push(fila);
                });
                window.moldpackCartSet(obj_fila);
                var url = '{{route('carrito')}}';
                
                setTimeout(function(){ window.location.href = url;; }, 1500);
           },
           error: function(response){
               console.log(response);
               swal("Algo salio mal reintentar mas tarde","","error");
           }
         });



    }
    </script>
@endsection
