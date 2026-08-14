@extends('layouts.plantilla')
@section('metadatos')

<meta name="description" content="{{$metadatos->descripcion}}"/>
<meta name="keywords" content="{{$metadatos->keyword}}"/>
@endsection


@section('content')
<style>
.accordion-button.collapsed {
    background: transparent;
}
.box_hover{    
    position: relative;
}
/* .box_hover:hover img{
  -webkit-transform: scale(1.05);
    transform: scale(1.05);
    transition: all 0.5s ease 0.2s;
    position: relative;
    z-index: 100;
} */
.box_hover:hover:before{
  content: "+";
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 45px;
    background: #D8D8D8;
    width: 30%;
    height: 30%;
    border-radius: 100%;
    opacity: 0.7;
    z-index: 99;
    color: #fff;
    position: absolute;    
}
.box_description > *{
    color:#717171!important;font-size:18px!important;
}
</style>
@php
    $productoActivo = $producto[0] ?? null;
    $categoriaActivaId = $productoActivo
        ? ($productoActivo->categorias_id ?: optional($productoActivo->obtenerSubCategoria)->categorias_id)
        : null;

    $publicImageUrl = function ($path) {
        if (!$path) {
            return null;
        }

        $storagePath = preg_replace('#^(storage/|public/)#', '', ltrim($path, '/'));
        $publicPath = ltrim(Storage::url($storagePath), '/');
        $publicFileExists = file_exists(public_path($publicPath)) || file_exists(base_path('../httpdocs/'.$publicPath));

        return (Storage::disk('public')->exists($storagePath) || $publicFileExists)
            ? asset($publicPath)
            : null;
    };
    $fallbackImage = asset('img/logo2.jpg');
@endphp

<div class="col-12 ps-4 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
    <div class="box_container">
        <a style="text-decoration: none;color:#000;" href="{{route('page.inicio')}}">Inicio</a>
        /
        <a style="text-decoration: none;color:#000;" href="{{route('page.productosCategorias')}}">Productos</a>        
    </div>
</div>
<div class="d-flex justify-content-center">
    

<div class="d-flex flex-row flex-wrap px-4 py-4 box_container">

<div class="col-12 col-md-3 pe-0 pe-md-4">    
    <div class="sidebar">
        
        <div class="accordion" id="accordionExample">
            @if(isset($producto[0]))
            @forelse ( $categorias as $item)
            @php
                $subcategoriasActivas = $item->obtenerProductos->where('activa', 1);
                $tieneSubcategorias = $subcategoriasActivas->count() > 0;
                $categoriaAbierta = $tieneSubcategorias && (int) $categoriaActivaId === (int) $item->id;
                $categoriaDirectaActiva = !$tieneSubcategorias && (int) $categoriaActivaId === (int) $item->id;
            @endphp
            <div class="accordion-item" data-aos="fade-up" data-aos-easing="linear"  data-aos-duration="800">
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
                  <div class="accordion-body">
                        @forelse ($subcategoriasActivas as $productos)                                   
                            <div class="w-100">
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

            @else
            <style>.accordion-button:not(.collapsed) {background: unset;}</style>
            @forelse ( $categorias as $item)
            @php
                $subcategoriasActivas = $item->obtenerProductos->where('activa', 1);
                $tieneSubcategorias = $subcategoriasActivas->count() > 0;
            @endphp
            <div class="accordion-item" data-aos="fade-up" data-aos-easing="linear"  data-aos-duration="800">
                <h2 class="accordion-header" id="heading_categoria_{{$item->id}}">
                  @if ($tieneSubcategorias)
                    <button class="py-2 ps-0 accordion-button public-category-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoria_{{$item->id}}" aria-expanded="false" aria-controls="categoria_{{$item->id}}">
                        <span class="p-2" style="margin:unset;font-size:16px;">{{$item->nombre}}</span>
                    </button>
                  @else
                    <a class="py-2 ps-0 accordion-button public-category-link collapsed" href="{{route('page.categorias',$item->id)}}">
                        <span class="p-2" style="margin:unset;font-size:16px;">{{$item->nombre}}</span>
                    </a>
                  @endif
                </h2>
                @if ($tieneSubcategorias)
                <div id="categoria_{{$item->id}}" class="accordion-collapse collapse" aria-labelledby="heading_categoria_{{$item->id}}" data-bs-parent="#accordionExample">
                  <div class="accordion-body">

                        @forelse ($subcategoriasActivas as $productos)                                   
                            <div class="w-100">
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
            @endif
        </div>        
       
    </div>
</div>

<div class="d-flex flex-row flex-wrap justify-content-start align-items-start col-12 col-md-9" style="height: fit-content;">
    {{-- PRODUCTOS DESTACADOS --}}    
      @forelse ($producto as $item)
      <div class="col-12 col-md-4 d-fex flex-column justify-content-center align-items-center align-items-md-start mb-5" style="position: relative;"  data-aos="zoom-in" >
        <div class="d-flex flex-column justify-content-start align-items-center" style="width:95%;cursor:pointer;border-radius:5px;">
          <div class="d-flex justify-content-center align-items-center productoContainer box_hover" style="background:#F5F5F5;" onclick="window.location='{{route('page.producto',$item->id)}}'">
            @php $imagenProducto = $publicImageUrl($item->imagen); @endphp
            @if ($imagenProducto)
            <img src="{{$imagenProducto}}" class="public-product-image" onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div class="public-image-placeholder" style="display:none;">
              <img src="{{$fallbackImage}}" alt="Moldpack">
              <span>Imagen no disponible</span>
            </div>
            @else
            <div class="public-image-placeholder">
              <img src="{{$fallbackImage}}" alt="Moldpack">
              <span>Imagen no disponible</span>
            </div>
            @endif
          </div>
          <div class="d-flex flex-row flex-wrap align-items-start mt-3 w-100">
            <div class="col-6" style="font-size:14px;font-weight:700;color:#000;margin:unset;word-break: break-word;overflow: hidden;">
              @isset($item->obtenerCategoria()->nombre)
                {{$item->obtenerCategoria()->nombre}}
              @endisset
            </div>
            <div class="col-6" style="text-align:end;font-size:14x;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->codigo}}</div>
            <div class="col-12" style="text-align:start;font-size:20px;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->nombre}}</div>
            <hr class="w-100">
            <div class="col-12" style="font-size:17px;font-weight:400;color:#000;margin:unset;word-break: break-word;">
                Presentaciones
              <select class="form-select presentacion" id="presentacion">
                @forelse ($item->obtenerPresentacionRelacionados as $presentacion)
                <option>{{$presentacion->presentacion}}</option>                
                @empty
                @endforelse
              </select>
            </div>
          </div>              
        </div>
      </div>         
      @empty
        <div class="col-12 px-2">
          <span>No se encontraron productos.</span>
        </div>          
      @endforelse

</div>
</div>
</div>
@endsection
