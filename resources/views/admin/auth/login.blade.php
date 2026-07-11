<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tabkhtnaa - تسجيل الدخول</title>
    <link href="{{asset('assets/fonts/inter/inter.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/icons/phosphor/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/rtl/all.min.css')}}" id="stylesheet" rel="stylesheet" type="text/css">
    <script src="{{asset('assets/demo/demo_configurator.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
</head>
<body class="bg-dark">
<div class="page-content">
    <div class="content-wrapper">
        <div class="content-inner">
            @if(session()->has('error'))
                <div class="alert alert-danger col-6 text-center align-self-center" role="alert">
                    <span class="text-danger mt-2">{{ session('error') }}</span>
                </div>
            @endif
            <div class="content d-flex justify-content-center align-items-center">
                <form action="{{route('login')}}" method="post">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                                    <img src="{{ asset('assets/images/logo_icon.svg') }}" class="h-48px" alt="Tabkhtnaa">
                                </div>
                                <h5 class="mb-0">تسجيل الدخول</h5>
                                <span class="d-block text-muted">أدخل بيانات حسابك</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">البريد الإلكتروني</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="text" name="email" class="form-control" placeholder="admin@tabkhtnaa.com" required>
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-user-circle text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">كلمة المرور</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="password" name="password" class="form-control" placeholder="•••••••••••" required>
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-lock text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                    <span class="form-check-label">تذكرني</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">دخول</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
