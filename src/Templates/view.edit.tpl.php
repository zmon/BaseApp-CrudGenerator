@extends('layouts.app')
@php $nav_path = ['[[model_singular]]'] @endphp
@section('page-title')
    Edit {{$[[model_singular]]->name}}
@endsection
@section('page-header-title')
    Edit {{$[[model_singular]]->name}}
@endsection
@section('page-help-link', '/help/[[model_singular]]#help-item-add-edit')
@section('content')
    <[[model_singular]]-form csrf_token="{{ csrf_token() }}" :record='@json($[[model_singular]])'>
        <std-page-header header="Edit {{ $[[model_singular]]->name }}" cancel-url="/[[model_singular]]">
        </std-page-header>
    </[[model_singular]]-form>
@endsection
