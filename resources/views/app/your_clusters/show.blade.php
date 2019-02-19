@extends ('layouts.app')

@section('content')

    <div class="main-heading">
        <ol class="breadcrumb">
            <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
            <li class="">Home</li>
            <li class="">Your Cluster
            </li>
            <li class="active">
                {{ ucfirst($cluster->cluster_name) }}
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
                                        Cluster
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">Cluster name</div>
                                        <div class="col-md-7">
                                            {{ ucfirst($cluster->cluster_name) }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Cluster leader</div>
                                        <div class="col-md-7">
                                            {{ $cluster->getClusterLeader->fname . ' ' . $cluster->getClusterLeader->lname }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Team(s)</div>
                                        <div class="col-md-7">
                                            @foreach ($cluster->getTeams($cluster->team_ids) as $key => $team)

                                                {{ $team->team_name }}
                                                {{ ($key != (count($cluster->getTeams($cluster->team_ids)) - 1)) ? ',' : '' }}

                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
