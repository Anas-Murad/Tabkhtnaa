@extends('admin.layouts.app')
@section('content')
    <div class="content">

        <!-- Collapse/expand card -->
        <div class="card  ">
            <div class="card-header d-flex align-items-center">
                <h6 class="mb-0">
                    تعديل اعدادات
                    {{ConfigurationTransClassification($classification)}}-
                    اختر الدولة</h6>
            </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.edit' ,  $classification) }}" id="filter_form">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <div class="row">

                                        @includeIf('admin.components.countries' , [
                                             'col_size'=>'col-md-6',
                                             'country_name'=>'country_id',
                                             'required'=>true,
                                            'with_cities'=>false,
                                        ])





                                        <div class="col">
                                            <label class="form-label" style="visibility: hidden">From Date:</label>
                                            <div class="text-left">
                                                <button type="submit"
                                                        class="btn btn-secondary">تطبيق
                                                    <i  class="ph-file-search ms-2"></i>
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
@endsection
@section('jscript')

    <script>

    </script>
@endsection
