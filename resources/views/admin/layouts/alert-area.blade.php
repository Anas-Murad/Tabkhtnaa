@if(session()->has('Success'))
    <div class="alert alert-success alert-dismissible fade show">
        <span class="fw-semibold">نجاح</span>
        {!! session()->get('Success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


@if(session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <span class="fw-semibold">تنبيه!</span>
        {!! session()->get('warning') !!}
        @php session()->forget('warning') @endphp
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <span class="fw-semibold">خطأ</span>
        {{--        {{ $errors->first() }}--}}
        @foreach($errors->all() as $error)
            <p>{!! $error !!}</p>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
