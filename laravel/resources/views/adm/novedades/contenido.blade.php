@extends('adm.layouts')

@section('content')



<a href="{{route('nuevonovedad')}}" class="btn btn-success mb-5" >Nueva Novedad</a>
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

<table class="table">
  <thead>
    <tr>
      <th scope="col">Orden</th>
      <th scope="col-md-4">Marcas</th>
      {{-- <th scope="col">Imagen</th> --}}
      <th scope="col">Accion</th>
    </tr>
  </thead>
  <tbody>
    <tr>
       @foreach($novedades as $a)
      <tr>
        <th scope="row" class="text-uppercase">{{$a->orden}}</th>
          <td scope="row"><img src="{{asset(Storage::url($a->imagen))}}" class="img-thumbnail w-25" ></td>
        <td>
           <a class="btn btn-warning" href="{{route('editarnovedad', $a->id)}}" role="button">edit</a>
           <a class="btn btn-danger" href="{{route('eliminarnovedad', $a->id)}}" role="button">borrar</a>  

        </td>
      </tr>
    
  @endforeach 
    </tr>
   
  </tbody>
</table>











@endsection