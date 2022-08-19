 
<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>{{  website()->nama_website }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('websiteIcon/'.website()->icon) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App css -->
    <link href="{{ asset('hy_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('hy_assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
</head>
<body class="authentication-bg pb-0" data-layout-config='{"darkMode":false}'>
 <div class="auth-fluid">
    <div class="auth-fluid-form-box">
        <div class="align-items-center d-flex h-100">
            <div class="card-body">

                <!-- Logo -->
                <div class="auth-brand text-center text-lg-start">
                    <a href="/" class="logo-dark">
                        <span><img src="{{ asset('websiteIcon/'.website()->icon) }}" alt="" height="60"></span>
                    </a>
                    <a href="/" class="logo-light">
                        <span><img src="{{ asset('websiteIcon/'.website()->icon) }}" alt="" height="18"></span>
                    </a>
                </div>

                <!-- title-->
                <h4 class="mt-0">Login</h4>
                <p class="text-muted mb-4">Masukkan Email dan Password yang sudah terdaftar.</p>

                <!-- form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="emailaddress" class="form-label">{{ __('Alamat Email') }}</label>
                        <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" id="emailaddress"  value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <!-- <a href="pages-recoverpw-2.html" class="text-muted float-end"><small>Lupa password?</small></a> -->
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div> -->
                    <div class="d-grid mb-0 text-center">
                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-login"></i> {{ __('Login') }} </button>
                    </div>

                </form>
                <!-- end form-->

                <!-- Footer-->
                <!-- <footer class="footer footer-alt">
                    <p class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-muted ms-1"><b>Daftar Disini</b></a></p>
                </footer> -->

            </div> <!-- end .card-body -->
        </div> <!-- end .align-items-center.d-flex.h-100-->
    </div>
    <!-- end auth-fluid-form-box-->
    
    <!-- Auth fluid right content -->
    <div class="auth-fluid-right text-center">
        <div class="auth-user-testimonial">
            <h2 class="mb-3">{{  website()->nama_website }}</h2>
            <p class="lead"><i class="mdi mdi-format-quote-open"></i> Spesialis Souvenir
& Seminar Kit  . <i class="mdi mdi-format-quote-close"></i>
            </p>
           
        </div> 
        <!-- end auth-user-testimonial -->
    </div>
    <!-- end Auth fluid right content -->
</div>
<!-- bundle -->
<script src="{{ asset('hy_assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('hy_assets/js/app.min.js') }}"></script>

</body>
</html>