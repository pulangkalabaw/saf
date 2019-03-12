@extends ('layouts.app')

@section('content')

	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Users</li>
			<li class="active">Teams</li>
		</ol>
	</div>
	<div class="container-fluid half-padding">
		<div class="template template__blank">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-8 col-xs-8">
									<h3 class="panel-title">
										Teams
									</h3>
								</div>
								<div class="col-md-4 col-xs-4 text-right">
									<a href="{{ route('app.teams.create') }}" class="btn btn-sm btn-primary">
										<span class='fa fa-plus-circle'></span>
									</a>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div class="row">

								@include('includes.filter')

								<div class="col-md-4 col-xs-4">
									<div class="form-inline">
										<div class="form-group">
											<label>Number of rows: </label>
											<select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value)" class="form-control">
												<option {{ !empty(request()->get('show') && request()->get('show') == 10) ? 'selected' : ''  }} value="{{ request()->fullUrlWithQuery(['show' => '10']) }}">
													10
												</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 25) ? 'selected' : ''  }} value="{{ request()->fullUrlWithQuery(['show' => '25']) }}">
													25
												</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 50) ? 'selected' : ''  }} value="{{ request()->fullUrlWithQuery(['show' => '50']) }}">
													50
												</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 100) ? 'selected' : ''  }} value="{{ request()->fullUrlWithQuery(['show' => '100']) }}">
													100
												</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-3"></div>
								<div class="col-md-5 col-xs-5">
									<form action="{{ url()->current() }}" method="GET">
										<div class="input-group">
											<input type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control"
											placeholder="Search for team name and team code">
											<span class="input-group-btn">
												<button class="btn btn-primary"><span class='fa fa-search'></span> </button>
											</span>
										</div>
									</form>
								</div>
							</div>
							<div class="clearfix"></div><br>

							<table class="table table-hovered table-striped">
								<thead>
									<tr>
										<th>
											Team code
											<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'team_id', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
												<span class='fa fa-sort'></span>
											</a>
										</th>
										<th>Cluster name</th>
										<th>
											Team name
											<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'team_name', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
												<span class='fa fa-sort'></span>
											</a>
										</th>
										<th>
											Team Leader
										</th>
										<th>Agent</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($teams as $team)
										<tr>
											<td>
												{{ $team->team_id }}
											</td>
											<td>
												@if (count($team->getCluster($team->team_id)) != 0)
													<a href="{{ route('app.clusters.show', $team->getCluster($team->team_id)[0]['id']) }}">
														{{  $team->getCluster($team->team_id)[0]['cluster_name'] }}
													</a>
												@else
													No cluster
												@endif
											</td>
											<td>{{ $team->team_name }}</td>
											<td>

												{{-- ***** TEAM LEADERS ***** --}}
												@if(!empty($team->tl_ids))

													{{-- Input <BR> every 3 encoders --}}
													@php $counter = 0; @endphp {{-- Counter --}}
													@foreach ($team->getTeamLeader($team->tl_ids) as $key => $tl)

														@php $counter++; @endphp {{-- Increment Counter --}}

														<a href="{{ route('app.users.show', $tl->id) }}">
															{{ $tl->fname }}
															{{ $tl->lname }}
														</a>

														{{-- Comma --}}
														{{ ($key != (count($team->getTeamLeader($team->tl_ids)) - 1)) ? ',' : '' }}

														{{-- Check counter if 3 --}}
														@if ($counter == 3)
															{{-- if its 3, we need to change the value of counter to 0 then insert <BR> --}}
															@php $counter = 0; @endphp
															<br>
														@endif

													@endforeach
												@else
													Nothing selected
												@endif

											</td>
											<td>

												{{-- ***** AGENTS ***** --}}
												@if(!empty($team->agent_ids))

													{{-- Input <BR> every 3 encoders --}}
													@php $counter = 0; @endphp {{-- Counter --}}
													@foreach ($team->getAgents($team->agent_ids) as $key => $agent)

														@php $counter++; @endphp {{-- Increment Counter --}}

														<a href="{{ route('app.users.show', $agent->id) }}">
															{{ $agent->fname }}
															{{ $agent->lname }}
														</a>

														{{-- Comma --}}
														{{ ($key != (count($team->getAgents($team->agent_ids)) - 1)) ? ',' : '' }}

														{{-- Check counter if 3 --}}
														@if ($counter == 3)
															{{-- if its 3, we need to change the value of counter to 0 then insert <BR> --}}
															@php $counter = 0; @endphp
															<br>
														@endif

													@endforeach

												@else
													Nothing selected
												@endif

											</td>
											<td>
												<a data-toggle="tooltip" title="View Team" href="{{ route('app.teams.show', $team->id) }}" class="btn btn-warning btn-xs"><span class='fa fa-eye'></span></a>
												<a data-toggle="tooltip" title="Edit Team" href="{{ route('app.teams.edit', $team->id) }}" class="btn btn-success btn-xs"><span class='fa fa-edit'></span></a>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
							<br>
							<div class="row">
								<div class="col-md-8 col-xs-8">
									{{ $teams->appends(request()->input())->links() }}
								</div>
								<div class="col-md-4 col-xs-4 text-right">
									Total <b>{{ $teams_total }}</b> result(s)
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>


@endsection
