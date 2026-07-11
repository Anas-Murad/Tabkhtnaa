@extends('admin.layouts.app')
@section('content')
<div class="content">
    @include('admin.layouts.alert-area')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">مستويات الولاء</h5></div>
        <div class="table-responsive">{!! $dataTable->table(['class' => 'table']) !!}</div>
    </div>
</div>

@include('admin.loyalty.partials.tier_modals')
@endsection
@section('jscript')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
function createTier(){ new bootstrap.Modal(document.getElementById('createTierModal')).show(); }
function EditTier(tier){
    const form = document.getElementById('editTierForm');
    for (const key in tier) {
        const el = form.querySelector('[name="'+key+'"]');
        if (!el) continue;
        if (el.type === 'checkbox') el.checked = !!tier[key]; else el.value = tier[key] ?? '';
    }
    new bootstrap.Modal(document.getElementById('editTierModal')).show();
}
$('#createTierForm,#editTierForm').on('submit', function(e){
    e.preventDefault();
    $.post($(this).attr('action'), $(this).serialize()).done(()=>location.reload());
});
</script>
@endsection
