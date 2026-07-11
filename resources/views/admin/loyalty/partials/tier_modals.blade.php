<div class="modal fade" id="createTierModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">إضافة مستوى</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<form id="createTierForm" method="POST" action="{{ route('admin.loyalty.tiers.store') }}">@csrf
<div class="modal-body">
    <div class="mb-2"><label>الاسم</label><input class="form-control" name="name" required placeholder="ذهبي"></div>
    <div class="mb-2"><label>المستوى (رقم)</label><input class="form-control" type="number" name="level" required></div>
    <div class="mb-2"><label>الإنفاق التراكمي المطلوب</label><input class="form-control" type="number" step="0.01" name="min_lifetime_spending" required></div>
    <div class="mb-2"><label>مضاعف النقاط</label><input class="form-control" type="number" step="0.01" name="points_multiplier" value="1" required></div>
    <div class="mb-2"><label>حد الاستبدال</label><input class="form-control" type="number" name="min_redemption_points"></div>
    <div class="mb-2"><label>الوصف</label><textarea class="form-control" name="description"></textarea></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="ct_active"><label class="form-check-label" for="ct_active">نشط</label></div>
</div>
<div class="modal-footer"><button type="submit" class="btn btn-primary">حفظ</button></div>
</form></div></div></div>

<div class="modal fade" id="editTierModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">تعديل مستوى</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<form id="editTierForm" method="POST" action="{{ route('admin.loyalty.tiers.update') }}">@csrf
<div class="modal-body">
    <input type="hidden" name="id">
    <div class="mb-2"><label>الاسم</label><input class="form-control" name="name" required></div>
    <div class="mb-2"><label>المستوى</label><input class="form-control" type="number" name="level" required></div>
    <div class="mb-2"><label>الإنفاق المطلوب</label><input class="form-control" type="number" step="0.01" name="min_lifetime_spending" required></div>
    <div class="mb-2"><label>مضاعف النقاط</label><input class="form-control" type="number" step="0.01" name="points_multiplier" required></div>
    <div class="mb-2"><label>حد الاستبدال</label><input class="form-control" type="number" name="min_redemption_points"></div>
    <div class="mb-2"><label>الوصف</label><textarea class="form-control" name="description"></textarea></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="et_active"><label class="form-check-label" for="et_active">نشط</label></div>
</div>
<div class="modal-footer"><button type="submit" class="btn btn-primary">تحديث</button></div>
</form></div></div></div>
