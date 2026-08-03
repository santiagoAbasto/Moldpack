@extends('layouts.plantilla')
@section('metadatos')

<meta name="description" content="{{$metadatos->descripcion}}"/>
<meta name="keywords" content="{{$metadatos->keyword}}"/>
@endsection


@section('content')
<style>
.textochico{
  max-width: 579px;
  max-height: 50%;
  overflow: hidden;
  position: relative;
  z-index: 2;
}
.textochico h5,h4,h3,h2,h1{    
    font-size: 43px;
   }
.textochico p{
    font-size: 22px;        
}

.titulo{
    color:white;
}

.producto:hover{
    text-decoration: none;
}

.btn-ficha{
    color: #fff;
    background-color: #124D6B;
    border-color: #124D6B;
    }


    .btn-ficha:hover{
    color: #124D6B;
    background-color: white;
    border-color: #124D6B;
	
    }

    .prodwrap{
        width: 100%;
        height: 300px;

    }
    .prodwrap:hover .imgoverlay{
    display: block;
    top: 13px;
    bottom: 15px;
    left: 10px;
    right: 20px;
    height: 202px;
    width: 354px;
    opacity: 0.5;
    transition: .5s ease;
    background-origin: content-box;
    padding: 85px;
    background-color: #009FE3;
    }
    .imgoverlay{
    cursor: pointer;
    position: relative;
    display: none;
    color: white;
    
    text-align: center
    
    }
    .imgoverlay:hover {
    position: relative;
    color: white;
    
    }
    .icono_hover{
    height:53px;
    width: 54px;
    }

    .producto:hover{
        text-decoration: none;

    }
    .carousel-indicators li{
        width: 10px;
        height: 10px;
        border-radius: 20px;
        border-top: none;
        border-bottom: none;
    }
    .home_slider .contenedor_texto {          
      position: absolute;
      padding: 8% 0;
      top:11%;
      display: block!important;    
      position: relative;
      z-index: 9;
    }
    .textoSlider h1{
      position: relative;     
      left: -138%;
      padding-left: 5px;
    }
    .contenedor_texto h2{
      font-size:27px;
      font-weight: bold;
      text-align:start;
      text-transform:uppercase;
    }
    .carousel-indicators{
        background-color: unset!important;        
        left: -88%;
        padding-left: 10vh;
    }
    .carousel-indicators.active{
        background-color: #fff!important;
    }
    #carouselExampleIndicators .carousel-indicators button.active {
        border-top: unset!important;
        border-bottom: unset!important;
        width: 10px!important;
        height: 10px!important;
        border-radius: 15px!important;
        background-color: #FFFFFF!important;
        
    }
    
    #carouselExampleIndicators .carousel-indicators button {
        border-top: unset!important;
        border-bottom: unset!important;
        width: 10px!important;
        height: 10px!important;
        border-radius: 15px!important;
        background-color: #fff!important;
        
    }
    .banner_titulo_slider h1{
        margin:unset;
        line-height: 0.6;
    }
    :focus-visible {
    outline: none;
    }
    .slick-dots li button:before{
        font-size: 17px;
    }
    .slick-dots{
        bottom: -50px;
    }
    .slick-dots li.slick-active button:before{
        color: #717171;
    }
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
/* .box_hover:hover img{
  -webkit-transform: scale(1.05);
    transform: scale(1.05);
    transition: all 0.5s ease 0.2s;
} */
.box_hover{    
    position: relative!important;
}
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
 .box_hoverImg img{  
    position: relative;
    z-index: 100;
}
 .box_hoverImg div{  
    position: relative;
    z-index: 101;
}
 .box_hoverImg:hover img{
  -webkit-transform: scale(1.03);
    transform: scale(1.03);
    transition: all 0.3s ease 0.2s;
    position: relative;
    z-index: 100;
}
 .box_hoverImg img:before{
    content: " ";
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 45px;
    background: #D8D8D8;
    width: 100%;
    height: 100%;    
    opacity: 0.7;
    z-index: 99;
    background: #D8D5C4;
    color: #fff;
    position: absolute;
}
/*
.box_hover2:hover:before{
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
} */
.box_banner p{
  padding-bottom: 3vh;
}
.boxHeader{
  position:absolute;z-index: 2;
}
.bg-container::after{
  background: linear-gradient(90deg, rgba(0, 0, 0, 0.68) 0%, rgba(0, 0, 0, 0) 100%);
  width: 100%;
  height: 100%;  
  z-index: 1;  
  position: absolute;
}


@media (max-width: 600px) {
    .home_slider .contenedor_texto{          
      padding: 0 0vh!important;
      padding-top: 0vh!important;
    }
    .home_slider .contenedor_texto h1,h2{
      font-size: 14px!important;
    }
    .home_slider .contenedor_texto P{
      font-size: 11px!important;
    }
    .banner_padding{
      padding: 5vh 2vh!important;
    }
    .banner_titulo_slider h1{
      line-height: 1.4!important;
    }
    .img_logo_hecho{
      left: 62%!important;
    }
}
</style>

{{-- SLDIER --}}
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      
    <div class="carousel-inner ">
     
      @forelse ($sliders as $key => $item)
         @if($loop->first)
            <div class="home_slider carousel-item active" style="position:relative;">
              <div class=" w-100 d-none d-md-flex justify-content-center" style="background: url({{asset(Storage::url($item->imagen))}});background-size: cover;background-repeat: no-repeat;background-position: right;position:relative;height:96vh;">
                <div class="contenedor_texto box_container" style="display: block!important">
                    <div class="pe-5 d-none d-md-block" data-aos="fade-down"
                    data-aos-easing="linear"
                    data-aos-duration="1000">                      
                      <div class="textochico">{!!$item->descripcion!!}</div>                      
                      @isset($item->link)                      
                        <div class="my-5">
                          <a href="{{$item->link}}" class="py-2 px-4" style="color:#fff;text-decoration: none;border-radius:0px;">{{$item->boton}}</a>
                        </div>
                      @endisset
                   </div>
                  </div>
                </div> 
                <img class="d-md-none" src="{{asset(Storage::url($item->imagen))}}" width="100%" height="auto">
              </div>
              @else
              <div class="home_slider carousel-item" style="position:relative;">
              <div class=" w-100 d-none d-md-flex justify-content-center" style="background: url({{asset(Storage::url($item->imagen))}});background-size: cover;background-repeat: no-repeat;background-position: right;position:relative;height:96vh;">
                <div class="contenedor_texto box_container" style="display: block!important">
                    <div class="pe-5 d-none d-md-block" data-aos="fade-down"
                    data-aos-easing="linear"
                    data-aos-duration="1500">                      
                      <div class="textochico">{!!$item->descripcion!!}</div>
                      @isset($item->link)                      
                        <div class="my-5">
                          <a href="{{$item->link}}" class="py-2 px-4" style="background:#EC458B;color:#fff;text-decoration: none;border-radius:0px;">{{$item->boton}}</a>
                        </div>
                      @endisset
                    </div>                  
                </div>
              </div>
              <img class="d-md-none" src="{{asset(Storage::url($item->imagen))}}" width="100%" height="auto">
            </div>
            @endif
            <div class="d-flex flex-column justify-content-start align-items-start">
              <div class="box_container">
            <ol class="carousel-indicators m-0 ps-5" style="justify-content:left;bottom:15px;left:15px;">
                @for ($i = 0; $i < count($sliders); $i++)
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$i}}" style="width: 27px!important;height: 5px!important;" class="{{($i == 0) ? 'active': ''}}" aria-current="true" aria-label="Slide 1"></button>
                @endfor
            </ol>
              </div>
            </div>
      @empty
  
      @endforelse    

    </div>
  
</div>

<div class="d-flex flex-column justify-content-center align-items-center">
  {{-- PRODUCTOS DESTACADOS --}}
  @if (count($productos)> 0)
  <div class="d-flex flex-row justify-content-start align-items-center align-items-md-start flex-wrap mx-1 mx-md-5 box_container">
    <div class="col-12 text-center pt-5 mb-3" style="font-size:32px;color:#000;font-weight:500;">Productos destacados</div>
     @forelse ($productos as $item)
      <div class="col-12 col-md-3 d-fex flex-column justify-content-center align-items-center align-items-md-start mb-5" style="position: relative;"  data-aos="zoom-in" >
        <div class="d-flex flex-column justify-content-start align-items-center" onclick="window.location='{{route('page.producto',$item->id)}}'"  style="width:95%;cursor:pointer;border-radius:5px;">
          <div class="d-flex justify-content-center align-items-center productoContainer box_hover" style="background:#F5F5F5;">
            <img src="{{asset(Storage::url($item->imagen))}}" style="max-width: -webkit-fill-available;height: inherit;">
          </div>
          <div class="d-flex flex-row flex-wrap align-items-start mt-3 w-100">
          @isset($item->obtenerCategoria()->nombre)
            <div class="col-6" style="height:45px;font-size:14px;font-weight:700;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->obtenerCategoria()->nombre}}</div>
            @endisset
            <div class="col-6" style="text-align:end;font-size:14x;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->codigo}}</div>
            <div class="col-12" style="text-align:start;font-size:20px;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->nombre}}</div>
            <hr class="w-100">
            @forelse ($item->obtenerPresentacionRelacionados as $presentacion)
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
        <div class="col-12 px-2">
          <span>No se encontraron productos.</span>
        </div>          
      @endforelse

</div>
@endif
<div class="d-flex flex-column justify-content-center align-items-center">
  {{-- CATEGORIAS DESTACADAS --}}
  <div class="d-flex flex-row justify-content-start align-items-center align-items-md-start flex-wrap mx-1 mx-md-5 box_container">
    <div class="col-12 text-center pt-5 mb-3" style="font-size:32px;color:#000;font-weight:500;">Categor&iacute;as</div>
    <div class="row">
      <div class="col-12 col-md-6">
        @forelse ($categorias as $item)
        @if($loop->first)
        <div data-aos="zoom-in" class="d-flex flex-column justify-content-center align-items-center align-items-md-start" style="position: relative;">
        <div class="d-flex flex-column justify-content-end align-items-center box_hoverImg" onclick="window.location='{{route('page.categorias',$item->id)}}'"  style="cursor:pointer;background:#D8D5C4;border-radius: 0px;">
            <div class="d-flex flex-column text-center align-items-center" style="position: absolute;">
              <p style="position: relative;z-index: 100;width: 100%;font-size:26px;color:#fff;margin:unset;word-break: break-word;overflow: hidden;font-weight: 500;padding-bottom:15px;">{{$item->nombre}}</p>
            </div>
            <img width="100%" height="auto" style="max-height: 600px;" src="{{asset(Storage::url($item->imagen))}}">            
        </div>
        </div>
        @endif
        @empty
          
        @endforelse  
      </div>
      <div class="col-12 col-md-6 d-flex align-content-between flex-wrap">
        @forelse ($categorias as $item)
        @if(!$loop->first && $loop->iteration <= 5)
        <div data-aos="zoom-in" class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center align-items-md-start" style="position: relative;">
        <div class="d-flex flex-column justify-content-end align-items-center box_hoverImg" onclick="window.location='{{route('page.categorias',$item->id)}}'"  style="width:95%;cursor:pointer;background:#D8D5C4;border-radius: 0px;">
            <div class="d-flex flex-column text-center align-items-center" style="position: absolute;">
              <p style="position: relative;z-index: 100;width: 100%;font-size:26px;color:#fff;margin:unset;word-break: break-word;overflow: hidden;font-weight: 500;padding-bottom:15px;">{{$item->nombre}}</p>
            </div>
            <img width="100%" height="auto" src="{{asset(Storage::url($item->imagen))}}">            
        </div>
        </div>
        @endif
        @empty
          
        @endforelse  
      </div>
    </div>
  </div>
</div>

  {{--NOVEDADES --}}
<div class="col-12 mt-4 d-flex flex-row flex-wrap justify-content-center" style="background:#F5F3EF;">
  <div class="col-12 d-flex flex-row justify-content-between flex-wrap mb-4 box_container" >
    @isset($novedades)      
    <div class="col-12 text-center pt-5 mb-3" style="font-size:32px;color:#000;font-weight:500;">Novedades</div>
    @endisset
      @forelse ($novedades as $item)
      <div data-aos="zoom-in" class="boxNovedad d-flex flex-column justify-content-start align-items-start mb-4" onclick="window.location='{{route('page.novedad',$item->id)}}'" style="cursor: pointer;">
          <img src="{{asset(Storage::url($item->imagen))}}" style="border:1px solid #ddd;">
          <div class="p-4 d-flex flex-column justify-content-between align-items-start" style="border:1px solid #ddd;height: -webkit-fill-available;height:247px;">
              @isset($item->obtenerCategoria)
                  <div style="font-size:16px;color:#EC458B;"><b>{{$item->obtenerCategoria->nombre}}</b></div>
              @endisset
              <p style="font-size: 22px;"><b>{{$item->nombre}}</b></p>
              <div class="box_descripcion" style="height:50px;overflow: hidden;"><b>{!!$item->descripcion!!}</b></div>
              <div class="d-flex justify-content-between align-items-center w-100 mt-2">
                  <div>
                      {{$item->fecha}}
                  </div>
                  <i class="fas fa-arrow-right"></i>
              </div>
          </div>
      </div>
      @empty

      @endforelse
  </div>
</div>

</div>
@endsection