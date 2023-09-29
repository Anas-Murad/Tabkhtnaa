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
                                                    ابحث عن اسم  meal ,
                                                    ..الخ:</label>
                                                <input type="text" class="form-control" name="search_key"
                                                       id="search_key" placeholder="كلمة البحث ... ">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">ابحث عن  number </label>
                                                <input type="text" class="form-control" name="number"
                                                       id="number" placeholder="Number ">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label">ابحث عن  percent </label>
                                                <input type="text" class="form-control" name="percent"
                                                       id="number" placeholder="percent ">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label"> تاريخ البداية:</label>
                                                <input type="date" placeholder="From Date" class="form-control"
                                                       name="start_date"
                                                       value=""
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label"> تاريخ النهاية:</label>
                                                <input type="date" placeholder="From Date" class="form-control"
                                                       name="end_date"
                                                       value=""
                                                >
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
                <h5 class="mb-0">Meals</h5>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table'] ) !!}
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editMealModal" tabindex="-1" aria-labelledby="MealModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MealModalLabel">Edit Meal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit" method="POST" action="{{ route('admin.meals.update', ['meal' => ':id']) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        @method('PUT')
                        <div class="p-1">
                            <input class="form-control" type="hidden" name="id">
                        </div>
                        <div class="p-1">
                            <label>Status :</label>
                            <select class="form-control type" name="admin_status" required>
                                <option value="">Select Status</option>
                                <option value="confirmed">confirmed</option>
                                <option value="disabled">disabled</option>
                            </select>
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
        function EditMealFunction(meal) {
            var mealData = meal;
            $('#editMealModal input[name="id"]').val(mealData.id);
            if (mealData.admin_status !== null && mealData.admin_status !== '') {
                $('#editMealModal select[name="admin_status"]').val(mealData.admin_status);
            } else {
                $('#editMealModal select[name="admin_status"]').val('');
            }
            $('#editMealModal').modal('show');
        }
    </script>

    <script>
        $(document).ready(function () {
            // When the form is submitted
            $('#edit').submit(function (e) {
                e.preventDefault();
                console.log($(this).id)
                var formData = $(this).serialize();
                var url = '{{ route("admin.meals.update", ":id") }}';
                url = url.replace(':id', $(this).id);
                $.ajax({
                    type: 'put',
                    url: url,
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            location.reload();
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

