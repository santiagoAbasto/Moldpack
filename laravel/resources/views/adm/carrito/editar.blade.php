@extends('adm.layouts')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<form method="post" action="{{route('carrito.editar',$carrito->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')    
  <div class="form-group">
    <label for="titulo">titulo</label>
    <input type="text" class="form-control" id="titulo" name="titulo" value="{{$carrito->titulo}}">   
  </div>
  <div class="form-group">
    <label for="texto">texto</label>
    <textarea class="form-control" name="texto" id="texto" cols="30" rows="10" value="" >{!!$carrito->texto!!}</textarea>    
  </div>

  <div class="form-group d-none">
    <label for="iva">Descuento global</label>
    <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="{{$carrito->descuento}}">
  </div>  
  
  <div class="form-group">
    <label for="iva">iva</label>
    <input type="number" step="0.01" class="form-control" id="iva" name="iva" value="{{$carrito->iva}}">
  </div>

  <div class="form-group d-none">
    <label for="limite">Limite del carrito</label>
    <input type="number" step="0.01" class="form-control" id="limite" name="limite" value="{{$carrito->limite}}">
  </div>

 <button type="submit" class="btn btn-success">Editar</button>
</form>


@endsection
@section('js')
 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
        $(document).ready(function() {
             $('textarea').summernote({
                
              height: 250,
                     fontNames: ['Montserrat-Bold', 'Montserrat-Light', 'Montserrat-Medium', 'Montserrat-Regular', 'Montserrat-SemiBold', 'Roboto-Regular'],
             });
         });
    
</script>


  

@endsection