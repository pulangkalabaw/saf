@extends ('layouts.auth')
@section('content')
<div class="login">
    <div class="login__form">
        <h1>404 | {{ env('APP_NAME') }}</h1>
        <hr>
        <b>This page either under maintenance or not found :(</b>
        <hr>
        <a onclick="window.history.back()" class="btn btn-sm btn-default"><span class='fa fa-arrow-left'></span> Go Back</a>
    </div>
</div>
@endsection
