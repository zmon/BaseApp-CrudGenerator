@extends('layouts.app')
@php $nav_path = ['[[model_singular]]'] @endphp
@section('page-title')
    View {{$[[model_singular]]->name}}
@endsection
@section('page-header-title')
    View {{$[[model_singular]]->name}}
@endsection
@section('content')

    <[[model_singular]]-show :record='@json($[[model_singular]])' csrf='{{ csrf_token() }}'>
        <std-page-header header="View {{ $[[model_singular]]->name }}" cancel-url="/[[model_singular]]">
        </std-page-header>


        <template v-slot:footer>
            <div class="row mt-1">
                <div class="col-md-4">
                    @if ($can_edit)
                        <a href="/[[model_singular]]/{{  $[[model_singular]]->id }}/edit" class="btn btn-primary">Edit
                            [[model_uc]]</a>
                    @endif
                </div>
                <div class="col-md-4 text-md-center mt-2 mt-md-0">
                    <a href="/[[model_singular]]/{{  $[[model_singular]]->id }}/history" class="btn btn-primary">Show History</a>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    @if ($can_delete)
                        <form class="form" method="POST" action="/[[model_singular]]/{{$[[model_singular]]->id}}"
                              onsubmit="return ConfirmDelete();">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value='{{ csrf_token() }}'/>
                            <input type="submit" class="btn btn-danger"
                                   value="Delete [[model_uc]]">
                        </form>
                    @endif
                </div>
            </div>
        </template>
    </[[model_singular]]-show>

@endsection
@section('scripts')
    <script>
        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete this [[model_uc]]?");
            if (x)
                return true;
            else
                return false;
        }
    </script>
@endsection
