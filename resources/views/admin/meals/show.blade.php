@extends('admin.layouts.app')
@section('content')
    <!-- Content area -->
    <div class="content">
        <!-- Scrollable datatable -->
        @include('admin.layouts.alert-area')
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">تفاصيل الوجبة</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>الحقل</th>
                                        <th class="text-center">القيمه</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($meal->name)
                                        <tr>
                                            <th class="text-nowrap" scope="row">Name</th>
                                            <td>{{$meal->name}}</td>
                                        </tr>
                                    @endif
                                    @if($meal->code)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> Code</th>
                                            <td>{{$meal->code }}</td>
                                        </tr>
                                    @endif
                                    @if($meal->description)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> Description</th>
                                            <td><{{$meal->description }}</td>
                                        </tr>
                                    @endif
                                    @if($meal->price)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> Price</th>
                                            <td>{{$meal->price}}</td>
                                        </tr>
                                    @endif

                                    @if($meal->admin_status)
                                        <tr>
                                            <th class="text-nowrap" scope="row">Admin Status</th>
                                            <td>{{$meal->admin_status}}</td>
                                        </tr>
                                    @endif

                                    @if($meal->admin_note)
                                        <tr>
                                            <th class="text-nowrap" scope="row">Admin Note</th>
                                            <td>{{$meal->admin_note}}</td>
                                        </tr>
                                    @endif

                                    @if($meal->preparation_time)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> preparation_time</th>
                                            <td>{{$meal->preparation_time}}</td>
                                        </tr>
                                    @endif

                                    @if($meal->days)
                                        <tr>
                                            <th class="text-nowrap" scope="row"> days</th>
                                            <td>{{json_encode($meal->days)}}</td>
                                        </tr>
                                    @endif
                                    @if($meal->is_active)
                                        <tr>
                                            <th class="text-nowrap" scope="row">is_active</th>
                                            <td>{{$meal->is_active == 1 ? 'Active' : 'In Active'}}</td>
                                        </tr>
                                    @endif
                                    @if($meal->type)
                                        <tr>
                                            <th class="text-nowrap" scope="row">type</th>
                                            <td>{{$meal->type}}</td>
                                        </tr>
                                    @endif
                                    @if($meal->image)
                                        <tr>
                                            <th class="text-nowrap" scope="row">image</th>
                                            <td>
                                                <a target="_blank" href="{{asset($meal->image)}}">
                                                    <img src="{{asset($meal->image)}}" width="50">
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if(!$meal->accessories->isEmpty())
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"> Accessories</h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>name</th>
                                        </tr>
                                        </thead>
                                        <tbody>


                                        @foreach($meal->accessories as $accessorie)
                                            <tr>
                                                <th class="text-nowrap" scope="row">
                                                    {{$accessorie->key}}
                                                </th>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!$meal->additions->isEmpty())
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"> Additions</h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>name</th>
                                            <th>price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($meal->additions as $addition)
                                            <tr>
                                                <th class="text-nowrap" scope="row">
                                                    ({{$addition->additionCategory->name}}) :
                                                    {{$addition->name}}
                                                </th>
                                                <th class="text-nowrap" scope="row">
                                                    {{$addition->price}}
                                                </th>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="text-center">
                    <a href="{{route('users.show' , $meal->user)}}">
                        <button type="submit" class="btn btn-teal">
                            Chef Details
                            <i class="ph-eye ms-2"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('jscript')
@endsection
