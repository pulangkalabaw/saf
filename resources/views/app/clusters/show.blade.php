@extends ('layouts.app')

@section('content')

	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Users</li>
			<li class="">
				<a href="{{ route('app.clusters.index') }}">Clusters</a>
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
								<div class="col-md-4 text-right">
									<a href="{{ route('app.clusters.edit', $cluster->cluster_id) }}" class="btn btn-xs btn-default">
										<span class='fa fa-edit'></span>
									</a>
									<a href="{{ route('app.clusters.index') }}" class="btn btn-xs btn-default">
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
											{{ ucfirst($cluster->cluster_name) }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3">Cluster leader</div>
										<div class="col-md-7">

											@if(!empty($cluster->cl_ids))
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


											{{-- <a href="{{ route('app.users.show', $cluster->getClusterLeader->id) }}">{{ $cluster->getClusterLeader->fname . ' ' . $cluster->getClusterLeader->lname }}</a> --}}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3">Team(s)</div>
										<div class="col-md-7">
											@if(!empty($cluster->team_ids))
												@foreach ($cluster->getTeams($cluster->team_ids) as $key => $team)

													<a href="{{ route('app.teams.show', $team->team_id) }}">
														{{ $team->team_name }}
													</a>
													{{ ($key != (count($cluster->getTeams($cluster->team_ids)) - 1)) ? ',' : '' }}

												@endforeach
											@else
												Nothing selected
											@endif
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
