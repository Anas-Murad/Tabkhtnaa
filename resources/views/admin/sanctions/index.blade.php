@extends('admin.layouts.app')
@section('content')
    <div class="content">
        @include('admin.layouts.alert-area')
        <!-- Collapse/expand card -->
        <div class="card  card-collapsed">
            <div class="card-header d-flex align-items-center">
                <h6 class="mb-0">فلتره</h6>
                <div class="d-inline-flex ms-auto">
                    <a class="text-body" data-card-action="collapse">
                        فلتره
                        <i class="ph-caret-down"></i>
                    </a>
                </div>
            </div>
            <div class="collapse ">
                <div class="card-body">
                    <form action="#" id="filter_form">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    ابحث عن اسم , ايميل , رقم هاتف , اسم مستخدم ,
                                                    ..الخ:</label>
                                                <input type="text" class="form-control" name="search_key"
                                                       id="search_key" placeholder="كلمة البحث ... ">
                                            </div>
                                        </div>
                                        @includeIf('admin.components.countries' , [
                                             'col_size'=>'col-md-2',
                                             'country_name'=>'country_id',
                                             'city_name'=>'city_id',
                                             'required'=>false,
                                            'with_cities'=>true,
                                        ])
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Admin</label>
                                                <select name="admin_id" id="admin_id"
                                                        class="select2 form-control form-control-select2" data-fouc>
                                                    <option value="">الكل</option>
                                                    @foreach($admins as $admin )
                                                        <option value="{{$admin->id}}">{{$admin->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input name="user_id" type="hidden" value="{{$user_id}}">
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">الجنس</label>
                                                <select name="gender" id="gender"
                                                        class="select2 form-control form-control-select2" data-fouc>
                                                    <option value="">الكل</option>
                                                    <option value="male">ذكر</option>
                                                    <option value="female">أنثى</option>
                                                </select>
                                            </div>
                                        </div>
                                        @if(!isset($type) || !$type)
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">type</label>
                                                    <select name="type" id="type"
                                                            class="select2 form-control form-control-select2"
                                                            data-fouc>
                                                        <option value="">الكل</option>
                                                        <option value="financial_violation">financial violation</option>
                                                        <option value="make_block">make block</option>
                                                        <option value="no_order_request">no order request</option>
                                                        <option value="no_chat">no chat</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">Seen</label>
                                                <select name="seen" id="seen"
                                                        class="select2 form-control form-control-select2"
                                                        data-fouc>
                                                    <option value="">الكل</option>
                                                    <option value="seen">seen</option>
                                                    <option value="not_seen">not seen</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">من تاريخ:</label>
                                                <input type="date" placeholder="From Date" class="form-control"
                                                       name="from_date"
                                                       value=""
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">الى تاريخ:</label>
                                                <input type="date" placeholder="From Date" class="form-control"
                                                       name="to_date"
                                                       value=""
                                                >
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label class="form-label" style="visibility: hidden">From Date:</label>
                                            <div class="text-left">

                                                <button type="button"
                                                        onclick="window.LaravelDataTables['data-table'].ajax.reload() "
                                                        class="btn btn-secondary">ابحث <i
                                                        class="ph-file-search ms-2"></i></button>


                                                <button type="button"
                                                        onclick="location.reload() "
                                                        class="btn btn-warning">اعادة <i
                                                        class="ph-key-return ms-2"></i></button>
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
        <!-- /collapse/expand card -->

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Complaints</h5>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table'] ) !!}
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editComplaintModal" tabindex="-1" aria-labelledby="ComplaintModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ComplaintModalLabel">Edit Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit" method="POST" action="{{ route('complaints.update', ['complaint' => ':id']) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        @method('PUT')
                        <div class="p-1">
                            <input class="form-control" type="hidden" name="id">
                        </div>
                        <div class="p-1">
                            <label>Type :</label>
                            <input class="form-control" type="text" name="type" readonly>
                        </div>
                        <div class="p-1">
                            <label>Photo :</label>
                            <img src="{{asset('photo')}}">
                        </div>
                        <div class="p-1">
                            <label>Description :</label>
                            <input class="form-control" type="text" name="description" readonly>
                        </div>
                        <div class="p-1">
                            <label>Status :</label>
                            <select class="form-control type" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="solved">Solved</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="p-1">
                            <label>Note with Admin :</label>
                            <textarea class="form-control" type="text" name="note" required></textarea>
                        </div>
                        <div class="p-1">
                            <label>Created At :</label>
                            <input class="form-control" type="text" name="created_at" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('jscript')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        function EditComplaintFunction(complaint) {
            var complaintlationData = complaint;

            $('#editComplaintModal input[name="id"]').val(complaintlationData.id);
            $('#editComplaintModal input[name="type"]').val(complaintlationData.type);
            $('#editComplaintModal input[name="description"]').val(complaintlationData.description);
            $('#editComplaintModal input[name="photo"]').val(complaintlationData.photo);

            if (complaintlationData.status !== null && complaintlationData.status !== '') {
                $('#editComplaintModal select[name="status"]').val(complaintlationData.status);
            } else {
                $('#editComplaintModal select[name="status"]').val('');
            }
            $('#editComplaintModal textarea[name="note"]').val(complaintlationData.note);
            var createdAtDate = new Date(complaintlationData.created_at);
            var formattedCreatedAt = createdAtDate.toLocaleString();
            $('#editComplaintModal input[name="created_at"]').val(formattedCreatedAt);

            $('#editComplaintModal').modal('show');
        }
    </script>

    <script>
        $(document).ready(function () {
            // When the form is submitted
            $('#edit').submit(function (e) {
                e.preventDefault();
                console.log($(this).id)
                var formData = $(this).serialize();
                var url = '{{ route("complaints.update", ":id") }}';
                url = url.replace(':id', $(this).id);
                $.ajax({
                    type: 'put',
                    url: url,
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                           location.reload()
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection

