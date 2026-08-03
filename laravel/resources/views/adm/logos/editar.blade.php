@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('updatelogos', $logos->id)}}" enctype="multipart/form-data">
  @csrf
  @method('put')

  <div class="form-group">
    <label for="imagen">Logo</label>
    <input type="file" class="form-control-file" id="imagen" name="imagen" value="">
    <img src="{{asset(Storage::url($logos->imagen))}}" class="img-thumbnail mt-4">
  </div>
  
 

 <button type="submit" class="btn btn-success">Editar</button>
  </form>


@endsection