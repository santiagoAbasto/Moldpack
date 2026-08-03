@extends('adm.layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<form method="post" action="{{route('updateprecio')}}" enctype="multipart/form-data" class="d-flex justify-content-between flex-wrap">
@csrf
<div class="w-100">
  <label>Aumento %</label>
<input type="number" required min="0" name="aumento" class="form-control my-5">
</div>
@foreach($categorias as $p)
<div class="form-check my-2 col-2">
  <input type="checkbox" class="form-check-input" id="{{$p->id}}" name="categorias[]" value="{{$p->id}}">
  <label class="form-check-label" for="{{$p->id}}">Actualizar</label>
</div>
<input type="text" value="{{$p->nombre}}" readonly class="form-control col-10 my-2">

@endforeach

<button class="btn btn-primary my-5" type="submit">Actualizar</button>
</form>

<script>
  document.getElementById("file").onchange = function(e) {

if(this.value != null){
$('#file_submint').removeAttr("disabled")
}      
}
</script>

@endsection