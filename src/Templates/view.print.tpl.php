@extends('layouts.print')
@section('page-title', '[[display_name_plural]]')
@section('table-headings-row')
    <tr>
        <th>Name</th>
        <th>Contact Name</th>
        <th>Email</th>
        <th>File Name Alias</th>
        <th>Active</th>
    </tr>
@endsection
@section('table-data-rows')
    @foreach($data as $obj)
        <tr>
            [[foreach:grid_columns]]
            <td>{{ $obj->[[i.name]] }}</td>
            [[endforeach]]
        </tr>
    @endforeach
@endsection
