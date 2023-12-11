@extends('admin.layouts.app')
@section('content')
    <div class="content">

        <div class="card">

            @include('admin.layouts.alert-area')


            <div class="card-header">
                <h5 class="mb-0">
                  شاشه تحويل المستحقات الى :
                    {{$user->name}}
                </h5>
            </div>


            <div class="table-responsive">


                <table class="table table-bordered table-inverse table-responsive">
                    <thead class="thead-inverse">
                    <tr>
                        <th>اختر للسداد</th>
                        <th>رقم الاوردر</th>
                        <th>القيمة</th>
                        <th>ملاحظات الادمن</th>
                        <th>تاريخ انشاء السجل</th>
                        <th>اخر تحديث</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($TransferRecord as $TR)

                    <tr>
                        <td>
                            <input type="checkbox" class="checkbox_trans"
                                   onclick="CalcSubmit()"
                            data-price="{{$TR->amount}}"
                            data-order_id="{{$TR->order_id}}"
                            data-trans_id="{{$TR->id}}"
                            >
                        </td>
                        <td>{{$TR->order_id}}</td>
                        <td>{{$TR->amount}}</td>
                        <td>{{$TR->admin_notes}}</td>
                        <td>{{$TR->created_at}}</td>
                        <td>{{$TR->updated_at}}</td>
                    </tr>
                    @endforeach


                     <tr style="display: none" id="total_row">
                        <td  colspan="6">
                                <p><b>عدد الطلبات المسدده</b> <span id="orders_count">500</span> </p>
                                <p><b>جمالي المبلغ المسدد</b> <span id="total">500</span> </p>
                            <button class="btn btn-success mt-3" onclick="TransNow()">سداد المستحقات</button>

                        </td>
                    </tr>

                    </tbody>
                </table>







            </div>
        </div>
    </div>
@endsection
@section('jscript')

    <script>
        var submitData =[] ;
        var total = 0;

        function TransNow() {

            CalcSubmit() ;


            swalInit.fire({
                title: 'هل انت متأكد',
                text:  "هل تريد بالفعل تأكيد السداد , لن تتمكن ابدا من الرجوع عن هذا الخيار ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم , تأكيد ',
                cancelButtonText: 'لا, الغاء ',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                } ,
            }).then(function (result) {
                if (result.isConfirmed) {


                    $.ajax({
                        url: '{{route('admin.transfer.do_transfer' ,  $user->id)}}',
                        method: "POST",
                        data: {
                            '_token': "{{csrf_token()}}",
                            'submitData':submitData,
                            'total':total,
                        },

                        success: function (result) {
                            if(result !== true)
                                if ("status" in result && result.status == false) {
                                    swalInit.fire(
                                        'حدث خطا ما',
                                        result.error_msg,
                                        'error'
                                    );
                                    return;
                                }
                            if (result) {
                                swalInit.fire(
                                    'تم التاكيد',
                                    'تم تأكيد السداد بنجاح',
                                    'success'
                                );
                                location.href='{{url()->previous()}}';
                            } else {
                                swalInit.fire(
                                    'حدث خطا ما',
                                    'لم يتم الحذف بسبب مشكله في الخادم !',
                                    'error'
                                );
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            swalInit.fire(
                                'حدث خطا ما',
                                'لم يتم الحذف بسبب مشكله في الخادم او لم يعد لديك صلاحيات كافيه !',
                                'error'
                            );
                            return;
                        }
                    });



                } else if (result.dismiss === swal.DismissReason.cancel) {
                    swalInit.fire(
                        'تم الالغاء',
                        'تم الغاء العمليه ',
                        'error'
                    );
                }
            });




        }
        function CalcSubmit() {
            submitData =[]
            total = 0;

            var orders_count =$('.checkbox_trans:checked').length ;
            if(orders_count <=0){
                $('#total_row').fadeOut();
                return ;
            }
            var checkbox_trans = $('.checkbox_trans:checked') ;
            $( checkbox_trans ).each(function( index ) {
                var price = parseFloat($(this).data('price'));
                var order_id = $(this).data('order_id');
                var trans_id = $(this).data('trans_id');
                total = total+price ;
                submitData.push({
                    price:price,
                    trans_id:trans_id,
                    order_id:order_id,
                })
            });

            total = total.toFixed(2)
            $('#total_row').fadeIn();
            $('#orders_count').text(orders_count);
            $('#total').text(total);
        }







    </script>
@endsection
