@extends('layouts.app')
@php $nav_path = ['[[model_singular]]'] @endphp
@section('page-title')
    Differences for {{$[[model_singular]]->name}} from {{ $[[model_singular]]->created_at->format('n/j/Y g:i a') }}
@endsection
@section('page-header-title')
    Differences for {{$[[model_singular]]->name}} from {{ $[[model_singular]]->created_at->format('n/j/Y g:i a') }}
@endsection
@section('content')
    <history-difference
        :history='@json($history)'
        :next='@json($next)'
        :previous='@json($previous)'
    >
        <std-page-header header="Differences for {{$[[model_singular]]->name}} from {{ $[[model_singular]]->created_at->format('n/j/Y g:i a') }}" cancel-url="/[[model_singular]]/{{$[[model_singular]]->id}}/history">
        </std-page-header>
    </history-difference>
@endsection
