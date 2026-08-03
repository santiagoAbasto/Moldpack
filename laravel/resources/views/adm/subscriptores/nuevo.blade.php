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


                <form method="POST" enctype="multipart/form-data" action="{{ route('subcriptores.store') }}">

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
<textarea class="form-control summernote" name="texto" id="descrip" rows="5">{{ old('texto') }}</textarea>

         @if($errors->has('texto'))
                            <div class="error-div">{{ $errors->first('texto') }}</div>
                        @endif
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

    <script>
        $(document).ready(function(){
            $('.summernote').summernote({
                lang: 'es-ES',
                height:200,
                toolbar:[
                    ['cleaner',['cleaner']], // The Button
                    ['style',['style']],
                    ['font',['bold','italic','underline','clear']],
                    ['fontname',['fontname']],
                    ['color',['color']],
                    ['para',['ul','ol','paragraph']],
                    ['height',['height']],
                    ['table',['table']],
                    ['insert',['media','link','hr']],
                    ['view',['fullscreen','codeview']],
                    ['help',['help']]
                ],
                cleaner:{
                    action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                    newline: '<br>', // Summernote's default is to use '<p><br></p>'
                    notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                    icon: '<i class="note-icon">[Your Button]</i>',
                    keepHtml: false, // Remove all Html formats
                    keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                    keepClasses: false, // Remove Classes
                    badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                    badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                    limitChars: false, // 0/false|# 0/false disables option
                    limitDisplay: 'both', // text|html|both
                    limitStop: false // true/false
                }
            });
        });

    </script>
@endsection