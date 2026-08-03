<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@yield('metadatos')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick-theme.css')}}"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- FAVICON-->
       <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/favicon.ico')}}?v=20260724">
       <link rel="icon" type="image/x-icon" href="{{asset('img/favicon.ico')}}?v=20260724">
    <!--Alertify-->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/plantilla.css?6') }}" rel="stylesheet">

    <!-- ANIMATION -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<style>
body {
  font-family: 'Roboto Slab', serif!important;
  overflow-x: hidden;
}
.box_container{ width: 100%;}
.img-container{
  position: relative;        
}
.img-container::after{
  width: 100%;
  height: 100%;  
  z-index: 99;  
  position: absolute;
  content: "";
  background:#034EA266;
}
.productoContainer{        
  width:285px;height:273px;
}
@media (min-width: 1250px) {
.box_container{ width: 1223px!important;}
.productoContainer{
width: 285px!important;
height: 273px!important;
}
.boxNovedad img{
width: 392px!important;
height: 392px!important;
}
.boxNovedad >*{
width: 392px!important;            
}
.manualContainer{        
width:285px!important;height:282px!important;
}        
}
@media (min-width: 768px) {
.box_container{ width: 800px;}
.productoContainer{width: 185px;height: 173px;}
.manualContainer{width: 181px;height: 182px;}
.boxNovedad img{width: 250px;height: 250px;}
.boxNovedad >*{width: 250px;}
}
</style>  
  </head>
<body>
@php
  $publicImageUrl = function ($path) {
      if (!$path) {
          return asset('img/logo2.jpg');
      }

      $storagePath = preg_replace('#^(storage/|public/)#', '', ltrim($path, '/'));
      $publicPath = ltrim(Storage::url($storagePath), '/');
      $publicFileExists = file_exists(public_path($publicPath)) || file_exists(base_path('../httpdocs/'.$publicPath));

      return Storage::disk('public')->exists($storagePath) && $publicFileExists
          ? asset($publicPath)
          : asset('img/logo2.jpg');
  };
  $fallbackImage = asset('img/logo2.jpg');
@endphp


<div class="d-flex justify-content-center">
    

<div class="d-flex flex-row flex-wrap px-4 py-4 box_container">
<div class="d-flex flex-row flex-wrap justify-content-start align-items-start col-12" style="height: fit-content;">
    {{-- PRODUCTOS DESTACADOS --}}  
    @forelse ($categorias as $categoria)
    <div class="w-100 my-4" style="background: #EC458B;text-align: center;color:#fff;"><b>{{$categoria->nombre}}</b></div>
      @forelse ($categoria->obtenerListaProductos->where('activa','!=',0) as $item)      
      <div id="boxProducto" class="col-12 col-md-4 d-fex flex-column justify-content-center align-items-center align-items-md-start mb-5 " style="position: relative;" >
        <div class="d-flex flex-column justify-content-start align-items-center" style="width:95%;cursor:pointer;border-radius:5px;">
          <div class="d-flex justify-content-center align-items-center productoContainer box_hover" style="background:#F5F5F5;">
            @if ($item->imagen)
            <img src="{{$publicImageUrl($item->imagen)}}" onerror="this.onerror=null;this.src='{{$fallbackImage}}';" style="max-width: -webkit-fill-available;height: inherit;">
            @else
            <img src="{{asset('img/logo2.jpg')}}" style="max-width: -webkit-fill-available;">
            @endif
          </div>
          <div class="d-flex flex-row flex-wrap align-items-start mt-3 w-100">
            <div class="col-6" style="height:45px;font-size:14px;font-weight:700;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->obtenerCategoria()->nombre}}</div>
            <div class="col-6" style="text-align:end;font-size:14x;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->codigo}}</div>
            <div class="col-12" style="text-align:start;font-size:20px;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">{{$item->nombre}}</div>
            <hr class="w-100">
            <div class="col-12" style="font-size:17px;font-weight:400;color:#000;margin:unset;word-break: break-word;overflow: hidden;">
              Presentaciones: 
            @forelse ($item->obtenerPresentacionRelacionados as $presentacion)
                {{$presentacion->presentacion}}<br>
            @empty                    
            @endforelse
              </div>
          </div>              
        </div>
      </div>         
      @empty
        <div class="col-12 px-2">
          <span>No se encontraron productos.</span>
        </div>          
      @endforelse
    @empty
      
    @endforelse  
      
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>        
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
  AOS.init();
</script>
  @yield('js')
  
  </body>
</html>
