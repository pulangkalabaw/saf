@extends('layouts.app')
@section('content')
    <div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Home</li>
			<li class="active">Officer In Charge</li>
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
										Officer In Charge
									</h3>
								</div>
                                <div class="col-md-4 text-right">
                                    @if (accessControl(['administrator','user']))
                                        @if (count(checkPosition(auth()->user(), ['tl', 'cl'], true)) != 0 || accessControl(['administrator']))
                                            <a href="{{ route('app.oic.create') }}" class="btn btn-xs btn-default">
                                                <span class='fa fa-plus-circle'></span> Add Application
                                            </a>
                                        @endif
                                    @endif
								</div>
							</div>
						</div>
						<div class="panel-body">
                                <div class="row">
                                    @include('includes.notif')
                                    @include('includes.filter')

    								<div class="col-md-4 col-xs-4">
    									<div class="form-inline">
    										<div class="form-group">
    											<label>Number of rows: </label>

    											<select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value)" class="form-control">
    												<option {{ !empty(request()->get('show') && request()->get('show') == 10) ? 'selected' : ''  }} value="{{ route('app.oic.index') }}?show=10">10</option>
    												<option {{ !empty(request()->get('show') && request()->get('show') == 25) ? 'selected' : ''  }} value="{{ route('app.oic.index') }}?show=25">25</option>
    												<option {{ !empty(request()->get('show') && request()->get('show') == 50) ? 'selected' : ''  }} value="{{ route('app.oic.index') }}?show=50">50</option>
    												<option {{ !empty(request()->get('show') && request()->get('show') == 100) ? 'selected' : ''  }} value="{{ route('app.oic.index') }}?show=100">100</option>
    											</select>

    										</div>
    									</div>
    								</div>
    								<div class="col-md-2 col-xs-2"></div>
    								<div class="col-md-6 col-xs-6">
    									<form action="{{ url()->current() }}" method="GET">
    										<div class="input-group">
    											<input type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control" placeholder="Search...">
    											<span class="input-group-btn">
    												<button class="btn btn-primary"><span class='fa fa-search'></span> </button>
    											</span>
    										</div>
    									</form>
    								</div>
    							</div>
    							<div class="clearfix"></div><br>
    						<div class="table-responsive">
    							<table class="table table-hovered table-striped">
    								<thead>
    									<tr>
                                            <th>Agent Name</th>
                                            <th>Cluster Name</th>
                                            <th>Team Name</th>
                                            <th>Assign Date</th>
    									</tr>
    								</thead>
    								<tbody>
                                        @if(count($oics) == 0)
                                            <tr>
                                                <td colspan="4" style="text-align:center;">No OIC Found!</td>
                                            </tr>
                                        @else
                                            @foreach($oics as $oic)
                                                <tr>
                                                    <td>
                                                        <a data-toggle="tooltip" title="View OIC" href="{{ route('app.oic.show', $oic->id) }}">
															<span class="fa fa-sign-in"></span>
                                                            {{ $oic->getAgent->fname }} {{ $oic->getAgent->lname }}
														</a>
                                                    </td>
                                                    <td>
                                                        {{ $oic->getCluster->cluster_name }}
                                                    </td>
                                                    <td>
                                                        {{ $oic->getTeam->team_name }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($oic->assign_date)->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
    								</tbody>
    							</table>
    						</div>
    						<br>
    						<div class="row">
    							<div class="col-md-10">
    							{{ $oics->appends(request()->input())->links() }}
    							</div>
    							<div class="col-md-2 text-right">
    								Total <b>{{ $total }}</b> result(s)
    							</div>
    						</div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
