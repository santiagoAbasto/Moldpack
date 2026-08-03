@extends('layouts.plantilla')

@section('metadatos')

<meta name="description" content="{{$metadatos->descripcion}}"/>
<meta name="keywords" content="{{$metadatos->keyword}}"/>
@endsection

@section('content')
<style>
.texto{ 	
 	margin-top:130px;
 	margin-left: 60px;
    font-size: 46px;
    font-weight: 700;
    color: #1F3579;

}
.textochico{    
    margin-left: 60px;
    width: 391px;
    font-size: 32px;
    color: black;

   }

   .fondodelcoso{
        background-color: #F2F2F2;
        height: 25px;
    }

    .carousel-indicators li{
        width: 10px;
        height: 10px;
        border-radius: 20px;
        border-top: none;
        border-bottom: none;
    }
    .home_slider .contenedor_texto {    
    width: 100%;
    position: absolute;
    padding: 17% 7vh;
    display: block!important;    
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
        background-color: #fff!important;
        
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
    .boxHeader{
      position:absolute;z-index: 2;
    }
    p{
      margin: 0;
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
      
  <div class="carousel-inner bg-container">
   
    @forelse ($sliders as $key => $item)
       @if($loop->first)
          <div class="home_slider carousel-item active" style="position:relative;">
            <div class="bg-container w-100 d-none d-md-flex justify-content-center" style="background: url({{asset(Storage::url($item->imagen))}});background-size: cover;background-repeat: no-repeat;background-position: right;position:relative;height:97vh;">
              <div class="contenedor_texto box_container" style="display: block!important">
                  <div class="pe-5 d-none d-md-block" data-aos="fade-down"
                  data-aos-easing="linear"
                  data-aos-duration="1000">                      
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
            @else
            <div class="home_slider carousel-item" style="position:relative;">
            <div class="bg-container w-100 d-none d-md-flex justify-content-center" style="background: url({{asset(Storage::url($item->imagen))}});background-size: cover;background-repeat: no-repeat;background-position: right;position:relative;height:97vh;">
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

<div class="d-flex justify-content-center my-5">
  <div class="box_container d-flex justify-content-center align-items-start flex-wrap">
    <div class="col-12 col-md-6 p-0 pe-md-1" data-aos="fade-right" data-aos-easing="linear" data-aos-duration="800">
      {!!$empresa->texto!!}
    </div>
    <div class="col-12 col-md-6 p-0 ps-md-1" data-aos="fade-left" data-aos-easing="linear" data-aos-duration="800">
      <img src="{{asset(Storage::url($empresa->imagen))}}" class="d-block w-100" alt="...">      
    </div>
  </div>
</div>

<div class="d-flex justify-content-center my-5">
  <div class="box_container d-flex justify-content-center align-items-start flex-wrap">
    <div class="col-12 col-md-6 p-0 pe-md-1" data-aos="fade-right" data-aos-easing="linear" data-aos-duration="800">
      <img src="{{asset(Storage::url($empresa->imagen2))}}" class="d-block w-100" alt="...">      
    </div>
    <div class="col-12 col-md-6 p-0 ps-md-1" data-aos="fade-left" data-aos-easing="linear" data-aos-duration="800">
      {!!$empresa->texto2!!}
    </div>
  </div>
</div>
@endsection