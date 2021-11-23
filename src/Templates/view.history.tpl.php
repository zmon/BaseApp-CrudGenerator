@extends('layouts.app')
@php $nav_path = ['[[route_path]]'] @endphp
@section('page-title')
    History for {{$[[model_singular]]->[[name_field]]}}
@endsection
@section('page-header-title')
    History for {{$[[model_singular]]->[[name_field]]}}
@endsection

@section('content')
    <histories-grid
        :histories='@json($histories)'
    >
        <std-page-header header="History for {{$[[model_singular]]->[[name_field]]}}" cancel-url="/[[route_path]]/{{$[[model_singular]]->id}}">
        </std-page-header>
    </histories-grid>
@endsection
