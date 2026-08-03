@extends('layouts.plantilla')
@section('content')
<style>
    .barra_titutlo{
    background:#3B6677;
    font-size:15px;
    color:#fff;
    padding: 5px 0;
    padding-left: 10px;
    font-weight: 500;
    }
    input[type=”file”]#nuestroinput {
     width: 0.1px;
     height: 0.1px;
     opacity: 0;
     overflow: hidden;
     position: absolute;
     z-index: -1;
     }
     .esconder {
        display: none;
    }
    </style>    
    <div class="col-12 text-center my-4" style="font-size:32px;color:#2C296B;"><b>Solicitud de presupuesto</b></div>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="alert alert-success">
                {{isset($obj->respuesta) ? $obj->respuesta : ''}}
            </div>
            <div class="d-flex flex-row justify-content-between align-items-center mt-5 col-md-8">
                <div class="d-flex flex-row align-items-center">
                    <img id="icono_edit" height="24px" width="auto" class="me-2" src="{{asset('img/edit.svg')}}">
                    <div id="text-cont" style="font-size: 24px;font-weight:600;">Datos Personales</div>
                </div>
                <div id="pasos" style="color:#2C296B;font-size:13px;font-weight:600;">
                    PASO 1 DE 2
                </div>
            </div>

            <div id="primero" class="mt-5 col-md-8">
                <form method="post" id="form"  enctype="multipart/form-data">
                    @csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        <input style="height:50px;" type="text" name="nombre" id="nombre" placeholder="Ingresar nombre*" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6" id="div_email">
                        <input style="height:50px;" type="email" name="email" id="email" placeholder="Ingrese su correo electrónico*" class="form-control" required="">
                    </div>
                </div>
                <div class="row mt-3">
                    <div id="div_telefono" class="col-12 col-md-6">
                        <input style="height:50px;" type="text" id="telefono" name="telefono" placeholder="Ingrese tu teléfono(*)" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <input style="height:50px;" required type="text" id="empresa" name="empresa" placeholder="Empresa" class="form-control">
                    </div>
                </div>
                
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-end">                 
                        <button onclick="PrimerValidacion()" type="button" class="btn px-4" style="background:#2C296B;color:#fff;border:1px solid #2C296B">Siguiente</button>
                    </div>
                </div>
            </div>
    
    
            <div id="segundo" class="mt-5 col-md-8 esconder">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <textarea name="mensaje" name="mensaje"placeholder="Mensaje" rows="3" class="form-control"></textarea>
                    </div>        
                    <div class="input-group col-6 mb-3 mt-3 ps-3">    
                        <div class="input-group mb-3" style="position: relative;">
                            <label for="inputGroupFile01" style="position:absolute;border-radius: 4px;background: #fff;z-index: 9;right:9px;display: flex;justify-content: center;align-items: center;font-size: 26px;color: #8e8e8e;top: 5px;"><i class="fas fa-folder"></i></label>
                            <div class="custom-file" style="display: flex;align-items: center;">                            
                              <input type="file"  name="file" class="custom-file-input" id="inputGroupFile01" style="position: relative;z-index: 2;width: 100%;height: calc(2.25rem + 2px);margin: 0;opacity: 0;">                          
                              <label class="custom-file-label" for="inputGroupFile01" style="position: absolute;top: 0;right: 0;left: 0;z-index: 1;height: calc(2.25rem + 2px);padding: .375rem .75rem;line-height: 1.5;color: #495057;background-color: #fff;border: 1px solid #ced4da;border-radius: .25rem;">Examinar Archivo</label>
                            </div>
                        </div>
                        
                    </div>        
                </div>
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-between">
                        <button onclick="anterior()" type="button" class="btn px-4 btn-outline-light" style="background:#fff;color:#2C296B;border:1px solid #2C296B">Volver</button>
                        <button type="submit" class="btn  px-4 " style="background-color: #2C296B;color:white;">
                            <span class="spinner-border spinner-border-sm d-none"> </span> <span class="btn-text"> Enviar </span>
                       </button>
                       
                    </div>
                </div>
            </div>
        </form>
        </div>
        
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function PrimerValidacion(){
            var email = $('#email').val();
            var div_email = $('#div_email').val();
            var validacion = 0;
            if($("#email").val().indexOf('@', 0) == -1 || $("#email").val().indexOf('.', 0) == -1) {
                $('#email').addClass('alert alert-danger')    
                validacion = 0;
            }else{
                validacion++;
            }
    
            var telefono = $('#telefono').val();
            var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
            if (filter.test(telefono)) {
                validacion++;
            }
            else {
                validacion = 0;
                $('#telefono').addClass('alert alert-danger')            
            }
    
            var nombre = $('#nombre').val();
            var filter = /[A-Za-z]/;
            if (filter.test(nombre)) {
                validacion++;
            }
            else {
                validacion = 0;
                $('#nombre').addClass('alert alert-danger')            
            }
      
            if(validacion == 3){
            $('#text-cont').html('Consulta')
            $('#pasos').html('PASO 2 DE 2')
            $('#datos').css('color',"#CBD0D3")
            $('#consulta').css('color',"#2C296B")
            $('#primero').addClass('esconder')
            $('#segundo').removeClass('esconder')
            }        
            
        }
    
        function anterior(){
            $('#text-cont').html('Datos Personales')
            $('#pasos').html('PASO 1 DE 2')
            $('#datos').css('color',"#2C296B")
            $('#consulta').css('color',"#CBD0D3")
            $('#primero').removeClass('esconder')
            $('#segundo').addClass('esconder')
        }
    </script>
    

@endsection