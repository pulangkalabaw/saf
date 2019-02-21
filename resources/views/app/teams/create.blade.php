@extends ('layouts.app')

@section ('styles')

<link href="{{ asset('assets/libs/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/selectize/css/selectize.default.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet">
@endsection


@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Users</li>
        <li class="">
            <a href="{{ route('app.teams.index') }}">Teams</a>
        </li>
        <li class="active">Create</li>
    </ol>
</div>
<div class="container-fluid half-padding">
    <div class="template template__blank">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="panel-title">
                                    Teams
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.teams.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('app.teams.store') }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">Team name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="team_name" id="" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Team Leader</div>
                                        <div class="col-md-7">
                                            <select name="tl_id" id="" class="form-control" required="">
                                                @foreach ($users->getAvailableTeamLeader() as $tl)
                                                <option value="{{ $tl->id }}">{{ $tl->fname . ' ' . $tl->lname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Agent</div>
                                        <div class="col-md-7">
											{{ $users->getAvailableAgent() }}
											
                                            <select name="agent_code[]" id="" class="form-control selectpicker" multiple="" required="">
                                                @foreach ($users->getAvailableAgent() as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->fname . ' ' . $agent->lname }}</option>
                                                @endforeach
                                            </select>
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
<script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/template/controls.js') }}"></script>
@endsection
