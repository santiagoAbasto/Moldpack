@extends('layouts.plantilla')

@section('content')
  <div class="d-flex justify-content-center align-items-center" style="height: 45vh;">
    <form class="row col-4" action="{{route('passwordpost')}}" method="post">
      @csrf
      @if(session('error'))
      <div class="alert alert-danger">
      {{session('error')}}
      </div>
      @endif
      @if(session('succes'))
      <div class="alert alert-success">
      {{session('succes')}}
      </div>
      @endif
      <label class="p-0">Ingrese su correo electronico</label>
      <input type="email" name="email" class="form-control mb-5 mt-2">
      
    
      <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
  </div>
@endsection