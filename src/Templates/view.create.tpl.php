@extends('layouts.app')
@php $nav_path = ['[[model_singular]]'] @endphp
@section('page-title')
    Add New [[model_uc]]
@endsection
@section('page-header-title')
    Add New [[model_uc]]
@endsection
@section('page-help-link', '/help/[[model_singular]]#help-item-add-edit')
@section('content')
    <[[model_singular]]-form csrf_token="{{ csrf_token() }}">
        <std-page-header header="Add [[model_uc]]" cancel-url="/[[model_singular]]">
        </std-page-header>
    </[[model_singular]]-form>
@endsection
