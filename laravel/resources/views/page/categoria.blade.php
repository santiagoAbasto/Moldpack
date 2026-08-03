@extends('layouts.plantilla')

@section('metadatos')

<meta name="description" content="{{$metadatos->descripcion}}"/>
<meta name="keywords" content="{{$metadatos->keyword}}"/>
@endsection
@section('content')
<style>
.fondodelcoso{
    background-color: #F2F2F2;
    height: 25px;
}
.box_hover:hover:before{
    content: "+";
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 45px;
    background: #D8D8D8;
    width: 100%;
    height: 100%;
    opacity: 0.4;
    z-index: 99;
    color: #fff;
    position: absolute;    
}
</style>
@php
    $publicImageUrl = function ($path) {
        if (!$path) {
            return null;
        }

        $storagePath = preg_replace('#^(storage/|public/)#', '', ltrim($path, '/'));
        $publicPath = ltrim(Storage::url($storagePath), '/');
        $publicFileExists = file_exists(public_path($publicPath)) || file_exists(base_path('../httpdocs/'.$publicPath));

        return Storage::disk('public')->exists($storagePath) && $publicFileExists
            ? asset($publicPath)
            : null;
    };
    $fallbackImage = asset('img/logo2.jpg');
@endphp

<div class="col-12 ps-4 py-2" style="font-size:14px;color:#000000;">
    <a style="text-decoration: none;" href="{{route('page.inicio')}}"><i class="fas fa-home text-white"></i></a>
    
    <a style="text-decoration: none;color:#000;" href="{{route('page.productosCategorias')}}">Productos</a>
    /
    <a style="text-decoration: none;color:#000;" href="{{route('page.categorias',$producto[0]->obtenerCategoria->obtenerProductoCategoria->id)}}">{{$producto[0]->obtenerCategoria->obtenerProductoCategoria->nombre}}</a>
    /
    <a style="text-decoration: none;color:#000;" href="{{route('page.productos',$producto[0]->obtenerCategoria->id)}}">{{$producto[0]->obtenerCategoria->nombre}}</a>
    
</div>
<div class="d-flex flex-row flex-wrap px-4 py-4">

<div class="col-12 col-md-3 pe-0 pe-md-4">
    <div class="sidebar">                    

        <div class="accordion" id="accordionExample">
            @isset($producto)
                
            
            @forelse ( $categorias as $item)
            @php
                $subcategoriasActivas = $item->obtenerProductos->where('activa', 1);
                $tieneSubcategorias = $subcategoriasActivas->count() > 0;
                $categoriaAbierta = $tieneSubcategorias && (int) optional($producto[0]->obtenerCategoria)->categorias_id === (int) $item->id;
                $categoriaDirectaActiva = !$tieneSubcategorias && (int) optional($producto[0]->obtenerCategoria)->categorias_id === (int) $item->id;
            @endphp

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading_categoria_{{$item->id}}">
                  @if ($tieneSubcategorias)
                    <button class="py-2 ps-0 accordion-button public-category-toggle {{$categoriaAbierta ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#categoria_{{$item->id}}" aria-expanded="{{$categoriaAbierta ? 'true' : 'false' }}" aria-controls="categoria_{{$item->id}}">
                        <span class="p-2" style="margin:unset;font-size:16px;">{{$item->nombre}}</span>
                    </button>
                  @else
                    <a class="py-2 ps-0 accordion-button public-category-link collapsed {{$categoriaDirectaActiva ? 'active-leaf' : '' }}" href="{{route('page.categorias',$item->id)}}">
                        <span class="p-2" style="margin:unset;font-size:16px;">{{$item->nombre}}</span>
                    </a>
                  @endif
                </h2>
                @if ($tieneSubcategorias)
                <div id="categoria_{{$item->id}}" class="accordion-collapse collapse {{$categoriaAbierta ? 'show' : '' }}" aria-labelledby="heading_categoria_{{$item->id}}" data-bs-parent="#accordionExample">
                  <div class="accordion-body" >

                        @forelse ($subcategoriasActivas as $productos)                                   
                            <div class="w-100" data-aos="fade-up" data-aos-easing="linear"  data-aos-duration="800">
                                <a href="{{route('page.productos',$productos->id)}}"  style="text-decoration:none;color:#000;">                                    
                                        {{$productos->nombre}}
                                </a>
                            </div>
                            
                        @empty
                            
                        @endforelse        
                    </div>
                </div>
                @endif
              </div>
            
            @empty
                        
            @endforelse  
            @endisset
        </div>        
       
    </div>
</div>

    <div class="col-12 col-md-9 d-flex flex-row justify-content-start align-items-start flex-wrap">

        @forelse ($producto as $item)
        <div class="col-12 col-md-4 d-flex flex-column justify-content-center align-items-start mb-5" style="position: relative;">
            <div class="pt-2 d-flex flex-column justify-content-start align-items-center" onclick="window.location='{{route($route,$item->id)}}'"  style="width:95%;cursor:pointer;">
                @php $imagenProducto = $publicImageUrl($item->imagen); @endphp
                <div class="w-100 box_hover public-image-placeholder" style="@if($imagenProducto) background: url({{$imagenProducto}}); @endif background-size: auto;background-repeat: no-repeat;background-position: center;position:relative;display: flex;justify-content: center;height:51vh;border: 1px solid #f4f1f4;">
                    @if (!$imagenProducto)
                    <img src="{{$fallbackImage}}" alt="Moldpack">
                    <span>Imagen no disponible</span>
                    @endif
                </div>
                <div class="d-flex flex-column w-100 text-center align-items-center p-2" style="border: 1px solid #f4f1f4;">
                    <p class="pt-3" style="width: 100%;font-size:12px;color:#2C296B;margin:unset;word-break: break-word;overflow: hidden;">
                        <b>{{$item->obtenerCategoria->obtenerProductoCategoria->nombre}} / {{$item->obtenerCategoria->nombre}}</b>
                    </p>
                  <p class="py-1" style="width: 100%;font-size:20px;color:#000;margin:unset;word-break: break-word;overflow: hidden;"><b>{{$item->nombre}}</b></p>
                  
                  
                </div>
                    
            </div>
        </div>
        @empty
            
        @endforelse

    </div>
</div>
@endsection
