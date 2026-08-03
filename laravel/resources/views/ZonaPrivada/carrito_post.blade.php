@extends('layouts.plantilla')

@section('content')
<style>
    .mercadopago-button{
        background: #083981;
        color: #fff;
        font-size: 15px;        
    }
</style>
@php
// SDK de Mercado Pago
require base_path('vendor/autoload.php');
// Agrega credenciales
MercadoPago\SDK::setAccessToken(config('services.mercadopago.token'));

// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();

// Crea un ítem en la preferencia
$ids = "";
foreach ($arr_productos as $producto) {
    $item = new MercadoPago\Item();
    $item->title = $producto->nombre;
    $item->quantity = $producto->cantidad;
    $item->unit_price = $producto->precio;
    $products[] = $item;
    
    $ids .= $producto->id."_".$producto->cantidad."-";
    
}

$preference->back_urls = array(
"success" => route('page.comprarmp',$ids)
);
$preference->auto_return="approved";

$preference->items = $products;
$preference->save();

@endphp

<div class="alert alert-warning m-5" role="alert">
    Importante: Sujeto a verificación de Stock
  </div>
<div class="d-flex justify-content-center m-5">
    <form action="{{route('page.comprar')}}"  method="post" enctype="multipart/form-data" id="formCarrito">
    <div class="d-flex justify-content-between flex-wrap box_container" style="font-size: 19px;">
        <div class="col-12 col-md-8"> 
            <h3 class="m-2">Detalle de facturacion</h3>
            <hr class="m-2">
            <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>Razon social</span>
                    <input type="text" name="username" class="form-control" value="{{Auth::guard('cliente')->user()->username}}">
                </div>

                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>Nombre y apellido</span>
                    <input type="text" name="nombre" class="form-control" value="{{Auth::guard('cliente')->user()->apellido}}">
                </div>

                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>DNI</span>
                    <input type="number" min="10000000" name="dni" class="form-control" value="{{Auth::guard('cliente')->user()->dni}}">
                </div>

                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>Email</span>
                    <input type="email" name="email" class="form-control" value="{{Auth::guard('cliente')->user()->email}}">
                </div>

                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>Celular</span>
                    <input type="text" name="celular" class="form-control" value="{{Auth::guard('cliente')->user()->celular}}">
                </div>

                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>Direccion</span>
                    <input type="text" name="direccion" class="form-control" value="{{Auth::guard('cliente')->user()->direccion}}">
                </div>

                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>Localidad</span>
                    <input type="text" name="localidad" class="form-control" value="{{Auth::guard('cliente')->user()->localidad}}">
                </div>

                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>Provincia</span>
                    <input type="text" name="provincia" class="form-control" value="{{Auth::guard('cliente')->user()->provincia}}">
                </div>

                <div class="d-flex flex-column justify-content-start col-6 p-2">
                    <span>Codigo postal</span>
                    <input type="number" min="1" name="cp" class="form-control" value="{{Auth::guard('cliente')->user()->cp}}">
                </div>

                <div class="col-12 p-2">
                <input class="form-check-input" type="checkbox" name="otro" id="otro" value="acept">
                    <label class="form-check-label" for="otro" style="font-size: 15px;color:#6E6F71;">
                        ¿Enviar a una dirección diferente?
                    </label>
                </div>

                <div class="d-flex flex-column justify-content-start col-12 p-2">
                    <span>Notas Del Pedido (Opcional)</span>
                    <textarea name="msj" class="form-control"></textarea>
                </div>

            </div>
        </div>
        <div class="col-12 col-md-4 border d-flex flex-column p-2">            
            
                @csrf

            <h3 class="mx-2">Pedido</h3>
            <hr class="mt-0">            
            <span>Productos</span>
            
            <div class="d-flex flex-column">
                @forelse ($arr_productos as $row)
                <input type="hidden" name="producto[]" value="{{$row->id}}">
                <input type="hidden" name="cantidad[]" value="{{$row->cantidad}}">
                    <div class="d-flex flex-row justify-content-between flex-wrap mb-3">
                        <span style="font-size:15px;">{!!$row->producto!!}</span>
                        <span style="font-size:13px;">{{$row->descripcion}}</span>
                        <div style="font-size:15px;" class="text-end">x {{$row->cantidad." $ ".$row->precio}} </div>
                    </div>
                @empty
                    
                @endforelse
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Total</span>
                    <span>$ {{round($totalGlobal,2)}}</span>
                </div>
                <hr>
                <div style="font-size:19px;">Envio</div>
                <span style="font-size:16px;">{{$envio}}</span>
                <input type="hidden" name="envio" value="{{$envio}}">
                <hr>
                <div style="font-size:19px;">Pago</div>
                <div class="form-check">
                    <input class="form-check-input btnRadio" type="radio" name="pago" id="pago" value="Efectivo" checked>
                    <div class="d-flex justify-content-between" style="font-size:19px;">
                    <label class="form-check-label" for="pago" style="font-size: 19px;color:#6E6F71;">
                        Efectivo                    
                    </label>
                    @if ($carrito->descuentoefec != 0)
                    <span><b>({{$carrito->descuentoefec}}%off)</b></span>
                    @endif
                </div>
                </div>
                <div class="form-check">
                    <input class="form-check-input btnRadio" type="radio" name="pago" id="pago2" value="Transferencia bancaria">
                    <div class="d-flex justify-content-between" style="font-size:19px;">
                        <label class="form-check-label" for="pago2" style="font-size: 19px;color:#6E6F71;">
                            Transferencia bancaria                    
                        </label>
                        @if ($carrito->descuentotransf != 0)
                        <span><b>({{$carrito->descuentotransf}}%off)</b></span>
                        @endif
                    </div>
                    <div class="w-100 d-none" id="cvu">
                        <span>{{$carrito->cvu}}</span>
                    </div>
                </div>
                <div class="form-check">
                    <input class="form-check-input btnRadio" type="radio" name="pago" id="pago3" value="Mercado pago">
                    
                    <label class="form-check-label" for="pago3" style="font-size: 19px;color:#6E6F71;">
                        Mercado pago                    
                    </label>
                
                </div>
                <hr>
                <div style="font-size:11px;">
                    Sus datos personales se utilizarán para respaldar su experiencia en este sitio web, para administrar el acceso a su cuenta y para otros fines descritos en nuestras <b>Política de privacidad</b>.
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="terminos" id="terminos" value="acept" checked>
                    <label class="form-check-label" for="terminos" style="font-size: 15px;color:#6E6F71;">
                        Acepto los términos y condiciones de privacidad
                    </label>
                </div>
                <button class="btn w-100" style="background: #083981;color:#fff;" id="btnpedido" value="sub" type="submit">REALIZAR PEDIDO</button>
                <div class="cho-container d-none" style="font-size: 1rem;"></div>
            </div>
                        
        </div>
    </div>
    <input name="totalCarrito" value="{{round($totalGlobal,2)}}" type="hidden">
</form>
</div>

@endsection
@section('js')
<script src="https://sdk.mercadopago.com/js/v2"></script>

<script>
    const mp = new MercadoPago("{{config('services.mercadopago.key')}}", {
    locale: "es-AR",
    });

    // Inicializa el checkout
    mp.checkout({
    preference: {
        id: "{{$preference->id}}",
    },
    render: {
        container: ".cho-container", // Indica el nombre de la clase donde se mostrará el botón de pago
        label: "REALIZAR PEDIDO", // Cambia el texto del botón de pago (opcional)
    },
    });
            $('.btnRadio').change(function() {
            if(this.checked) {            
                if(this.value == "Mercado pago"){
                    $('#btnpedido').addClass('d-none')
                    $('#cvu').addClass('d-none')
                    $('.cho-container').removeClass('d-none')
                    $('.mercadopago-button').addClass('w-100')
                    let btnCheck = document.getElementById('terminos')
                    if(btnCheck.checked){
                        $('.cho-container').prop('disabled',false)
                    }else{
                        $('.cho-container').prop('disabled',true)
                    }
                }else{
                    $('#btnpedido').removeClass('d-none')
                    $('.cho-container').addClass('d-none')
                    if(this.value == "Transferencia bancaria"){
                        $('#cvu').removeClass('d-none')
                    }else{
                        $('#cvu').addClass('d-none')
                    }
                }
            }
        });
        $('#terminos').change(function() {            
            let pago = document.querySelector('input[name="pago"]:checked').value;            
            btn = document.getElementById("btnpedido")
            if(pago == "Mercado pago"){                
                btn = $('.cho-container button')
            }
            btn.disabled = true            
            if(this.checked){
                btn.disabled = false
            }
        });
        
        $('#formCarrito').on('click','.mercadopago-button',function(e){
            console.log(e)
            e.preventDefault();
        });
  </script>

@endsection