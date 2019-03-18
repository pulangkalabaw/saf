@extends ('layouts.auth')

@section ('content')
<div class="login">
    <form class="login__form" action="{{ route('app.changePassword') }}" method="POST">
        <div class="form-group">
            <h3 class="text-center">Change password</h3>
        </div>
        @if (Session::has('message'))
        <div class="alert alert-dismissable alert-danger">
            {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}
                {{ Session::get('message') }}
        </div>
        @endif
        @include('includes.notif')
        {{ csrf_field() }}
        <input name="password_status" type="hidden" value="1">
        <div class="form-group">
            <input class="form-control" type="password" name="newpassword" placeholder="New password">
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Confirm new password">
        </div>
        <div class="form-group login__action">
            <div class="login__submit">
                <button class="btn btn-default" type="submit">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
