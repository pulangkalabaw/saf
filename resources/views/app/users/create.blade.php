@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Users</li>
        <li class="">
            <a href="{{ route('app.users.index') }}">User Accounts</a>
        </li>
        <li class="active">Create</li>
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
                                <a href="{{ route('app.users.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('app.users.store') }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">First name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="fname" id="" class="form-control" required value="{{ old('fname') }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Last name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="lname" id="" class="form-control" required value="{{ old('lname') }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Email</div>
                                        <div class="col-md-7">
                                            <input type="email" name="email" id="" class="form-control" required value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Password</div>
                                        <div class="col-md-7">
                                            <input type="password" name="password" id="" class="form-control" required value="{{ old('password') }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Role</div>
                                        <div class="col-md-7">
                                            <select name="role" id="roles" class="form-control" required>
                                                <option   value="" disabled="" selected="">Please select for role</option>
                                                <option  {{ old('role') == 'spiderman' ? 'selected': ''  }} value="spiderman">Administrator</option>
                                                <option  {{ old('role') == 'tl' ? 'selected': ''  }} value="tl">Team Leader</option>
                                                <option  {{ old('role') == 'cl' ? 'selected': ''  }} value="cl">Cluster Leader</option>
                                                <option  {{ old('role') == 'encoder' ? 'selected': ''  }} value="encoder">Encoder</option>
                                                <option  {{ old('role') == 'agent' ? 'selected': ''  }} value="agent">Agent</option>
                                                <option  {{ old('role') == 'agent_referral' ? 'selected': ''  }} value="agent_referral">Agent Referral</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div class="code">
                                        <div class="col-md-3">Agent Code</div>
                                        <div class="col-md-7">
                                            <input type="text" name="agent_code" id="" class="form-control" value="{{ old('agent_code') }}">
                                        </div>
                                        <div class="clearfix"></div><br>
                                    </div>

                                    <div>
                                        <div class="col-md-3">Status</div>
                                        <div class="col-md-7">
                                            <select name="isActive" class="form-control" required>
                                                <option {{ old('isActive') == '1' ? 'selected': ''  }} value="1">Activated</option>
                                                <option {{ old('isActive') == '0' ? 'selected': ''  }} value="0">Deactivated</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div class="code">
                                        <div class="col-md-3">PAT</div>
                                        <div class="col-md-7">
                                            <input type="text" name="pat" class="form-control" value="{{ old('pat') }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-7 text-right">
                                            <button class="btn btn-xs btn-primary">Submit <span class='fa fa-plus-circle'></span> </button>
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
        if ($('#roles').val() == "agent" || $('#roles').val() == "agent_referral") {
            $('.code').css({'display': 'block'});
        }
        else {
            $('.code').css({'display': 'none'});
        }
    }

    roleSwitcher();

    $('#roles').on('change', function(){
        roleSwitcher();
    })

</script>
@endsection
