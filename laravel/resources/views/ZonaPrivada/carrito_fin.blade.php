@extends('layouts.plantilla')

@section('content')
<div class="d-flex justify-content-center ">
    @csrf
    <div class="d-flex flex-column justify-content-center align-items-center col-12 producto_container mt-4">
                
        <div class="col-12 table-responsive border">
            <h3 class="m-2">Carrito</h3>
            <div class="text-center w-100 p-5 carritoVacio">
                <span><b>Gracias {{Auth::guard('cliente')->user()->username}} por su compra, nos contactaremos en la brevedad</b></span>
            </div>
        </div>        
    </div>  
</div>
<script>
    window.onload = function () {      
      sessionStorage.removeItem('obj_fila');
    }
</script>
@endsection