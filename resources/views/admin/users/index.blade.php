@extends('admin.layouts.app')
@section('cssStyle')

@endsection

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Default style</h5>
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
