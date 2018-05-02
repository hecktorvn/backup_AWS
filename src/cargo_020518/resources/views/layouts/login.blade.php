<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-fav.png') }}">
    <title>Beagle</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/material-design-icons/css/material-design-iconic-font.min.css') }}" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" type="text/css" />
</head>

<body class="be-splash-screen">
    <div class="be-wrapper be-login">
        <div class="be-content">
            <div class="main-content container-fluid">
                <div class="splash-container">
                    @component('components.alert', ['type'=>'danger'])
                        {!! Session::get('erro') !!}
                    @endcomponent

                    <div class="card card-border-color card-border-color-primary">
                        <div class="card-header">
                            <img src="{{ asset('assets/img/logo-xx.png') }}" alt="logo" width="102" height="27" class="logo-img">
                            <span class="splash-description">@lang('layout.login.enter_info')</span>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('login') }}" method="post">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <input id="username" required name="username" type="text" placeholder="@lang('layout.login.username')" autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input id="password" required name="password" type="password" placeholder="@lang('layout.login.password')" class="form-control">
                                </div>
                                <div class="form-group row login-tools">
                                    <div class="col-6 login-remember mt-1">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label">@lang('layout.login.remember')</span>
                                        </label>
                                    </div>
                                    <div class="col-6 login-forgot-password"><a href="pages-forgot-password.html">@lang('layout.login.forgot')</a></div>
                                </div>
                                <div class="form-group login-submit">
                                    <button data-dismiss="modal" type="submit" class="btn btn-primary btn-xl">@lang('layout.login.login')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/lib/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/bootstrap/dist/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //initialize the javascript
            App.init();
        });
    </script>
</body>

</html>
