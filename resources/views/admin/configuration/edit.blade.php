@extends('admin.layouts.app')
@section('content')
    <div class="content">

        <!-- Collapse/expand card -->
        <div class="card  ">
            <div class="card-header d-flex align-items-center">
                <h6 class="mb-0">
                    تعديل اعدادات
                    @if($classification !='all')
                        {{ConfigurationTransClassification($classification)}}-
                    @endif

                    - {{$country->name}}
                </h6>
            </div>
            <div class="card-body">

                @include('admin.layouts.alert-area')

                <form action="{{ route('admin.settings.update' ,  [$classification , $country]) }}" id="submit_form" method="post">
                    @csrf


                    @if($classification !='all')

                        <div class="row">
                            <div class="col-md-12">


                                <fieldset>
                                    <div class="row">


                                        @foreach($initForm as $Form)
                                            <div class="col-md-4">
                                                <div class="mb-3"  >
                                                    <label class="form-label"> {{$Form['title']}}  </label>
                                                    <input type="text"
                                                           name="{{$Form['config_key']}}"
                                                           class="form-control"
                                                           value="{{$Form['config_value']}}"
                                                           required
                                                    >
                                                </div>
                                            </div>
                                        @endforeach



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

                    @else


                        <div class="row">
                            <div class="col-md-12">

                                @foreach($initForm as $key=> $FormS)
                                        <h6 class="mb-2 mt-5">
                                            {{ConfigurationTransClassification($key)}}
                                        </h6>
                                <fieldset>
                                    <div class="row">

                                        @foreach($FormS as $Form)
                                            <div class="col-md-4">
                                                <div class="mb-3"  >
                                                    <label class="form-label"> {{$Form['title']}}  </label>
                                                    <input type="text"
                                                           name="{{$key}}[{{$Form['config_key']}}]"
                                                           class="form-control"
                                                           value="{{$Form['config_value']}}"
                                                           required
                                                    >
                                                </div>
                                            </div>
                                        @endforeach


                                    </div>
                                </fieldset>
                                @endforeach


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





                    @endif



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
