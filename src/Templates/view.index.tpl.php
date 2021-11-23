@extends('layouts.app')
@php $nav_path = ['[[route_path]]'] @endphp
@section('page-title', '[[display_name_plural]]')
@section('page-header-title', '[[display_name_plural]]')
@section('page-help-link', '/help/[[route_path]]#help-item-list')

@section('content')
    <[[view_folder]]-grid-advanced
        :filters='@json($filters)'
        :permissions='@json($permissions)'
    ></[[view_folder]]-grid-advanced>
@endsection
