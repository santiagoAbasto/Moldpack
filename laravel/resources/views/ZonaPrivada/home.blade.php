<style>
    table tbody td{
        font-size: 17px;
        color:#333333;
    }
    
    .filtro_banner:before {
        content: "";
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 45px;
        background: #1F3041;
        width: 100%;
        height: 100%;
        opacity: 0.6;
        top: 0;
        position: absolute;
    }
    </style>
<div class="col-12 text-center p-5" style="font-size:30px;color:#2C296B;"><b>Productos</b></div>
{{-- BUSCADOR --}}
<form method="GET" action="{{route('page.buscarPedido')}}">
  <div class="col-12 d-flex flex-row justify-content-strech align-items-center flex-wrap px-4 py-5" style="background:#F9F9F9;">
    <div class="col-3 pe-5">
      <select class="form-control" name="categoria" style="border-radius: 5px;">
        <option value="0">Categorias</option>
        @forelse ($categorias as $cat)
          <option value="{{$cat->id}}">{{$cat->nombre}}</option>
        @empty
          
        @endforelse
      </select>
    </div>

  
    <div class="col-3 pe-5">
      <input type="text" class="form-control" value="" name="codigo" placeholder="Codigo o descripcion"  style="border-radius: 5px;">
    </div>
  
    <div class="col-2">
      <button class="btn px-3" style="background:#cccccc;color:#fff;border-radius:5px;" type="submit"><i class="fas fa-search"></i></button>
    </div>
    
  </div>
  </form>
<div class="d-flex flex-row flex-wrap justify-content-center align-items-start px-5 py-4">


    <div class="col-12"> 
        <table class="table w-100">
                <thead style="color:#000;" class="pb-5">
                    <tr style="font-size:20px;text-transform: uppercase;border-bottom:1px solid #ccc;">
                    
                    <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div></div></td>
                    <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Categor&iacute;a</div></td>
                    <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Descripci&oacute;n</div></td>
                    <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>C&oacute;digo</div></td>
                    <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Precio</div></td>
                    <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Cantidad</div></td>                    
                    <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"></td>
                    </tr>
                </thead>
                <tbody>  
                    
                @forelse ($productos as $producto )           
                @php
                    $style='';
                    @endphp
                @if ($loop->last)
                    @php
                    $style='border-bottom:1px solid #ccc;';
                    @endphp
                @endif
                <tr id="fila_{{$producto->id}}" class="{{strtoupper(str_replace(' ','',$producto->nombre))}} {{strtoupper (str_replace(' ','',$producto->codigo))}}" style="{{$style}}">
                    <td class="pt-2 pb-2"  name="imagen" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                        @if ($loop->first)
                        <img id="imagen" src="{{asset(Storage::url($producto->imagen))}}" width="77px" height="auto">
                        @endif
                        </td>                    
                    
                    <td class="pt-2 pb-2 " id="categoria" data-categoria="{{$producto->obtenerCategoria->obtenerProductoCategoria->nombre}} / {{$producto->obtenerCategoria->nombre}}" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                        <b>{{$producto->obtenerCategoria->obtenerProductoCategoria->nombre}} / {{$producto->obtenerCategoria->nombre}}</b>
                    </td>

                    <td class="pt-2 pb-2 " data-nombre="{{$producto->nombre}}" id="nombre" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                        {{$producto->nombre}}
                    </td>
                    <td class="pt-2 pb-2 codigo_" data-codigo="{{$producto->codigo}}" id="codigo" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                        {{$producto->codigo}}
                    </td>                    
                    {{-- @php
                        $precio = $producto->precio;                        

                        if(Auth::guard('cliente')->user()->descuento != 0){
                            $descuento = 100 - Auth::guard('cliente')->user()->descuento;
                            $descuento = $descuento / 100;
                            $precio = $precio*$descuento;
                        }
                        
                        if(Auth::guard('cliente')->user()->obtenerDescuento($producto->id) != 0){
                            $descuento = 100 - Auth::guard('cliente')->user()->obtenerDescuento($producto->id);
                            $descuento = $descuento / 100;
                            $precio = $precio*$descuento;
                        }

                        if($carrito->descuento != 0){
                            $descuento = $carrito->descuento;
                            $descuento = $descuento / 100;
                            $precio = $precio*$descuento;
                        }
                        $precio = round($precio,3)
                    @endphp 
                    <td class="pt-2 pb-2 precio_" data-precio="{{$precio}}" id="precio{{$producto->id}}" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                        $ {{$precio}}
                    </td> --}}
                    <td class="pt-2 pb-2" data-precio="{{$producto->precio}}" id="precio" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                        $ {{$producto->precio}}
                    </td>

                    <td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">                
                        <input class="input_number" data-fila="{{$producto->id}}" type="number" min="1" name="cantidad" id="cantidad" style="width: 5vw;">
                    </td>                    
                    
                    <td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;"><button onclick="pedido('{{$producto->id}}')" style="color: #2C296B;background:#fff;border-radius:5px;border:1px solid #2C296B;font-weight: bold;padding: 8px 2vw;font-size: 13px;" type="button">Agregar</button></td>
                </tr>
                                
                @empty
                    
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@if(!isset($route))
<div class="w-100 d-flex justify-content-center py-5">
    {!! $productos->links() !!}
</div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

    function pedido(id){
        const fila = {
         imagen: $(`#fila_${id} #imagen`).attr('src'),
         precio: $(`#fila_${id} #precio`).data('precio'),
         cantidad: $(`#fila_${id} #cantidad`).val(),
         codigo: $(`#fila_${id} #codigo`).data('codigo'),
         nombre: $(`#fila_${id} #nombre`).data('nombre'),
         categoria: $(`#fila_${id} #categoria`).data('categoria'),
         
     };

     obj_fila = sessionStorage.getItem('obj_fila');
     if(obj_fila != null){
        obj_fila = jQuery.parseJSON(obj_fila);
        obj_fila = $.makeArray(obj_fila);   
        obj_fila.push(fila);
        consulta = obj_fila;
          
         sessionStorage.setItem('obj_fila',JSON.stringify(obj_fila));
     }else{
        sessionStorage.setItem('obj_fila',JSON.stringify(fila));
        consulta = fila;
     }
    alertify.set('notifier','position', 'bottom-right');
    alertify.notify(`Se agrego el producto al carrito`,'success',3);     
    }

    ///FUNCION ESCUCHAR SELECT
    function ShowSelected(id)
    {
        var presentacion = document.getElementById("presentacion"+id);
        var presentacionvalor = presentacion.value;
        var precio = $('#presentacion'+id+' option:selected').data('precio');        
        $('#precio'+id).data('precio',precio)        
        $('#precio'+id).html('$ '+precio)
        var codigo = $('#presentacion'+id+' option:selected').data('codigo');        
        $('#codigo'+id).data('codigo',codigo)        
        $('#codigo'+id).html(codigo)

        $('#preciodescuento'+id).data('descuento',precio)        
        $('#preciodescuento'+id).html('$ '+precio)
    }
    ///FUNCION ESCUCHAR CANTIDAD
    $(document).on('keyup mouseup', '.input_number', function() {

        var fila = $(this).data('fila')
        var cantidad = $(this).val();
        var precio = $(`#fila_${fila} #preciodescuento${fila}`).data('descuento')
        var total = parseFloat(precio)*cantidad
        console.log(total)
        $(`#fila_${fila} #total`).html("$ "+total.toFixed(2))
          
    });
    ///FUNCION SEARCH
    function search(){
        let producto = $('#searchproducto').val();
        console.log(producto)
        producto = producto.replace(' ','');
        producto = producto.toUpperCase();
        let codigo = $('#searchcodigo').val();
        codigo = codigo.replace(' ','');
        codigo = codigo.toUpperCase();
        
        if(producto == "" && codigo == ""){
            $(`tbody tr`).show();
        }else{
            $(`tbody tr`).hide();
            $(`tbody tr.${producto}`).show();
            $(`tbody tr.${codigo}`).show();
        }
    }
</script>
