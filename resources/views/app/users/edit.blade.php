@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Users</li>
        <li class=""><a href="{{ route('app.users.index') }}">User Accounts</a></li>
        <li class="">
            <a href="{{ route('app.users.show', $user->id) }}">{{ $user->fname }} {{ $user->lname }}</a>
        </li>
        <li class="active">Edit</li>
    </ol>
</div>
<div class="container-fluid half-padding">
    <div class="template template__blank">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="panel-title">
                                    User Accounts
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.users.show', $user->id) }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-eye'></span> 
                                </a>
                                <a href="{{ route('app.users.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span> 
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('app.users.update', $user->id) }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">First name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="fname" id="" class="form-control" value="{{ $user->fname }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Last name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="lname" id="" class="form-control" value="{{ $user->lname }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Email</div>
                                        <div class="col-md-7">
                                            <input type="email" name="email" id="" class="form-control" value="{{ $user->email }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Password</div>
                                        <div class="col-md-7">
                                            <input type="password" name="password" id="" class="form-control" value="">
                                            <span class='fa fa-info-circle'></span> if no change, leave it blank
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Role</div>
                                        <div class="col-md-7">
                                            <select name="role" id="roles" class="form-control" required>
                                                <option {{ (base64_decode($user->role) == 'spiderman') ? 'selected' : '' }}value="spiderman">Administrator</option>
                                                <option {{ (base64_decode($user->role) == 'tl') ? 'selected' : '' }} value="tl">Team Leader</option>
                                                <option {{ (base64_decode($user->role) == 'cl') ? 'selected' : '' }} value="cl">Cluster Leader</option>
                                                <option {{ (base64_decode($user->role) == 'agent') ? 'selected' : '' }} value="agent">Agent</option>
                                                <option {{ (base64_decode($user->role) == 'encoder') ? 'selected' : '' }} value="encoder">Encoder</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    @if ($user->agent_code)
                                    <div id="code">
                                        <div class="col-md-3">Agent Code</div>
                                        <div class="col-md-7">
                                            <input type="text" name="agent_code" id="" class="form-control" value="{{ $user->agent_code }}">
                                        </div>
                                        <div class="clearfix"></div><br>
                                    </div>
                                    @endif

                                    <div>
                                        <div class="col-md-3">Status</div>
                                        <div class="col-md-7">
                                            <select name="isActive" class="form-control" required>
                                                <option {{ ($user->isActive == 1) ? 'selected' : '' }} value="1">Activated</option>
                                                <option {{ ($user->isActive == 0) ? 'selected' : '' }} value="0">Deactivated</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-7 text-right">
                                            <button class="btn btn-xs btn-success">Update changes <span class='fa fa-edit'></span> </button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section ('scripts')
<script>
    function roleSwitcher()
    {
        if ($('#roles').val() == "agent") {
            $('#code').css({'display': 'block'});
        }
        else {
            $('#code').css({'display': 'none'});
        }
    }

    roleSwitcher();

    $('#roles').on('change', function(){
        roleSwitcher();
    })

</script>
@endsection