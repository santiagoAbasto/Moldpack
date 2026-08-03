@extends('layouts.plantilla')

@section('content')
<div class="col-12 d-flex justify-content-center flex-wrap align-items-center mb-5 pb-5">
 @forelse ($listas as $item)
  <div class="box_container d-flex flex-row justify-content-start align-items-center mt-5" style="background: #F4F1F4;">
    <img class="me-5" src="{{asset(Storage::url($item->imagen))}}" height="100%" width="auto"> 
    <div class="d-flex flex-column justify-content-start align-items-start" style="color:#083981;font-size:26px;font-weight:500;">{!!$item->titulo!!}
      <span class="w-75" style="color:#000;font-size:18px;font-weight:400;">Descargá nuestro catálogo actualizado con todos nuestros artículos en venta</span>
      <br>
    <a class="px-3 py-2" style="background:#ED1C24;color:#fff;font-size:16px;text-decoration:none;border-radius:30px;" download href="{{asset(Storage::url($item->archivo))}}">Descargar</a>
    </div>
  </div>   
 @empty
   
 @endforelse

</div>

@endsection