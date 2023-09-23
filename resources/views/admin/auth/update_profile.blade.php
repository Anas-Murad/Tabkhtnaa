@extends('admin.layouts.app')
@section('title')
    Home
@endsection
@section('page')
    Profile
@endsection
@section('content')
    <!-- Content area -->
    <div class="content">
        <!-- Basic layout -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Profile</h5>
            </div>
            @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card-body border-top">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">Name :</label>
                        <div class="col-lg-9">
                            <input type="text" name="name" class="form-control" value="{{$user->name}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">Email :</label>
                        <div class="col-lg-9">
                            <input type="text" name="email" class="form-control" value="{{$user->email}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">Phone :</label>
                        <div class="col-lg-9">
                            <input type="number" name="mobile" class="form-control" value="{{$user->mobile}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">DOB :</label>
                        <div class="col-lg-9">
                            <input type="date" name="dob" class="form-control" value="{{$user->dob}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">Gender:</label>
                        <div class="col-lg-9">
                            <div class="form-check-horizontal">
                                <label class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" value="male" name="gender" @checked($user->gender == 'male')>
                                    <span class="form-check-label">Male</span>
                                </label>

                                <label class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" value="female" name="gender" @checked($user->gender == 'female')>
                                    <span class="form-check-label">Female</span>
                                </label>

                                <label class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" value="other" name="gender" @checked($user->gender == 'other')>
                                    <span class="form-check-label">Other</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">Your Profile Image:</label>
                        <div class="col-lg-9">
                            <img class="p-1" src="{{asset($user->profile_image)}}">
                            <input type="file" class="form-control" name="profile_image">
                            <div class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-3 col-form-label">Account Status:</label>
                        <div class="col-lg-9">
                            <div class="form-check-horizontal">
                                <label class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input"  name="account_status" @checked($user->account_status == 'active')>
                                    <span class="form-check-label text-success">Active</span>
                                </label>

                                <label class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input"  name="account_status" @checked($user->account_status == 'inactive')>
                                    <span class="form-check-label text-danger">In Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Edit <i class="ph-paper-plane-tilt ms-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /basic layout -->
@endsection
