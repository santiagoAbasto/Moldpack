@if (isset(Auth::guard('cliente')->user()->id))
@isset($contactos[0]->celular)
<div class="logo_wsp d-flex flex-column justify-content-center align-items-center" style="width:75px;height:75px;background:#0DC143;border-radius: 40px;position: fixed;top: 84%;right: 20px;;z-index:3;">
    <a style="width: 50%;height: 50%;" target="_blank" href="https://wa.me/{{str_replace(array('+',' ','(',')','-'),'',$contactos[0]->celular)}}">
    <img width="100%" src="{{ asset('img/logo_wsp.svg') }}">
    </a>
</div>
@endisset
@endif
<div style="background:#EC458B;min-height:342px;" class="d-flex justify-content-center align-items-start">
	<div class="container-fluid p-3 d-flex justify-content-center">
		<div class="row justify-content-between box_container">
            <div class="col-md-3 mt-4">
                <img src="{{asset(Storage::url($logosfooter->imagen))}}" width="100%" height="auto" style="max-width:267px;height: fit-content;"> 
                <div class="d-none flex-column justify-content-start w-100">
                    <span class="pt-4" style="color:#fff;">Redes sociales</span>
                    <div class="d-flex flex-row align-items-start">
                        @foreach($redes as $r)
                        @isset($r->facebook)                                    
                        <div style="height: 48px;" class="d-flex align-items-center">
                        <a href="{{$r->facebook}}" target="_blank" style="color:transparent;">                                    
                            <i class="px-3 icono fab fa-facebook-f text-white"></i>
                        </a>
                        </div>
                        @endisset
                        @isset($r->instagram)
                        <div style="height: 48px;" class="d-flex align-items-center">
                        <a href="{{$r->instagram}}" target="_blank" style="color:transparent!important;">
                        
                            <i class="px-3 icono fab fa-instagram text-white"></i>
                        
                        </a> 
                        </div>
                        @endisset
                        @isset($r->youtube)                                    
                        <div style="height: 48px;" class="d-flex align-items-center">
                        <a href="{{$r->youtube}}" target="_blank">
                        
                            <i class="px-3 icono fab fa-youtube text-white"></i>
                          
                        </a>
                        </div>
                        @endisset
                        @isset($r->twitter)                                    
                        <div style="height: 48px;" class="d-flex align-items-center">
                        <a href="{{$r->twitter}}" target="_blank">
                          
                              <i class="px-3 icono fab fa-twitter text-white"></i>
                        </a>
                        </div>
                        @endisset
                        @isset($r->youtube)                                    
                        <div style="height: 48px;" class="d-flex align-items-center">
                        <a href="{{$r->youtube}}" target="_blank">
                          
                             <i class="px-3 icono fab fa-youtube-f text-white"></i>
                        </a>
                        </div>
                        @endisset                                   
                        @endforeach
                              
                      </div>
                    </div>
            </div>
            <div class="col-md-3 mt-4">	
				<span class="letra  " style="font-size:16px;color:#fff;font-weight:700;">Secciones</span>
				<div class="d-flex justify-content-start flex-wrap">
					<div class="col me-4">							
						<a href="{{route('page.inicio')}}" class="letraenlace">Inicio</a><br>
						<a href="{{route('page.empresa')}}" class="letraenlace">Empresa</a><br>
                        <a href="{{route('page.productosCategorias')}}" class="letraenlace">Productos</a>
					</div>		
					<div class="col">							
						<a href="{{route('page.novedades')}}" class="letraenlace">Novedades</a><br>						
                        <a href="{{route('page.contacto')}}" class="letraenlace">Contacto</a><br>
                        <a href="{{route('page.pedido')}}" class="letraenlace">Zona de Cliente</a>
					</div>		
				</div>	
			</div>			            

            <div class="col-md-3 mt-4">
                <div class="d-flex flex-column justify-content-start w-100">
                    <div class="">
                        <div class="letra pb-4" style="font-size:16px;color:#fff;font-weight: 600;">Suscribite al Newsletter</div>
                        <form id="formSubscribirse">
                            @csrf
                            <div class="input-group mb-3 p-0">
                                <div class="input-group-append w-100" style="position: relative;border: 1px solid #dddddd;border-radius:5px;">
                                    <input type="email" id="correo_news" name="email" class="form-control border-0 newsletter" placeholder="Ingresa un mail" style="font-size: 14px;background:transparent;border:1px solid #fff;background:#fff;border-radius:5px;" aria-describedby="basic-addon2">
                                        <button type="submit" class="newsletter border-0" id="basic-addon2" style="position: absolute;right: 0;top:0px;height:33px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;background:#fff;">
                                            <i class="fas fa-arrow-right px-3" style="color:#000;"></i>
                                        </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                
                <div class="col-md-3 pt-3 mt-2">
                    <div class="letra " style="font-size:16px;color:#fff;font-weight:700;">Datos Contacto</div>
                    <div class="row pt-2">
                        <div class="col-md-12 ">
                            
                            @foreach ($contactos as $c)                            
                            <div class="d-flex">
                                <i class="fas fa-map-marker-alt fa-lg me-3" style="color: #fff"></i>
                                <a href="https://goo.gl/maps/Q3zyPq1ZG5udGdWBA"  target="_blank" class="letraenlace ml-3">{{$c->direccion}}</a>
                            </div>
                            <div class="d-flex mt-3">
                                <i class="fas fa-envelope fa-lg me-3" style="color:#fff"></i>
                                <a href="mailto:{{$c->correo}}" class="letraenlace ml-3">{{$c->correo}}</a>
                            </div>                            
                            <div class="d-flex mt-3">
                                <i class="fas fa-phone-alt fa-lg me-3" style="color: #fff"></i>
                                <a  href="tel:{{$c->telefono}}" class="letraenlace ml-3">{{$c->telefono}}</a>
                            </div>
                            @endforeach
                            
                    </div>
                </div>
            </div>            
		
        </div>
			
				
	</div>
</div>
    
    <div class="d-flex flex-row justify-content-between align-items-center flex-wrap px-5 py-1" style="background:#FFFFFF;color:#000;font-size:14px;height: 60px;">
        <p class="p-0 m-0">Todos los derechos reservados por Moldpack</p>
        <a target="_blank" href="https://moldpack.com.ar/" style="text-decoration: none;color:#000;"></a>
    </div>
    @section('js')
    
    <script>    

        $('#formSubscribirse').on('submit',function(e){
         e.preventDefault();
         let form= new FormData($('#formSubscribirse')[0]);
         var loc = window.location;
       var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
       
         $.ajax({           
           url: '{{route('subscribirse')}}',
           data: form,
           type: "post",
           processData: false,  // tell jQuery not to process the data
           contentType: false,   // tell jQuery not to set contentType           
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           success: function (response) {
               swal("Se ha subscripto correctamente","","success");
               $('#correo_news').val("");
               setTimeout(function(){ location.reload(); }, 1500);
           },
           error: function(response){
               console.log(response);
               swal("Algo salió mal","","error");
           }
         });
        });
        
        
        $('.iconBuscar').click(function(){        
        $('#search').toggle('ocultar_')
        });
      </script>
      
@endsection