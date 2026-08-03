@extends('adm.layouts')
@section('content')
<div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="card-head-row">
                <div class="card-title">Enviar e-mails</div>
                <div class="card-tools">
                    <a href="{{ route('Subcriptores.view')}}" class="btn btn-info btn-border btn-round btn-sm mr-2">
                        <span class="btn-label">
                            <i class="fa fa-back"></i>
                        </span>
                        Volver
                    </a>
                    
                </div>
            </div>
        </div>
        <div class="card-body">

        @if(session()->has('success'))
        <div class="alert alert-success">
        {{ session()->get('success') }}
        </div>
        @endif


        <form method="POST" enctype="multipart/form-data" action="{{ route('clientes.email') }}">

            @csrf

        <div class="form-group form-inline">
            <label for="inlineinput" class="col-md-1 col-form-label">Asunto </label>
            <div class="col-md-12 p-0">
                <input name="asunto" type="text" class="form-control input-full" id="inlineinput" placeholder=" ">
            </div>
        </div>


        <div class="form-group  @if($errors->has('texto')) has-error @endif">
        <label for="inlineinput" class="col-md-3 col-form-label">Cuerpo del mensaje</label>
            <div class="col-md-12 p-0">                
                <textarea class="form-control" name="texto" id="descripcion" cols="30" rows="10" value="" ></textarea>
            </div>
        </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Enviar</button>                
                </div>

        </form>


        </div>

    </div>
    </div>
</div>
@section('js')
 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
             $('textarea').summernote({
                
              height: 250,
                     fontNames: ['Montserrat-Bold', 'Montserrat-Light', 'Montserrat-Medium', 'Montserrat-Regular', 'Montserrat-SemiBold', 'Roboto-Regular'],
                     toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['link']],
                    ['table', ['table']],
                ]
             });
         });
    </script>
    
@endsection
@endsection