@extends('layouts.app')
@php $nav_path = ['[[route_path]]'] @endphp
@section('page-title')
    Add New [[model_uc]]

@endsection
@section('page-header-title')
    Add New [[model_uc]]

@endsection
@section('page-help-link', '/help/[[route_path]]#help-item-add-edit')
@section('content')
    <[[view_folder]]-form csrf_token="{{ csrf_token() }}">
        <std-page-header header="Add [[model_uc]]" cancel-url="/[[route_path]]">
        </std-page-header>
    </[[view_folder]]-form>
@endsection
