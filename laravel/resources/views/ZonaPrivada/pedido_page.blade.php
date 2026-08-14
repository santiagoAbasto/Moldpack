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
.box_hover:hover img{
  -webkit-transform: scale(1.05);
    transform: scale(1.05);
    transition: all 0.5s ease 0.2s;
}
</style>
<div class="d-flex justify-content-center ">

    <div class="d-flex flex-column justify-content-center align-items-center col-12 producto_container mt-4">
        
        <div class="col-12 table-responsive d-flex justify-content-center">            
            <div class="d-flex flex-row justify-content-start align-items-center align-items-md-start flex-wrap mx-1 mx-md-5 box_container">
                <div class="col-12 d-flex justify-content-between align-items-center flex-wrap flex-row my-2">
                    <div class="text-start" style="font-size:32px;color:#034EA2;font-weight:600;">CAT&Aacute;LOGO</div>
                    <div>                    
                    <a style="color:#fff;text-decoration:none;cursor-pointer:pointer;background:#ED1C24;border-radius:30px;" href="{{route('page.pedido')}}" class="my-5 px-4 py-2">Continuar al Carrito</a>
                    </div>
                </div>

                
                    <form class="col-12 p-4 d-flex justify-content-between mb-5" id="form" method="GET" action="{{route('page.buscarPedido')}}" style="background:#F5F5F5;">
                    <div class="col-11 d-flex justify-content-between flex-wrap">
                        <div class="col-md-3 px-1">
                            <input class="form-control" type="text" name="marca" placeholder="Marca">
                        </div>
                        <div class="col-md-3 px-1">
                            <input class="form-control" type="text" name="medida" placeholder="Medida">
                        </div>
                        <div class="col-md-3 px-1">
                            <input class="form-control" type="text" name="codigo" placeholder="C&oacute;digo">
                        </div>
                        <div class="col-md-3 px-1">
                            <input class="form-control" type="text" name="sistema" placeholder="Sistema">
                        </div>

                        <hr class="w-100">

                        <div class="col-6 d-flex flex-row flex-wrap">
                            <label class="w-100">Cubetas</label>
                            <div class="d-flex flex-column col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="bf" id="bf" name="bf">
                                    <label class="form-check-label" for="bf">
                                    Bombas de freno
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="be" id="be" name="be">
                                    <label class="form-check-label" for="be">
                                    Bombas de embrague
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex flex-column col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="primera" name="primera">
                                    <label class="form-check-label" for="primera">
                                    1°
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="2" id="segunda" name="segunda">
                                    <label class="form-check-label" for="segunda">
                                    2°
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-flex flex-row flex-wrap">
                            <label class="w-100" style="height: 0px;">Caliper</label>
                            <div class="d-flex flex-column col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="delantera" id="delantera" name="delantera">
                                    <label class="form-check-label" for="delantera">
                                    Delantera
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex flex-column col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="trasera" id="trasera" name="trasera">
                                    <label class="form-check-label" for="trasera">
                                    Trasera
                                    </label>
                                </div>                            
                            </div>
                        </div>
                    </div>
                    <div class="col-1 d-flex justify-content-end">
                        <button class="btn" type="submit" style="background:#ED1C24;border-radius:150px;width:50px;height:50px;"><i class="fas fa-search" style="color:#fff;"></i></button>
                    </div>
                    </form>
                
                @forelse ($categorias as $item)
                @if($loop->iteration %2!=0)
                    <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center align-items-md-start mb-5" style="position: relative;">
                    <div class="d-flex flex-row justify-content-between align-items-end box_hover" onclick="window.location='{{route('page.categoriasPedido',$item->id)}}'"  style="width:95%;cursor:pointer;background:#F5F5F5;height:247px;border-radius: 10px;">
                        
                        <div class="d-flex flex-column text-center align-items-center" style="padding:40px 28px;">
                        <p style="width: 100%;font-size:24px;color:#083981;margin:unset;word-break: break-word;overflow: hidden;">{{$item->nombre}}</p>                        
                        </div>
                
                        <img class="productoContainer" src="{{asset(Storage::url($item->imagen))}}">
                        
                            
                    </div>
                    </div>
                @else
                    <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center align-items-md-end mb-5" style="position: relative;">
                        <div class="d-flex flex-row justify-content-between align-items-end box_hover" onclick="window.location='{{route('page.categoriasPedido',$item->id)}}'"  style="width:95%;cursor:pointer;background:#F5F5F5;height:247px;border-radius: 10px;">
                            
                            <div class="d-flex flex-column text-center align-items-center" style="padding:40px 28px;">
                            <p style="width: 100%;font-size:24px;color:#083981;margin:unset;word-break: break-word;overflow: hidden;">{{$item->nombre}}</p>                        
                            </div>
                    
                            <img class="productoContainer" src="{{asset(Storage::url($item->imagen))}}">
                            
                                
                        </div>
                    </div>
                @endif
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
