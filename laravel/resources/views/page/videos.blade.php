@extends('layouts.plantilla')



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
    background: #3CA4DC;
    width: 95%;
    height: 82%;
    opacity: 0.4;
    z-index: 99;
    color: #fff;
    position: absolute;
    border-radius: 10px;
}
.noveadadDescripcion p{
    width: 100%;
    word-break: break-all;
    height: auto;
}
.noveadadDescripcion img{
    display: none!important;
}
.box_descripcion>*{
    font-size: 13px!important;
    color:#131313!important;
    font-weight: 300!important;
}
</style>
<div class="col-12 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
    <div class="box_container">
        
    <a style="text-decoration: none;color:#000;" href="{{route('page.inicio')}}">Inicio</a>
    /
    <a style="text-decoration: none;color:#000;" href="#">Videos</a>  
    </div>
  </div>
<div class="container-fluid d-flex justify-content-center flex-wrap p-0">
    <div class="d-flex flex-row justify-content-start align-items-start flex-wrap mb-4 box_container">
        <div class="col-12 text-rigth pt-5 mb-3" style="font-size:30px;color:#000;">Videos</div>
        @forelse ($videos as $item)
            <div class="col-12 col-md-3 d-flex flex-column justify-content-center align-items-center pb-5" >
                <div class="videoContainer" data-aos="zoom-in" style="position: relative;overflow: hidden;width: 95%;padding-top: 73%;">
                    <iframe data-aos="zoom-in" data-aos-duration="2000" class="responsive-iframe" src="https://www.youtube.com/embed/{{$item->codigo()}}" style="position: absolute;top: 0;left: 0;bottom: 0;right: 0;width: 100%;height: 100%;"></iframe>
                </div>
                <div class="pb-4" style="border-bottom:1px solid #ddd;width:95%;">
                    <div class="mt-2" style="font-size:16px;height:22px;"><b>{{strtoupper($item->nombre)}}</b></div>
                    <div class="mt-2" style="font-size:13px;height:36px;overflow: hidden;">{{$item->descripcion}}</div>
                </div>
            </div>            
        @empty
            
        @endforelse
    </div>
</div>
@endsection