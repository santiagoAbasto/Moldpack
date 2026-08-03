@extends('layouts.plantilla')

@section('content')
<?php
    $email = Auth::guard('cliente')->user()->email;
    $id = Auth::guard('cliente')->user()->id;
    $username = Auth::guard('cliente')->user()->username;
    
?>
<?php 
    $iva = $carrito->iva;
    $iva = $iva / 100;
    $iva = $iva + 1;
?>
<?php 
    $ingresosbrutos = Auth::guard('cliente')->user()->ingresosbrutos;
    $ingresosbrutos = $ingresosbrutos / 100;
    $ingresosbrutos = $ingresosbrutos + 1;
?>

<?php 
$descuento = 1;
if(Auth::guard('cliente')->user()->descuento != 0){
    $descuento = 100 - Auth::guard('cliente')->user()->descuento;
    $descuento = $descuento / 100;
}
?>
<?php
    $descGlobal = 1;
    if($carrito->descuento != 0){
    $descGlobal = 100 - $carrito->descuento;
    $descGlobal = $descGlobal/ 100;
}
?>
<style>
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
td{
    border-top: 1px solid #ddd!important;
    border-bottom: 1px solid #ddd!important;
}
.box_hover:hover{
  -webkit-transform: scale(1.03);
    transform: scale(1.03);
    transition: all 0.5s ease 0.2s;
}
</style>
<div class="d-flex justify-content-center flex-wrap">
    <div class="col-12 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
        <div class="box_container">
        <a style="text-decoration: none;color:#000;" href="{{route('page.pedido')}}">Pedidos</a>        
        </div>
    </div>

    <form class="w-100 d-flex justify-content-center mb-5 py-4" method="GET" action="{{route('page.buscarPedido')}}" style="background:#EC458B;">
    <div class="d-flex justify-content-between flex-wrap">                        
        <div class="me-2">
            <select class="form-control" name="categoria" style="width: 288px;">
                <option value="0">Categorias</option>
                @forelse ($categorias as $categoria)
                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                @empty
                    
                @endforelse
            </select>            
        </div>
        <div class="me-2">
            <input style="width: 288px;" class="form-control pe-2" type="text" name="producto" placeholder="Producto">
        </div>
        <div class="me-2">
            <input style="width: 288px;" class="form-control pe-2" type="text" name="codigo" placeholder="C&oacute;digo">
        </div>
        <div class="">
            <button class="btn" type="submit" style="background:#F5F3EF;width:288px">BUSCAR</button>
        </div>
    </div>
    </form>

    <div class="d-flex flex-column justify-content-center align-items-center box_container mt-4">    
        <div class="table-responsive w-100">            
                <table class="w-100">
                    <thead style="background:#EC458B;color:#fff;">
                        <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div></div></td>
                        <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>C&Oacute;DIGO</div></td>                        
                        <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>DESCRIPCI&Oacute;N</div></td>
                        <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>PRESENTACI&Oacute;N</div></td>
                        <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>CANTIDAD</div></td>
                        <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>PRECIO LISTA x U.</div></td>                        
                        <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>SUBTOTAL</div></td>
                        <td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div></div></td>
                    </thead>
                    <tbody style="background:#fff;">
                        
                        @forelse ($categorias as $cat)
                        @forelse($cat->obtenerListaProductos->where('activa','!=',0) as $producto)
                        @php
                        $style='';
                        @endphp
                        @if ($loop->last)
                        @php
                        $style='border-bottom:1px solid #ccc;';
                        @endphp
                        @endif
                            <tr id="fila_{{$producto->id}}" data-producto="{{$producto->id}}">
                            <td class="pt-2 pb-2"  name="imagen" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                                @if ($producto->imagen)
                                <img id="imagen" src="{{asset(Storage::url($producto->imagen))}}" width="77px" height="auto">
                                @else
                                <img id="imagen" src="{{asset('img/logo2.jpg')}}" width="77px" height="auto">
                                @endif
                            </td>
                            @php
                                $codigo = "";
                                $id = 0;
                                if(count($producto->obtenerPresentacionRelacionados)>0){
                                    $codigo = $producto->obtenerPresentacionRelacionados[0]->codigo;
                                    $id = $producto->obtenerPresentacionRelacionados[0]->codigo;
                                }
                            @endphp
                            <td class="pt-2 pb-2 " id="codigo" data-codigo="{{$codigo}}" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                                {{$codigo}} 
                            </td>
                            <td class="pt-2 pb-2 " id="nombre" data-nombre="{{$producto->nombre}}" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                                {{$producto->nombre}} 
                            </td>
                            <td class="pt-2 pb-2 " style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                                <select class="form-select presentacion" id="presentacion">
                                    @forelse ($producto->obtenerPresentacionRelacionados as $presentacion)
                                        @if ($loop->first)
                                            @php
                                            $precio = round($presentacion->precio,2);
                                            @endphp
                                        @endif
                                        <option data-id="{{$producto->id}}" data-stock="{{$presentacion->stock}}" data-presentacionid="{{$presentacion->id}}" data-precio="{{$presentacion->precio}}" value="{{$presentacion->presentacion}}">{{$presentacion->presentacion}}</option>
                                    @empty 
                                        @php
                                        $precio = 0;
                                        @endphp
                                        <option disabled data-presentacionid="0" data-id="{{$producto->id}}" data-stock="0" data-precio="0" value="NaN">No hay presentaciones</option>
                                    @endforelse
                                </select>
                            </td>
                            
                            <td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                                <input class="input_number cantidad{{$producto->id}} form-control" data-fila="{{$producto->id}}" type="number" value="1" min="1" name="cantidad" id="cantidad" style="width: 5vw;">
                            </td>
                            <td class="pt-2 pb-2 precio{{$producto->id}}" data-precio="{{round($precio,2)}}" id="precio" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">                                
                                $ {{round($precio,2)}}
                            </td>                            
                            {{-- @php                            
                            $string ="(";
                            $flag=0;
                            if($carrito->descuento != 0){
                                $descuento = 100 - $carrito->descuento;
                                $descuento = $descuento / 100;
                                $precio = $precio*$descuento;                                     
                                $string .=$carrito->descuento." %";
                                $flag=1;
                            }
                            if(Auth::guard('cliente')->user()->descuentoGlobal() != 1){
                                $precio = $precio*Auth::guard('cliente')->user()->descuentoGlobal();
                                if($flag == 1){
                                    $string .=" +";
                                }
                                $string .=Auth::guard('cliente')->user()->descuento." %";
                                $flag =2;
                            }                            
                            if(isset(Auth::guard('cliente')->user()->obtenerProductoRelacionado($producto->obtenerFamilia->id)->descuento)){
                            $precio = floatval($precio)*floatval(Auth::guard('cliente')->user()->obtenerDescuento($producto->obtenerFamilia->id));
                            if($flag != 0){
                                $string .=" +";
                            }
                            $string .=Auth::guard('cliente')->user()->obtenerProductoRelacionado($producto->obtenerFamilia->id)->descuento." %";
                            
                        }
                            $precio = round($precio,2);
                            $string.=")";
                            @endphp

                            <td class="pt-2 pb-2" data-descuento="{{$precio}}" id="descuento" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                                {{$signo}} {{$precio}}
                                @if ($string != "()")
                                    <br>{{$string}}
                                @endif
                            </td> --}}
                            <td class="pt-2 pb-2 subtotal{{$producto->id}}" data-subtotal="{{round($precio,2)}}" id="subtotal" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;color:#707070;">
                                $ {{$precio}}
                            </td>
                            
                            
                            <td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;">
                                <button id="btnComprar" onclick="pedido('{{$producto->id}}')" style="color: #EC458B;background:#fff;border:1px solid #EC458B;font-weight: bold;padding: 8px 2vw;font-size: 18px;" type="button">Agregar</button>
                            </td>
                            </tr>
                        @empty
                        @endforelse
                        @empty
                        <tr>
                            <td colspan="8" class="py-5">
                                No se encontraron resultados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
        </div>
        {{-- Pagination --}}
        @if(!$buscador)
        @if($productos->links() !== null)
        <div class="w-100 d-flex justify-content-center">
            {!! $productos->links() !!}
        </div>
        @endif
        @endif
        
    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

    function pedido(id){
        const fila = {         
        cantidad: $(`#fila_${id} #cantidad`).val(),
        codigo: $(`#fila_${id} #codigo`).data('codigo'),
        imagen: $(`#fila_${id} #imagen`).attr('src'),
        nombre: $(`#fila_${id} #nombre`).data('nombre'),
        presentacion: $(`#fila_${id} #presentacion`).find(":selected").text(),
        stock: $(`#fila_${id} #presentacion`).find(":selected").data('stock'),
        productoid: $(`#fila_${id} #presentacion`).find(":selected").data('id'),
        presentacionid: $(`#fila_${id} #presentacion`).find(":selected").data('presentacionid'),
        precio: $(`#fila_${id} #presentacion`).find(":selected").data('precio'),         
        subtotal: $(`#fila_${id} #subtotal`).data('subtotal'),
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
    
    ///FUNCION ESCUCHAR SELECTED
    $(document).on('keyup mouseup', '#presentacion', function() {
        let presentacion = $(this).find(":selected").text();
        
        let precio = parseFloat($(this).find(":selected").data('precio'));   
        
        let id  = $(this).find(":selected").data('id');
        
        let cantidad = $(`.cantidad${id}`).val();
        
        let total = parseFloat(precio)*parseInt(cantidad)
        
        $(`.precio${id}`).data('precio',precio.toFixed(2))
        
        $(`.precio${id}`).html('$ '+precio.toFixed(2))

        let subtotal = cantidad*precio

        $(`.subtotal${id}`).data('subtotal',subtotal.toFixed(2))
        
        $(`.subtotal${id}`).html('$ '+subtotal.toFixed(2))

        
    });

    ///FUNCION ESCUCHAR CANTIDAD
    $(document).on('keyup mouseup', '.input_number', function() {
        
        let id = $(this).data('fila')        
        let cantidad = $(this).val();
        let precio = $(`.precio${id}`).data('precio')
        
        let total = parseFloat(precio)*cantidad        
        $(`#fila_${id} #subtotal`).html("$ "+total.toFixed(2))
        $(`#fila_${id} #subtotal`).data('subtotal',"$ "+total.toFixed(2))
          
    });

    ///FUNCION ESCUCHAR CANTIDAD TODO
    $(document).on('keyup mouseup', '#todo', function() {        
        let fila = document.querySelectorAll("tr");
        var cantidad = $(this).val();        
        fila.forEach(e =>{
            if(e.querySelector('#cantidad') !== null){                
                e.querySelector('#cantidad').value = cantidad
            }
        });
          
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
    @endsection
