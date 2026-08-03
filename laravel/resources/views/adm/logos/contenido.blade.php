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
      @foreach($logos as $l)
    <tr>
      <tr>
        <td scope="row" class="w-50"><img src="{{asset(Storage::url($l->imagen))}}" class="img-thumbnail w-25"></td>
          <td scope="row">{{$l->seccion}}</td>
        <td>
           <a class="btn btn-warning" href="{{route('editarlogos', $l->id)}}" role="button">edit</a>
        </td>
      </tr>
    
    </tr>
  @endforeach
   
  </tbody>
</table>

@endsection