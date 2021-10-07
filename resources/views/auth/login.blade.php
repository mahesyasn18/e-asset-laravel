@extends('auth.template.layout', ['class' => 'bg-default'])

@section('content')
<div class="header bg-gradient-primary py-7 py-lg-8">
    <div class="container">
        <div class="header-body text-center mb-7">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <h1 class="text-white">Welcome to E-Asset Dashboard @if(Request::route()->getName()=="auth_admin")
                        Admin Area @endif </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
            xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</div>

<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card bg-secondary shadow border-0">
                <div class="card-header bg-transparent">
                    <div class="text-muted text-center mt-2 mb-3">
                        <h2>Silahkan Login</h2>
                    </div>
                </div>
                <div class="card-body px-lg-5 py-lg-5">
                    <form role="form" method="POST" action="{{ route('login') }}">
                        @csrf
                        @if (Session::has('error'))
                        <div class="alert-danger alert mt-2">
                            <strong>{{ Session::get("error") }}</strong>
                        </div>
                        @endif
                        <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }} mb-3">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Username') }}" name="username" value="{{ old('username') }}"
                                    value="admin@argon.com" required autofocus>
                            </div>
                            @if ($errors->has('username'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" placeholder="{{ __('Password') }}" type="password" required>
                            </div>
                            @if ($errors->has('password'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary my-4"
                                onclick="button_animate()"><span>{{ __('Sign in') }}<span id="spinner"
                                        style="display:none;"><img src="{{ asset("img/svg/pulse.svg") }}"
                                            width="30px"></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    function button_animate(){
                $("#spinner").css("display","inline-block");
            }
</script>
@endpush