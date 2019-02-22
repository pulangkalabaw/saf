@extends ('layouts.app')

@section('content')

	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Users</li>
			<li class="active">Clusters</li>
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
										Clusters
									</h3>
								</div>
								<div class="col-md-4 text-right">
									<a href="{{ route('app.clusters.create') }}" class="btn btn-xs btn-default">
										<span class='fa fa-plus-circle'></span>
									</a>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-4 col-xs-4">
									<div class="form-inline">
										<div class="form-group">
											<label>Number of rows: </label>

											<select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value)" class="form-control">
												<option {{ !empty(request()->get('show') && request()->get('show') == 10) ? 'selected' : ''  }}
													value="{{ request()->fullUrlWithQuery(['show' => '10']) }}">10</option>
													<option {{ !empty(request()->get('show') && request()->get('show') == 25) ? 'selected' : ''  }}
														value="{{ request()->fullUrlWithQuery(['show' => '25']) }}">25</option>
														<option {{ !empty(request()->get('show') && request()->get('show') == 50) ? 'selected' : ''  }}
															value="{{ request()->fullUrlWithQuery(['show' => '50']) }}">50</option>
															<option {{ !empty(request()->get('show') && request()->get('show') == 100) ? 'selected' : ''  }}
																value="{{ request()->fullUrlWithQuery(['show' => '100']) }}">100</option>
															</select>

														</div>
													</div>
												</div>

												<div class="col-md-3"></div>
												<div class="col-md-5 col-xs-5">
													<form action="{{ url()->current() }}" method="GET">
														<div class="input-group">
															<input type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control" placeholder="Search for Cluster name, Cluster Leader">
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
															Cluster code
														</th>
														<th>
															Cluster name
															<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'cluster-name', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
																<span class='fa fa-sort'></span>
															</a>
														</th>
														<th>
															Cluster Leader
															<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'cluster-leader', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
																<span class='fa fa-sort'></span>
															</a>
														</th>
														<th width="40%">Team(s)</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($clusters as $cluster)
														<tr>
															<td>{{ $cluster->cluster_id }}</td>
															<td>{{ $cluster->cluster_name }}</td>
															<td>
																{{-- ***** CLUSTER LEADERS ***** --}}
																@if(!empty($cluster->cl_ids))

																	{{-- Input <BR> every 3 encoders --}}
																	@php $counter = 0; @endphp {{-- Counter --}}
																	@foreach ($cluster->getClusterLeader($cluster->cl_ids) as $key => $cl)

																		@php $counter++; @endphp {{-- Increment Counter --}}

																		<a href="{{ route('app.users.show', $cl->id) }}">
																			{{ $cl->fname }}
																			{{ $cl->lname }}
																		</a>

																		{{-- Comma --}}
																		{{ ($key != (count($cluster->getClusterLeader($cluster->cl_ids)) - 1)) ? ',' : '' }}

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

																{{-- ***** CLUSTER LEADERS ***** --}}
																@if(!empty($cluster->team_ids))

																	{{-- Input <BR> every 3 encoders --}}
																	@php $counter = 0; @endphp {{-- Counter --}}
																	@foreach ($cluster->getTeams($cluster->team_ids) as $key => $team)

																		@php $counter++; @endphp {{-- Increment Counter --}}

																		<a href="{{ route('app.teams.show', $team->id) }}">
																			{{ $team->team_name }}
																		</a>

																		{{-- Comma --}}
																		{{ ($key != (count($cluster->getTeams($cluster->team_ids)) - 1)) ? ',' : '' }}

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
																<a data-toggle="tooltip" title="View Cluster" href="{{ route('app.clusters.show', $cluster->id) }}" class="btn btn-warning btn-xs"><span class='fa fa-eye'></span></a>
																<a data-toggle="tooltip" title="Edit Cluster" href="{{ route('app.clusters.edit', $cluster->id) }}" class="btn btn-success btn-xs"><span class='fa fa-edit'></span></a>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
											<br>
											<div class="row">
												<div class="col-md-10">
													{{ $clusters->appends(request()->input())->links() }}
												</div>
												<div class="col-md-2 text-right">
													Total <b>{{ $clusters_total }}</b> result(s)
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
