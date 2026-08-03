@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@include('adm.partials.filtros_pedidos', ['routeName' => 'adm.facturacion', 'estados' => $estados ?? []])

@forelse ( $pedidos as $item)

<div class="card esconder mb-1 d-flex flex-column flex-md-row justify-content-between align-items-center" >
    <div class="d-flex align-items-center">
	    <h4 class="m-0 p-3">Numero de pedido <span class="pedido-numero d-inline-block">#{{$item->id}}</span></h4>  
    |<span class="pl-2" style="font-size:17px;">Cliente: {{$item->username}}</span>
    </div>
    <div class="col-md-3 d-flex justify-content-between" style="text-align:center">
      <span style="cursor:pointer;" onclick="mostrar_producto({{$item->id}})" >MODIFICAR</span>
      <div style="border-left: 1px solid #000;"></div>
      <form method="POST" action="{{ route('adm.facturacion_delete', $item->id) }}" onsubmit="return confirm('¿Estás seguro de que deseas anular este pedido?');" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="eliminar btn btn-link p-0">Eliminar</button>
      </form>
    </div>
</div>
<div class="card box_toggle" id="{{$item->id}}" style="display: none">
  <div class="row d-flex flex-row justify-content-center w-100">
    <div class="row w-100 p-4 ms-4 table-responsive">
      <table class="table table-bordered">
        <thead style="background:#2E3091;color:#fff">
          <tr>            
	            <th class="pedido-col" style="color:#fff">NUMERO DE PEDIDO</th>
            <th style="color:#fff">FECHA DE PEDIDO</th>
            <th style="color:#fff">ESTADO DE PEDIDO</th>
            <th style="color:#fff">TOTAL</th>
            <th style="color:#fff">ACCIONES</th>            
          </tr>
        </thead>
        <tbody>
          <tr>              
	            <th class="pedido-col"><span class="pedido-numero">#{{$item->id}}</span></th>
            <th>{{$item->fecha}}</th>
            <th class="estado_{{$item->id}}">{{$item->estado}}</th>
            <th>{{$item->total}}</th>                                
            <th>
              <button class="btn btn-primary" onclick="mostrar_producto('modal_{{$item->id}}')" style="color:#fff;">VER DETALLE</button>
              <button onclick="aprobar({{$item->id}})" style="background: green;color:#fff;" class="btn">ENTREGADO</button>
            </th>
          </tr>
        </tbody>
      </table>
      <div id="msj" style="display:none;position: relative;left: 84%;background: green;width: 150px;text-align: center;border: 2px solid green;color:#fff;"></div>
    </div>
  </div>
</div>   

<!-- Modal -->
<div id="modal_{{$item->id}}" class="box_toggle" style="display: none">    
    <div id="modal_{{$item->id}}" class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Codigo</th>
            <th scope="col">Produco</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Precio</th>
            <th scope="col">Total</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($item->pedido as $value)
			
          <tr id="form{{$loop->index}}{{$item->id}}">
              <input type="hidden" id="idPedido" value="{{$item->id}}">
              <input type="hidden" id="idItem" value="{{$value->idPedido}}">
              @csrf
              <td><input disabled class="form-control" type="text" value="{{$value->codigo}}" id="codigo" name="codigo"></td>
              <td><input disabled class="form-control" type="text" value="{{$value->nombre}}" id="nombre" name="nombre"></td>
              <td><input disabled class="form-control" type="number" value="{{$value->cantidad}}" id="cantidad" name="cantidad"></td>
              <td><input disabled class="form-control" type="numbre" value="{{$value->precio}}" id="precio" name="precio"></td>
              <td><input disabled class="form-control" type="number" value="{{floatval($value->precio)*intval($value->cantidad)}}" id="total" name="total"></td>
              <td class="d-flex flex-row justify-content-between">
                <button id="editar" type="button" class="btn btn-primary mr-2">Editar</button>
                <button id="btnSubmit" type="button" onclick="enviar('form{{$loop->index}}{{$item->id}}')" class="btn btn-danger">Guardar</button>
              </td>
          </tr>          
            @empty
            @endforelse
        </tbody>
      </table>   
    </div>
  
</div>
@empty
    
@endforelse

@if($pedidos->links() !== null)
<div class="w-100 d-flex justify-content-center">
    {!! $pedidos->links() !!}
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
          url:'{{route('adm.facturacion_put')}}',
          data: {'id': id, '_token': '{{ csrf_token() }}' },
          type:'post',
          success: function (response) {
            $('#tabla').hide();
            $('#msj').text(response['msj'])
            $('#msj').css('display','block')
            $('.estado_'+id).text('ENTREGADO')
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
