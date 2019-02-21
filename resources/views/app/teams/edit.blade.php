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
        <li class="">
            <a href="{{ route('app.teams.show', $team->team_id) }}">{{ ucfirst($team->team_name) }}</a>
        </li>
        <li class="active">Edit</li>
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
                                    Team
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.teams.show', $team->team_id) }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-eye'></span>
                                </a>
                                <a href="{{ route('app.teams.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('app.teams.update', $team->team_id) }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">Team name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="team_name" id="" class="form-control" value="{{ $team->team_name }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Team Leader</div>
                                        <div class="col-md-7">


                                            <select name="tl_id" id="" class="form-control" required="">
												<option selected value="{{ $team->tl_id }}">
													{{ $team->getTeamLeader->fname }}
													{{ $team->getTeamLeader->lname }}
												</option>
                                                @foreach ($users->getAvailableTeamLeader() as $tl)
                                                <option {{ $team->getTeamLeader->id == $tl->id ? 'selected' : '' }} value="{{ $tl->id }}">{{ $tl->fname . ' ' . $tl->lname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Agent</div>
                                        <div class="col-md-7">
                                            <select name="agent_code" id="" class="form-control selectpicker" required="" multiple>
                                                @foreach ($users->getAvailableAgent() as $agent)
                                                {{-- <option {{ $team->getAgentCode->agent_code == $agent->agent_code ? 'selected' : '' }} value="{{ $agent->agent_code }}">{{ $agent->fname . ' ' . $agent->lname }}</option> --}}
                                                <option {{ in_array($agent->id, $team->getAgents($team->agent_code)->map(function($r) {
                                                    return $r['id'];
                                                })->toArray()) ? 'selected' : '' }}
												 value="{{ $agent->id }}">{{ $agent->fname . ' ' . $agent->lname }}</option>
                                                @endforeach
												{{--
												@foreach ($users->getAvailableEncoder() as $encoder)
                                                <option {{ in_array($encoder->id, $team->getEncoder($team->encoder_ids)->map(function($r) {
                                                    return $r['id'];
                                                })->toArray()) ? 'selected' : '' }}
                                                value="{{ $encoder->id }}">{{ $encoder->fname . ' ' . $encoder->lname }}</option>
                                                @endforeach --}}
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
<script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/template/controls.js') }}"></script>
@endsection
