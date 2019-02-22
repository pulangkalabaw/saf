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
            <a href="{{ route('app.clusters.index') }}">Clusters</a>
        </li>
        <li class="">
            {{ ucfirst($cluster->cluster_name) }}
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
                                    Cluster
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.clusters.show', $cluster->id) }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-eye'></span>
                                </a>
                                <a href="{{ route('app.clusters.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('app.clusters.update', $cluster->id) }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">Cluster code</div>
                                        <div class="col-md-7">
                                            <input type="text" name="cluster_id" id="" class="form-control" value="{{ $cluster->cluster_id }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

									<div>
                                        <div class="col-md-3">Cluster name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="cluster_name" id="" class="form-control" value="{{ $cluster->cluster_name }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Cluster Leader</div>
                                        <div class="col-md-7">
											<select name="cl_ids[]" id="" class="form-control selectpicker" multiple>
												{{-- Available --}}
												@foreach ($cluster_leaders as $cl)
													@if (!empty($cluster->cl_ids))
														<option {{ in_array($cl->id, $cluster->cl_ids) ? 'selected' : '' }} value="{{ $cl->id }}">
															{{ $cl->fname . ' ' . $cl->lname }}
														</option>
													@else
														<option value="{{ $cl->id }}">
															{{ $cl->fname . ' ' . $cl->lname }}
														</option>
													@endif
												@endforeach
											</select>

                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Team(s)</div>
                                        <div class="col-md-7">
                                            <select name="team_ids[]" id="" class="form-control selectpicker" multiple>
												@foreach ($teams as $team)
													@if (!empty($cluster->team_ids))
														<option {{ in_array($team->id, $cluster->team_ids) ? 'selected' : '' }} value="{{ $team->id }}">
															{{ $team->team_name }}
														</option>
													@else
														<option value="{{ $team->id }}">
															{{ $team->team_name }}
														</option>
													@endif
												@endforeach
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
