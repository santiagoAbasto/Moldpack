@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@include('adm.partials.filtros_pedidos', ['routeName' => $routeName ?? 'adm.contabilidad.pedidos', 'estados' => $estados ?? []])
<table class="table table-bordered">
      <thead style="background:#2E3091;color:#fff">
        <tr>            
	          <th class="pedido-col" style="color:#fff">N°</th>
          <th style="color:#fff">Estado</th>
          <th style="color:#fff">Cliente</th>
          <th style="color:#fff">Nombre</th>
          <th style="color:#fff" colspan="2">Eliminar</th>            
        </tr>
      </thead>
      <tbody>
      @forelse ( $pedidos as $item)
        <tr>
	          <td class="pedido-col"><span class="pedido-numero">#{{$item->id}}</span></td>
          <td>{{$item->estado}}</td>
          <td>{{$item->razonSocial}}</td>
          <td>{{$item->nombre}}</td>
          <td><span style="cursor:pointer;" onclick="mostrar_producto({{$item->id}})" >MODIFICAR</span></td>
          <td>
            <form method="POST" action="{{ route('adm.facturacion_delete', $item->id) }}" onsubmit="return confirm('¿Estás seguro de que deseas anular este pedido?');" style="display:inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="eliminar btn btn-link p-0">Eliminar</button>
            </form>
          </td>
        </tr>
      @empty
      @endforelse
      </tbody>
    </table>
@forelse ( $pedidos as $item)
<div class="card box_toggle" id="{{$item->id}}" style="display: none">
  <div class="row d-flex flex-row justify-content-center w-100">
    <div class="row w-100 p-4 ms-4 table-responsive">
      <table class="table table-bordered">
        <thead style="background:#2E3091;color:#fff">
          <tr>            
            <th style="color:#fff">NUMERO DE PEDIDO</th>
            <th style="color:#fff">FECHA DE PEDIDO</th>
            <th style="color:#fff">ESTADO DE PEDIDO</th>
            <th style="color:#fff">BULTOS</th>
            <th style="color:#fff">TOTAL</th>
            <th style="color:#fff">ACCIONES</th>            
          </tr>
        </thead>
        <tbody>
          <tr>              
            <th>{{$item->id}}</th>
            <th>{{$item->fecha}}</th>
            <th class="estado_{{$item->id}}">{{$item->estado}}</th>  
              <th class="bultos_{{$item->id}}">              
              {{$item->bultos}}
            </th>
            <th>$ {{round($item->total,2)}}</th>
            <th>
              <button class="btn btn-primary" onclick="mostrar_producto('modal_{{$item->id}}')" style="color:#fff;">VER DETALLE</button>              
            </th>
          </tr>
        </tbody>
      </table>
      <div id="msj" style="display:none;position: relative;left: 84%;background: green;width: 150px;text-align: center;border: 2px solid green;color:#fff;"></div>
    </div>
  </div>
</div>   
<!-- Modal -->
<div id="modal_{{$item->id}}" class="box_toggle" style="display: none;overflow-x: auto;">    
<div>
    <button class="btn btn-primary btn-duplicar my-3 mx-2" style="margin-bottom: 10px;" onclick="addEnvio('{{$item->id}}')">Facturar envio </button>
</div>
    <div id="modal_{{$item->id}}" style="min-width: 1075px;" >      
      <form class="d-flex flex-column table-responsive" method="post" action="{{route('adm.facturacion.post')}}"  id="tabla_{{$item->id}}">
        @csrf
        <div class="d-flex flex-row justify-content-between w-100 table">
		  <input type="hidden" value="{{$item->id}}" name="idPedido">
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
            <input readonly="readonly" class="form-control" id="nombre" name="nombre[]" value="{{$value->codigo}} {{$value->nombre}}">
            </input>
          </div>
          <div class="col-1 mb-2">
            <input type="number"  class="form-control" id="precio" name="precio[]" step="0.01" value="{{$value->precio}}">
            </input>
          </div>
          <div class="col-1 mb-2">
            <input  readonly="readonly" class="form-control" id="cantidad" name="cantidad[]" value="{{$value->cantidad}}">
            </input>
          </div>
          <div class="col-1 mb-2">
            @php
              $valueF = 0;
			  $valueP = 0;	
            @endphp
            @isset($value->cantidadF)
			@php
			$valueP = (int)$value->cantidad - (int)$value->cantidadF;
            @endphp
            @isset($value->cantidadP)
                @php $valueP = $value->cantidadP; @endphp
            @endisset
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
            
            <input disabled type="number" min="0" @if($valueP != 0) style="background-color:red;font-weight: 900;color: #000;" @endif class="form-control" value="{{$valueP}}" max="{{$value->cantidad}}">
            <input type="hidden" id="cantidad" name="cantidadP[]" value="{{$valueP}}">
            </input>
          </div>          
        </div>
        @empty

        @endforelse
        <div>
          <div class="form-check form-switch" style="margin-left: 0.75rem;">
            <input class="form-check-input" type="checkbox" value="{{$value->presentacionid}}" name="descuento" id="descuento" checked>
            <label class="form-check-label" for="descuento">Aplicar descuento {{$item->descuento}}%</label>
          </div>          
        </div>
        <div class="d-flex justify-content-end align-items-end w-100 mb-5">
          	<button type="submit" class="btn btn-primary mx-5" value="A" name="factura">Facturar A</button>
			<button type="submit" class="btn btn-primary" value="B" name="factura">Facturar B</button>
        </div>
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
	function addEnvio(id){
    var tabla = document.getElementById('tabla_'+id);
    var primeraFila = tabla.querySelector('#row');
    
    var nuevaFila = primeraFila.cloneNode(true);
    
     // Vaciar los campos de entrada
    nuevaFila.querySelectorAll('input').forEach(function(input) {
        input.value = '';
    });

    // Establecer los valores de los atributos name y id
    nuevaFila.querySelector('input[name="presentacion[]"]').value = 0;
    nuevaFila.querySelector('input[name="cantidad[]"]').value = 1;
    nuevaFila.querySelector('input[name="codigo[]"]').value = 0;    
	nuevaFila.querySelector('#nombre').removeAttribute('readonly');
	nuevaFila.querySelector('#nombre').setAttribute('type', 'text');

    tabla.insertBefore(nuevaFila, primeraFila);
}
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
	document.addEventListener('DOMContentLoaded', function() {
    const cantidadFInputs = document.querySelectorAll('input[name="cantidadF[]"]');
    const cantidadNInputs = document.querySelectorAll('input[name="cantidadN[]"]');
    const cantidadTotalInputs = document.querySelectorAll('input[name="cantidad[]"]');

    const validateSum = (index) => {
        let sumF = parseInt(cantidadFInputs[index].value || '0');
        let sumN = parseInt(cantidadNInputs[index].value || '0');
        let total = parseInt(cantidadTotalInputs[index].value || '0');

        if ((sumF + sumN) > total) {
            cantidadFInputs[index].style.backgroundColor = 'red';
            cantidadNInputs[index].style.backgroundColor = 'red';
        } else {
            cantidadFInputs[index].style.backgroundColor = '';
            cantidadNInputs[index].style.backgroundColor = '';
        }
    };

    cantidadFInputs.forEach((input, index) => {
        input.addEventListener('input', () => validateSum(index));
    });

    cantidadNInputs.forEach((input, index) => {
        input.addEventListener('input', () => validateSum(index));
    });
  });
</script> 

@endsection
