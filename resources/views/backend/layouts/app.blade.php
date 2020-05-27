@extends('adminlte::page')
<link href="{{ asset('vendor/glorifiedking/css/backend_css.css') }}" rel="stylesheet">

@section('title','Bus Travel')


@section('content')


@stop


@section('css')



@stop


@section('js')
@include('bustravel::backend.partials.toast')
<script src="{{ asset('vendor/glorifiedking/charts/echarts.min.js') }}"></script>
@stop
