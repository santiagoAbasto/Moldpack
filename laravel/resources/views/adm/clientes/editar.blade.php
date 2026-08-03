@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('put.cliente',$cliente->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="row px-4">

    <div class="form-group col-12">
      <label for="username">Nombre de usuario / User login</label>
      <input type="text" class="form-control" id="username" name="username" value="{{$cliente->username}}"required>      
    </div>   
    
    <div class="form-group col-6">
      <label for="razonSocial">Empresa</label>
      <input type="text" class="form-control" id="razonSocial" name="razonSocial"  value="{{$cliente->razonSocial}}">
    </div>
    <div class="form-group col-6">
      <label for="nombre">Nombre de fantasia</label>
      <input type="text" class="form-control" id="nombre" name="nombre"  value="{{$cliente->nombre}}">
    </div>
    <div class="form-group col-6">
      <label for="apellido">Nombre y Apellido</label>
      <input type="text" class="form-control" id="apellido" name="apellido"  value="{{$cliente->apellido}}">
    </div>
    <div class="form-group col-6">
      <label for="telefono">Telefono</label>
      <input type="text" class="form-control" id="telefono" name="telefono" value="{{$cliente->telefono}}">      
    </div>
    <div class="form-group col-12">
      <label for="direccion">Direccion</label>
      <input type="text" class="form-control" id="direccion" name="direccion"  value="{{$cliente->direccion}}">
    </div>
    <div class="form-group col-12">
      <label for="direccionEntrega">Direccion de entrega</label>
      <input type="text" class="form-control" id="direccionEntrega" name="direccionEntrega" value="{{$cliente->direccionEntrega}}">
    </div>
    <div class="form-group col-6">
      <label for="dni">DNI</label>
      <input type="number" class="form-control" id="dni" name="dni"  value="{{$cliente->dni}}">
    </div>
    <div class="form-group col-6">
      <label for="cuit">CUIT</label>
      <input type="number" class="form-control" id="cuit" name="cuit"  value="{{$cliente->cuit}}">
    </div>
    
    <div class="form-group col-12">
      <label for="email">Email</label>
      <input type="text" class="form-control" id="email" name="email" value="{{$cliente->email}}"required>
    </div>
	  
	  <div class="form-group col-12">
      <label for="emailAux">Email aux</label>
      <input type="text" class="form-control" id="emailAux" name="emailAux" value="{{$cliente->emailAux}}">
    </div>
	    <div class="form-group col-12">
      <label for="fechaInicio">Fecha Inicio</label>
      <input type="text" class="form-control" id="fechaInicio" name="fechaInicio" value="{{$cliente->fechaInicio}}"required>
    </div>
  </div>

  <div class="form-group col-12">
    <div class="admin-access-box">
      <div class="admin-access-copy">
        <span class="admin-eyebrow">Acceso del cliente</span>
        <h2>Administrar contrase&ntilde;a</h2>
        <p>
          Puede escribir una contrase&ntilde;a manualmente o usar "Generar clave segura".
          Para ver la contrase&ntilde;a actual del cliente use "Ver contrase&ntilde;a actual" (requiere clave unica).
          Para guardar una clave nueva, ingrese la clave unica y guarde.
        </p>
      </div>

      <div class="admin-password-grid">
        <div>
          <label for="password">Nueva contrase&ntilde;a</label>
          <div class="admin-password-control">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Dejar vacia para conservar la actual">
            <button type="button" class="admin-btn admin-btn-secondary" id="toggle-password" onclick="togglePassword()">Mostrar</button>
          </div>
          @error('password')
            <span class="invalid-feedback d-block" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div>
          <label for="password-confirm">Confirmar nueva contrase&ntilde;a</label>
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Repita la nueva contrase&ntilde;a">
        </div>

        <div>
          <label for="password_view_key">Clave unica de vista de contrase&ntilde;as</label>
          <input id="password_view_key" type="password" class="form-control @error('password_view_key') is-invalid @enderror" name="password_view_key" autocomplete="off" placeholder="Requerida solo si cambia la clave">
          @error('password_view_key')
            <span class="invalid-feedback d-block" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
      </div>

      <div class="admin-password-actions">
                <button type="button" class="admin-btn admin-btn-primary" id="btn-view-password" data-url="/adm/zona_privada/clientes_password_view/{{ $cliente->id }}" onclick="viewCurrentPassword()">Ver contrase&ntilde;a actual</button>
        <button type="button" class="admin-btn admin-btn-primary" onclick="generateClientPassword()">Generar clave segura</button>
        <button type="button" class="admin-btn admin-btn-secondary" onclick="copyGeneratedPassword()">Copiar clave</button>
      </div>

      <div id="current-password-box" class="admin-generated-password d-none" aria-live="polite" style="margin-bottom:12px;">
        <span>Contrase&ntilde;a actual del cliente:</span>
        <strong id="current-password-value"></strong>
        <button type="button" class="admin-btn admin-btn-secondary btn-sm" onclick="copyCurrentPassword()">Copiar</button>
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
      <input type="number" class="form-control" name="descuentogeneral" value="{{$cliente->descuento}}">
  </div>    

  </div>
    

  <div class="form-group col-12">
    <label for="password-confirm" class="col-md-4 col-form-label ">Estado de cuenta</label>
    <div class="col-12">
        <select id="estado" class="form-select w-100" aria-label="Default select example" @error('estado') is-invalid @enderror name="estado" required autocomplete="new-estado">    
            <option {{ $cliente->estado == '0' ? 'selected':''}} value="0">Inactivo</option>
            <option  {{ $cliente->estado == '1' ? 'selected':''}} value="1">Activo</option>
          </select>      
    </div>
  </div>
  

	 <button type="submit" class="btn btn-success my-3">Guardar cambios</button>
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

          function eliminar_fila(id){   
            $(id).parent().parent().remove();
          }
          async function verifyPasswordViewKey(){
            const key = document.getElementById('password_view_key');

            if (!key || !key.value) {
              alert('Ingrese la clave unica de vista de contraseñas.');
              return false;
            }

            try {
              const response = await fetch('/adm/zona_privada/clientes_password_key_check', {
                method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                  password_view_key: key.value
                })
              });

              if (response.ok) {
                return true;
              }

              const data = await response.json();
              alert(data.message || 'Clave unica incorrecta.');
            } catch (error) {
              alert('No se pudo validar la clave unica. Intente nuevamente.');
            }

            return false;
          }

          function setPasswordVisibility(visible){
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password-confirm');
            const toggle = document.getElementById('toggle-password');
            const nextType = visible ? 'text' : 'password';

            password.type = nextType;
            confirmation.type = nextType;

            if (toggle) {
              toggle.textContent = visible ? 'Ocultar' : 'Mostrar';
            }
          }

          function togglePassword(){
            const password = document.getElementById('password');
            const shouldShow = password.type === 'password';
            setPasswordVisibility(shouldShow);
          }

          function randomIndex(length){
            if (window.crypto && window.crypto.getRandomValues) {
              const array = new Uint32Array(1);
              window.crypto.getRandomValues(array);
              return array[0] % length;
            }

            return Math.floor(Math.random() * length);
          }

          function takeRandom(chars){
            return chars.charAt(randomIndex(chars.length));
          }

          function shufflePassword(chars){
            for (let i = chars.length - 1; i > 0; i--) {
              const j = randomIndex(i + 1);
              const tmp = chars[i];
              chars[i] = chars[j];
              chars[j] = tmp;
            }

            return chars.join('');
          }

          function generateClientPassword(){
            const upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
            const lower = 'abcdefghijkmnopqrstuvwxyz';
            const numbers = '23456789';
            const symbols = '@#$%';
            const all = upper + lower + numbers + symbols;
            const chars = [
              takeRandom(upper),
              takeRandom(lower),
              takeRandom(numbers),
              takeRandom(symbols)
            ];

            while (chars.length < 14) {
              chars.push(takeRandom(all));
            }

            const generated = shufflePassword(chars);
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password-confirm');
            const box = document.getElementById('generated-password-box');
            const value = document.getElementById('generated-password-value');

            password.value = generated;
            confirmation.value = generated;
            setPasswordVisibility(true);

            if (box && value) {
              value.textContent = generated;
              box.classList.remove('d-none');
            }
          }

          function copyGeneratedPassword(){
            const value = document.getElementById('generated-password-value');
            const password = document.getElementById('password');
            const text = value && value.textContent ? value.textContent : password.value;

            if (!text) {
              alert('No hay clave para copiar.');
              return;
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
              navigator.clipboard.writeText(text);
              return;
            }

            password.type = 'text';
            password.select();
            document.execCommand('copy');
          }

          async function viewCurrentPassword(){
            if (!(await verifyPasswordViewKey())) {
              return;
            }

            const key = document.getElementById('password_view_key');
            const box = document.getElementById('current-password-box');
            const value = document.getElementById('current-password-value');

            try {
              const btn = document.getElementById('btn-view-password');
            const viewUrl = btn ? btn.dataset.url : '';
            if (!viewUrl) {
              alert('Funcion no disponible en este momento.');
              return;
            }

            const response = await fetch(viewUrl, {
                method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                  password_view_key: key.value
                })
              });

              const data = await response.json();

              if (response.ok && data.ok) {
                if (box && value) {
                  value.textContent = data.password;
                  box.classList.remove('d-none');
                }
              } else {
                alert(data.message || 'No se pudo obtener la contrasena.');
              }
            } catch (error) {
              alert('No se pudo obtener la contrasena. Intente nuevamente.');
            }
          }

          function copyCurrentPassword(){
            const value = document.getElementById('current-password-value');
            const text = value && value.textContent ? value.textContent : '';

            if (!text) {
              alert('No hay contrasena para copiar.');
              return;
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
              navigator.clipboard.writeText(text);
              return;
            }

            const tmp = document.createElement('input');
            tmp.value = text;
            document.body.appendChild(tmp);
            tmp.select();
            document.execCommand('copy');
            document.body.removeChild(tmp);
          }
</script>


  

@endsection
