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
									<a href="{{ route('app.teams.edit', $team->id) }}" class="btn btn-xs btn-default">
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
												No cluster
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
												No cluster
											@endif
										</div>
									</div>
									<div class="clearfix"></div>
									<hr>


									<div>
										<div class="col-md-3">Team code</div>
										<div class="col-md-7">
											{{ $team->team_id }}
										</div>
									</div>
									<div class="clearfix"></div><br>

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

											@if(!empty($team->tl_ids))
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


											{{-- <a href="{{ route('app.users.show', $team->getTeamLeader->id) }}">{{ $team->getTeamLeader->fname . ' ' . $team->getTeamLeader->lname }}</a> --}}
										</div>
									</div>
									<div class="clearfix"></div><br>


									<div id="code">
										<div class="col-md-3">Agent Code</div>
										<div class="col-md-7">
											@if(!empty($team->agent_ids))
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
	</div>
@endsection
