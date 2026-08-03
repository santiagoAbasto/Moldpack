@extends('layouts.plantilla')



@section('content')

<div style="color:#EC458B;font-size:32px;" class="py-3 text-center">
    <b>Crear cuenta</b>
</div>
<div class="d-flex justify-content-center">
    @if(session('success'))
    <div class="alert alert-success box_container">
    {{session('success')}}
    </div>
    @endif
</div>
<div class="d-flex justify-content-center">
    @if(session('msj'))
    <div class="alert alert-success box_container">
    {{session('msj')}}
    </div>
    @endif
</div>
<div class="d-flex justify-content-center">    

<div class="d-flex flex-row justify-content-start align-items-center align-items-md-start flex-wrap m-1 m-md-5 box_container">
  <form class="col-4" method="POST" action="{{route('login.clientes')}}">
  @csrf
  <span style="color:#EC458B;font-size:24px;"><b>Iniciar sesi&oacute;n</b></span>
  <div class="mt-3 form-group row d-flex justify-content-center align-items-center">
      <div class="col-md-10">
          <span style="color:#000;font-size:16px;"><b>Usuario</b></span>
          <input style="background:transparent;color:#000;border-color:#083981;" id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
      </div>
  </div>

  <div class="mt-3 form-group row d-flex justify-content-center align-items-center">
      <div class="col-md-10">
          <span style="color:#000;font-size:16px;"><b>Contrase&ntilde;a</b></span>
          <input style="background:transparent;color:#000;border-color:#083981;" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
      </div>
  </div>

  <div class="mt-3 form-group row mb-0 d-flex justify-content-center align-items-center">
      <div class="col-md-10 d-flex justify-content-center align-items-center">
          <button style="background:#EC458B;color: #fff;" type="submit" class="btn w-100">
              INGRESAR
          </button>
      </div>
  </div>
</form>
 <form class="col-8" method="post" action="{{route('page.nuevoclienteform')}}" enctype="multipart/form-data">
	@csrf
  <div class="row px-4">

    <div class="form-group col-12">
      <label for="email">Email</label>
      <input type="text" class="form-control" id="email" name="email" required>
    </div>

    <div class="form-group col-12">
      <label for="empresa">Empresa</label>
      <input type="text" class="form-control" id="razonSocial" name="razonSocial" required>      
    </div>
    
    
    <div class="form-group col-12">
      <label for="nombre">Nombre de fantas&iacutea</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>      
    </div>

    <div class="form-group col-12">
      <label for="apellido">Nombre y Apellido</label>
      <input type="text" class="form-control" id="apellido" name="apellido" required>      
    </div>

    <div class="form-group col-12">
      <label for="direccion">Direccion / Localidad / Provincia</label>
      <input type="text" class="form-control" id="direccion" name="direccion" required>      
    </div>
    
    <div class="form-group col-12">
      <label for="direccion">Direccion de entrega</label>
      <input type="text" class="form-control" id="direccionEntrega" name="direccionEntrega" required>      
    </div>
	  <div class="form-group col-12">
      <label for="telefono">Telefono</label>
      <input type="tel" class="form-control" id="telefono" name="telefono" required>      
    </div>

    <div class="form-group col-12">
      <label for="dni">DNI</label>
      <input type="number" class="form-control" id="dni" name="dni" required>      
    </div>

    <div class="form-group col-12">
      <label for="cuit">Cuit</label>
      <input type="number" class="form-control" id="cuit" name="cuit" required>      
    </div>
    
    <div class="form-group col-12">
      <label for="password" class="col-md-4 col-form-label ">Contrase&ntilde;a</label>
      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    
    <div class="form-group col-12">
        <button type="submit" class="btn btn-primary my-3">Crear cuenta</button>
    </div>
    
  </div>
  

</form>
    </div>
</div>


@endsection