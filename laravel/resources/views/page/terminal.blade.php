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
<div class="d-flex justify-content-end align-items-center flex-column" style="position:relative">          
  <img src="{{asset(Storage::url($terminal->imagen))}}" class="d-block w-100" alt="...">
  <div class="box_container" style="position: absolute;">
    <p class="pb-4" style="font-size:36px;color:#fff;">TERMINALES</p>
  </div>
</div>
<div class="d-flex justify-content-center my-5">
  <div class="box_container d-flex justify-content-center align-items-start">
    <div class="col-12 col-md-6 p-0 pe-md-1">
      {!!$terminal->texto!!}
      <img src="{{asset(Storage::url($terminal->icono2))}}" width="184px" height="184px">
    </div>
    <div class="col-12 col-md-6 p-0 ps-md-1" style="background:#F5F5F5;">
      <img src="{{asset(Storage::url($terminal->icono))}}" width="100%" height="auto">
    </div>
  </div>
</div>

@endsection