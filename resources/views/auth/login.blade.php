@extends ('layouts.auth')

@section ('content')
<div class="login">
    <form class="login__form" action="{{ route('login') }}" method="POST">
        @include('includes.notif')
        {{ csrf_field() }}
        <div class="form-group">
            <input class="form-control" type="email" name="email" placeholder="Email">
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Password">
        </div>
        <div class="form-group login__action">
            <div class="login__submit">
                <button class="btn btn-default" type="submit">Sign in</button>
            </div>
            <a href="{{ route('forgot') }}">Forgot password</a>
        </div>
    </form>
</div>
@endsection
