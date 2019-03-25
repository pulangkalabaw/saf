@extends('layouts.auth')
@section('content')
<div class="row">
    <div class="col-md-6 col-sm-offset-3" style="padding-top:50px;">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title fa fa-lock"> Forgot Password</h3>
            </div>
            <div class="panel-body-xs">
                <div class="ld-widget">
                    <div class="ld-widget__cont">
                        <div class="ld-widget-main" style="padding-left: 190px;">
                            <div class="ld-widget-main__chart">
                                <form method="POST" action=" {{ route('password_reset') }} ">
                                    {{ csrf_field() }}
                                    <div class="form-group d-flex justify-content-center" style="padding-top:40px;">
                                        <input class="form-control" type="email" name="email"
                                         placeholder="Enter Email address">
                                    </div>
                                    <div class="form-group login__action">
                                        <div class="login__submit">
                                            <button class="btn btn-default" type="submit">Send</button>
                                        </div>
                                    </div>
                                    @if (Session::has('message'))
                                        <div class="alert alert-dismissable alert-danger">
                                            {{ Session::get('message') }}
                                        </div>
                                    @endif
                                    @if (Session::has('success'))
                                        <div class="alert alert-dismissable alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
