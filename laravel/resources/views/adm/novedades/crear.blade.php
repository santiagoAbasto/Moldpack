@extends('adm.layouts')

@section('content')
<form method="post" action="{{route('crearnovedad')}}" enctype="multipart/form-data">
	@csrf
  <div class="row">

    <div class="form-group col-12">
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >
      
    </div>

    <div class="form-group col-12">
      <label for="nombre">nombre</label>
      <input required type="text" class="form-control" id="nombre" name="nombre" >      
    </div>
    <div class="form-group col-12">
      <label for="descripcion2">Breve descripcion</label>
      <input type="text" class="form-control" id="descripcion2" name="descripcion2" >      
    </div>
  </div>

  
  
  <div class="row">
    <div class="form-group col-md-12">
      <label for="descripcion">Descripcion</label>
      <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="10" value="" ></textarea>
    </div>
  </div>  
  
  
  <div class="row">
    <div class="form-group">
      <label for="imagen">Imagen</label>
      <input required type="file" class="form-control-file" required id="imagen" name="imagen" >
    </div>        
  </div>  
    
  <label>Categoria</label>
  <div class="row">
    <select class="form-group w-100" name="categoria" required>
      @forelse ($cat as $item)
        <option value="{{$item->id}}">{{$item->nombre}}</option>
      @empty
        
      @endforelse
    </select>
  </div>

  <div class="form-check">
    <input class="form-check-input" name="destacar" type="checkbox" id="destacar" value="1">
    <label class="form-check-label" for="destacar">
      Destacar en la home
    </label>
  </div>



 <button type="submit" class="btn btn-success my-3">Agregar</button>
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