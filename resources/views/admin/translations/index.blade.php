@extends('admin.layouts.app')
@section('content')
    <div class="content">
        @include('admin.layouts.alert-area')
        <div class="card">
            <div id="success-message" style="display: none;" class="alert alert-success">
                Form submitted successfully!
            </div>
            <div class="card-header">
            <h5 class="mb-0">Transactions</h5>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['class' => 'table'] ) !!}
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createTransactionModal" tabindex="-1" aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTransactionModalLabel">Create Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="create" method="POST" action="{{ route('translations.store') }}">
                    @csrf
                    <div class="modal-body">
                            <div class="p-1">
                                 <label>Key :</label>
                                 <input class="form-control" type="text"  name="key"  placeholder="please Enter the Key" required>
                            </div>
                            <div class="p-1">
                                 <label>Model :</label>
                                 <input class="form-control" type="text"  name="model" placeholder="please Enter the Model">
                            </div>
                            <div class="p-1">
                                 <label>En :</label>
                                 <input class="form-control" type="text"  name="en" placeholder="please Enter the En" required>
                            </div>
                            <div class="p-1">
                                 <label>Ar :</label>
                                 <input class="form-control" type="text"  name="ar" placeholder="please Enter the Ar" required>
                            </div>
                            <div class="p-1">
                                 <label>Fr :</label>
                                 <input class="form-control" type="text"  name="fr" placeholder="please Enter the Fr">
                            </div>
                            <div class="p-1">
                                <label>Tr :</label>
                                <input class="form-control" type="text"  name="tr" placeholder="please Enter the Tr">
                            </div>
                     </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="TransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TransactionModalLabel">Edit Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit" method="POST" action="{{ route('translations.update', ['translation' => ':id']) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                            @method('PUT')
                            <div class="p-1">
                                  <input class="form-control" type="hidden"  name="id" >
                            </div>
                            <div class="p-1">
                                  <label>Key :</label>
                                  <input class="form-control" type="text"  name="key" required>
                            </div>
                            <div class="p-1">
                                  <label>Model :</label>
                                  <input class="form-control" type="text"  name="model">
                            </div>
                            <div class="p-1">
                                 <label>En :</label>
                                 <input class="form-control" type="text"  name="en" required>
                            </div>
                            <div class="p-1">
                                  <label>Ar :</label>
                                  <input class="form-control" type="text"  name="ar" required>
                            </div>
                            <div class="p-1">
                                   <label>Fr :</label>
                                   <input class="form-control" type="text"  name="fr">
                            </div>
                            <div class="p-1">
                                  <label>Tr :</label>
                                  <input class="form-control" type="text"  name="tr">
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
        function EditFunction(translation) {
            var translationData = translation;
            $('#editTransactionModal input[name="id"]').val(translationData.id);
            $('#editTransactionModal input[name="key"]').val(translationData.key);
            $('#editTransactionModal input[name="model"]').val(translationData.model);
            $('#editTransactionModal input[name="en"]').val(translationData.en);
            $('#editTransactionModal input[name="ar"]').val(translationData.ar);
            $('#editTransactionModal input[name="fr"]').val(translationData.fr);
            $('#editTransactionModal input[name="tr"]').val(translationData.tr);

            $('#editTransactionModal').modal('show');
        }
    </script>

    <script>
        $(document).ready(function () {
            // When the form is submitted
            $('#edit').submit(function (e) {
                e.preventDefault();
                console.log($(this).id)
                var formData = $(this).serialize();
                var url = '{{ route("translations.update", ":id") }}';
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

    <script>
        function createTranslations(){
            $('#createTransactionModal').modal('show');
        }
    </script>
@endsection

