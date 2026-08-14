@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@include('adm.partials.filtros_pedidos', ['routeName' => 'adm.facturado', 'estados' => $estados ?? []])
<div class="card esconder mb-1 d-flex flex-column flex-md-row justify-content-between align-items-center" >
	<table class="table" style="margin-bottom:unset">
        <thead style="color:#fff">
          <tr>            
	            <th class="pedido-col" style="color:#858796;font-size: 14px;">N° Pedido</th>
            <th style="color:#858796;width: 34%;font-size: 14px;">Cliente</th>
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
            <th style="color:#858796;width: 34%;font-size: 14px;">{{$item->razonSocial}}</th>
            <th style="color:#858796;width: 14%;font-size: 14px;">
              <span style="cursor:pointer;" onclick="mostrar_producto({{$item->id}})" >MODIFICAR</span></th>
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
            <th style="color:#fff">BULTOS</th>
            <th style="color:#fff">TOTAL<br>SIN IVA</th>
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
            <th>
              @php
              $total = 0;
              @endphp
              @forelse ($item->pedido as $pedido)
              @php              
                $precio = $pedido->precio;
                $cantidad = $pedido->cantidad;
                $resultado = floatval($precio) * intval($cantidad);
                $total += $resultado;
                $total = round($total,2);
              @endphp
            @empty
              
            @endforelse
            $ {{ number_format(round($total,2),2,".",",") }}
            </th>
            <th>$ {{number_format(round($item->total,2),2,".",",") }}</th>
            <th class="d-flex justify-content-between flex-wrap">
              <button class="btn btn-primary mb-3" onclick="mostrar_producto('modal_{{$item->id}}')" style="color:#fff;">Ver detalle</button>
              
              <!-- Button trigger modal -->
<button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#ficha{{$item->id}}">
  Ficha de cliente
</button>

<!-- Modal -->
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
              
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal{{$item->id}}">
                  Nota de credito
                </button>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#debito{{$item->id}}">
                  Nota de debito
                </button>
                <!-- Modal CREDITO -->
                <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel{{$item->id}}" aria-hidden="true">
                  <form method="post" action="{{route('adm.facturacion.nota.post',$item->id)}}">
                    @csrf
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel{{$item->id}}">Nota de credito</h5>
						  <span>TOTAL CON IVA: {{round($item->total*1.21,2)}}</span>
                      </div>
                      <div class="modal-body">
                        Seleccione una factura
                          <select name="factura" class="form-control mb-5" required>                            
                          @forelse ($item->obtenerRelacionados->where('factura','=','A') as $factura)
                            <option value="{{$factura->numeroFactura}}">Factura N&deg;:{{$factura->numeroFactura}} $ {{$factura->total}}</option>
                          @empty
                            
                          @endforelse
                          </select>
                          <label for="cantidad">Monto $</label>
                          <input type="number" class="form-control" name="cantidad" min="1" id="cantidad" value="1"  step=0.01 required>
                          <label for="descripcion">Descripcion</label>
                          <input type="text" class="form-control" name="descripcion" id="descripcion" required>
                        <div class="form-check">
					  <input class="form-check-input" value="A" type="radio" name="facturar" id="factura1">
					  <label class="form-check-label" for="factura1">
						Responsable Inscripto
					  </label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" value="B" name="facturar" id="factura2" checked>
					  <label class="form-check-label" for="factura2">
						Monotributista
					  </label>
					</div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" value="credito" name="nota" class="btn btn-primary" style="color:#fff;">Generar nota</button>
                      </div>
                    </div>
                  </div>
                </form>
                </div>
                <!-- Modal DEBITO -->
                <div class="modal fade" id="debito{{$item->id}}" tabindex="-1" aria-labelledby="debitoLabel" aria-hidden="true">
                  <form method="post" action="{{route('adm.facturacion.nota.post',$item->id)}}">
                    @csrf
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="debitoLabel">Nota de debito</h5>
						  <span>TOTAL CON IVA: {{round($item->total*1.21,2)}}</span>
                      </div>
                      <div class="modal-body">
                          Seleccione una factura
                          <select name="factura" class="form-control mb-5" required>                            
                          @forelse ($item->obtenerRelacionados->where('factura','=','A') as $factura)
                            <option value="{{$factura->numeroFactura}}">Factura N°:{{$factura->numeroFactura}} $ {{$factura->total}}</option>
                          @empty
                            
                          @endforelse
                          </select>
                          <label for="cantidad">Monto $</label>
                          <input type="number" class="form-control" name="cantidad" min="1" id="cantidad" value="1" step=0.01 required>
                          <label for="descripcion">Descripcion</label>
                          <input type="text" class="form-control" name="descripcion" id="descripcion" required>
                                                <div class="form-check">
					  <input class="form-check-input" value="A" type="radio" name="facturar" id="factura1">
					  <label class="form-check-label" for="factura1">
						Responsable Inscripto
					  </label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" value="B" name="facturar" id="factura2" checked>
					  <label class="form-check-label" for="factura2">
						Monotributista
					  </label>
					</div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" value="debito" name="nota" class="btn btn-primary" style="color:#fff;">Generar Nota</button>
                      </div>
                    </div>
                  </div>
                  </form>
                </div>
                
                
              </form>
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
      <div class="w-100 py-5 table-responsive">
        <table class="w-100 table">
          <tbody>
            <tr>
              <td>Tipo</td>
              <td>Archivo</td>
              <td>Estado</td>
              <td></td>
            </tr>
            @forelse ($item->obtenerRelacionados as $factura)     
            <tr>
            <td>
              @if ($factura->factura == "A")
                Factura
              @endif
              @if ($factura->factura == "N")
                X
              @endif
              @if ($factura->factura == "T")
                Remito Pedido
              @endif
              @if ($factura->factura == "P")
                Pendiente
              @endif
              @if ($factura->factura == 'debito' || $factura->factura == 'credito')
                Nota de {{$factura->factura}}
              @endif
            </td>
            <td>              
            @if ($factura->factura == 'debito' || $factura->factura == 'credito')
              <a target="_blank" href="{{asset('pdf/'.$factura->factura.$factura->relacion_id.'.pdf')}}" style="text-decoration:none;font-size: 11px;font-weight: bold;width: 140px;color:#EC458B;border:1px solid #EC458B;background:#fff;" class="btn btn-danger me-4">Nota de {{$factura->factura}} ${{$factura->total}}</a>
            @else          
              <a target="_blank" href="{{asset('pdf/'.$factura->relacion_id.'.pdf')}}" style="text-decoration:none;font-size: 11px;font-weight: bold;width: 140px;color:#EC458B;border:1px solid #EC458B;background:#fff;" class="btn btn-danger me-4"> FACTURA ${{$factura->total}}</a>
            @endif
            </td>
            <td id="estadoFactura{{$factura->id}}">
              @if ($factura->estado != "PAGADO")
                PENDIENTE
              @else
                {{$factura->estado}}
              @endif
            </td>
            <td>
              <button type="button" class="btn btn-info" onclick="aprobar({{$factura->id}})">Pagado</button>
            </td>
            </tr>
            @empty
              
            @endforelse

          </tbody>
        </table>
      </div>
		<div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Codigo</th>
            <th scope="col">Produco</th>
            <th scope="col">Cantidad</th>
            <th class="col-1 mb-2">Cantidad facturado</th>
            <th class="col-1 mb-2">Cantidad X</th>
            <th class="col-1 mb-2">Cantidad Pendiente</th>
            <th scope="col">Logistica al facturar</th>
            <th scope="col">Precio</th>
            <th scope="col">Total</th>            
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
              @php
                $valueF = 0;
              @endphp
              @isset($value->cantidadF)
              @php
              $valueF = $value->cantidadF; 
              @endphp
              @endisset
              <td><input disabled class="form-control" type="number" value="{{$valueF}}" id="cantidad" name="cantidad"></td>
              @php
              $valueN = 0;
              @endphp
              @isset($value->cantidadN)
              @php 
              $valueN = $value->cantidadN; 
              @endphp
              @endisset
              <td><input disabled class="form-control" type="number" value="{{$valueN}}" id="cantidad" name="cantidad"></td>
              @php
              $valueP = 0;
              @endphp
              @isset($value->cantidadP)
              @php 
              $valueP = $value->cantidadP; 
              @endphp
              @endisset
              <td><input disabled @if($valueP != 0) style="background-color:red;font-weight: 900;color: #000;" @endif class="form-control" type="number" value="{{$valueP}}" id="cantidad" name="cantidad"></td>
              <td style="font-size:12px;min-width:190px;">
                @php
                  $snapshot = $value->logistica_facturacion_snapshot ?? null;
                  $snapshot = $snapshot ? (array) $snapshot : null;
                @endphp
                @if($snapshot)
                  <b>{{ $snapshot['fecha'] ?? '' }}</b><br>
                  Pedido: {{ $snapshot['cantidad_original'] ?? 0 }}<br>
                  A: {{ $snapshot['cantidad_a_enviada'] ?? 0 }} /
                  X: {{ $snapshot['cantidad_x_enviada'] ?? 0 }}<br>
                  Pendiente: {{ $snapshot['cantidad_pendiente_calculada'] ?? 0 }}
                @else
                  -
                @endif
              </td>
              <td><input disabled class="form-control" type="numbre" value="{{$value->precio}}" id="precio" name="precio"></td>
              @php
                $totalFila = floatval($value->precio)*intval($value->cantidad);
                if($valueP != 0){
                  $cant = $value->cantidad - $valueP;
                  $totalFila = floatval($value->precio)*$cant;
                }
              @endphp
              <td><input disabled class="form-control" type="number" value="{{$totalFila}}" id="total" name="total"></td>
          </tr>          
          @empty

          @endforelse          
        </tbody>
      </table>   
		</div>
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
