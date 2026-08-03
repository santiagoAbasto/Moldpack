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
    font-size: 18px!important;
    color:#131313!important;
    font-weight: 300!important;
}
</style>
<div class="col-12 mt-4 d-flex flex-row flex-wrap justify-content-center">
    <div class="col-12 d-flex flex-row flex-wrap mb-4 box_container" >
        @forelse ($novedades as $item)
        
        @if ($loop->iteration <=3)            
            <div  data-aos="zoom-in" class="novedadHover col-12 col-md-4 d-flex flex-column justify-content-start align-items-start mb-4" onclick="window.location='{{route($route,$item->id)}}'" style="cursor: pointer;">
                <img src="{{asset(Storage::url($item->imagen))}}" width="95%" style="height:392px;border:1px solid #ddd;">
                <div class="p-1 d-flex flex-column justify-content-between align-items-start" style="border:1px solid #ddd;width:95%;height: -webkit-fill-available;height:168px;">
                    <p style="font-size: 21px;font-weight:400;"><b>{{$item->nombre}}</b></p>
                    <div class="box_descripcion" style="height:50px;overflow: hidden;font-size:16px;"><b>{!!$item->descripcion2!!}</b></div>
                    <div class="d-flex justify-content-between align-items-center w-100 mt-2" style="color:#EC458B;">
                        LEER M&Aacute;S
                    </div>
                </div>
            </div>
        @endif
        @empty

        @endforelse
    </div>
</div>

<div class="col-12 mt-4 d-flex flex-row flex-wrap justify-content-center">
    <div class="col-12 d-flex flex-row flex-wrap mb-4 box_container" >
        <div class="col-12 col-md-9 d-flex flex-row flex-wrap">
            @forelse ($novedades as $item)
            @if ($loop->iteration > 3)            
                <div  data-aos="zoom-in" class="novedadHover col-12 col-md-4 d-flex flex-column justify-content-start align-items-start mb-4" onclick="window.location='{{route($route,$item->id)}}'" style="cursor: pointer;">
                    <img src="{{asset(Storage::url($item->imagen))}}" width="95%" style="height:288px;border:1px solid #ddd;">
                    <div class="p-1 d-flex flex-column justify-content-between align-items-start" style="border:1px solid #ddd;width:95%;height: -webkit-fill-available;height:146px;">
                        <p style="font-size: 21px;font-weight:400;"><b>{{$item->nombre}}</b></p>                        
                        <div class="d-flex justify-content-between align-items-center w-100 mt-2" style="color:#EC458B;">
                            LEER M&Aacute;S
                        </div>
                    </div>
                </div>
            @endif
            @empty
            @endforelse
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
@endsection