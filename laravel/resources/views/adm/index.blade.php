@extends('adm.layouts')

@section('content')
<div id="admin-dashboard-root" data-endpoint="{{route('adm.dashboard.data')}}"></div>
<script type="application/json" id="admin-dashboard-data">@json($dashboard)</script>
@endsection

@section('js')
@php
    $adminDashboardJsPath = public_path('js/admin-dashboard.js');
    $adminDashboardHttpdocsJsPath = base_path('../httpdocs/js/admin-dashboard.js');
    $adminDashboardJsVersion = file_exists($adminDashboardJsPath)
        ? filemtime($adminDashboardJsPath)
        : (file_exists($adminDashboardHttpdocsJsPath) ? filemtime($adminDashboardHttpdocsJsPath) : '20260724');
@endphp
<script src="{{asset('js/admin-dashboard.js')}}?v={{$adminDashboardJsVersion}}"></script>
@endsection
