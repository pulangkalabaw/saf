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
								<div class="col-md-8 col-xs-8">
									<h3 class="panel-title">
										Applications
									</h3>
								</div>
								<div class="col-md-4 col-xs-4 text-right">

									@if (count(checkPosition(auth()->user(), ['tl', 'cl'], true)) != 0)
										<a href="{{ route('app.applications.create') }}" class="btn btn-sm btn-primary">
											<span class='fa fa-plus-circle'></span> Add Application
										</a>
									@endif

								</div>
							</div>
						</div>
						<div class="panel-body">
							<div class="row">

								@include('includes.filter')

								<div class="col-md-4 col-xs-12">
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
								<div class="col-md-2 col-xs-12"></div>
								<div class="col-md-6 col-xs-12">
									<form action="{{ url()->current() }}" method="GET">
										<div class="input-group">
											<input type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control"
											placeholder="Search for customer, teams, sr and so">
											<span class="input-group-btn">
												<button class="btn btn-primary"><span class='fa fa-search'></span> </button>
											</span>
										</div>
									</form>
								</div>
							</div>
							<div class="clearfix"></div><br>

							{{--
							Desktop View
							--}}

							<div id="div-md" class="table-responsive">
								<table class="table table-hovered table-striped">
									<thead>
										<tr>
											<th>
												Customer
												<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'customer_name', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
													<span class='fa fa-sort'></span>
												</a>
											</th>
											<th>Team</th>
											<th>
												SR
												<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'sr_no', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
													<span class='fa fa-sort'></span>
												</a>
											</th>
											<th>
												SO
												<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'so_no', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
													<span class='fa fa-sort'></span>
												</a>
											</th>
											<th>Agent</th>
											<th>
												Date
												<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'created_at', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
													<span class='fa fa-sort'></span>
												</a>
											</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($applications as $application)
											<tr>
												<td>
													<a data-toggle="tooltip" title="View Application" href="{{ route('app.applications.show', $application->application_id) }}">
														<span class="fa fa-sign-in"></span>
														{{ $application->customer_name }}
													</a>
												</td>
												<td>
													{{ $application->getTeam->team_name }}
												</td>
												<td>{{ $application->sr_no == '' ? '-' : $application->sr_no }}</td>
												<td>{{ $application->so_no == '' ? '-' : $application->so_no }}</td>
												<td>{{ $application->getAgentName->fname . ' ' . $application->getAgentName->lname }}</td>
												<td>{{ $application->created_at->diffForHumans() }}</td>
												<td>{{ ucfirst($application->status) }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>

							{{--
							Mobile and Table View
							--}}
							<div id="div-sm">
								@foreach($applications as $application)
									<div class="breadcrumb">
										<h4>
											Customer: {{ $application->customer_name }}
										</h4>
										Team: {{ $application->getTeam->team_name }} <br />
										SR: {{ $application->sr_no }} <br />
										SO: {{ $application->so_no }} <br />
										Agent: {{ $application->getAgentName->fname }} {{ $application->getAgentName->lname }} <br />
										Date: {{ $application->created_at->diffForHumans() }} <br />
										Status: {{ ucfirst($application->status) }} <br />

										<hr>
										<a data-toggle="tooltip" title="View Application" href="{{ route('app.applications.show', $application->application_id) }}">

											<a data-toggle="tooltip" title="View Application" href="{{ route('app.applications.show', $application->application_id) }}" class="btn btn-sm btn-warning">
												<span class="fa fa-eye"></span>
												View
											</a>
										</a>
									</div>
								@endforeach
							</div>


							<br>
							<div class="row">
								<div class="col-md-8 col-xs-8">
									{{ $applications->appends(request()->input())->links() }}
								</div>
								<div class="col-md-4 col-xs-4 text-right">
									Total <b>{{ $applications_total }}</b> result(s)
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<style>
	#div-sm { display: none;}

	@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px)  {
		#div-md {
			display: none;
		}
		#div-sm {
			display: block;
		}
	}
</style>

@endsection
