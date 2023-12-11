@extends('admin.layouts.app')
@section('content')
    <div class="content">

        <!-- Collapse/expand card -->
        <div class="card  ">
            <div class="card-header d-flex align-items-center">
                <h6 class="mb-0">
                    @if(isset($city))
                    تعديل المدينه
                    - {{$city->name}}
                    @else
                    اضف مدينه الى دولة
                    {{$country->name}}
                    @endif
                </h6>
            </div>
            <div class="card-body">

                @include('admin.layouts.alert-area')

                <form


                    @if(isset($city))
                        action="{{ route('admin.settings.cities.update' ,  [$country_id , $city_id]) }}"
                    @else
                        action="{{ route('admin.settings.cities.store' ,  [$country_id ]) }}"
                    @endif

                    id="submit_form" method="post">
                    @csrf
                    @if(isset($city))
                        @method('PUT')
                    @else
                        @method('POST')
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="mb-3"  >
                                            <label class="form-label"> Name </label>
                                            <input type="text"
                                                   name="name"
                                                   class="form-control"
                                                   value="{{$city->name ?? ''}}"
                                                   required
                                            >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3"  >
                                            <label class="form-label"> Country Code </label>
                                            <input type="text"
                                                   name="country_code"
                                                   class="form-control"
                                                   value="{{$city->country_code ?? ''}}"
                                                   required
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3"  >
                                            <label class="form-label"> Iso2 </label>
                                            <input type="text"
                                                   name="iso2"
                                                   class="form-control"
                                                   value="{{$city->iso2 ?? ''}}"
                                                   required
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3"  >
                                            <label class="form-label"> Latitude </label>
                                            <input
                                                type="number" step="any"
                                                   name="latitude"
                                                   maxlength="10"

                                                   class="form-control"
                                                   value="{{$city->latitude ?? ''}}"
                                                   required
                                            >
                                        </div>
                                    </div>






                                    <div class="col-md-4">
                                        <div class="mb-3"  >
                                            <label class="form-label"> Longitude </label>
                                            <input
                                                type="number" step="any"
                                                   name="longitude"
                                                   maxlength="10"
                                                   class="form-control"
                                                   value="{{$city->longitude ?? ''}}"
                                                   required
                                            >
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="mb-3"  >
                                            <label class="form-label"> Status </label>
                                            <select type="text" name="flag" class="form-control" required>
                                                <option value="1" @selected(isset($city) && $city->flag )>Active</option>
                                                <option value="0" @selected(isset($city) && !$city->flag )>InActive</option>
                                            </select>
                                        </div>
                                    </div>







                                    <div class="col-12">
                                        <div class="text-left">
                                            <button type="submit"
                                                    class="btn btn-secondary">حفظ
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
        const validator = $('#submit_form').validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-invalid-label',
            successClass: 'validation-valid-label',
            validClass: 'validation-valid-label',
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
            },
            /*   success: function(label) {
                   label.addClass('validation-valid-label').text('Success.'); // remove to hide Success message
               },*/

            // Different components require proper error label placement
            errorPlacement: function(error, element) {

                // Input with icons and Select2
                if (element.hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }

                // Input group, form checks and custom controls
                else if (element.parents().hasClass('form-control-feedback') || element.parents().hasClass('form-check') || element.parents().hasClass('input-group')) {
                    error.appendTo(element.parent().parent());
                }

                // Other elements
                else {
                    error.insertAfter(element);
                }
            },
        });
    </script>
@endsection
