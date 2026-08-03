@extends('adm.layouts')
@section('content')  
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Subcriptores</div>
                                        <div class="card-tools">
                                            <a href="{{ route('subcriptores.create')}}" class="btn btn-success btn-border btn-round btn-sm mr-2">
                                                <span class="btn-label">
                                                    <i class="fa fa-plus"></i>
                                                </span>
                                                Enviar Correo Masivo
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

                                    <table class="table mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Email</th>
                                                <th scope="col"> </th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <div class="form-button-action">
    <!---<a href=""  data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Editar">
        <i class="fa fa-edit"></i>
    </a> --->

{{-- @if(Auth::user()->id == $user->id)

@else
<a href="{{ route('subcriptores.destroy', $user->id) }}" data-toggle="tooltip" title="" class="btn btn-link btn-danger botonborrar" data-original-title="Borrar">
<i class="fa fa-times"></i></a>
@endif --}}
                                                   

                                                            
                                                    </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        
                    </div>


@endsection