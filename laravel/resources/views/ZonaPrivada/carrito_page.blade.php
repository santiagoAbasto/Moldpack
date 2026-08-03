<?php
    $email = Auth::guard('cliente')->user()->email;
    $id = Auth::guard('cliente')->user()->id;
    $username = Auth::guard('cliente')->user()->username;
    $signo = "$";
    if(Auth::guard('cliente')->user()->precios == 0){        
        $signo = "USD";
    }
?>
<?php 
    $iva = $carrito->iva;
    $iva = $iva / 100;
    $iva = $iva + 1;
?>

<?php
    $descuento = 1;
    if(Auth::guard('cliente')->user()->descuento != 0 || Auth::guard('cliente')->user()->descuento != null){
        $descuento = 100 - Auth::guard('cliente')->user()->descuento;
        $descuento = $descuento / 100;
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
</style>

<form method="POST" id="form" action="{{route('enviarpedido')}}" enctype="multipart/form-data">
    @csrf
    <div class="d-flex flex-column justify-content-center align-items-center col-12 producto_container mt-4 px-5">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap flex-row my-2">
            <div class="text-start" style="font-size:32px;color:#EC458B;">CARRITO</div>            
        </div>           
        <div class="col-12 table-responsive" style="border-top-left-radius: 10px;border-top-right-radius: 10px;">            
        </div>
    </div>
    <div class="col-12 px-4 d-flex flex-column flex-md-row justify-content-between align-items-start container-fluid  px-5">
        <div class="col-12 col-md-6 pe-0 pe-md-4">            
            <div style="border:1px solid #ddd;">
                <div class="p-2 carrito_titulo" style="color:#707070;font-size:16px;font-weight: bold;background:#ddd;">
                    {!! $carrito->titulo !!}
                </div>
                <div class="p-2 carrito_texto" style="background:#fff;color:#6E6F71;font-size:16px">
                    {!! $carrito->texto !!}
                </div>
            </div>
                <div style="background:#fff;font-size:16px;color:#707070;font-weight: bold;">
                    ESCRIBENOS UN COMENTARIO
                </div>
                <div style="color:#6E6F71;font-size:16px">
                    <textarea class="form-control" style="height: 85px;" id="obeservacion" name="obeservacion" placeholder="Escriba aquí días especiales para la entrega, cambios de domicilio, expresos, requerimientos especiales en la mercadería, etc..."></textarea>
                </div>

                <div style="background:#fff;color:#707070;font-size:15px;font-weight: bold;" >ADJUNTAR ARCHIVO</div>
                <div class="d-flex flex-row justify-content-between align-content-center"style="font-size:16px;color:#000586;font-weight: bold;">                    
                    <input type="file" class="form-control" id="file_carrito" name="archivo">
                </div>
                <div class="p-3 carrito_texto_3">
                    {!! $carrito->texto_3 !!}
                </div>
            
        </div>
        <div class="col-12 col-md-6 d-flex flex-column ps-0 ps-md-4">
            <div style="background:#fff;border:1px solid #ddd;">
                <div class="p-2" style="background: #ddd;font-size:16px;color:#000;"><b>TU PEDIDO</b></div>
                <div class="w-100 d-flex justify-content-between p-2">
                    <span>Subtotal</span><span id="subtotal"></span>
                </div>                
                <div class="w-100 d-flex justify-content-between p-2">
                    <span>Descuento<small>({{Auth::guard('cliente')->user()->descuento;}} %)</small></span><span id="totalDescuento" style="font-size:16px;"></span>
                </div>
                <div class="w-100 d-flex justify-content-between p-2">
                    <span>Total <small>(+iva)</small></span><span style="font-size:26px;" data-total="" id="total"></span>
                </div>
            </div>

            <div style="border:1px solid #ccc;" class="d-flex flex-column mt-4">
                <div class="p-3" style="background:#F6F6F6;color:#000;font-size:15px;font-weight: bold;" >ENTREGA</div>
                <div class="d-flex flex-column justify-content-start p-3" style="background:#fff;">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="entrega" id="entrega1" value="Retiro Cliente" checked>
                        <label class="form-check-label" for="entrega1">
                            <label style="font-size: 15px;color:#6E6F71;">Retiro Cliente</label>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="entrega" id="entrega2" value="Reparto Moldpack Frenos">
                        <label class="form-check-label" for="entrega2" style="font-size: 15px;color:#6E6F71;">
                            Reparto Moldpack                    
                        </label>
                    </div>                      
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="entrega" id="entrega3" value="A convenir">
                        <label class="form-check-label" for="entrega3" style="font-size: 15px;color:#6E6F71;">
                            A convenir
                        </label>
                    </div>
                    <div class="form-check">                        
                        <textarea class="form-control d-none" name="entregaconvenir" id="entregaconvenir" rows="3" placeholder="Indique forma de entrega"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex flex-row justify-content-between mb-4 mt-4 px-5">
        <button style="border:1px solid #EC458B;color:#EC458B;font-size:13px;font-weight: bold;width: 163px;border-radius:5px;width:216px;" onclick="window.location='{{route('page.pedido')}}'" class="btn me-4" type="button"><b>Volver</b></button>
        <button type="submit" id="btnprocesa" class="btn  px-md-3 rounded-pill" style="width: 168px;border-radius: 5px!important;background-color: #EC458B;color:white;width:216px;font-weight: bold;">Procesar compra</button>
    </div>

    <div class="d-flex flex-row justify-content-between mb-4 mt-4" style="padding:0 2%;">        
        <div class="d-flex flex-row justify-content-between align-items-center">            
            <div style="color:#EC458B;font-size:16px;font-weight: bold;" id="total_pedido"></div>
            <input type="hidden" name="total_pedido"  id="totalhidden" value="0">
            <input type="hidden" id="chektotal" value="0">
            <input type="hidden" name="usuario_id" value="{{$id}}">
            <input type="hidden" name="usuario_email" value="{{$email}}">
        </div>
    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!--Alertify-->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
   <script>
        
        function removeItemFromArr ( arr, item ) {
            var i = arr.indexOf( item );
            arr.splice( i, 1 );
        }
    
        
        function tabla(){
            tabla = $('.table-responsive');
            iva = '{{$iva}}';
            descuento = '{{$descuento}}';
            var template='';
            obj_fila = sessionStorage.getItem('obj_fila');
            subtotal = 0;
            if(obj_fila != null){
                obj_fila = jQuery.parseJSON(obj_fila);
                obj_fila = $.makeArray(obj_fila);
                total = 0;                
                
                template += '<table class="table w-100 border" >'
                template +='<thead style="color:#fff;background:#EC458B;">'
                template +='<tr style="font-size:20px;text-transform: uppercase;border-bottom:3px;">'                             
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>C&Oacute;DIGO</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>DESCRIPCI&Oacute;N</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>PRESENTACI&Oacute;N</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>CANTIDAD</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>PRECIO LISTA x U.</div></td>'                
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"><div>SUBTOTAL</div></td>'
                template +='<td class="pt-2 pb-2" style="border:unset;padding-left:1vh;font-size: 14px;font-weight:bold;height: 100%"></td>'                
                template +='</tr>'
                template +='</thead>'
                template +='<tbody style="background:#fff;">'
                
                
                for(i = 0; i < obj_fila.length; i++){
                    template +=`<tr class="tablaFila" id="fila_${i}" data-presentacion="${obj_fila[i]['presentacion']}" data-codigo="${obj_fila[i]['codigo']}" style="border-bottom:1px solid #ccc;">`
                    template +=`<input type="hidden" class="pcodigo" name="codigo[]" value="${obj_fila[i]['codigo']}">`
                    template +=`<input type="hidden" name="nombre[]" value="${obj_fila[i]['nombre']}">`                    
                    template +=`<input type="hidden" name="presentacion[]" value="${obj_fila[i]['presentacion']}">`                    
                    template +=`<input type="hidden" name="cantidad[]" value="${obj_fila[i]['cantidad']}">`
                    template +=`<input type="hidden" class="filaPrecio" name="precio[]" value="${obj_fila[i]['precio']}">`                    
                    template +=`<input type="hidden" class="filaSubtotal filaSubtotal${i}" name="subtotal[]" value="${obj_fila[i]['subtotal']}">`
                    template +=`<input type="hidden" class="filaStock" name="stock[]" value="${obj_fila[i]['stock']}">`
                    template +=`<input type="hidden" class="filaProductoId" name="filaProductoId[]" value="${obj_fila[i]['productoid']}">`
                    template +=`<input type="hidden" class="filaPresentacionId" name="filaPresentacionId[]" value="${obj_fila[i]['presentacionid']}">`
                    
                    template +=`<td  class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;"><img id="imagen" src="${obj_fila[i]['imagen']}" width="77px" height="auto"></td>`
                    template +=`<td  class="pt-2 pb-2 filaCodigo" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${obj_fila[i]['codigo']}</td>`
                    template +=`<td  class="pt-2 pb-2 filaNombre" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${obj_fila[i]['nombre']}</td>`
                    template +=`<td  class="pt-2 pb-2 filaPresentacion" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${obj_fila[i]['presentacion']}</td>`
                    template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;"><input type="number" data-precio="${obj_fila[i]['precio']}" data-fila="${i}" value="${obj_fila[i]['cantidad']}" min="1" class="form-control filaCantidad" style="width: 82px;"></td>`
                    template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;" data-precio="${obj_fila[i]['precio']}">$ ${obj_fila[i]['precio'].toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>`
                    // template +=`<td id="filatotal" data-filatotal="${obj_fila[i]['total']}" class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${signo} ${parseFloat(obj_fila[i]['descuento'])}</td>`
                    // let subtotal =parseFloat(obj_fila[i]['descuento'])*parseFloat(obj_fila[i]['cantidad'])
					let sub = obj_fila[i]['subtotal'];
if (typeof sub === 'string') {
obj_fila[i]['subtotal'] = obj_fila[i]['subtotal'].replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}else{
	obj_fila[i]['subtotal'] = '$ '+obj_fila[i]['subtotal'].toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
}
                    template +=`<td class="pt-2 pb-2 subtotal${i}" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;">${	obj_fila[i]['subtotal']}</td>`
                    template +=`<td class="pt-2 pb-2" style="vertical-align: middle;border:unset;padding-left:1vh;font-size: 16px;font-weight:bold;text-transform:uppercase;color:#707070;"><buttom type="buttom" onclick="eliminar('fila_${i}')" class="btn"><img src="{{ asset('img/delete_logo.png') }}"></buttom></td>`
                    template +='</tr>'                    
                    console.log(obj_fila[i]['subtotal'])
                    precio = parseFloat(obj_fila[i]['precio']);
                    
                    cantidad =parseInt(obj_fila[i]['cantidad']);
                    subtotal = precio*cantidad;
                    total +=subtotal; 
                }
                total = total.toFixed(2)
                $('#subtotal').html("$ "+total.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'))
                totalDescuento = total*descuento;
                totalResto = total-totalDescuento;
                $('#totalDescuento').html("$ "+totalResto.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'))

                totaliva = totalDescuento*iva
                totaliva = totaliva.toFixed(2)
                $('#subtotaliva').html("$ "+total.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'))
                $('#total').html("$ "+totaliva.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'))
                $('#chektotal').val(totaliva)
                template +='</tbody>'                
                template +='</table>'
                tabla.append(template)
                
                $('#totalhidden').val("$ "+totaliva)                
                
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

        function eliminar(id){
            var codigo = $('#'+id).data('codigo')
            var presentacion = $('#'+id).data('presentacion')
            
            obj_fila = sessionStorage.getItem('obj_fila');
            
            obj_fila = jQuery.parseJSON(obj_fila);
            obj_fila = $.makeArray(obj_fila);
            
            obj_fila.forEach(function(element){
                if(element['codigo'] == codigo && element['presentacion'] == presentacion){
                    removeItemFromArr( obj_fila, element )
                }
            })            

            sessionStorage.setItem('obj_fila',JSON.stringify(obj_fila));
            
            $('#'+id).remove();
            location.reload();
        }
            ///FUNCION ESCUCHAR CANTIDAD
    $(document).on('keyup mouseup', '.filaCantidad', function() {
        iva = '{{$iva}}';
        descuento = '{{$descuento}}';

        let id = $(this).data('fila')        
        let cantidad = $(this).val();
        let precio = $(this).data('precio')
        let total = parseFloat(precio)*cantidad        

        $(`.subtotal${id}`).html("$ "+total.toFixed(2))
        $(`.filaSubtotal${id}`).value = total.toFixed(2);
        let totalPedido = 0;
        let filaTabla = document.querySelectorAll(".tablaFila");
            let producto = new Array();
            filaTabla.forEach(element => {
                cantidad= element.querySelector('.filaCantidad').value;
                precio= element.querySelector('.filaPrecio').value;
                subtotal = parseInt(cantidad)*parseFloat(precio)
                subtotal = subtotal.toFixed(2);
                console.log(parseFloat(subtotal).toFixed(2))
                totalPedido = parseFloat(subtotal)+parseFloat(totalPedido);               
            });

            $('#subtotal').html("$ "+totalPedido.toFixed(2))
            totalDescuento = totalPedido*descuento;
            totalResto = totalPedido-totalDescuento;
            $('#totalDescuento').html("$ "+totalResto.toFixed(2))

            totaliva = totalDescuento*iva
            totaliva = totaliva.toFixed(2)
            $('#subtotaliva').html("$ "+totalPedido.toFixed(2))
            $('#total').html("$ "+totaliva)
            $('#chektotal').val(totaliva)
    }); 

        ///ENVIO AJAX
        $('#form').on('submit',function(e){
            e.preventDefault();
			$('#btnprocesa').prop('disabled', true);
            let filaTabla = document.querySelectorAll(".tablaFila");
            let producto = new Array();
            filaTabla.forEach(element => {
                const fila = {         
                    imagen: element.querySelector('#imagen').src,
                    codigo: element.querySelector('.filaCodigo').textContent,
                    cantidad: element.querySelector('.filaCantidad').value,
                    nombre: element.querySelector('.filaNombre').textContent,
                    presentacion: element.querySelector('.filaPresentacion').textContent,
                    stock: element.querySelector('.filaStock').value,
                    productoid: element.querySelector('.filaProductoId').value,
                    presentacionid: element.querySelector('.filaPresentacionId').value,
                    precio: element.querySelector('.filaPrecio').value,
                    subtotal: element.querySelector('.filaSubtotal').value,            
                };
                producto.push(fila);
            });

            if (producto.length === 0) {
                $('#btnprocesa').prop('disabled', false);
                swal("El carrito esta vacio","","warning");
                return;
            }

            producto = JSON.stringify(producto);
            let entregaSeleccionada = document.querySelector('[name="entrega"]:checked');

             //const obj = sessionStorage.getItem('obj_fila');
             //console.log(obj);
            data = new FormData();
            data.append( 'producto', producto);
            data.append( 'total_pedido', document.querySelector('[name="total_pedido"]').value);
            data.append( 'entrega', entregaSeleccionada ? entregaSeleccionada.value : '');
            data.append( '_token', document.querySelector('[name="_token"]').value);
            if ($('input[type=file]')[0].files[0]) {
                data.append('file', $('input[type=file]')[0].files[0]);
            }
            data.append('obeservacion',document.querySelector('[name="obeservacion"]').value); 

            $.ajax({
            url: '{{route('enviarpedido')}}',
            data: data,
            type: "post",
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType      
            success: function (response) {                  
                console.log(response);
                swal(response,"","success");
                sessionStorage.removeItem('obj_fila');
            },
            error: function(response){
                console.log(response);
                $('#btnprocesa').prop('disabled', false);
                if ([401, 419].indexOf(response.status) !== -1) {
                    sessionStorage.removeItem('obj_fila');
                    swal("La sesion expiro","Inicie sesion nuevamente.","warning");
                    setTimeout(function () {
                        window.location.href = response.responseJSON && response.responseJSON.redirect ? response.responseJSON.redirect : '{{route('page.inicio')}}';
                    }, 1000);
                    return;
                }
                swal("Algo salió mal","","error");
            }
            });
        });
    </script>
