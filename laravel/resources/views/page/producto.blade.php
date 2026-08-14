@extends('layouts.plantilla')
@section('metadatos')

<meta name="description" content="{{$metadatos->descripcion}}"/>
<meta name="keywords" content="{{$metadatos->keyword}}"/>
@endsection
@section('content')
<style>
.fotorama__nav--thumbs {
    display:flex!important;
}
.accordion-button.collapsed {
    background: transparent;
}
.table, tbody, tr,td  {
    border:none;
    font-size: 15px!important;    
}
.table>:not(caption)>*>*{
    padding-left:0px;
}
.propiedadList ul {
  list-style-image: url("{{asset('img/market.png')}}")
}
.listMarket ul {
  list-style-image: url("{{asset('img/market2.png')}}")
}
.accordion-button:not(.collapsed){
    color: #EC458B;
    font-weight: 700;
    background: none;
}
@media (max-width: 600px) {
    .aplicaciones table tr{
    display: flex;
    flex-flow: column;
}
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

        return (Storage::disk('public')->exists($storagePath) || $publicFileExists)
            ? asset($publicPath)
            : null;
    };
    $fallbackImage = asset('img/logo2.jpg');
    $productoImagenes = collect(array_merge([$producto->imagen], $producto->obtenerGaleria() ?: []))
        ->map($publicImageUrl)
        ->filter()
        ->unique()
        ->values();
    $categoriaActivaId = $producto
        ? ($producto->categorias_id ?: optional($producto->obtenerSubCategoria)->categorias_id)
        : null;
@endphp
    
<div class="col-12 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
    <div class="box_container">
        
    <a style="text-decoration: none;color:#000;" href="{{route('page.inicio')}}">Inicio</a>
    /
    <a style="text-decoration: none;color:#000;" href="{{route('page.productosCategorias')}}">Productos</a>    
    </div>

</div>

<div class="d-flex justify-content-center">
    <div class="box_container d-flex flex-row flex-wrap justify-content-between align-items-start py-4">
        <div class="col-12 col-md-3 pe-0 pe-md-4">    
            <div class="sidebar">                
                <div class="accordion" id="accordionExample">
                    @forelse ( $categorias as $item)        
                    @php
                        $subcategoriasActivas = $item->obtenerProductos->where('activa', 1);
                        $tieneSubcategorias = $subcategoriasActivas->count() > 0;
                        $categoriaAbierta = $tieneSubcategorias && (int) $categoriaActivaId === (int) $item->id;
                        $categoriaDirectaActiva = !$tieneSubcategorias && (int) $categoriaActivaId === (int) $item->id;
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
                        <div class="accordion-body">        
                                @forelse ($subcategoriasActivas as $productos)
                                <div class="w-100">
                                    <a href="{{route('page.productos',$productos->id)}}"  style="text-decoration:none;color:#000;font-size:14px;">
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
                </div>
            </div>
        </div>            
        
        <div class="col-12 col-md-9 d-flex flex-column">
            <div class="row flex-wrap">
                <div class="col-12 col-md-6">
                    @if ($productoImagenes->isNotEmpty())
                    <div class="fotorama product-gallery" data-nav="thumbs" data-width="100%" data-ratio="800/600" data-fit="contain">
                        @foreach ($productoImagenes as $imagenProducto)
                            <img src="{{$imagenProducto}}" class="img-fluid public-product-image" alt="{{$producto->nombre}}" onerror="this.onerror=null;this.src='{{$fallbackImage}}';">
                        @endforeach
                    </div>
                    @else
                    <div class="public-image-placeholder public-product-detail-placeholder">
                        <img src="{{$fallbackImage}}" alt="Moldpack">
                        <span>Imagen no disponible</span>
                    </div>
                    @endif
                </div>
                @forelse ($producto->obtenerPresentacionRelacionados as $presentacion)
                    @if ($loop->first)
                        @php
                            $producto->codigo = $presentacion->codigo;
                        @endphp
                    @endif
                @empty
                @endforelse

                <div class="col-12 col-md-6 d-flex flex-column propiedadList">
                    <div class="pb-3" style="font-size:17px;color:#000;font-weight:700;">{{@$producto->obtenerFamilia->nombre}} <span>{{$producto->codigo}}</span></div>
                    <div class="pb-4" style="font-size:26px;color:#000;font-weight:400;">{{@$producto->nombre}}</div>
                    <div class="pb-4 d-flex justify-content-between" style="font-size:16px;color:#000;font-weight:400;"><span>{{$producto->medida}}</span></div>
                    @isset($producto->descripcion)
                    {!!$producto->descripcion!!}
                    @endisset
                    @isset($producto->obtenerPresentacionRelacionados)
                    <div class="d-flex justify-content-between align-items-center w-100 py-5">
                        <span style="font-size: 16px;font-weight:400;">Presentaci&oacute;n</span>
                        <select class="form-select w-50">
                        @forelse ($producto->obtenerPresentacionRelacionados as $presentacion)
                                <option value="{{$presentacion->codigo}}">{{$presentacion->presentacion}}</option>
                        @empty
                        @endforelse
                        </select>
                    </div>
                    @endisset                    
                    <br>
                    <br>                    
                    <a href="{{route('page.contacto')}}" class="btn px-4 w-100 mb-4" style="text-decoration:none;background:#EC458B;color:#fff;font-size:14px;font-weight:600;">CONTACTAR</a>                    
                </div>
            @if (count($producto->obtenerRelacionados) > 0)
                <div class="d-flex flex-row flex-wrap justify-content-start align-items-start col-12 my-5" style="height: fit-content;">
                    <div class="col-12 d-flex flex-row align-items-center">
                        <div class="mb-4" style="color:#131313;font-size:24px;"><b>Productos Relacionados</b></div>                        
                    </div>
                    {{-- PRODUCTOS DESTACADOS --}}    
                      @forelse ($producto->obtenerRelacionados as $item)
                      
                      <div id="boxProducto" class="col-12 col-md-4 d-fex flex-column justify-content-center align-items-center align-items-md-start mb-5" style="position: relative;"  data-aos="zoom-in" >
                        <div class="d-flex flex-column justify-content-start align-items-center" onclick="window.location='{{route('page.producto',$item->producto->id)}}'"  style="width:95%;cursor:pointer;border-radius:5px;">
                          <div class="d-flex justify-content-center align-items-center productoContainer box_hover" style="background:#F5F5F5;">
                            @php $imagenRelacionada = $publicImageUrl($item->producto->imagen); @endphp
                            @if ($imagenRelacionada)
                            <img src="{{$imagenRelacionada}}" class="public-product-image" onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';">
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
                            <div class="col-6" style="height:45px;font-size:14px;font-weight:700;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->producto->obtenerFamilia->nombre}}</div>
                            @forelse ($item->producto->obtenerPresentacionRelacionados as $presentacion)
                            @if ($loop->first)
                            <div class="col-6" style="text-align:end;font-size:14x;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$presentacion->codigo}}</div>
                            @endif
                            @empty                    
                            @endforelse
                            <div class="col-12" style="text-align:start;font-size:20px;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->producto->nombre}}</div>
                            <hr class="w-100">
                            @forelse ($item->producto->obtenerPresentacionRelacionados as $presentacion)
                            @if ($loop->first)
                            <div class="col-12" style="font-size:17px;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">
                                {{$presentacion->presentacion}}          
                            </div>            
                            @endif
                            @empty                    
                            @endforelse
                          </div>              
                        </div>
                      </div> 
                      @empty
                          
                      @endforelse
                  
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
@endsection
