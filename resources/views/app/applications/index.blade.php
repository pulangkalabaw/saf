@extends ('layouts.app')

@section('content')

	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Home</li>
			<li class="active">Applications</li>
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
										Applications
									</h3>
								</div>
								<div class="col-md-4 text-right">

									@if (accesesControl(['user']))
										@if (count(checkPosition(auth()->user(), ['tl', 'cl'], true)) != 0)
										<a href="{{ route('app.applications.create') }}" class="btn btn-xs btn-default">
											<span class='fa fa-plus-circle'></span> Add Application
										</a>
										@endif
									@endif

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
												<option {{ !empty(request()->get('show') && request()->get('show') == 10) ? 'selected' : ''  }} value="{{ route('app.applications.index') }}?show=10">10</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 25) ? 'selected' : ''  }} value="{{ route('app.applications.index') }}?show=25">25</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 50) ? 'selected' : ''  }} value="{{ route('app.applications.index') }}?show=50">50</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 100) ? 'selected' : ''  }} value="{{ route('app.applications.index') }}?show=100">100</option>
											</select>

										</div>
									</div>
								</div>
								<div class="col-md-2 col-xs-2"></div>
								<div class="col-md-6 col-xs-6">
									<form action="{{ url()->current() }}" method="GET">
										<div class="input-group">
											<input type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control" placeholder="Search for Application #, Encoder, Team, Customer and SR #">
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
											<th>Application #</th>
											<th>Team</th>
											<th>Customer</th>
											<th>SR #</th>
											<th>Agent</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($applications as $application)
											<tr>
												<td>
													<a data-toggle="tooltip" title="View Application" href="{{ route('app.applications.show', $application->application_id) }}">
														<span class="fa fa-sign-in"></span>
														{{ $application->application_id }}
													</a>
												</td>
												<td>{{ $application->getTeam->team_name }}</td>
												<td>{{ $application->customer_name }}</td>
												<td>{{ $application->sr_no == '' ? '-' : $application->sr_no }}</td>
												<td>{{ $application->getAgentName->fname . ' ' . $application->getAgentName->lname }}</td>
												<td>{{ ucfirst($application->status) }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<br>
							<div class="row">
								<div class="col-md-10">
									{{ $applications->appends(request()->input())->links() }}
								</div>
								<div class="col-md-2 text-right">
									Total <b>{{ $applications_total }}</b> result(s)
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


@endsection
