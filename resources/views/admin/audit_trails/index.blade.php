@extends('admin.layouts.app')
@section('content')
    <div class="content">
        @include('admin.layouts.alert-area')
        <div class="card card-collapsed">
            <div class="card-header d-flex align-items-center">
                <h6 class="mb-0">فلتره</h6>
                <div class="d-inline-flex ms-auto">
                    <a class="text-body" data-card-action="collapse">
                        فلتره
                        <i class="ph-caret-down"></i>
                    </a>
                </div>
            </div>
            <div class="collapse">
                <div class="card-body">
                    <form action="#" id="filter_form">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">بحث (اسم، IP، رابط)</label>
                                                <input type="text" class="form-control" name="search_key" placeholder="كلمة البحث...">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">المستخدم</label>
                                                <select name="user_id" class="select2 form-control form-control-select2" data-fouc>
                                                    <option value="">الكل</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">المدير</label>
                                                <select name="admin_id" class="select2 form-control form-control-select2" data-fouc>
                                                    <option value="">الكل</option>
                                                    @foreach($admins as $admin)
                                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">نوع الحدث</label>
                                                <select name="event" class="select2 form-control form-control-select2" data-fouc>
                                                    <option value="">الكل</option>
                                                    @foreach($events as $event)
                                                        <option value="{{ $event }}">{{ $event }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">نوع الكيان</label>
                                                <select name="auditable_type" class="select2 form-control form-control-select2" data-fouc>
                                                    <option value="">الكل</option>
                                                    @foreach($auditableTypes as $type)
                                                        <option value="{{ $type }}">{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">من تاريخ</label>
                                                <input type="date" class="form-control" name="from_date">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">إلى تاريخ</label>
                                                <input type="date" class="form-control" name="to_date">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label class="form-label" style="visibility: hidden">بحث</label>
                                            <div class="text-left">
                                                <button type="button"
                                                        onclick="window.LaravelDataTables['data-table'].ajax.reload()"
                                                        class="btn btn-secondary">
                                                    ابحث <i class="ph-file-search ms-2"></i>
                                                </button>
                                                <button type="button" onclick="location.reload()" class="btn btn-warning">
                                                    إعادة <i class="ph-key-return ms-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">سجل التدقيق / Audit Trail</h5>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table']) !!}
            </div>
        </div>
    </div>

    <div class="modal fade" id="auditDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تفاصيل سجل التدقيق</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>المستخدم:</strong> <span id="audit-actor"></span></div>
                        <div class="col-md-6"><strong>الحدث:</strong> <span id="audit-event"></span></div>
                        <div class="col-md-6 mt-2"><strong>الكيان:</strong> <span id="audit-entity"></span></div>
                        <div class="col-md-6 mt-2"><strong>IP:</strong> <span id="audit-ip"></span></div>
                        <div class="col-md-12 mt-2"><strong>الرابط:</strong> <span id="audit-url" class="text-break"></span></div>
                        <div class="col-md-12 mt-2"><strong>User Agent:</strong> <span id="audit-agent" class="text-break small"></span></div>
                        <div class="col-md-6 mt-2"><strong>التاريخ:</strong> <span id="audit-date"></span></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>القيم القديمة</h6>
                            <pre id="audit-old" class="bg-light p-2 rounded small" style="max-height:300px;overflow:auto;"></pre>
                        </div>
                        <div class="col-md-6">
                            <h6>القيم الجديدة</h6>
                            <pre id="audit-new" class="bg-light p-2 rounded small" style="max-height:300px;overflow:auto;"></pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jscript')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        function showAuditDetails(audit) {
            const actor = audit.admin
                ? audit.admin.name + ' (مدير)'
                : (audit.user ? audit.user.name : 'النظام');
            const entity = audit.auditable_type
                ? (audit.auditable_type.split('\\').pop() + (audit.auditable_id ? ' #' + audit.auditable_id : ''))
                : '-';

            document.getElementById('audit-actor').textContent = actor;
            document.getElementById('audit-event').textContent = audit.event;
            document.getElementById('audit-entity').textContent = entity;
            document.getElementById('audit-ip').textContent = audit.ip_address || '-';
            document.getElementById('audit-url').textContent = audit.url || '-';
            document.getElementById('audit-agent').textContent = audit.user_agent || '-';
            document.getElementById('audit-date').textContent = audit.created_at || '-';
            document.getElementById('audit-old').textContent = JSON.stringify(audit.old_values || {}, null, 2);
            document.getElementById('audit-new').textContent = JSON.stringify(audit.new_values || {}, null, 2);

            new bootstrap.Modal(document.getElementById('auditDetailsModal')).show();
        }
    </script>
@endsection
