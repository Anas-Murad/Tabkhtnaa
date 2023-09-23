@extends('admin.layouts.app')
@section('content')
    <div class="content">
        <div class="card">
            @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card-header">
            <h5 class="mb-0">Complaints</h5>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table'] ) !!}
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editComplaintModal" tabindex="-1" aria-labelledby="ComplaintModalLabel" aria-hidden="true">
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
                                  <input class="form-control" type="hidden"  name="id" >
                            </div>
                            <div class="p-1">
                                  <label>Type :</label>
                                  <input class="form-control" type="text"  name="type" readonly>
                            </div>
                            <div class="p-1">
                                  <label>Photo :</label>
                                  <img src="{{asset('photo')}}">
                            </div>
                            <div class="p-1">
                                 <label>Description :</label>
                                 <input class="form-control" type="text"  name="description" readonly>
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
                                <textarea class="form-control" type="text"  name="note" required></textarea>
                            </div>
                            <div class="p-1">
                                  <label>Created At :</label>
                                  <input class="form-control" type="text"  name="created_at" readonly>
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
                            window.location.href = '/admin/complaints';
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

