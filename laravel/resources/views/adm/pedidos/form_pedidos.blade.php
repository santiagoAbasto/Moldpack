@extends('adm.layouts')

@section('content')

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@include('adm.partials.filtros_pedidos', ['routeName' => 'pedido', 'estados' => $estados ?? []])
<table class="table table-bordered">
  <thead style="background:#2E3091;color:#fff">
    <tr>
	      <th class="pedido-col">N</th>
      <th>Estado</th>
      <th>Cliente</th>
      <th>Nombre</th>
      <th colspan="2"> </th>
    </tr>
    <tbody>
@forelse ( $pedidos as $item)
    <tr>
	      <td class="pedido-col"><span class="pedido-numero">#{{$item->id}}</span></td>
      <td>{{$item->estado}}</td>
      <td>{{$item->razonSocial}}</td>
      <td>{{$item->nombre}}</td>
      <td><span style="cursor:pointer;" onclick="mostrar_producto({{$item->id}})" >MODIFICAR</span></td>
		<td>
      <form method="POST" action="{{ route('pedido_delete', $item->id) }}" onsubmit="return confirm('¿Estás seguro de que deseas anular este pedido?');" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="eliminar btn btn-link p-0">Eliminar</button>
      </form>
    </td>
    </tr>
@empty
    <tr>
      <td colspan="6">Sin datos</td>
    </tr>
@endforelse
    </tbody>
    </table>@forelse ( $pedidos as $item)

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
			  <th style="color:#fff">DIRECCION</th>
            <th style="color:#fff">ACCIONES</th>            
          </tr>
        </thead>
        <tbody>
          <tr>              
            <th>{{$item->id}}</th>
            <th>{{$item->fecha}}</th>
            <th class="estado_{{$item->id}}">{{$item->estado}}</th>
			  <th class="bultos_{{$item->id}}">              
              <input type="number" id="bultos{{$item->id}}" min="1" value="{{$item->bultos}}"><br>
              <button onclick="bultos({{$item->id}})" style="background: rgb(72, 46, 219);color:#fff;" class="btn my-2">Guardar</button>
            </th>
            <th>
                <div>
                    @if(optional($item->obtenerCliente)->direccionEntrega)
                    {{$item->obtenerCliente->direccionEntrega}}
                    @else
                    {{optional($item->obtenerCliente)->direccion ?? 'Cliente eliminado'}}
                    @endif
                </div>
            </th>
            <th>
              <button class="btn btn-primary" onclick="mostrar_producto('modal_{{$item->id}}')" style="color:#fff;">VER DETALLE</button>
              <button onclick="armado({{$item->id}})" style="background: orange;color:#fff;" class="btn m-2">ARMADO</button>
              <button onclick="aprobar({{$item->id}})" style="background: green;color:#fff;" class="btn">APROBADO</button>
			  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ficha{{$item->id}}">CLIENTE</button>
            </th>
          </tr>
        </tbody>
      </table>
      <div id="msj" style="display:none;position: relative;left: 84%;background: green;width: 150px;text-align: center;border: 2px solid green;color:#fff;"></div>
    </div>
  </div>
</div>
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
<!-- Modal -->
<div id="modal_{{$item->id}}" class="box_toggle" style="display: none">    
    <div id="modal_{{$item->id}}">
      <div id="cliente_tabla_{{$item->id}}" style="display:none;">
          Pedido: #{{$item->id}}<br>
          Empresa: {{optional($item->obtenerCliente)->razonSocial ?? 'Cliente eliminado'}}
          <br>
Nombre: {{optional($item->obtenerCliente)->nombre}}
          <br>
          Direccion de entrega: 
          @if(optional($item->obtenerCliente)->direccionEntrega)
          {{$item->obtenerCliente->direccionEntrega}}
          @else
          {{optional($item->obtenerCliente)->direccion ?? 'Cliente eliminado'}}
          @endif
      </div>
      
      @isset($item->mensaje)
      <hr class="w-100">
      <div id="msj_tabla_{{$item->id}}" class="my-5">Mensaje: {{$item->mensaje}}</div>
      <hr class="w-100">
      @endisset
		
		      <div class="wrap">
          <div class="d-flex flex-row" id="newBoxProduct{{$item->id}}">
              <div class="col-10">
                <label>Producto</label>
                <input list="arrprod" name="arrprod" id="newProductInput" type="text" placeholder="Buscar" class="form-control mx-2">
              </div>
              <div class=" col-2">
                <label>Cantidad</label>
                <input type="number" id="newCant" min="1" class="form-control">
              </div>
          </div>
      <button class="btn btn-primary btn-duplicar my-3 mx-2" style="margin-bottom: 10px;" onclick="newProduct('{{$item->id}}')">Agregar Producto</button>
        <datalist id="arrprod">
            @forelse($arrProductos as $product)
                @forelse($product->obtenerPresentacionRelacionados()->get() as $pres)
                    <option value="{{$pres->id}} - {{$pres->codigo}} - {{$pres->presentacion}}"></option>
                @empty
                @endforelse
            @empty
            @endforelse
        </datalist>
        </div>
		
<div class="table-responsive"> 
      <table id="tabla_{{$item->id}}" class="table modal_{{$item->id}}">
        <thead>
          <tr>            
            <th class="img" scope="col">Imagen</th>
            <th scope="col">Producto</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Cantidad a Facturar</th>
            <th scope="col">Stock disponible</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody id="table{{$item->id}}">
          @forelse ($item->pedido as $value)    
          
          <tr id="form{{$loop->index}}{{$item->id}}">
              <input type="hidden" id="idPedido" value="{{$item->id}}">
              <input type="hidden" id="idItem" value="{{$value->idPedido}}">
              @csrf
              <td class="img">
                  @isset($value->imagen)
                  <img  src="{{$value->imagen}}" width="50px" height="auto">
                  @endisset
              </td>
              <td>
                <select disabled class="form-control" id="nombre" name="nombre">
                  <option data-precio="{{$value->precio}}" data-id="{{$value->presentacionid}}" data-codigo="{{$value->codigo}}" selected value="{{$value->codigo}} {{$value->nombre}}">
                    {{$value->codigo}} {{$value->nombre}}
                  </option>
                  @forelse ($arrProductos as $producto)
                    @forelse ($producto->obtenerPresentacionRelacionados as $presentacion)
                    <option data-precio="{{$presentacion->precio}}" data-id="{{$presentacion->id}}" data-codigo="{{$presentacion->codigo}}" value="{{$producto->nombre}} {{$presentacion->presentacion}}">{{$presentacion->codigo}} {{$producto->nombre}} {{$presentacion->presentacion}}</option>
                    @empty                      
                    @endforelse
                  @empty
                  @endforelse
                </select>                
              </td>
              <td>
                <input disabled class="form-control" type="number" value="{{$value->cantidad}}" id="cantidad" name="cantidad">
              </td>
              <td>
                <input  class="form-control" type="number" max="{{$value->cantidad}}" min="0" value="{{$value->cantidad}}" id="cantidadF" name="cantidadF">
              </td>
              <td>
                {{$value->stock}}
              </td>
              <td class="d-flex flex-row justify-content-between columBtn">
                <button id="editar" type="button" class="btn btn-primary mr-2">Editar</button>
                <button id="btnSubmit" type="button" onclick="enviar('form{{$loop->index}}{{$item->id}}')" class="btn btn-success">Guardar</button>
                <button id="eliminar" type="button" onclick="eliminar('form{{$loop->index}}{{$item->id}}')" class="btn btn-danger mr-2">Eliminar</button>
              </td>
          </tr>
            @empty
            @endforelse
                        <tr><td>
            <button type="button" class="btn btn-dark" onclick="PrintElem('tabla_{{$item->id}}')">
              Imprimir pedido
           </button>
          </td></tr>
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
	function newProduct(id){
    let box =  document.getElementById('newBoxProduct'+id);
    const productoInput = box.querySelector('#newProductInput')
    let cantidad = box.querySelector('#newCant').value
    console.log(productoInput.value)
    console.log(cantidad)
    
    var tabla = document.getElementById('tabla_'+id);
    var primeraFila = tabla.querySelector('tbody tr:first-child');
    var nuevaFila = primeraFila.cloneNode(true);
    nuevaFila.querySelector('#nombre').outerHTML = '<input disabled class="form-control" type="text" value="' + productoInput.value + '" id="nombre" name="nombre">';
    nuevaFila.querySelector('#editar').style.display = 'none';
    nuevaFila.querySelector('#btnSubmit').style.display = 'none';
    nuevaFila.querySelector('#eliminar').style.display = 'none';
    nuevaFila.querySelector('#cantidad').value = cantidad;
    nuevaFila.querySelector('#cantidadF').value = cantidad;
    tabla.querySelector('tbody').insertBefore(nuevaFila, primeraFila);
    
    data = new FormData();
    data.append( 'pedido', id);
    data.append( 'producto', productoInput.value);
    data.append( 'cantidad', cantidad);
    data.append( '_token', document.querySelector('[name="_token"]').value);
    
      $.ajax({
        url: '{{route('adm.updateAddProduct.pedido')}}',
        data: data,
        type: "post",
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType      
        success: function (response) {                  
          console.log(response);
          //swal(response,"","success");
        },
        error: function(response){
          console.log(response);
          //swal("Algo sali車 mal","","error");
        }
      });
    
}
    function PrintElem(elem)
{
  var divToPrint = document.getElementById(elem);
  var msj = document.getElementById('msj_'+elem);
  
  var cliente = document.getElementById('cliente_'+elem);
  cliente.style.display = "block";
  if(msj){
      divToPrint.appendChild(msj)
  }
  divToPrint.appendChild(cliente)
  let columBtn = divToPrint.querySelectorAll(".columBtn")
  columBtn.forEach(element => {
    element.style.display = "none";
  });
  let columImg = divToPrint.querySelectorAll(".img")
  columImg.forEach(element => {
    element.style.display = "none";
  });
  
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
        divToPrint.querySelectorAll(".columBtn").style.display = "block";
  
  columBtn.forEach(element => {
    element.style.display = "block";
  });
  cliente.style.display = "none";
    
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
      data.append( 'cantidadF', formulario.querySelector("#cantidadF").value);
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
          swal("Algo sali車 mal","","error");
        }
      });
  }
  
  function mostrar_producto(item){
      $('.box_toggle').css('display','none')
      $('#'+item).toggle('esconder')
  }
	

  function aprobar(id){
      $.ajax({
        url:'{{route('pedido_putAprobado')}}',
        data: {'id': id, '_token': '{{ csrf_token() }}' },
        type:'post',
        success: function (response) {
          $('#tabla').hide();
          swal('Registro modificado',"","success");
          $('.estado_'+id).text('APROBADO')
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
  
  function bultos(id){
    let formulario = document.querySelector('.bultos_'+id)
    let bultos = document.getElementById('bultos'+id).value        
      $.ajax({
        url:'{{route('pedido_bulto')}}',
        data: { 'id' : id, 'bultos': bultos, '_token': '{{ csrf_token() }}'},
        type:'post',
        success: function (response) {          
          swal('Registro modificado',"","success");               
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
          swal("Algo sali車 mal","","error");
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
