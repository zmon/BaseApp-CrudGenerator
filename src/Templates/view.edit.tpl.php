@extends('layouts.app')
@php $nav_path = ['[[route_path]]'] @endphp
@section('page-title')
    Edit {{$[[model_singular]]->[[name_field]]}}
@endsection
@section('page-header-title')
    Edit {{$[[model_singular]]->[[name_field]]}}
@endsection
@section('page-help-link', '/help/[[route_path]]#help-item-add-edit')
@section('content')
    <[[view_folder]]-form csrf_token="{{ csrf_token() }}" :record='@json($[[model_singular]])'>
        <std-page-header header="Edit {{ $[[model_singular]]->[[name_field]] }}" cancel-url="/[[route_path]]">
        </std-page-header>
    </[[view_folder]]-form>
@endsection
