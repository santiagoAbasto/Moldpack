@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<style>
  .cantidad-pendiente-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .cantidad-pendiente-alerta {
    color: #b42318;
    font-size: 12px;
    font-weight: 800;
    line-height: 1.15;
    text-transform: uppercase;
  }

  .cantidad-pendiente-campo.is-warning {
    background: #fff1f1;
    border: 2px solid #dc2626;
    color: #b42318;
    font-weight: 900;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
  }

  .cantidad-logistica-modificada {
    border: 2px solid #dc2626 !important;
    background: #fff1f1 !important;
    color: #b42318 !important;
    font-weight: 900;
  }

  .cantidad-logistica-alerta {
    color: #b42318;
    display: block;
    font-size: 11px;
    font-weight: 900;
    line-height: 1.15;
    margin-top: 4px;
    text-transform: uppercase;
  }
</style>
@include('adm.partials.filtros_pedidos', ['routeName' => 'pedidoAll', 'estados' => $estados ?? []])
<div class="card esconder mb-1 d-flex flex-column flex-md-row justify-content-between align-items-center" >
	<table class="table" style="margin-bottom:unset">
        <thead style="color:#fff">
          <tr>            
	            <th class="pedido-col" style="color:#858796;font-size: 14px;">N°</th>
            <th style="color:#858796;width: 34%;font-size: 14px;"><span class="" style="font-size:14px;">Cliente</span></th>
            <th style="color:#858796;width: 14%;"><span  style="font-size:14px;">Estado</span></th>            
            <th style="color:#858796;width: 14%; text-align:center;"><span  style="font-size:14px;">Total</span></th>
            <th style="color:#858796;width: 14%;font-size: 14px;"></th>
			<th style="color:#858796;width: 14%;font-size: 14px;"></th>
          </tr>
        </thead>
	</table>
</div>
@forelse ( $pedidos as $item)
<div class="card esconder mb-1 d-flex flex-column flex-md-row justify-content-between align-items-center" >
	<table class="table" style="margin-bottom:unset">
        <thead style="color:#fff">
          <tr>            
	            <th class="pedido-col" style="color:#858796;font-size: 14px;"><span class="pedido-numero">#{{$item->id}}</span></th>
            <th style="color:#858796;width: 34%;font-size: 14px;"><span class="" style="font-size:17px;">{{$item->razonSocial}}</span></th>
            <th style="color:#858796;width: 14%;"><span  style="font-size:14px;">{{$item->estado}}</span></th>            
            <th style="color:#858796;width: 16%;text-align:right"><span  style="font-size:14px;"><b>$ {{number_format(round($item->total,2),2,".",",")}}</b></span></th>
            <th style="color:#858796;width: 14%;font-size: 14px;"><span style="cursor:pointer;" onclick="mostrar_producto({{$item->id}})" >MODIFICAR</span></th>         		
			<th style="color:#858796;width: 14%;font-size: 14px;">
				<form method="POST" action="{{ route('adm.facturacion_delete', $item->id) }}" onsubmit="return confirm('¿Estás seguro de que deseas anular este pedido?');" style="display:inline">
					@csrf
					@method('DELETE')
					<button type="submit" class="eliminar btn btn-link p-0">Eliminar</button>
				</form>
			  </th>
          </tr>
        </thead>
	</table>

</div>
<div class="card box_toggle" id="{{$item->id}}" style="display: none">
  <div class="row d-flex flex-row justify-content-center w-100">
    <div class="row w-100 p-4 ms-4 table-responsive">
      <table class="table table-bordered">
        <thead style="background:#2E3091;color:#fff">
          <tr>            
            <th style="color:#fff">NUMERO DE PEDIDO</th>
            <th style="color:#fff">FECHA DE PEDIDO</th>
            <th style="color:#fff">ESTADO DE PEDIDO</th>            
            <th style="color:#fff">TOTAL</th>
            <th style="color:#fff">ACCIONES</th>            
          </tr>
        </thead>
        <tbody>
          <tr>              
            <th>{{$item->id}}</th>
            <th>{{$item->fecha}}</th>
            <th class="estado_{{$item->id}}">{{$item->estado}}</th>
            <th>$ {{number_format(round($item->total,2),2,".",",")}}</th>
            <th>
				<button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#ficha{{$item->id}}">
                Ficha de cliente
              </button><br>
              <button class="btn btn-primary" onclick="mostrar_producto('modal_{{$item->id}}')" style="color:#fff;">VER DETALLE</button>
				<div class="modal fade" id="ficha{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{optional($item->obtenerCliente)->razonSocial ?? 'Cliente eliminado'}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>DNI</th>
                <th>CUIT</th>
                <th>Direccion</th>
                <th>Direccion<br>entrega</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{optional($item->obtenerCliente)->nombre}}  {{optional($item->obtenerCliente)->apellido}}</td>
                <td>{{optional($item->obtenerCliente)->email}}</td>
                <td>{{optional($item->obtenerCliente)->telefono}}</td>
                <td>{{optional($item->obtenerCliente)->dni}}</td>
                <td>{{optional($item->obtenerCliente)->cuit}}</td>
                <td>{{optional($item->obtenerCliente)->direccion}}</td>
                <td>{{optional($item->obtenerCliente)->direccionEntrega}}</td>
              </tr>
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
            </th>
          </tr>
        </tbody>
      </table>
      @include('adm.partials.comentario_pedido', ['item' => $item])
      <div id="msj" style="display:none;position: relative;left: 84%;background: green;width: 150px;text-align: center;border: 2px solid green;color:#fff;"></div>
    </div>
  </div>
</div>   
<!-- Modal -->
<div id="modal_{{$item->id}}" class="box_toggle" style="display: none">    
    <div id="modal_{{$item->id}}">      
      <form class="d-flex flex-column" method="post" action="{{route('adm.facturacion.post')}}" id="tabla_{{$item->id}}">
        <input type="hidden" value="{{$item->id}}" name="idPedido">        
        @csrf
        <div class="d-flex flex-row justify-content-between w-100">
          <div class="col-6 mb-2">Producto</div>
          <div class="col-1 mb-2">Precio $</div>
          <div class="col-1 mb-2">Cantidad</div>
          <div class="col-1 mb-2">Cantidad A</div>
          <div class="col-1 mb-2">Cantidad X</div>
          <div class="col-1 mb-2">Cantidad Pendiente</div>
        </div>
        @forelse ($item->pedido as $value)		  
        <div class="d-flex flex-row justify-content-between w-100" id="row">
          <div class="col-6 mb-2">
            <input type="hidden" name="presentacion[]" value="{{$value->presentacionid}}">
            <input type="hidden" name="codigo[]" value="{{$value->codigo}}">
            <input type="hidden" name="idItem[]" value="{{$value->idPedido ?? $loop->index}}">
            <input type="hidden" name="cantidad_original_cliente[]" value="{{$value->cantidad_original_cliente ?? $value->cantidad}}">
            <input type="hidden" name="logistica_modificada[]" value="{{!empty($value->cantidad_modificada_logistica) ? 1 : 0}}">
            <input readonly="readonly" class="form-control" id="nombre" name="nombre[]" value="{{$value->codigo}} {{$value->nombre}}">
            </input>
          </div>
          <div class="col-1 mb-2">
            <input   class="form-control" id="precio" name="precio[]" value="{{$value->precio}}">
            </input>
          </div>
          <div class="col-1 mb-2">
            <input  readonly="readonly" class="form-control @if(!empty($value->cantidad_modificada_logistica)) cantidad-logistica-modificada @endif" id="cantidad" name="cantidad[]" value="{{$value->cantidad}}">
            </input>
            @if(!empty($value->cantidad_modificada_logistica))
              <small class="cantidad-logistica-alerta">Logistica modifico: original {{$value->cantidad_original_cliente ?? '-'}}</small>
            @endif
          </div>
          <div class="col-1 mb-2">
            @php
              $valueF = 0;
            @endphp
            @isset($value->cantidadF)
            @php $valueF = $value->cantidadF; @endphp
            @endisset
            <input type="number" min="0" class="form-control" id="cantidad" name="cantidadF[]" value="{{$valueF}}" max="{{$value->cantidad}}">
            </input>
          </div>
          <div class="col-1 mb-2">
            @php
            $valueN = 0;
            @endphp
            @isset($value->cantidadN)
            @php $valueN = $value->cantidadN; @endphp
            @endisset
            <input type="number" min="0" class="form-control" id="cantidad" name="cantidadN[]" value="{{$valueN}}" max="{{$value->cantidad}}">
            </input>
          </div>
          <div class="col-1 mb-2">
            @php
            $valueP = 0;
            @endphp
            @isset($value->cantidadP)
                @php $valueP = $value->cantidadP; @endphp
            @endisset
            <div class="cantidad-pendiente-wrap">
              <input readonly type="text" class="form-control cantidad-pendiente-campo" value="{{$valueP > 0 ? '+' . $valueP : $valueP}}">
              <input type="hidden" id="cantidad" name="cantidadP[]" value="{{$valueP}}">
              <small class="cantidad-pendiente-alerta"></small>
            </div>
          </div>          
        </div>
        @empty

        @endforelse
      </form>
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
  function numeroEnteroSeguro(valor) {
    const numero = parseInt(valor, 10);
    return Number.isNaN(numero) ? 0 : numero;
  }

  function pintarPendiente(row, recalcular) {
    const codigoInput = row.querySelector('input[name="codigo[]"]');
    const cantidadInput = row.querySelector('input[name="cantidad[]"]');
    const cantidadOriginalInput = row.querySelector('input[name="cantidad_original_cliente[]"]');
    const logisticaInput = row.querySelector('input[name="logistica_modificada[]"]');
    const cantidadFInput = row.querySelector('input[name="cantidadF[]"]');
    const cantidadNInput = row.querySelector('input[name="cantidadN[]"]');
    const pendienteInput = row.querySelector('.cantidad-pendiente-campo');
    const pendienteHidden = row.querySelector('input[name="cantidadP[]"]');
    const alerta = row.querySelector('.cantidad-pendiente-alerta');

    if (!cantidadInput || !cantidadFInput || !cantidadNInput || !pendienteInput || !pendienteHidden || !alerta) {
      return;
    }

    const cantidad = numeroEnteroSeguro(cantidadInput.value);
    const cantidadOriginal = cantidadOriginalInput ? numeroEnteroSeguro(cantidadOriginalInput.value) : cantidad;
    const logisticaModificada = logisticaInput && logisticaInput.value === '1';
    const cantidadA = numeroEnteroSeguro(cantidadFInput.value);
    const cantidadX = numeroEnteroSeguro(cantidadNInput.value);
    const esEnvio = codigoInput && numeroEnteroSeguro(codigoInput.value) === 0;
    const basePendiente = logisticaModificada ? cantidadOriginal : cantidad;
    const pendiente = recalcular
      ? (esEnvio ? 0 : basePendiente - cantidadA - cantidadX)
      : numeroEnteroSeguro(pendienteHidden.value);

    pendienteInput.value = pendiente > 0 ? '+' + pendiente : String(pendiente);
    pendienteHidden.value = pendiente;
    pendienteInput.classList.toggle('is-warning', pendiente !== 0);

    if (pendiente > 0) {
      alerta.textContent = logisticaModificada
        ? 'Faltan ' + pendiente + ' unidad(es). Logistica preparo ' + cantidad + ' de ' + cantidadOriginal
        : 'Faltan ' + pendiente + ' unidad(es)';
    } else if (pendiente < 0) {
      alerta.textContent = 'Sobran ' + Math.abs(pendiente) + ' unidad(es)';
    } else {
      alerta.textContent = '';
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[id^="tabla_"] #row').forEach(function(row) {
      pintarPendiente(row, false);
    });

    document.addEventListener('input', function(event) {
      if (!event.target.matches('input[name="cantidadF[]"], input[name="cantidadN[]"], input[name="cantidad[]"]')) {
        return;
      }

      const row = event.target.closest('#row');
      if (row) {
        pintarPendiente(row, true);
      }
    });

    document.addEventListener('focusin', function(event) {
      if (!event.target.matches('input[name="cantidadF[]"], input[name="cantidadN[]"], input[name="cantidad[]"], input[name="nombre[]"]')) {
        return;
      }

      const row = event.target.closest('#row');
      if (row) {
        pintarPendiente(row, true);
      }
    });

    document.addEventListener('click', function(event) {
      const row = event.target.closest('form[id^="tabla_"] #row');
      if (row) {
        pintarPendiente(row, true);
      }
    });
  });

  $(document).on('keyup mouseup', '#editar', function() {
      let contenedor = this.parentElement;
      let fila = contenedor.parentElement
      let inputs = fila.querySelectorAll('input')
      inputs.forEach(element => {
        element.disabled = false;
      });
      let select = fila.querySelectorAll('select')
      select.forEach(element => {
        element.disabled = false;
      });
  });
  function enviar(id){
    let formulario = document.getElementById(id)     
    let select = formulario.querySelector("#nombre");
    console.log(select.options[select.selectedIndex].dataset.precio)
    //var text = e.options[e.selectedIndex].text;      
      data = new FormData();
      data.append( 'codigo', select.options[select.selectedIndex].dataset.codigo);
      data.append( 'cantidad', formulario.querySelector("#cantidad").value);
      data.append( 'precio', select.options[select.selectedIndex].dataset.precio);
      data.append( 'idPresentacion', select.options[select.selectedIndex].dataset.id);
      data.append( 'nombre', formulario.querySelector("#nombre").value);      
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
          swal('Registro modificado',"","success");
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

  function eliminar(id){
    let formulario = document.getElementById(id)     
    let select = formulario.querySelector("#nombre");
    console.log(select.options[select.selectedIndex].dataset.precio)
    //var text = e.options[e.selectedIndex].text;      
      data = new FormData();
      data.append( 'codigo', select.options[select.selectedIndex].dataset.codigo);
      data.append( 'cantidad', formulario.querySelector("#cantidad").value);
      data.append( 'precio', select.options[select.selectedIndex].dataset.precio);
      data.append( 'idPresentacion', select.options[select.selectedIndex].dataset.id);
      data.append( 'nombre', formulario.querySelector("#nombre").value);      
      data.append( 'id', formulario.querySelector("#idPedido").value);
      data.append( 'idItem', formulario.querySelector("#idItem").value);
      data.append( '_token', formulario.querySelector('[name="_token"]').value);
      $.ajax({
        url: '{{route('adm.pedido.eliminar')}}',
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

    function armado(id){
        $.ajax({
          url:'{{route('pedido_put2')}}',
          data: {'id': id, '_token': '{{ csrf_token() }}' },
          type:'post',
          success: function (response) {
            $('#tabla').hide();
            swal('Registro modificado',"","success");
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
