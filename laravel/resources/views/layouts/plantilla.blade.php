<!doctype html>
<html lang="en">
  <head>
	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="csrf-token" content="{{ csrf_token() }}">
	@yield('metadatos')
	  <link rel="canonical" href="https://www.moldpack.com.ar" />
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

    <title></title>
    @yield('style')
    @yield('recaptcha')  

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Encode+Sans:wght@100;200;300;400;500;600&family=Montserrat:wght@500;600;700&display=swap');
      .navbar-nav .nav-item .nav-link.active{
        border-bottom: 3px solid #B32B2D;
        padding-top: 25px;
        padding-bottom: 25px;
      }
      .nav-link{        
        font-size: 15px;
      }
      
      .manualContainer{        
        width:281px;height:282px;
      }
	      body {
	        font-family: 'Roboto Slab', serif!important;
	        overflow-x: hidden;
	      }
	      .pedido-col {
	        width: 110px!important;
	        min-width: 110px;
	        max-width: 110px;
	      }
	      .pedido-numero {
	        display: block;
	        width: 90px;
	        min-width: 90px;
	        white-space: nowrap;
	        text-align: left;
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
      .productoContainer img,
      .public-product-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
      }
      .public-image-placeholder {
        width: 100%;
        height: 100%;
        min-height: 173px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: 1px solid #f0e8ee;
        background: linear-gradient(135deg, #f8f8f8 0%, #fff 100%);
        color: #777;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
      }
      .public-image-placeholder img {
        width: 112px;
        height: auto;
        opacity: 0.35;
      }
      .public-product-detail-placeholder {
        min-height: 360px;
      }
      .public-product-detail-placeholder img {
        width: 150px;
      }
      .boxNovedad >*{
        width: 192px;       
      }
      .boxNovedad img{
        width: 192px;
        height: 192px;
      }
      .boxNovedad{
        position: relative;
      }
      .boxNovedad:hover:before{
        content: "+";
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 45px;
        background: #D8D8D8;
        width: 70px;
        height: 70px;
        border-radius: 100%;
        opacity: 0.7;
        z-index: 99;
        color: #fff;
        position: absolute;
        left: 43%;
        top: 25%;
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
            html, body {
        height: 100%; /* Asegura que el body ocupe toda la altura */
        margin: 0; /* Elimina márgenes por defecto */
      }

      #app {
          //background-image: url("{{ asset('img/navidadlights.jpg')}}"); /* Cambia esta ruta a la ubicación de tu imagen */
          background-size: cover; /* La imagen cubrirá todo el contenedor */
          background-position: center; /* Centra la imagen en el contenedor */
          background-repeat: no-repeat; /* Evita que la imagen se repita */
          height: 100vh; /* Asegura que el div ocupe toda la altura de la ventana */
      }
      #homefooter {
          background-image: url("{{ asset('img/navidadlights.jpg')}}"); /* Cambia esta ruta a la ubicación de tu imagen */
          background-size: cover; /* La imagen cubrirá todo el contenedor */
          background-position: center; /* Centra la imagen en el contenedor */
          background-repeat: no-repeat; /* Evita que la imagen se repita */
      }
    </style>  
  </head>
 <body>
<div id="app">
        @include('page.layouts.header')
        <main>
            @yield('content')
        </main>
        @include('page.layouts.footer')
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>        
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
  function mostrar(){
	  let id = "boxBuscador"
    
    document.getElementById(id).style.visibility = "hidden";
    document.getElementById(id).style.visibility = "visible";	
	
}
$('.btnBuscador').click(function(){
  $('#boxBuscador').toggle('ocultar_')
});
$('.btnOcultar').click(function(){  
  $('#boxOcultar').toggle('ocultar_')
});
$('.zp_container').click(function(){
  $('#area_privada').toggle('ocultar_')
});
	function limpiarPedidoCliente() {
	    sessionStorage.removeItem('obj_fila');
	    sessionStorage.removeItem('moldpack_cliente_last_activity');
	}

	function salir_clientes() {
	    limpiarPedidoCliente();
	    var form = document.getElementById('cliente-logout-form');
	    var logoutUrl = '{{route('salir')}}';
	    var homeUrl = '{{route('page.inicio')}}';

	    if (form && window.fetch) {
	        var token = form.querySelector('input[name="_token"]');

	        fetch(logoutUrl, {
	            method: 'POST',
	            credentials: 'same-origin',
	            headers: {
	                'Accept': 'text/html,application/xhtml+xml',
	                'X-CSRF-TOKEN': token ? token.value : ''
	            }
	        }).then(function (response) {
	            if ([409, 419].indexOf(response.status) !== -1) {
	                window.location.replace(logoutUrl);
	                return;
	            }

	            window.location.replace(response.url || homeUrl);
	        }).catch(function () {
	            window.location.replace(logoutUrl);
	        });
	        return;
	    }

	    window.location.replace(logoutUrl);
	}

	window.addEventListener('pageshow', function (event) {
	    if (event.persisted) {
	        window.location.reload();
	    }
	});

	@if(Auth::guard('cliente')->check())
	(function () {
	    var idleLimitMs = {{ (int) env('CLIENT_IDLE_TIMEOUT', config('session.lifetime')) }} * 60 * 1000;
	    var idleStorageKey = 'moldpack_cliente_last_activity';
	    var idleTimer = null;
	    var logoutUrl = '{{route('salir')}}';

	    function cerrarPorInactividad() {
	        limpiarPedidoCliente();
	        window.location.replace(logoutUrl + '?timeout=1');
	    }

	    function programarRevision(ms) {
	        clearTimeout(idleTimer);
	        idleTimer = setTimeout(revisarInactividad, Math.max(ms || idleLimitMs, 1000));
	    }

	    function marcarActividad() {
	        sessionStorage.setItem(idleStorageKey, Date.now().toString());
	        programarRevision(idleLimitMs);
	    }

	    function revisarInactividad() {
	        var ultimaActividad = parseInt(sessionStorage.getItem(idleStorageKey) || '0', 10);

	        if (!ultimaActividad) {
	            marcarActividad();
	            return;
	        }

	        var restante = idleLimitMs - (Date.now() - ultimaActividad);
	        if (restante <= 0) {
	            cerrarPorInactividad();
	            return;
	        }

	        programarRevision(restante);
	    }

	    ['click', 'mousemove', 'keydown', 'scroll', 'touchstart'].forEach(function (eventName) {
	        window.addEventListener(eventName, marcarActividad, { passive: true });
	    });

	    window.addEventListener('focus', revisarInactividad);
	    window.addEventListener('pageshow', function (event) {
	        if (event.persisted) {
	            window.location.reload();
	            return;
	        }

	        revisarInactividad();
	    });
	    document.addEventListener('visibilitychange', function () {
	        if (!document.hidden) {
	            revisarInactividad();
	        }
	    });

	    marcarActividad();
	})();
	@endif

	if (window.jQuery) {
	    $(document).ajaxError(function (event, xhr) {
	        if ([401, 409, 419].indexOf(xhr.status) !== -1) {
	            limpiarPedidoCliente();
	            var redirect = (xhr.responseJSON && xhr.responseJSON.redirect) ? xhr.responseJSON.redirect : '{{route('page.inicio')}}';
	            window.location.href = redirect;
	        }
    });
}
</script>
<script>
  AOS.init();
</script>
  @yield('js')
  
  </body>
</html>
