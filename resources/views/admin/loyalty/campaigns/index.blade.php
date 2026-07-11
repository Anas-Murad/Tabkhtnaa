@extends('admin.layouts.app')
@section('content')
<div class="content">
    @include('admin.layouts.alert-area')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">حملات مضاعفة النقاط</h5></div>
        <div class="table-responsive">{!! $dataTable->table(['class' => 'table']) !!}</div>
    </div>
</div>

<div class="modal fade" id="createCampaignModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">حملة جديدة</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" action="{{ route('admin.loyalty.campaigns.store') }}">@csrf
<div class="modal-body row g-2">
    <div class="col-md-6"><label>الاسم</label><input class="form-control" name="name" required></div>
    <div class="col-md-6"><label>المضاعف</label><input class="form-control" type="number" step="0.1" name="multiplier" value="2" required></div>
    <div class="col-md-6"><label>من تاريخ</label><input class="form-control" type="date" name="start_date" required></div>
    <div class="col-md-6"><label>إلى تاريخ</label><input class="form-control" type="date" name="end_date" required></div>
    <div class="col-md-6"><label>ينطبق على</label>
        <select class="form-select" name="applies_to"><option value="all">الكل</option><option value="delivery">توصيل</option><option value="pick_up">استلام</option></select>
    </div>
    <div class="col-md-6 form-check mt-4"><input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="camp_active"><label for="camp_active">نشطة</label></div>
</div>
<div class="modal-footer"><button type="submit" class="btn btn-primary">إنشاء</button></div>
</form></div></div></div>

<div class="modal fade" id="editCampaignModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">تعديل الحملة</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<form method="POST" id="editCampaignForm">@csrf @method('PUT')
<div class="modal-body row g-2">
    <div class="col-md-6"><label>الاسم</label><input class="form-control" name="name" id="edit_camp_name" required></div>
    <div class="col-md-6"><label>المضاعف</label><input class="form-control" type="number" step="0.1" name="multiplier" id="edit_camp_multiplier" required></div>
    <div class="col-md-6"><label>من تاريخ</label><input class="form-control" type="date" name="start_date" id="edit_camp_start" required></div>
    <div class="col-md-6"><label>إلى تاريخ</label><input class="form-control" type="date" name="end_date" id="edit_camp_end" required></div>
    <div class="col-md-6"><label>ينطبق على</label>
        <select class="form-select" name="applies_to" id="edit_camp_applies">
            <option value="all">الكل</option><option value="delivery">توصيل</option><option value="pick_up">استلام</option>
        </select>
    </div>
    <div class="col-md-6 form-check mt-4"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_camp_active"><label for="edit_camp_active">نشطة</label></div>
</div>
<div class="modal-footer"><button type="submit" class="btn btn-primary">حفظ</button></div>
</form></div></div></div>
@endsection
@section('jscript')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
function createCampaign(){ new bootstrap.Modal(document.getElementById('createCampaignModal')).show(); }
function editCampaign(id, name, start, end, multiplier, appliesTo, isActive) {
    document.getElementById('editCampaignForm').action = '{{ url('admin/loyalty/campaigns') }}/' + id;
    document.getElementById('edit_camp_name').value = name;
    document.getElementById('edit_camp_start').value = start;
    document.getElementById('edit_camp_end').value = end;
    document.getElementById('edit_camp_multiplier').value = multiplier;
    document.getElementById('edit_camp_applies').value = appliesTo;
    document.getElementById('edit_camp_active').checked = !!isActive;
    new bootstrap.Modal(document.getElementById('editCampaignModal')).show();
}
</script>
@endsection
