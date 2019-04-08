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
                                <a href="{{ route('app.users.index') }}" class="btn btn-sm btn-default">
                                    <span class='fa fa-th-list'></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{-- <form action="{{ route('app.import-users') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <label>Import Users</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-sm-7">
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                    <div class="col-sm-5">
                                        <button class="btn btn-success">Import</button>
                                    </div>
                                    <div class="clearfix"></div><br><br>
                                </div>
                            </div>
                        </form> --}}
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
                                            <label>The default password is <span class="text-info">Password123</span></label>
                                            {{-- <input type="password" name="password" id="" class="form-control" required value="{{ old('password') }}"> --}}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

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

                                    <div>
                                        <div class="col-md-3">Role</div>
                                        <div class="col-md-7">
                                            <select name="role" id="roles" class="form-control" required onchange="roleValue()">
                                                <option  value="" disabled="" selected="">Please select for role</option>
                                                <option  {{ old('role') == 'administrator' ? 'selected': ''  }} value="administrator">Administrator</option>
                                                <option  {{ old('role') == 'encoder' ? 'selected': ''  }} value="encoder">Encoder</option>
                                                <option  {{ old('role') == 'user' ? 'selected': ''  }} value="user">User</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div id="ag_ref" hidden>
                                        <div class="col-md-3">Agent Referral:</div>
                                        <div class="col-md-7">
                                            <input type="checkbox" name="agent_referral" value="1">
                                        </div>
                                        <div class="clearfix"></div><br>
                                    </div>

                                    <div id="target" hidden>
                                        <div class="col-md-3">Target</div>
                                        <div class="col-md-7">
                                            <input class="pull-left" type="checkbox" id="checkTarget" onclick="showTarget()">
                                            <div class="col-sm-10">
                                                <input type="text" name="target" class="form-control" id="targetField" style="display:none">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div><br>
                                    </div>

                                    <div id="ag_code" hidden>
                                        <div class="col-md-3">Agent Code</div>
                                        <div class="col-md-7">
                                            <input type="text" id="agent_code" name="agent_code" class="form-control" value="{{ old('agent_code') }}">
                                        </div>
                                        <div class="clearfix"></div><br>
                                    </div>

                                    <div id="en_admin" hidden>
                                        <div class="col-md-3">Encoder Admin:</div>
                                        <div class="col-md-7">
                                            <input type="checkbox" name="encoder_admin" value="1">
                                        </div>
                                        <div class="clearfix"></div><br>
                                    </div>

                                    <div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-7 text-right">
                                            <button class="btn btn-sm btn-primary">Submit <span class='fa fa-plus-circle'></span> </button>
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
    function showTarget() {
      var checkBox = document.getElementById("checkTarget");
      var target = document.getElementById("targetField");
      if (checkBox.checked == true){
        target.style.display = "block";
      } else {
        target.style.display = "none";
      }
    }

    function roleValue() {
        var roleValue = $('#roles').val();

        if(roleValue == "user") {
            $('#en_admin').hide();
            $('#ag_code').show();
            $('#ag_ref').show();
            $('#target').show();
        } else if(roleValue == "encoder") {
            $('#agent_code').val('');
            $('#targetField').val('');
            $('input[type="checkbox"]').prop('checked', false);
            $('#target').hide();
            $('#ag_ref').hide();
            $('#ag_code').hide();
            $('#en_admin').show();
        } else if(roleValue == "administrator") {
            $('#agent_code').val('');
            $('#targetField').val('');
            $('input[type="checkbox"]').prop('checked', false);
            $('#ag_ref').hide();
            $('#en_admin').hide();
            $('#target').hide();
            $('#ag_code').hide();
        }
    }

</script>
@endsection
