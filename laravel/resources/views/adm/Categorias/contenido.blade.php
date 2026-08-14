@extends('adm.layouts')

@section('content')
@php
  $categoryImageUrl = function ($path) {
      $path = trim((string) $path);
      if ($path === '') {
          return null;
      }
      if (preg_match('/^https?:\/\//', $path)) {
          return $path;
      }
      $path = ltrim($path, '/');
      if (strpos($path, 'storage/') === 0 || strpos($path, 'img/') === 0) {
          return asset($path);
      }
      if (strpos($path, 'public/') === 0) {
          return asset(Storage::url($path));
      }
      return asset('storage/'.$path);
  };
@endphp
<a href="{{route('nuevoCategoria')}}" class="btn btn-success mb-5" >Nueva Categoria</a>
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<div class="alert alert-info" style="background:#f6f8ff;border-left:5px solid #2E3091;color:#303342;">
  Las categorias marcadas como <b>destacadas</b> son las que se muestran en la pagina principal. Desde esta lista pueden cambiar la foto de portada entrando en <b>Cambiar foto</b>.
</div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Orden</th>
      <th scope="col">Imagen home / categoria</th>
      <th scope="col">nombre</th>
      <th scope="col">Home</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
 
  <tbody>
  	@foreach($Categorias as $p)
	    <tr>
	      <th scope="row">{{$p->orden}}</th>
        <td scope="row" style="width:160px;">
          @php $imageUrl = $categoryImageUrl($p->imagen); @endphp
          @if($imageUrl)
            <img src="{{$imageUrl}}" class="img-thumbnail" style="width:130px;height:90px;object-fit:cover;" onerror="this.src='{{asset('img/logo2.jpg')}}';">
          @else
            <div class="d-flex align-items-center justify-content-center" style="width:130px;height:90px;background:#f3f4f6;border:1px solid #ddd;color:#858796;font-size:12px;">Sin imagen</div>
          @endif
        </td>
	      <th scope="row">{{$p->nombre}}</th>
        <td>
          @if($p->destacado == 1)
            <span class="badge badge-success">Destacada</span>
          @else
            <span class="badge badge-secondary">No</span>
          @endif
        </td>
	     {{--  <td scope="row"><img src="{{asset(Storage::url($p->imagen))}}" class="img-thumbnail w-25"></td> --}}
	      {{-- <td>{!!$p->descripcion!!}</td> --}}
	      <td>
	      	<a class="btn btn-warning" href="{{route('editarCategoria',$p->id)}}" role="button">editar</a>
          <a class="btn btn-outline-primary" href="{{route('editarCategoria',$p->id)}}#imagen" role="button">Cambiar foto</a>
	      	<a class="btn btn-danger " href="{{route('eliminarCategoria',$p->id)}}" role="button">borrar</a>

	      </td>
	    </tr>
    
	@endforeach
  </tbody>
</table>
@endsection
