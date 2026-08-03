@extends('adm.layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<form method="post" action="{{route('updateredes',$redes->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')
  <div class="form-group">
    <label for="instagram">instagram</label>
    <input type="text" class="form-control" id="instagram" name="instagram" value="{{$redes->instagram}}">   
  </div>
  <div class="form-group">
    <label for="facebook">facebook</label>
    <input type="text" class="form-control" id="facebook" name="facebook" value="{{$redes->facebook}}">
  </div>
  <div class="form-group">
    <label for="youtube">youtube</label>
    <input type="text" class="form-control" id="youtube" name="youtube" value="{{$redes->youtube}}">
  </div>
   <div class="form-group">
    <label for="linkedin">linkedin</label>
    <input type="text" class="form-control" id="linkedin" name="linkedin" value="{{$redes->linkedin}}">
  </div> 
 <button type="submit" class="btn btn-success">Editar</button>
</form>


@endsection