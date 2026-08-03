@extends('adm.layouts')
@section('content')
<div class="contenido">
    <div class="col-12">
       
        @if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
        <h5>Meta-datos</h5>
        <table class="table datatable">
            <thead>
                <tr>
                <th scope="col">Keywords</th>
                <th scope="col">Descripcion</th>
                <th scope="col">Secci&oacute;n</th>
                <th scope="col">Editar</th>
                </tr>
            </thead>
            <tbody>
            @forelse($metadata as $md)
                <tr>
                    <td>{!! $md->keyword !!}</td>
                    <td>{!! $md->descripcion !!}</td>
                    <td>{!! $md->seccion !!}</td>
                    <td>
                        <a class="btn btn-warning" href="{{route('editarmetadatos', $md->id)}}" role="button">editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td >No existen registros</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
