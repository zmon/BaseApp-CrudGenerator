@extends('layouts.app')
@php $nav_path = ['[[model_singular]]'] @endphp
@section('page-title', '[[display_name_plural]]')
@section('page-header-title', '[[display_name_plural]]')
@section('page-help-link', '/help/[[model_singular]]#help-item-list')

@section('content')
    <[[model_singular]]-grid-advanced
        :filters='@json($filters)'
        :permissions='@json($permissions)'
    ></[[model_singular]]-grid-advanced>
@endsection
