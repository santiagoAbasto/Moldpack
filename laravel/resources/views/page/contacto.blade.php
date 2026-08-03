@extends('layouts.plantilla')

@section('metadatos')
 <script src="https://www.google.com/recaptcha/api.js?render=6LfTHjcgAAAAAGITE7V8RnEJSEewBzLe7YInxDHR"></script>
<meta name="description" content="{{$metadatos->descripcion}}"/>
<meta name="keywords" content="{{$metadatos->keyword}}"/>
@endsection
@section('content') 
<style type="text/css">
.iconocom{
	color:#000;
}
.titulo_contacto{
    font-weight: 600;
    font-size:13px;
    color:#ED1C24;
}
.letracont{
	font-size: 18px;
	color: #000000;
}
.link:hover{
    color:#858592;
    text-decoration: none;
} 
.bordercont{    
    border:1px solid #D2D2D2 !important;
} 

input::placeholder{
    font-size: 14px;
    color: #8D8D8D;

}
textarea::placeholder{
    font-size: 14px;
}
.btn-ficha{
    color: #fff;
    background-color: #EC458B;
    border-color: #EC458B;
}
.btn-ficha:hover{
    color: #EC458B;
    background-color: white;
    border-color: #EC458B;
	
}

</style>
<div class="row mb-3">		
    <div class="mapouter">
        <div data-aos="zoom-in" class="gmap_canvas">
            <iframe width="100%" height="350" id="gmap_canvas" 
            src="https://maps.google.com/maps?q=Dante%20Alighieri%201377%20Don%20Torcuato%20Buenos%20Aires,%20Argentina&t=&z=13&ie=UTF8&iwloc=&output=embed"
            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://youtube-embed-code.com">youtube embed code</a><br><style>.mapouter{position:relative;text-align:right;height:350px;width:100%;}</style><a href="https://www.embedgooglemap.net">google map responsive</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:350px;width:100%;}</style>            
        </div>
    </div>
</div>
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
@isset($obj)
<div class="ms-5 my-5">
    {!!$obj->respuesta!!}  
</div>
@endisset

<div class="container my-3">
    <div class="d-flex justify-content-between box_container">
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @isset($obj)
    <div class="ms-5 my-5">
        {!!$obj->respuesta!!}  
    </div>
    @endisset
    
    <div class="container-fluid px-4 py-4">        
        <div class="row">
            <div class="col-md-4 mt-4">
                <div class="row">
                        <div data-aos="fade-right" data-aos-easing="linear" data-aos-duration="800" class="col-12">
                        <span style="font-size:32px;color:#000;font-weight: 500;line-height: 42px;">Contacto</span><br>
                        </span>
                            <div class="row mt-4">
                                <div class="col-xl-1 col-lg-1 col-md-2 col-sm-1 col-2 my-2">
                                    <i class="iconocom fas fa-map-marker-alt fa-lg"></i>                                   
                                </div>
                                <div class="col-xl-11 col-lg-10 col-md-10 col-sm-11 col-10 my-2">
                                    @foreach ($contactos as $contacto)                                         
                                        <a class="link"  href="https://goo.gl/maps/x3kP5ZhrQr2ecs4s6" target="_blank">   
                                        <span class="letracont">{{$contacto->direccion}}</span></a>                  
                                    @endforeach
                                </div>
                                
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-2 my-2">
                                    <i class="iconocom fas fa-phone-alt fa-lg"></i>
                                </div>
                                <div class="col-xl-11 col-lg-10 col-md-10 col-sm-11 col-10  my-2">
                                    @foreach ($contactos as $contacto)
                                    <a class="link" href="tel:{{$contacto->telefono}}">
                                    <span class="letracont">{{$contacto->telefono}} </span></a>                                
                                    @endforeach
                                </div>
                                
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-2 my-2">
                                    <i class="iconocom far fa-envelope fa-lg"></i>
                                </div>                            
                                <div class="col-xl-11 col-lg-10 col-md-10 col-sm-10 col-10 my-2">
                                    @foreach($contactos as $contacto)
                                    <a class="link" href="mailto:{{$contacto->correo}}">
                                        <span class="letracont">{{$contacto->correo}} </span></a>                                
                                    @endforeach
                                </div>                 
                        
                        </div>
                        
                    </div>
                </div>            
            </div>
            <div class="col-md-8 my-4">
                <form data-aos="fade-left" data-aos-easing="linear" data-aos-duration="800" method="post" id="form" action="{{route('page.contacto.post')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 ">
                            <input type="text" class="form-control bordercont" id="nombre" name="nombre" required placeholder="Nombre y apellido*">
                        </div>
                        <div class="col-md-6 ">
                            <input type="email" class="form-control bordercont" id="email" name="email" required placeholder="E-Mail*">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-4">
                            <input type="text" class="form-control bordercont" id="telefono" name="telefono" required placeholder="Teléfono">
                        </div>
                        <div class="col-md-6 mt-4">
                            <input type="text" class="form-control bordercont" id="empresa" name="empresa" required placeholder="Empresa">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <textarea class="form-control bordercont" id="mensaje" name="mensaje" rows="6"  cols="10" placeholder="Mensaje*"></textarea>
                        </div>   
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-4">
                        <p class="m-0 pe-4" style="font-size:18px;color:#131313;">* campos obligatorios</p>
                                    <button class="btn btn-ficha font-weight-bold px-5 float-right" 
                        style="font-size: 18px;"
                        id="btnEnviar"
                        type="button"
                        onclick="enviarForm()"
                        >Enviar mensaje</button>
                    </div> 
                </form>
            </div>	
        </div>
    </div>
    </div>    

    

</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!--Alertify-->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
    function enviarForm(){
           console.log('click')
        grecaptcha.ready(function() {
          grecaptcha.execute('6LfTHjcgAAAAAGITE7V8RnEJSEewBzLe7YInxDHR', {action: 'validarUsuario'}).then(function(token) {
              $('#form').prepend('<input type="hidden" name="token" value="'+ token +'" >');
              $('#form').prepend('<input type="hidden" name="action" value="validarUsuario" >');
                  data = new FormData();    
                    data.append( 'nombre', document.getElementById("nombre").value);
                    data.append( 'email', document.getElementById("email").value);
                    data.append( 'telefono', document.getElementById("telefono").value);
                    data.append( 'empresa', document.getElementById("empresa").value);    
                    data.append( 'mensaje', document.getElementById("mensaje").value);   
                    data.append( '_token', document.querySelector('[name="_token"]').value);
                    data.append( 'token', document.querySelector('[name="token"]').value);
                    data.append( 'action', document.querySelector('[name="action"]').value);
                    $.ajax({           
                        url: '{{route('page.contacto.post')}}',
                        data: data,
                        type: "post",
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,   // tell jQuery not to set contentType      
                        success: function (response) {  
                            console.log(response)
                            swal(response,"","success");
                        },
                        error: function(response){
                            console.log(response);
                            swal("Algo salio mal","","error");
                        }
                    });
          });
          });
}
</script>
@endsection