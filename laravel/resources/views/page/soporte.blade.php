@extends('layouts.plantilla')

@section('content')
<style>
 .nav-tabs .nav-item .nav-link{  
  opacity:0.3;
  border: none;
 }
 .nav-tabs .nav-item .nav-link.active{
  opacity: inherit;
 }
</style>
<div class="col-12 py-2 d-flex justify-content-center" style="font-size:14px;color:#000000;">
  <div class="box_container">
      
  <a style="text-decoration: none;color:#000;" href="{{route('page.inicio')}}">Inicio</a>
  /
  <a style="text-decoration: none;color:#000;" href="#">Soporte</a>  
  </div>
</div>
<div class="col-12 d-flex justify-content-center flex-wrap align-items-center">
  <div class="box_container d-flex flex-row flex-wrap justify-content-start align-items-center">
    <ul class="nav nav-tabs col-12" style="border: none;">
      <ul class="nav nav-tabs" id="myTab" role="tablist" style="border:none;">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="manuales-tab" data-bs-toggle="tab" data-bs-target="#manuales" type="button" role="tab" aria-controls="manuales" aria-selected="true" style="text-transform:none;border:none;font-size:30px;font-weight:400;">Manuales</button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="service-tab" data-bs-toggle="tab" data-bs-target="#service" type="button" role="tab" aria-controls="service" aria-selected="false" style="font-size:30px;text-transform:none;font-weight:400;">Service Oficial</button>
        </li>        
      </ul>

      <hr class="my-2 w-100">

      <div class="tab-content col-12" id="myTabContent">
        <div class="tab-pane fade show active" id="manuales" role="tabpanel" aria-labelledby="manuales-tab">
          <div class="d-flex flex-row justify-content-start align-items-center my-4">
          @forelse ($descargas as $item)
            <div class="col-12 col-md-3 d-flex flex-column justify-content-center align-items-center align-items-md-start mb-5"  data-aos="flip-left"  data-aos-easing="linear" data-aos-duration="1500">
              <div class="d-flex flex-column justify-content-start align-items-center" style="width:95%;">
                <div class="d-flex justify-content-center align-items-center manualContainer flex-wrap" style="background:#F5F5F5;">
                  <img src="{{asset(Storage::url($item->imagen))}}">
                  <div class="col-12 py-2 d-flex flex-column" style="border-bottom:1px solid #ddd">
                    <div class="text-start col-12" style="font-size:13px;font-weight:600;">
                      {{$item->titulo}}
                    </div>
                    <div class="text-start col-12" style="font-size:13px;">
                      <a class="w-100 d-flex justify-content-between align-items-center" style="text-decoration:none;color:#000;" href="{{asset(Storage::url($item->archivo))}}" download>
                        <span>PDF - {{$item->sizefile()}}kb</span><span><img src="{{asset('img/dowload.png')}}"></span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
          
          @endforelse
          </div>
        </div>

        <div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">
          <?php $cont_distribuidor = 0; ?>
          @foreach($distribuidores as $distribuidor)
              <input id="p1_{{ $cont_distribuidor }}" name="" type="hidden" value="{{ $distribuidor->latitud }}">
                  <input id="p2_{{ $cont_distribuidor }}" name="" type="hidden" value="{{ $distribuidor->longitud }}">
                  <input id="nombre_{{ $cont_distribuidor }}" name="" type="hidden" value="{{ $distribuidor->nombre }} | {{ $distribuidor->direccion }} | {{ $distribuidor->telefono }} | {{ $distribuidor->correo }} | {{ $distribuidor->web }}">
                      <?php $cont_distribuidor++; ?>
           @endforeach
           <input type="hidden" id="cantidad" name="" value="{{ $cont_distribuidor }}">
           <div class="col-12 d-flex flex-row flex-wrap" >
               <div class="col-12 col-md-4" style="max-height: 696px;overflow: overlay;">
                <div class="d-flex flex-row justify-content-between align-items-center pb-4" style="border-bottom:1px solid #ddd;">
                  <div class="me-2 w-100">
                    <select class="form-select pe-2" id="selec_provincia" aria-label="Default select example">
                      <option value="0" selected>Provincia</option>
                      @forelse ($provincias as $item )
                        <option value="{{str_replace(' ','',$item->provincia)}}" id="{{str_replace(' ','',$item->provincia)}}">{{$item->provincia}}</option>
                      @empty
                        
                      @endforelse						
                      
                      </select>
                  </div>
          
                  <div class="ms-2 w-100"  data-aos="flip-left"  data-aos-easing="linear" data-aos-duration="1500">
                    <select class="form-select pe-2" id="selec_localidad" aria-label="Default select example">
                      <option value="0" selected>Localidad</option>
                      @forelse ($localidades as $item )
                        <option value="{{str_replace(' ','',$item->localidad)}}" id="{{str_replace(' ','',$item->localidad)}}">{{$item->localidad}}</option>
                      @empty
                        
                      @endforelse						
                      
                      </select>
                  </div>
          
                  <div>
                    <img src="{{ asset('img/lupa.png') }}" width="30px" height="auto" class="ps-2" style="cursor: pointer;" onclick="search_distribuidor()">
                  </div>
                </div>
                  @forelse ($distribuidores as $item)
              <div  style="display:flex;cursor: pointer;" class="flex-column box_distribuidor {{str_replace(' ','',$item->localidad)}}" id="{{str_replace(' ','',$item->provincia)}}" onclick="localizar({{$item->latitud}},{{$item->longitud}})">
                      <div class="pt-2" style="font-size:18px;color:#EC458B;"><b>{{$item->nombre}}</b></div>
                      <div style="font-size:14px;color:#000;">{!!$item->direccion!!}</div>
                      <div style="font-size:14px;color:#000;">{!!$item->telefono!!}</div>
                      <div style="font-size:14px;color:#000;">{!!$item->correo!!}</div>                      
                      <div style="font-size:14px;color:#000;border-bottom:1px solid #ccc;">{!!$item->horario!!}</div>
              </div>
                  @empty
                      
                  @endforelse
          
               </div>
              <div class="mt-3 col-12 col-md-8" id="map" style="height: 696px;border-left:15px solid #fff;"></div>
           </div>
        </div>        
      </div>

    
  </div>   

</div>

@section('js')
<script>
var map;

jQuery(function($) {

var script = document.createElement('script');
script.src = "//maps.googleapis.com/maps/api/js?sensor=false&callback=initialize&key=AIzaSyAZUlidy4Exa3bvZLRh4qgqx4lwlLy6khw&libraries=geometry,places";
document.body.appendChild(script);
});
function initialize() 
{ 
var mapOptions = {
center: new google.maps.LatLng('{{$distribuidores[0]->latitud}}', '{{$distribuidores[0]->longitud}}'),
zoom: 10,
width:100,
scrollwheel: true,
disableDefaultUI: false,
mapTypeId: google.maps.MapTypeId.ROADMAP,
};

var codificador = new google.maps.Geocoder();


var direccion = '<?php if(isset($_GET['direccion'])){echo $_GET['direccion'];}else{echo '';} ?>';
var kilometros = '<?php if(isset($_GET['radio'])){echo $_GET['radio'];}else{echo '';} ?>';
console.log(direccion);
if(kilometros == ''){
kilometros = 1000000;
}

var position;

codificador.geocode({ 'address': direccion }, function(results, status){

if (status == 'OK') {

position = {lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()};

map = new google.maps.Map(document.getElementById("map"),mapOptions);

var cantidad = $("#cantidad").val();
var p1, p2;
var ubicacion1, ubicacion2, ubicacion3, ubicacion4;
var distancia;
ubicacion2 = new google.maps.LatLng(position.lat, position.lng);
map.setCenter(position);




for(var i = 0; i < cantidad; i++) {
p1 = $("#p1_"+i).val();
p2 = $("#p2_"+i).val();
nombre = $("#nombre_"+i).val(); 

ubicacion1 = new google.maps.LatLng(p1, p2);
distancia = google.maps.geometry.spherical.computeDistanceBetween(ubicacion1, ubicacion2);

if(distancia/1000 < kilometros){
addMarker(p1, p2, nombre);
console.log(distancia/1000 + " "+ nombre);
}


}

}else{
map = new google.maps.Map(document.getElementById("map"),mapOptions);

var cantidad = $("#cantidad").val();

for(var i = 0; i < cantidad; i++) {
addMarker($("#p1_"+i).val(), $("#p2_"+i).val(), $("#nombre_"+i).val());
}
}
});

googleAutocompleteInstance = new google.maps.places.Autocomplete($('#direccion')[0], {
types: ['geocode'],
componentRestrictions: {
country: 'AR'
}
});
}
var activeInfoWindow; 

function addMarker(c1, c2, nombre){

point = new google.maps.LatLng(c1, c2);

var marker = new google.maps.Marker({map: map, position: point, clickable: true});

var res = nombre.split("|");

marker.info = new google.maps.InfoWindow({    
content: '<div id="content">'+
'<div id="siteNotice">'+
'</div>'+
'<h5 id="firstHeading" class="azul bold firstHeading">'+ res[0] +'</h5>'+
'<div id="bodyContent">'+
'<p><b>Direcci&oacute;n:</b>'+ res[1] +'</p>'+
'<p><b>Tel&eacute;ono:</b> '+ res[2] +' </p>'+
'<small>'+ c1 +', '+ c2 +'</small>'+
'</div>'+
'</div>'
});

google.maps.event.addListener(marker, 'click', function() {

if (activeInfoWindow) { activeInfoWindow.close();}

activeInfoWindow = marker.info;

marker.info.open(map, marker);

});

}

function localizar(lat,long){
var mapOptions = {
center: new google.maps.LatLng(lat, long),
zoom: 12,
width:100,
scrollwheel: true,
disableDefaultUI: false,
mapTypeId: google.maps.MapTypeId.ROADMAP,
mapTypeId: google.maps.MapTypeId.ROADMAP,
};
var codificador = new google.maps.Geocoder();


var direccion = '<?php if(isset($_GET['direccion'])){echo $_GET['direccion'];}else{echo '';} ?>';
var kilometros = '<?php if(isset($_GET['radio'])){echo $_GET['radio'];}else{echo '';} ?>';

if(kilometros == ''){
kilometros = 1000000;
}

var position;

codificador.geocode({ 'address': direccion }, function(results, status){

if (status == 'OK') {

position = {lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()};

map = new google.maps.Map(document.getElementById("map"),mapOptions);

var cantidad = $("#cantidad").val();
var p1, p2;
var ubicacion1, ubicacion2, ubicacion3, ubicacion4;
var distancia;
ubicacion2 = new google.maps.LatLng(position.lat, position.lng);
map.setCenter(position);




for(var i = 0; i < cantidad; i++) {
p1 = $("#p1_"+i).val();
p2 = $("#p2_"+i).val();
nombre = $("#nombre_"+i).val(); 

ubicacion1 = new google.maps.LatLng(p1, p2);
distancia = google.maps.geometry.spherical.computeDistanceBetween(ubicacion1, ubicacion2);

if(distancia/1000 < kilometros){
addMarker(p1, p2, nombre);
console.log(distancia/1000 + " "+ nombre);
}


}

}else{
map = new google.maps.Map(document.getElementById("map"),mapOptions);
position = [lat,long]
map.setCenter(position);

var cantidad = $("#cantidad").val();

for(var i = 0; i < cantidad; i++) {
addMarker($("#p1_"+i).val(), $("#p2_"+i).val(), $("#nombre_"+i).val());
}
}
});

map = new google.maps.Map(document.getElementById("map"),mapOptions);
position = [lat,long]
map.setCenter(position);


}
</script>
@endsection

@endsection