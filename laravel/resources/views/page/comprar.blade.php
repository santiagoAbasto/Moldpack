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
 .form-group label{  
  font-size: 15px; 
 }
</style>

<div class="col-12 d-flex justify-content-center flex-wrap align-items-center">
  <div class="w-100 d-flex flex-row flex-wrap justify-content-start align-items-center">
      <div class="col-12">
          <?php $cont_distribuidor = 0; ?>
          @foreach($distribuidores as $distribuidor)
              <input id="p1_{{ $cont_distribuidor }}" name="" type="hidden" value="{{ $distribuidor->latitud }}">
                  <input id="p2_{{ $cont_distribuidor }}" name="" type="hidden" value="{{ $distribuidor->longitud }}">
                  <input id="nombre_{{ $cont_distribuidor }}" name="" type="hidden" value="{{ $distribuidor->nombre }} | {{ $distribuidor->direccion }} | {{ $distribuidor->telefono }} | {{ $distribuidor->correo }} | {{ $distribuidor->web }}">
                      <?php $cont_distribuidor++; ?>
           @endforeach
           <input type="hidden" id="cantidad" name="" value="{{ $cont_distribuidor }}">
           <div class="col-12 d-flex flex-row flex-wrap">
               <div class="col-12 d-flex flex-row flex-wrap" style="max-height: 696px;overflow: overlay;background:#EC458B;color:#fff;">
                <div class="col-12 py-2 d-flex justify-content-center" style="font-size:14px;color:#fff;">
                  <div class="box_container">                      
                  <a style="text-decoration: none;color:#fff;" href="{{route('page.inicio')}}">Inicio</a>
                  /
                  <a style="text-decoration: none;color:#fff;" href="#">Donde comprar</a>  
                  </div>
                </div>
                <div class="col-12 py-2 d-flex justify-content-center">
                  <div class="box_container">
                    <form method="post" action="{{route('page.search.mapa')}}">
                      @csrf
                      <div class="d-flex flex-row justify-content-between align-items-center pb-4" style="border-bottom:1px solid #ddd;">                  
                        <div class="m-2" style="width: 288px;">
                          <select class="form-select pe-2" name="provincia" id="selec_provincia" aria-label="Default select example" style="width: 288px;">
                            <option value="0" selected>Provincia</option>
                            @forelse ($provincias as $item )
                              <option value="{{$item->provincia}}" id="{{str_replace(' ','',$item->provincia)}}">{{$item->provincia}}</option>
                            @empty
                            @endforelse
                            </select>
                        </div>
                        <div class="m-2" style="width: 288px;">
                          <select class="form-select pe-2" name="localidad" id="selec_localidad" aria-label="Default select example" style="width: 288px;">
                            <option value="0" selected>Localidad</option>
                            @forelse ($localidades as $item )
                              <option value="{{$item->localidad}}" id="{{str_replace(' ','',$item->localidad)}}">{{$item->localidad}}</option>
                            @empty
                            @endforelse
                            </select>
                        </div>
                        <div>
                          <button style="color:#EC458B;background:#F5F3EF;width:184px;" class="btn" onclick="search_distribuidor()">BUSCAR</button>
                        </div>
                        <div class="w-100 mx-2">
                          <button class="btn btnOcultar" type="button" style="border:1px solid #F5F3EF;color:#fff;"><img class="me-2" src="{{asset('img/lista.svg')}}" width="28px" height="22px">Ver listado</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div id="boxOcultar" class="ocultar_ w-100" style="background: #fff;">
                  <div class="d-flex justify-content-center align-items-center flex-wrap">
                  @forelse ($distribuidores as $item)
                  <div style="display:flex;cursor: pointer;" class="box_container flex-column box_distribuidor {{str_replace(' ','',$item->localidad)}}" id="{{str_replace(' ','',$item->provincia)}}" onclick="localizar({{$item->latitud}},{{$item->longitud}})">
                      <div class="pt-2" style="font-size:18px;color:#EC458B;"><b>{{$item->nombre}}</b></div>
                      <div style="font-size:14px;color:#000;">{!!$item->direccion!!}</div>
                      <div style="font-size:14px;color:#000;">{!!$item->telefono!!}</div>
                      <div style="font-size:14px;color:#000;">{!!$item->correo!!}</div>                      
                      <div style="font-size:14px;color:#000;border-bottom:1px solid #ccc;">{!!$item->horario!!}</div>
                  </div>
                  @empty                      
                  @endforelse
                  </div>
                </div>
               </div>
              <div class="m-3 w-100" id="map" style="height: 696px;border-left:15px solid #fff;"></div>
           </div>        
      </div>      
  </div>
</div>
@section('js')
<script>
function search_distribuidor(){
	var prov = $('#selec_provincia option:selected').val();

	var localidad = $('#selec_localidad option:selected').val();
    if(prov != 0)   {
        $(".box_distribuidor").css('display','none')
        $(".box_distribuidor#"+prov).css('display','flex')
    }else{
        $(".box_distribuidor").css('display','flex')      
    }
    
    if(localidad != 0){
        $(".box_distribuidor").css('display','none')
        $(".box_distribuidor."+localidad).css('display','flex')
    }

    if(localidad == 0 && proc == 0){
      $(".box_distribuidor").css('display','flex')
    }
	
}
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