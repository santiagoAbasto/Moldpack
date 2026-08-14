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
<div class="d-flex justify-content-center ">

    <div class="d-flex flex-column justify-content-center align-items-center col-12 producto_container mt-4">
                
        <div class="col-12 table-responsive d-flex justify-content-center">            
            <div class="d-flex flex-row justify-content-start align-items-center align-items-md-start flex-wrap mx-1 mx-md-5 box_container">
                <div class="col-12 d-flex justify-content-between align-items-center flex-wrap flex-row my-2">
                    <div class="text-start" style="font-size:32px;color:#083981;">PERDIDOS</div>
                    <div>
                    <a  style="color:#000;text-decoration:none;cursor-pointer:pointer;border:1px solid #034EA2;border-radius:30px;" href="{{route('historico',Auth::guard('cliente')->user()->id)}}" class="my-5 px-4 py-2 me-4">Historial de Pedidos </a>
                    <a style="color:#000;text-decoration:none;cursor-pointer:pointer;background:#034EA2;border-radius:30px;" href="{{route('page.pedido')}}" class="my-5 px-4 py-2">Continuar al Carrito</a>
                    </div>
                </div>

                <form class="col-12 d-flex justify-content-between mb-5" method="GET" action="{{route('page.buscarPedido')}}">
                <div class="col-11 d-flex justify-content-between flex-wrap">
                    <div class="col-md-3 px-1">
                        <input class="form-control" type="text" name="categoria" placeholder="Producto">
                    </div>
                    <div class="col-md-3 px-1">
                        <input class="form-control" type="text" name="original" placeholder="C&oacute;digo OEM">
                    </div>
                    <div class="col-md-3 px-1">
                        <input class="form-control" type="text" name="tp" placeholder="C&oacute;digo TP">
                    </div>
                    <div class="col-md-3 px-1">
                        <input class="form-control" type="text" name="producto" placeholder="Conjunto">
                    </div>
                </div>
                <div class="col-1 d-flex justify-content-end">
                    <button class="btn" type="submit" style="background:#034EA2;border-radius:150px;"><i class="fas fa-search"></i></button>
                </div>
                </form>

                @forelse ($producto as $item)
                <div class="col-12 col-md-3 d-flex flex-column justify-content-center align-items-center align-items-md-start mb-5">
                    <div class="d-flex flex-column justify-content-between align-items-start p-3" onclick="window.location='{{route('page.productoPedido',$item->id)}}'"  style="width:95%;cursor:pointer;background:#F5F5F5;height:173px;">
                        <p style="color:#034EA2;font-size:14px;text-transform: uppercase;">{{$item->obtenerProductoCategoria->nombre}}</p>
                        <p style="color:#083981;font-size:22px;">{{$item->nombre}}</p>
                        <div class="box_description" style="color:#717171;font-size:18px;height:60px;overflow: hidden;">{!!$item->descripcion!!}</div>
                    </div>
                </div>
                @empty
                    
                @endforelse
            
            </div>
        </div>
        
    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@include('ZonaPrivada.partials.carrito_storage')
    <script>
        
        function removeItemFromArr ( arr, item ) {
            var i = arr.indexOf( item );
            arr.splice( i, 1 );
        }
    
        function eliminar(id){
            var nombre = $('#'+id).data('pid')            
            
            obj_fila = window.moldpackCartGet();
            
            obj_fila.forEach(function(element){                
                prod = element['pid']
                if(prod == nombre){                    
                    removeItemFromArr( obj_fila, element )
                }
            })            

            window.moldpackCartSet(obj_fila);
            
            $('#'+id).remove();
            f_total()
        }
        function tabla(){
            tabla = $('.table-responsive');
            tabla.empty();
            $('.carritoVacio').addClass('d-none');
            
            var template='';
            obj_fila = window.moldpackCartGet();
            if(obj_fila.length === 0){
                $('.carritoVacio').removeClass('d-none')
            }
            subtotal = 0;
            if(obj_fila.length > 0){
                total = 0;
                
                template += '<table class="table w-100 m-0">'
                template +='<thead style="color:#000;">'
                template +='<tr style="font-size:20px;text-transform: uppercase;border-bottom:3px;">'                             
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Producto</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Descripcion</div></td>'
                // template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Diametro en pulgadas</div></td>'
                // template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Diametro en mm</div></td>'
                // template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Espesor en mm</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Cantidad</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Precio Unitario</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>Precio Total</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"></td>'                
                template +='</tr>'
                template +='</thead>'
                template +='<tbody>'
                
                
                for(i = 0; i < obj_fila.length; i++){
                    template +=`<tr id="fila_${i}" data-pid="${obj_fila[i]['pid']}" style="border-bottom:1px solid #ccc;" class="filaProducto">`                                        
                    template +=`<input type="hidden" name="pid[]" value="${obj_fila[i]['pid']}">`
                    template +=`<input type="hidden" name="producto[]" value="${obj_fila[i]['producto']}">`
                    template +=`<input type="hidden" name="cantidad[]" id="cantidadHidden" value="${parseInt(obj_fila[i]['cantidad'] || 1)}">`
                    
                    
                    template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${obj_fila[i]['categoria']}<br>${obj_fila[i]['nombre']}</td>`
                    template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${obj_fila[i]['medidas']}</td>`
                    // template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${obj_fila[i]['pulgadas']}</td>`
                    // template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${obj_fila[i]['diametro']}</td>`
                    // template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${obj_fila[i]['espesor']}</td>`
                    template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;width: 150px;"><input data-fila="fila_${i}" class="form-control w-50 input_number" type="number" min="1" value="${parseInt(obj_fila[i]['cantidad'] || 1)}"></td>`
                    template +=`<td id="unitario"  data-precio="${parseFloat(obj_fila[i]['precio'])}" class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">$ ${parseFloat(obj_fila[i]['precio'])}</td>`
                    template +=`<td id="ptotal" data-precio="${parseFloat(obj_fila[i]['precio']) * parseInt(obj_fila[i]['cantidad'] || 1)}"  class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">$ ${parseFloat(obj_fila[i]['precio']) * parseInt(obj_fila[i]['cantidad'] || 1)}</td>`
                    template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;"><buttom type="buttom" onclick="eliminar('fila_${i}')" class="btn"><i class="far fa-trash-alt"></i></buttom></td>`
                    template +='</tr>'
                    
                    
                    precio = parseFloat(obj_fila[i]['precio']);
                    cantidad = parseInt(obj_fila[i]['cantidad'] || 1);
                    subtotal = precio*cantidad;
                    total +=subtotal; 
                }
                total = total.toFixed(3)
                $('#subtotal').html("$ "+total)
                iva = '{{$iva}}';
                totaliva = total*iva
                totaliva = totaliva.toFixed(3)
                $('#subtotaliva').html("$ "+totaliva)

                let contSubTotal = document.getElementById('subtotal');
                contSubTotal.dataset.subtotal = totaliva

                $('#chektotal').val(totaliva)
                template +='</tbody>'                
                template +='</table>'
                tabla.append(template)
                
                f_total();
           
            }else{
               
            }
            
        }


        $('.form-check-input').change(function() {
            if(this.checked) {            
                if(this.value == "A convenir"){                    
                    $('#entregaconvenir').removeClass('d-none')
                }else{
                    $('#entregaconvenir').addClass('d-none')
                }
            }
        });
        document.onload = tabla();
        document.addEventListener('moldpack-cart-restored', function () {
            tabla();
        });
        if (window.moldpackCartRestoredItems) {
            tabla();
        }

        ///FUNCION ESCUCHAR CANTIDAD
        $(document).on('keyup mouseup', '.input_number', function() {
            var fila = $(this).data('fila')
            var cantidad = $(this).val();
            var precio = $(`#${fila} #unitario`).data('precio')
            var filaIndice = parseInt(String(fila).replace('fila_', ''), 10);
            var carritoPersistido = window.moldpackCartGet();
            if (carritoPersistido[filaIndice]) {
                carritoPersistido[filaIndice]['cantidad'] = cantidad;
                window.moldpackCartSet(carritoPersistido);
            }

            precio = parseFloat(precio).toFixed(2)
            var total =precio*cantidad            
            total = parseFloat(total)
            total = total.toFixed(2)

            $(`#${fila} #ptotal`).data('precio',total)
            $(`#${fila} #cantidadHidden`).val(cantidad)
            
            $(`#${fila} #ptotal`).html("$ "+total)
            console.log()
            f_total()
        });

        function f_total(){
            let ptotal = document.querySelectorAll("#ptotal");
            let total = 0;
            total = parseFloat(total)            
            ptotal.forEach(element => {
                var valor = element.innerHTML
                valor = valor.slice(2)                
                valor = parseFloat(valor)
                total +=valor                
            })
            total = total.toFixed(2)

            iva = '{{$iva}}';
            ingresosbrutos = '{{$ingresosbrutos}}';
            iva = parseFloat(iva)
            ingresosbrutos = parseFloat(ingresosbrutos)            

            totaliva = parseFloat(total)*iva            
            totalivaBrutos = parseFloat(totaliva)*ingresosbrutos
            
            let descuento = '{{$descuento}}'
            descuento = parseFloat(descuento)

            let totaldescuento = totalivaBrutos*descuento

            let descGlobal = '{{$descGlobal}}'
            descGlobal = parseFloat(descGlobal)
            let totaldescGlobal = totaldescuento*descGlobal
            agregarValor(totaldescuento)
            

            let contSubTotal = document.getElementById("subtotal")
            contSubTotal.dataset.total = total
            contSubTotal.innerHTML = "$ "+total

            let limite = "{{$carrito->limite}}"
                limite = parseFloat(limite)
                
                if(total >= limite){
                    $('#entrega2').parent().show()
                    $('#entrega3').parent().hide()
                    $('#entrega4').parent().hide()
                    $('#entrega5').parent().hide()
                }else{
                    $('#entrega2').parent().hide()
                    $('#entrega3').parent().show()
                    $('#entrega4').parent().show()
                    $('#entrega5').parent().show()
                }
        }
        function agregarValor(total){
            console.log(total)
            let contTotal = document.getElementById("total")

            contTotal.dataset.total = total.toFixed(2)
            contTotal.innerHTML = "$ "+total.toFixed(2)
        }

    </script>
    @endsection
