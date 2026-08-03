<style>.enlace,.link,.letraenlace{text-decoration: none;}.navbar-light .navbar-nav .nav-link{font-weight: 500;}.ocultar_{display: none;}
    .accordion-button{background-color:#fff;color: #000;}
    .accordion-button.collapsed{background-color:#fff;color: #000;}
    .accordion-button:not(.collapsed){color: #000;}
    .accordion-button::after{background-image:unset;content: "▾";transform: unset;font-size: 18px;color:#707070;}
    .accordion-button:not(.collapsed)::after{background-image:unset;content: "▾";transform: unset;font-size: 18px;color:#707070;}
    .accordion-button.collapsed::after{background-image:unset;content: "▸";transform: unset;font-size: 18px;color:#707070;}
    .accordion-button.public-category-toggle,
    .accordion-button.public-category-link{width:100%;text-align:left;box-shadow:none;cursor:pointer;}
    .accordion-button.public-category-link{text-decoration:none;color:#000;}
    .accordion-button.public-category-link::after,
    .accordion-button.public-category-link.collapsed::after,
    .accordion-button.public-category-link:not(.collapsed)::after{content:"›";font-size:22px;color:#707070;}
    .accordion-button.public-category-link.active-leaf{color:#EC458B;font-weight:700;background:transparent;}
    .accordion-item{border-left: none;border-right: none;}
    .page-link{border: unset;color: #000}
    .page-item.active .page-link{background: unset;color: #000;border: 1px solid #000;border-radius: 0px;}
    .novedadHover:hover{
        transform: scale(1.03);
        transition: all 0.5s ease 0.2s;
    }
    </style>
<!-- <div class="container-fluid d-flex justify-content-center flex-wrap p-0" style="background: #fff;box-shadow: 0px 3px 23px rgba(0, 0, 0, 0.1);"> -->
<div class="container-fluid d-flex justify-content-center flex-wrap p-0" style="box-shadow: 0px 3px 23px rgba(0, 0, 0, 0.1);">
        <nav class="navbar navbar-expand-lg navbar-light p-0 box_container">
                  
                     <a class="navbar-brand my-3 p-0" href="{{route('page.inicio')}}">
                        @foreach($logosheader as $l)
                        <img src="{{asset(Storage::url($l->imagen))}}" class="img-fluid ">
                        @endforeach
                    </a>
                        <button class="navbar-toggler mb-2 " type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-end"  id="navbarNavAltMarkup">
                            <div class="d-flex flex-row flex-wrap justify-content-center align-items-end">
                                <div class="d-flex justify-content-start">
                                    <div class="d-flex">
                                        <i class="fas fa-phone-alt fa-lg me-3" style="color: #000"></i>
                                        <a style="color:#000;" href="tel:{{$contactos[0]->telefono}}" class="letraenlace ml-3">{{$contactos[0]->telefono}}</a>
                
                                    </div>
                                    <div class="d-flex ms-3">
                                        <i class="fas fa-envelope fa-lg me-3" style="color:#000"></i>
                                        <a style="color:#000;" href="tel:{{$contactos[0]->correo}}" class="letraenlace ml-3">{{$contactos[0]->correo}}</a>
                                    </div>
                                    <div class="d-flex ms-3">                        
                                        <div class="d-flex flex-row align-items-start">
                                            @foreach($redes as $r)
                                            @isset($r->facebook)                                    
                                            <div style="height:20px;" class="d-flex align-items-center">
                                            <a href="{{$r->facebook}}" target="_blank" style="color:transparent;">                                    
                                                <i class="px-3 icono fab fa-facebook-f text-black"></i>
                                            </a>
                                            </div>
                                            @endisset
                                            @isset($r->instagram)
                                            <div style="height:20px;" class="d-flex align-items-center">
                                            <a href="{{$r->instagram}}" target="_blank" style="color:#000" class="letraenlace">
                                                <i class="px-3 icono fab fa-instagram text-black"></i>@moldpack
                                            </a>
                                            </div>
                                            @endisset
                                            @isset($r->youtube)                                    
                                            <div style="height:20px;" class="d-flex align-items-center">
                                            <a href="{{$r->youtube}}" target="_blank">
                                            
                                                <i class="px-3 icono fab fa-youtube text-black"></i>
                                              
                                            </a>
                                            </div>
                                            @endisset
                                            @isset($r->twitter)                                    
                                            <div style="height:20px;" class="d-flex align-items-center">
                                            <a href="{{$r->twitter}}" target="_blank">
                                              
                                                  <i class="px-3 icono fab fa-twitter text-black"></i>
                                            </a>
                                            </div>
                                            @endisset
                                            @isset($r->youtube)                                    
                                            <div style="height:20px;" class="d-flex align-items-center">
                                            <a href="{{$r->youtube}}" target="_blank">
                                              
                                                 <i class="px-3 icono fab fa-youtube-f text-black"></i>
                                            </a>
                                            </div>
                                            @endisset                                   
                                            @endforeach
                                                  
                                          </div>
                                    </div>
                                </div>
                            </div>
                             <div class="navbar-nav  d-flex justify-content-between ml-auto mt-3">                            
                                @if (isset(Auth::guard('cliente')->user()->id))                              
                                <a class="nav-item nav-link mx-1 {{$active == 'page.pedido' ? 'activeheader' : ''}}" href="{{route('page.pedido')}}">Catalogo</a>
                                <a class="nav-item nav-link mx-1 {{$active == 'page.carrito' ? 'activeheader' : ''}}" href="{{route('carrito')}}">Pedido</a>
                                <a class="nav-item nav-link mx-1 {{$active == 'page.historico' ? 'activeheader' : ''}}" href="{{route('historico',Auth::guard('cliente')->user()->id)}}">Historial</a>
                                <a class="nav-item nav-link mx-1 {{$active == 'page.facturas' ? 'activeheader' : ''}}" href="{{route('zp.factura')}}">Facturas</a>
                                <div class="dropdown">
                                    <button style="background:#EC458B;" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{Auth::guard('cliente')->user()->username}}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="width: 87px;min-width: unset;">
                                        <li><a class="nav-item nav-link mx-1 dropdown-item" onclick="salir_clientes()" style="cursor:pointer;">SALIR</a></li>                                    
                                    </ul>
                                </div>
                                <form id="cliente-logout-form" action="{{route('salir')}}" method="POST" class="d-none">
                                  @csrf
                                </form>
                                @else                                                                                          
                                  <a class="nav-item nav-link mx-1 {{ $active == 'page.empresa' ? 'activeheader' : ''}}" href="{{route('page.empresa')}}">Empresa</a>
                                  <a class="nav-item nav-link mx-1 {{ $active == 'page.productos' ? 'activeheader' : ''}}" href="{{route('page.productosCategorias')}}">Productos</a>
                                  <a class="d-none nav-item nav-link mx-1 {{ $active == 'page.comprar' ? 'activeheader' : ''}}" href="{{route('page.dondeComprar')}}">Donde comprar</a>
                                  <a class="nav-item nav-link mx-1 {{ $active == 'page.novedades' ? 'activeheader' : ''}}" href="{{route('page.novedades')}}">Novedades</a>
                                  <a class="nav-item nav-link mx-1 {{ $active == 'page.catalogo' ? 'activeheader' : ''}}" href="{{route('page.catalogo')}}">Cat&aacute;logo</a>
                                  <a class="nav-item nav-link mx-1 {{ $active == 'page.contacto' ? 'activeheader' : ''}}" href="{{route('page.contacto')}}">Contacto</a>
                                  <div class="d-flex justify-content-end">
                                    <span style="position: relative;display: flex;justify-content: center;align-items: center;" class="mx-2 py-1">
                                        <a style="color:#EC458B!important;cursor:pointer;" class="iconBuscar">
                                            <i class="fas fa-search fa-lg"></i>
                                        </a>
                                        <div id="search" class="ocultar_" style="position: absolute;background:#EC458B;width: 12vw;top: 36px;z-index:1;right: 0;">
                                            <form action="{{route('buscar')}}" method="GET" class="p-3">
                                                <input type="text" class="form-control" name="buscador" placeholder="BUSCAR">
                                            </form>
                                        </div>
                                    </span>
                                    <button class="btn zp_container py-1 px-4" type="button" style="color:#fff;background:#EC458B;border-radius:0px;">
                                         Area Cliente
                                    </button>                                    
                                @if (!isset(Auth::guard('cliente')->user()->id))
                                <div id="area_privada" class="ocultar_" style="position:absolute;width:295px;height:315px;top:112px;right:13;z-index:101;background:#f2f2f2;border-radius:0px;">
                                    <div class="container">
                                        <div class="justify-content-center align-items-center">
                                            <div class="col-md-12">
                                                <div class="card-body px-0 pt-3">
                                                    @isset($msj)
                                                            <div>{{$msj}}</div>
                                                        @endisset
                                                
                                                    <form method="POST" action="{{route('login.clientes')}}">
                                                        @csrf
                                                        <span style="color:#EC458B;font-size:20px;"><b>INGRESO PARA CLIENTES</b></span>
                                                        <div class="mt-3 form-group row d-flex justify-content-center align-items-center">
                                                            <div class="col-md-10 p-0">
                                                                <span style="color:#000;font-size:16px;"><b>Usuario</b></span>
                                                                <input style="background:transparent;color:#000;border-color:#EC458B;" id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                                            </div>
                                                        </div>
                    
                                                        <div class="mt-3 form-group row d-flex justify-content-center align-items-center">
                                                            <div class="col-md-10 p-0">
                                                                <span style="color:#000;font-size:16px;"><b>Contraseña</b></span>
                                                                <input style="background:transparent;color:#000;border-color:#EC458B;" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                            </div>
                                                        </div>
                    
                                                        <div class="mt-3 form-group row mb-0 d-flex justify-content-center align-items-center">
                                                            <div class="col-md-10 d-flex justify-content-center align-items-center px-0">
                                                                <button style="background:#EC458B;color: #fff;border-radius:0px;" type="submit" class="btn w-100">
                                                                    INGRESAR
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="w-100 text-center">
                                                        <a  href="{{route('page.registro')}}">Registrarme</a><br>
                                                        <a href="{{route('password')}}">Olvide mi contraseña</a>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                @endif        
                                </div>
                                @endif
                        
                                </div>
    
                        </div>
                </nav>
            
            </div>  
    
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
    
    
    
