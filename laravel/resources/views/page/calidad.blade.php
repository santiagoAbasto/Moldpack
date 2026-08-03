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
    padding: 20% 7vh;
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
<div class="col-12 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
  <div class="box_container">
      
  <a style="text-decoration: none;color:#000;" href="{{route('page.inicio')}}">Inicio</a>
  /
  <a style="text-decoration: none;color:#000;" href="#">Calidad</a>  
  </div>
</div>
<div class="d-flex justify-content-center my-5">
  <div class="box_container d-flex flex-column justify-content-center align-items-start">
    <div class="d-flex flex-wrap justify-content-between">
      <div class="col-12 col-md-6 p-0 pe-md-1" data-aos="zoom-in"  data-aos-easing="linear"  data-aos-duration="800">
        {!!$calidad->texto!!}      
      </div>
      <div class="col-12 col-md-6 p-0 ps-md-1" style="background:#F5F5F5;">
        <img src="{{asset(Storage::url($calidad->imagen))}}" width="100%" height="auto">
      </div>
    </div>
    
    <div class="row w-100 my-5">
      <div class="col-sm-6 ps-0">
        <div class="card" style="border-left: none;border-right:none;border-radius:0px;">
          <div class="card-body py-1">
            <a href="{{asset(Storage::url($calidad->certificado))}}" download class="btn w-100 d-flex justify-content-between align-items-center">Certificado de calidad<img src="{{asset('img/dowload.png')}}"></a>
          </div>
        </div>
      </div>
      <div class="col-sm-6 pe-0">
        <div class="card" style="border-left: none;border-right:none;border-radius:0px;">
          <div class="card-body py-1">
            <a href="{{asset(Storage::url($calidad->politicas))}}" download class="btn w-100 d-flex justify-content-between align-items-center">Pol&iacute;ticas de calidad<img src="{{asset('img/dowload.png')}}"></a>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection