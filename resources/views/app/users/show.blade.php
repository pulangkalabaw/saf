@extends ('layouts.app')

@section('content')

	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Users</li>
			<li class="">
				<a href="{{ route('app.users.index') }}">User Accounts</a>
			</li>
			<li class="active">Create</li>
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
									<a href="{{ route('app.users.edit', $user->id) }}" class="btn btn-xs btn-default">
										<span class='fa fa-edit'></span>
									</a>
									<a href="{{ route('app.users.index') }}" class="btn btn-xs btn-default">
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
											{{ $user->fname }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3">Last name:</div>
										<div class="col-md-7">
											{{ $user->lname }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3">Email:</div>
										<div class="col-md-7">
											{{ $user->email }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3">Role:</div>
										<div class="col-md-7">
											{{ strtoupper(base64_decode($user->role)) }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									@if ($user->agent_code)
										<div id="code">
											<div class="col-md-3">Agent Code:</div>
											<div class="col-md-7">
												{{ $user->agent_code }}
											</div>
											<div class="clearfix"></div><br>
										</div>
									@endif

									<div>
										<div class="col-md-3">Status:</div>
										<div class="col-md-7">
											{{ ($user->isActive == 1) ? 'Activated' : 'Deactivated' }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									@if($user->target)
										<div>
											<div class="col-md-3">Target:</div>
											<div class="col-md-7">
												{{ $user->target }}
											</div>
										</div>
										<div class="clearfix"></div><br>
									@endif


									{{-- If agent --}}
									@if ($data['_a'])
										<div>
											<div class="col-md-3">Team(s):</div>
											<div class="col-md-7">
												@foreach ($data['_a'] as $key => $team)
													<a href="{{ route('app.teams.show', $team['id']) }}">{{ ucfirst($team['team_name']) }}</a>
													{{ ($key != (count($data['_a']) - 1)) ? ',' : '' }}
												@endforeach
											</div>
										</div>
									@elseif ($data['_t'])
										<div>
											<div class="col-md-3">Team(s):</div>
											<div class="col-md-7">
												@foreach ($data['_t'] as $key => $team)
													<a href="{{ route('app.teams.show', $team['id']) }}">{{ ucfirst($team['team_name']) }}</a>
													{{ ($key != (count($data['_t']) - 1)) ? ',' : '' }}
												@endforeach
											</div>
										</div>
									@elseif ($data['_c'])
										<div class="col-md-3">Cluster(s):</div>
										<div class="col-md-7">
											@foreach ($data['_c'] as $key => $cluster)
												<a href="{{ route('app.clusters.show', $cluster['id']) }}">{{ ucfirst($cluster['cluster_name']) }}</a>
												{{ ($key != (count($data['_c']) - 1)) ? ',' : '' }}
											@endforeach
										</div>
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
