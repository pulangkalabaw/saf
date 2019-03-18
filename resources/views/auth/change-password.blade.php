@extends ('layouts.auth')

@section ('content')
<div class="login">
    <form class="login__form" action="{{ route('app.changePassword') }}" method="POST">
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
