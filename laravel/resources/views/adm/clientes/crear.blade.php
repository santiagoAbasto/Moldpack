@extends('adm.layouts')

@section('content')
@if($errors->any())
  <div class="alert alert-danger" role="alert">
    <strong>No se pudo crear el cliente.</strong>
    <ul class="mb-0 mt-2">
      @foreach($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
    </ul>
  </div>
@endif
<form method="post" action="{{route('nuevocliente')}}" enctype="multipart/form-data">
	@csrf
  <div class="row px-4">

    <div class="form-group col-12">
      <label for="username">Nombre de usuario / User login</label>
      <input type="text" class="form-control" id="username" name="username" required>      
    </div>
    <div class="form-group col-6">
      <label for="razonSocial">Empresa</label>
      <input type="text" class="form-control" id="razonSocial" name="razonSocial" >      
    </div>
    <div class="form-group col-6">
      <label for="nombre">Nombre de fantasia</label>
      <input type="text" class="form-control" id="nombre" name="nombre" >      
    </div>
    <div class="form-group col-6">
      <label for="apellido">Nombre y Apellido</label>
      <input type="text" class="form-control" id="apellido" name="apellido" >      
    </div>
    <div class="form-group col-6">
      <label for="telefono">Telefono</label>
      <input type="text" class="form-control" id="telefono" name="telefono" >      
    </div>
    <div class="form-group col-12">
      <label for="direccion">Direccion / Localidad / Provincia</label>
      <input type="text" class="form-control" id="direccion" name="direccion" >
    </div>
    <div class="form-group col-12">
      <label for="direccionEntrega">Direccion de entrega</label>
      <input type="text" class="form-control" id="direccionEntrega" name="direccionEntrega" >
    </div>
    <div class="form-group col-6">
      <label for="dni">DNI</label>
      <input type="number" class="form-control" id="dni" name="dni" >
    </div>
    <div class="form-group col-6">
      <label for="cuit">CUIT</label>
      <input type="number" class="form-control" id="cuit" name="cuit" >
    </div>

    <div class="form-group col-12">
      <label for="email">Email</label>
      <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{old('email')}}" required>
    </div>
  </div>

  <div class="form-group col-12">
    <div class="admin-access-box">
      <div class="admin-access-copy">
        <span class="admin-eyebrow">Acceso del cliente</span>
        <h2>Contrase&ntilde;a</h2>
        <p>
          Escriba una contrase&ntilde;a manualmente o use "Generar clave segura".
        </p>
      </div>

      <div class="admin-password-grid">
        <div>
          <label for="password">Contrase&ntilde;a</label>
          <div class="admin-password-control">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required minlength="8" autocomplete="new-password">
            <button type="button" class="admin-btn admin-btn-secondary" id="toggle-password" onclick="togglePassword()">Mostrar</button>
          </div>
          @error('password')
            <span class="invalid-feedback d-block" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div>
          <label for="password-confirm">Confirmar contrase&ntilde;a</label>
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required minlength="8" autocomplete="new-password">
        </div>
      </div>

      <div class="admin-password-actions">
        <button type="button" class="admin-btn admin-btn-primary" onclick="generateClientPassword()">Generar clave segura</button>
        <button type="button" class="admin-btn admin-btn-secondary" onclick="copyGeneratedPassword()">Copiar clave</button>
      </div>

      <div id="generated-password-box" class="admin-generated-password d-none" aria-live="polite">
        <span>Clave para informar al cliente:</span>
        <strong id="generated-password-value"></strong>
      </div>
    </div>
  </div>

  <div class="form-group col-12">
    <hr class="w-100 my-5">
    <div class="col-12">
      <label>Descuento general</label>
        <input type="number" class="form-control" name="descuentogeneral">
    </div>
  </div>
  </div>

  <div class="form-group col-12">
    <label for="password-confirm" class="col-md-4 col-form-label ">Estado de cuenta</label>

    <div class="col-12">
        <select id="estado" class="form-select w-100" aria-label="Default select example" @error('estado') is-invalid @enderror name="estado" required autocomplete="new-estado">    
            <option selected value="0">Inactivo</option>
            <option  value="1">Activo</option>
          </select>      
    </div>
  </div>
  

 <button type="submit" class="btn btn-success my-3">Agregar</button>
</form>

@endsection
@section('js')
 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
        $(document).ready(function() {
             $('textarea').summernote({
                
              height: 250,
                     fontNames: ['Montserrat-Bold', 'Montserrat-Light', 'Montserrat-Medium', 'Montserrat-Regular', 'Montserrat-SemiBold', 'Roboto-Regular'],
                     toolbar: [
                     ['style', ['style']],
                     ['font', ['bold', 'underline', 'clear']],
                     ['fontNames', ['fontname']],
                     ['color', ['color']],
                     ['table', ['table']],
                     ['para', ['ul', 'ol', 'paragraph']]
                     
                     ]
             });
         });

         function crear_fila(){            
            $("#boton").remove();
            var fila = $("#descuentos:first").clone();
            $(fila).find('input').val("");
            $("#clone").append(fila);
            $("#clone").append(`<br><div class="w-100 d-flex justify-content-end pr-2 pl-2"><button id="boton" type="button" class="btn btn-primary" onclick="crear_fila()">+ FILA</button></div>`)            
          
          }
    
</script>


  

@endsection
