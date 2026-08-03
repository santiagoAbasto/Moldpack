@extends('adm.layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<table class="table">
  <thead>
    <tr>
      <th scope="col">Logo</th>
      <th scope="col">Seccion</th>
      <th scope="col">Accion</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
      @foreach($redes as $r)
    <tr>
      <tr>
          <td scope="row">{{$r->instagram}}</td>
        <td scope="row"> {{$r->facebook}}</td>
        <td>
          {{--  <a class="btn btn-warning" href="{{route('editarlogos', $l->id)}}" role="button">edit</a>
         --}}

        </td>
      </tr>
    
    </tr>
  @endforeach
   
  </tbody>
</table>

@endsection