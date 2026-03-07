@extends('admin.layouts.app')
@section('content')
    <div class="content">
        <div class="card">
            @include('admin.layouts.alert-area')
            <div class="card-header">
                <h5 class="mb-0">
                    قائمه مدن
                   {{$country->name}}
                </h5>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table'] ) !!}
            </div>
        </div>
    </div>
@endsection
@section('jscript')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endsection
