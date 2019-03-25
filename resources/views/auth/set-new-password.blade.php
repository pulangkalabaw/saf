@extends('layouts.auth')
@section('content')
<div class="row">
    <div class="col-md-6 col-sm-offset-3" style="padding-top:50px;">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Reset Password</h3>
            </div>
            <div class="panel-body-xs">
                <div class="ld-widget">
                    <div class="ld-widget__cont">
                        <div class="ld-widget-main" style="padding-left: 190px;">
                            <form method="POST" action=" {{ url('set-new-password/'. $token ) }} ">
                                {{ csrf_field() }}
                               
                                <div class="form-group" style="padding-top: 30px;">
                                    @if (Session::has('message'))
                                        <div class="alert alert-dismissable alert-danger">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                                {{ Session::get('message') }}
                                        </div>
                                    @endif
                                    <input class="form-control" type="password" name="password" placeholder="Enter new Password">
                                </div>
                                 <div class="form-group">
                                    <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password">
                                </div>
                                <div class="form-group login__action" style="padding-bottom: 30px;">
                                    <div class="login__submit">
                                        <button class="btn btn-default" type="submit">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
