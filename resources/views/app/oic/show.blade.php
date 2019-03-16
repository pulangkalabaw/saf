@extends('layouts.app')
@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Home</li>
        <li class="">
            <a href="{{ route('app.oic.index') }}">Officer In Charge</a>
        </li>
        <li class="active">Show</li>
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
                                <a href="{{ route('app.oic.edit', $oic->id) }}" class="btn btn-sm btn-success">
                                    <span class='fa fa-edit'></span>
                                </a>
                                <a href="{{ route('app.oic.index') }}" class="btn btn-sm btn-default">
                                    <span class='fa fa-th-list'></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-7">

                                <div>
                                    <div class="col-md-3">First name:</div>
                                    <div class="col-md-7">
                                        {{ $oic->getAgent->fname }}
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                <div>
                                    <div class="col-md-3">Last name:</div>
                                    <div class="col-md-7">
                                        {{ $oic->getAgent->lname }}
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                <div>
                                    <div class="col-md-3">Cluster name:</div>
                                    <div class="col-md-7">
                                        {{ $oic->getCluster->cluster_name }}
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                <div>
                                    <div class="col-md-3">Team name:</div>
                                    <div class="col-md-7">
                                        {{ $oic->getTeam->team_name }}
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                <div>
                                    <div class="col-md-3">Date Assigned:</div>
                                    <div class="col-md-7">
                                        {{ $oic->assign_date }}
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>

                                <div>
                                    <div class="col-md-3">Assigned End:</div>
                                    <div class="col-md-7">
                                        {{ $oic->expired_at }}
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
