@extends('layouts.plantilla')

@section('content')
<style>
    .fondodelcoso{
        background-color: #F2F2F2;
        height: 25px;
    }
</style>
<div class="col-12 ps-4 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
    <div class="box_container">
        
    <a style="text-decoration: none;color:#000;" href="{{route('page.inicio')}}">Inicio</a>
    /
    <a style="text-decoration: none;color:#000;" href="{{route('page.novedades')}}">Novedades</a>
    </div>

</div>
<div class="d-flex justify-content-center">
    <div class="d-flex flex-row flex-wrap justify-content-between box_container">
        <div class="col-12 col-md-9 d-flex flex-column flex-wrap justify-content-center align-items-center pe-0 pe-md-4">
            @isset($novedad->obtenerCategoria)
                <div class="mb-1 w-100 text-start" style="font-size:18px;color:#EC458B;"><b>{{$novedad->obtenerCategoria->nombre}}</b></div>
            @endisset
            <div class="mb-3 w-100 text-start" style="font-size:32x;"><b>{{$novedad->nombre}}</b></div>
            <div class="w-100 d-flex justify-content-center mb-4" style="position: relative;">
                <div class="w-100 d-flex justify-content-center" style="background-image:url({{asset(Storage::url($novedad->imagen))}});filter: blur(10px);">
                    <img class="pe-0 pe-md-3 "  src="{{asset(Storage::url($novedad->imagen))}}" alt="" width="50%" height="auto">                
                </div>
                <img class="pe-0 pe-md-3 " src="{{asset(Storage::url($novedad->imagen))}}" alt="" width="50%" height="auto" style="position: absolute;">
            </div>
            <div  data-aos="fade-up" data-aos-easing="linear"  data-aos-duration="800">
            {!! $novedad->descripcion!!}
            </div>
        </div>
        <div class="col-12 col-md-3 d-flex flex-column flex-wrap align-items-start justify-content-start">
            <span class="mb-4" style="font-size:24px;color:#EC458B;">Categor&iacute;as</span>
            @forelse ($categorias as $item)
            @if ($loop->last)
            <div class="w-100 d-flex justify-content-lg-between align-items-center" style="font-size:17px;height:50px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
            @else                
            <div class="w-100 d-flex justify-content-lg-between align-items-center" style="font-size:17px;height:50px;border-top:1px solid #ccc;">
            @endif
                <div>
                    {{$item->nombre}}
                </div>
                <div>
                    {{count($item->obtenerNovedades)}}
                </div>
            </div>                
            @empty
                
            @endforelse
            </div>    
        </div>
    </div>
</div>
    
@endsection