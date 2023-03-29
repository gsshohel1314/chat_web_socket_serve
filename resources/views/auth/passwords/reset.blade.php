@extends('user.layouts.master-without-nav')

@section('title')
    @lang('login.label_login')
@endsection

@section('css')
    .application_rules{
        @if(config('app.locale') == 'en')
            list-style: decimal;
        @else
             list-style: bengali;
        @endif

        text-align: justify;
    }

    .left_side_part,.left_side_part h4{
        color: #fff;
    }

    .left_side_part a{
        color: #49f112;
    }
@endsection

@section('body')
    <body class="auth-body-bg">

    <div class="bg-light">
        <div class="container-fluid">
            @include('auth.partials.topnav')
        </div>
    </div>
    @endsection

    @section('content')

        <div>
            <div class="container-fluid p-0">
                <div class="row g-0 container-row">

                    <div class="col-xl-8 font-size-17">
                        @include('auth.partials.application_rules')
                    </div>
                    <!-- end col -->

                    <div class="col-xl-4">
                        <div class="auth-full-page-content p-md-3 p-3">
                            <div class="w-100">

                                <div class="d-flex flex-column h-100">
                                    <div class="mb-2 mb-md-2 text-center">
                                        <a href="/" class="d-block auth-logo">
                                            <img src="{{ URL::asset('images/logo-fsc-noc.png') }}" alt="" height="75">
                                        </a>
                                        <span class="font-size-13 mt-2 text-success">
                                            @lang('settings.website_title')
                                        </span>
                                    </div>
                                    <div class="mb-1 mt-1 my-auto">

                                        <div>
                                            <p class="font-size-12 text-center text-muted">@lang('passwords.write_new_password') </p>
                                        </div>

                                        <div class="mt-1">
                                            <form class="form-horizontal" method="POST" action="{{ route('password.update') }}">
                                                @csrf
                                                <input name="email" type="hidden" class="form-control {{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}" value="{{ $email ?? old('email') }}" id="username" placeholder="@lang('passwords.label_email_placeholder')" autocomplete="email" autofocus>

                                                <input type="hidden" name="token" value="{{ $token }}">

                                                <div class="mb-3">
                                                    <label for="userpassword">@lang('auth.label_password')</label>
                                                    <input type="password"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           name="password" id="userpassword" placeholder="@lang('auth.label_password_placeholder')" required>
                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="userpassword">@lang('registration.label_password_confirmation')</label>
                                                    <input id="password-confirm" type="password"
                                                           name="password_confirmation" class="form-control"
                                                           placeholder="@lang('registration.label_password_confirmation_placeholder')" required>
                                                </div>

                                                <div class="mt-3 d-grid">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit">@lang('passwords.label_reset')</button>
                                                </div>

                                            </form>
                                            <div class="mt-3 text-center">
                                                <p class="mb-1">{{--@lang('auth.label_no_account') --}}<a href="{{route('register')}}" class="fw-medium text-primary"> @lang('login.label_create_new_account') </a> </p>
                                                <p class="mb-1">{{--@lang('auth.label_no_account') --}}<a href="{{route('login')}}" class="fw-medium text-primary"> @lang('login.label_do_login') </a> </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="{{--mt-2 mt-md-2--}} text-center">
                                        <p class="font-size-12 mb-0">© <script>
                                                document.write(new Date().getFullYear())

                                            </script> @lang('settings.website_title').

                                        </p>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container-fluid -->
        </div>

    @endsection
    @section('script')
        <!-- owl.carousel js -->
        <script src="{{ URL::asset('/assets/common/libs/owl.carousel/owl.carousel.min.js') }}"></script>
        <!-- auth-2-carousel init -->
        <script src="{{ URL::asset('/assets/common/js/pages/auth-2-carousel.init.js') }}"></script>
@endsection
