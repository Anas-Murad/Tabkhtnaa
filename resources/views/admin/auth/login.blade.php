<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>
    <!-- Global stylesheets -->
    <link href="{{asset('assets/fonts/inter/inter.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/icons/phosphor/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/ltr/all.min.css')}}" id="stylesheet" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <!-- Core JS files -->
    <script src="{{asset('assets/demo/demo_configurator.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- /core JS files -->
    <!-- Theme JS files -->
    <script src="{{asset('assets/js/app.js')}}"></script>
    <!-- /theme JS files -->
</head>
<body class="bg-dark">
<!-- Page content -->
<div class="page-content">
    <!-- Main content -->
    <div class="content-wrapper">
        <!-- Inner content -->
        <div class="content-inner">
            <!-- Content area -->
            @if(session()->has('error'))
                <div class="alert alert-danger col-6 text-center align-self-center"  role="alert">
                    <span class="text-danger  mt-2">{{ session('error') }}</span>
                </div>
            @endif
            <div class="content d-flex justify-content-center align-items-center">
                <!-- Login card -->
                <form action="{{route('login')}}" method="post">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                                    <img src="../../../assets/images/logo_icon.svg" class="h-48px" alt="">
                                </div>
                                <h5 class="mb-0">Login to your account</h5>
                                <span class="d-block text-muted">Enter your credentials below</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="text" name="email" class="form-control" placeholder="john@doe.com" required>
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-user-circle text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
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
                                    <span class="form-check-label">Remember</span>
                                </label>
                                <a href="login_password_recover.html" class="ms-auto">Forgot password?</a>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">Sign in</button>
                            </div>
                            <span class="form-text text-center text-muted">By continuing, you're confirming that you've read our <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span>
                        </div>
                    </div>
                </form>
                <!-- /login card -->
            </div>
            <!-- /content area -->
        </div>
        <!-- /inner content -->
    </div>
    <!-- /main content -->
</div>
<!-- /page content -->
</body>
</html>
