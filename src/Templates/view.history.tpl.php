@extends('layouts.app')
@php $nav_path = ['[[model_singular]]'] @endphp
@section('page-title')
    History for {{$[[model_singular]]->name}}
@endsection
@section('page-header-title')
    History for {{$[[model_singular]]->name}}
@endsection

@section('content')
    <histories-grid
        :histories='@json($histories)'
    >
        <std-page-header header="History for {{$[[model_singular]]->name}}" cancel-url="/[[model_singular]]/{{$[[model_singular]]->id}}">
        </std-page-header>
    </histories-grid>
@endsection
