@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Users</li>
        <li class="">
            <a href="{{ route('app.teams.index') }}">Teams</a>
        </li>
        <li class="">
            {{ ucfirst($team->team_name) }}
        </li>
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
                                    TEAM
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.teams.edit', $team->team_id) }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-edit'></span> 
                                </a>
                                <a href="{{ route('app.teams.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span> 
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-7">

                                <div>
                                    <div class="col-md-3">Cluster name</div>
                                    <div class="col-md-7">
                                        @if (count($team->getCluster($team->team_id)) != 0)
                                        {{  $team->getCluster($team->team_id)[0]['cluster_name'] }}
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                <div>
                                    <div class="col-md-3">Cluster Leader</div>
                                    <div class="col-md-7">
                                        @if (count($team->getCluster($team->team_id)) != 0)
                                        {{  $team->getCluster($team->team_id)[0]['get_cluster_leader']['fname'] }}
                                        {{  $team->getCluster($team->team_id)[0]['get_cluster_leader']['lname'] }}
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <hr>
                                <div>
                                    <div class="col-md-3">Team name</div>
                                    <div class="col-md-7">
                                        {{ ucfirst($team->team_name) }}
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                <div>
                                    <div class="col-md-3">Team leader</div>
                                    <div class="col-md-7">
                                        <a href="{{ route('app.users.show', $team->getTeamLeader->id) }}">{{ $team->getTeamLeader->fname . ' ' . $team->getTeamLeader->lname }}</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                <div>
                                    <div class="col-md-3">Encoder(s)</div>
                                    <div class="col-md-7">
                                        @foreach ($team->getEncoder($team->encoder_ids) as $key => $encoder)
                                        <a href="{{ route('app.users.show', $encoder->id) }}">
                                            {{ $encoder->fname }}
                                            {{ $encoder->lname }}
                                        </a>
                                        {{ ($key != (count($team->getEncoder($team->encoder_ids)) - 1)) ? ', ' : '' }}
                                        @endforeach
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                @if ($team->agent_code)
                                <div id="code">
                                    <div class="col-md-3">Agent Code</div>
                                    <div class="col-md-7">
                                        {{ $team->getAgentCode->fname . " " . $team->getAgentCode->lname }}
                                    </div>
                                    <div class="clearfix"></div><br>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
