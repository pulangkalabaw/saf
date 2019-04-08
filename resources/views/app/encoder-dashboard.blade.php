@extends ('layouts.app')

@section ('styles')

@endsection

@section('content')
	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Users</li>
			<li class="active">Dashboard</li>
		</ol>
	</div>
	<!-- {{ base64_encode('administrator') }} -->
	<div class="container-fluid half-padding">
		<div class="template template__blank">
            <div class="row">
                <div class="col-md-3">
                    <div class="panel bg-info">
                        <div class="panel-body text-center">
                            <h3>Total Application</h3>
                            <h2>{{ $count['total_applications'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-body text-center">
                            <h3>Not Encoded</h3>
                            <h2>{{ $count['not_encoded'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-body text-center">
                            <h3>Total Encoded</h3>
                            <h2>{{ $count['total_encoded'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-body text-center">
                            <h3>Your Encoded</h3>
                            <h2>{{ $count['your_encoded'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

			<!-- Product Chart and Application Chart -->
			<div class="row">

			@if (count($_w_prod_data['prod']) != 0)
				@if ($_w_prod_data['count'] != 0)
					<div class="col-md-6">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">
									Product Chart
								</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-8">
										<canvas id="product_chart"></canvas>
									</div>
									<div class="col-md-4">
										<h4>Product chart</h4>
										<ul>
											@foreach ($_w_product_chart as $key => $pc)
												@if ($pc['count'] != 0)
													<li>
														{{ productNameConvert($pc['product']) }}:
														{{ $pc['count'] }}
													</li>
												@endif
											@endforeach
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- <div class="clearfix"></div><Br /> -->
				@endif
			@endif


			{{-- Appplication Status --}}
			@if (count($_w_application_status_counter['application_status_c']) != 0)
				@if ($_w_application_status_counter['application_status_c'][0]['count'] != 0)
					<div class="col-md-6">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">
									Application Status
								</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-8">
										<canvas id="application_status"></canvas>
									</div>
									<div class="col-md-4">
										<h4>Application statuses</h4>
										<ul>
											@foreach ($_w_application_status_counter['application_status_c'] as $key => $asc)
												@if ($asc['count'] != 0)
													<li>
														{{ ucfirst($asc['status']) }}:
														{{ $asc['count'] }}
													</li>
												@endif
											@endforeach
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- <div class="clearfix"></div><Br /> -->
				@endif
			@endif
			</div>
			<!-- End of Product Chart and Application Chart -->


			<!-- PAT WIDGET  -->
			@if( (!empty(checkPosition(auth()->user(), ['tl','cl'])) || accessControl(['administrator','user'])) && isset($heirarchy) )
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">Applications</h3>
							</div>
							<div class="panel-body">
								@if(!isset($heirarchy['clusters'][0]['cluster_name']))
									@if(checkPosition(auth()->user(), ['a']))
										<h5 class="text-center text-info"><i class="fa fa-info-circle"></i> *Agents dashboard coming soon*</h5>
									@else
										<h5 class="text-center text-warning"><i class="fa fa-warning"></i> You have no clusters or team</h5>
									@endif
								@endif
								<!-- <h5>As of {{ now()->format('M d y g:i a') }} - {{ now()->format('M d y g:i a') }}</h5> -->
								@if(isset($heirarchy['clusters']))
									@if(count(checkPosition(auth()->user(), ['tl','cl'], true)) || accessControl(['administrator']))
										<div class="row">
											<form action="{{ route('app.dashboard') }}" method="get">
												<div class="col-md-2 col-xs-5">From<input type="date" name="from" value="{{ request()->get('from') }}" class="form-control input-sm" required></div>
												<div class="col-md-2 col-xs-5">To<input type="date" name="to" value="{{ request()->get('to') }}" class="form-control input-sm" required></div>
												<div class="col-md-2 col-xs-2"><br><button class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button></div>
											</form>
										</div>
									@endif
								@endif
								<!-- PUT FOREACH TEAM  -->
								@foreach($heirarchy['clusters'] as $clus)
									@if($clus) <!-- FOR CATCHING NULL ERRORS -->
										<div class="container-fluid">
											<!-- <div class="col-md-4" style="float:none;margin: 0 auto;"> -->
											<div class="col-md-12">
												<h4 class="alert alert-primary text-center">{{ $clus->cluster_name }}</h4>
												<h5 class="text-center">As of {{ $clus->date }}</h5>
											</div>
											<!-- PUT FOREACH TEAM  -->
											@foreach($clus->teams as $team)
												@if($team)
													<div class="col-md-3">
														<div class="breadcrumb">
															<h5><b>{{ $team->team_name }}</b></h5>
															<p>New Applications: <b>₱ {{ number_format((float)$team->getallsafthiscutoff['new'],2) }}</b></p>
															<p>Activated Applications: <b class="text-info">₱ {{ number_format((float)$team->getallsafthiscutoff['activated'],2) }}</b></p>
															<p>Paid Applications: <b class="text-primary">₱ {{ number_format((float)$team->getallsafthiscutoff['paid'],2) }}</b></p>
															<p>Total Target: <b class="text-secondary">₱ {{ number_format($team->total_based_target,2) }}</b></p>
															<p>Accumulated Target: <b class="text-success">₱ {{ number_format((float)$team->total_target,2) }}</b> <small class="text-muted">({{ (float)number_format($team->pat, 2) }}%)</small></p>
														</div>
													</div>
												@endif
											@endforeach
											<!-- PUT ENDFOREACH TEAM -->
										</div>
									@endif
								@endforeach
								<!-- PUT ENDFOREACH CLUSTER  -->
							</div>
						</div>
					</div>
				</div>
			@endif
			<!-- PAT WIDGET -->

			<!-- ATTENDANCE TODAY WIDGET  -->
			@if(auth()->user()->role != base64_encode('encoder'))
			<div class="row">
				<div class="col-md-12">

				  <div class="panel panel-info">
					  <div class="panel-heading">
						  <h3 class="panel-title">Today's Team Attendance</h3>
					  </div>
					  <div class="panel-body">
						  @if(!empty($heirarchy))
	  					  @foreach($heirarchy['clusters'] as $clus)
	  						  @if(!empty($clus))
	  							  <div class="col-md-12">
	  								<h4>{{ $clus->cluster_name }} <!--<small>Total Agents: 100</small>--></h4>
	  							  </div>
	  							  <!-- TEAMS  -->
	  							  @if(!empty($clus->teams))
	  								@foreach($clus->teams as $team)
	  									@if(!empty($team))
	  										<div class="{{ (  count(checkPosition(auth()->user(), ['tl','agent'], true))  ) ? 'col-md-3' : 'col-md-4'  }}">
	  										  <div class="breadcrumb">
	  											<h5>{{ $team->team_name }} <br><small>Total Agents: {{ $team->total_agents }}</small></h5>
	  											<!-- <p>TL Present: <b class="{{ ($team->tlattendance > 0) ? 'text-success' : 'text-danger' }}">{{ $team->tlattendance }}<span class="text-muted">/{{ $team->totaltl }}</span></b></p> -->
	  											<p>Present: <b class="text-success">{{ $team->attendance['present'] }}</b></p>
	  											<p>Absent: <b class="text-danger">{{ $team->attendance['absent'] }}</b></p>
	  											<p>Unkown: <b class="text-warning">{{ $team->attendance['unkown'] }}</b></p>
	  										  </div>
	  										</div>
	  									@endif
	  								@endforeach
	  							  @endif
	  							  <!-- TEAMS -->
	  						  @endif
	  					  @endforeach
	  				  @endif
					  </div>
				  </div>

				</div>
			</div>
			<!-- END ATTENDANCE TODAY WIDGET  -->
			@endif
		</div>
	</div>
@endsection

@section ('scripts')

	<script src="{{ asset('assets/js/Chart.bundle.js') }}"></script>
	<script src="{{ asset('assets/js/Chart.js') }}"></script>

	<script>

	{{--
	***
	Application Status
	***
	--}}
	$(document).ready(function(){

	var aps_ctx = document.getElementById("application_status").getContext('2d');

	var aps_labels = {!! collect($_w_application_status_counter['application_status_c'])->map(function ($r) {
		// return '('.$r['count'].') '. ucfirst($r['status']);
		return ucfirst($r['status']);
	}) !!};

	var aps_data = {!! collect($_w_application_status_counter['application_status_c'])->map(function ($r) {
		return ucfirst($r['count']);
	}) !!}


	var aps_myChart = new Chart(aps_ctx, {
		type: 'pie',
		data: {
			labels: aps_labels,
			datasets: [
				{
					label: 'Application counter',
					data: aps_data,
					backgroundColor:[
						'rgb(220,20,60)',
						'rgb(30,144,255)',
						'rgb(173,255,47)',
						'rgb(143,188,143)',
					],
					borderWidth: 3
				}
			]
		},
		options: {
			scales: {
				xAxes: [{
					ticks: {
						beginAtZero:true,
					}
				}],
				yAxes: [{
					ticks: {
						maxRotation: 30,
						minRotation: 30
					}
				}] },
				responsive: true,
			},
		});


		{{--
		***
		Product Chart
		***
		--}}

		var acbc_ctx = document.getElementById("product_chart").getContext('2d');

		var acbc_labels = {!! collect(array_filter(collect($_w_product_chart)->filter(function ($r) {
			if ($r['count'] != 0) {
				return productNameConvert($r['product'])."llll";
			}
		})->pluck('product')->toArray())) !!};

		var acbc_data = {!! collect(array_filter(collect($_w_product_chart)->filter(function ($r) {
			if ($r['count'] != 0) {
				return $r['count'];
			}
		})->pluck('count')->toArray())) !!}


		var acbc_myChart = new Chart(acbc_ctx, {
			type: 'pie',
			data: {
				labels: acbc_labels,
				datasets: [
					{
						label: 'Application counter',
						data: acbc_data,
						backgroundColor:[
							'rgb(220,20,60)',
							'rgb(30,144,255)',
							'rgb(173,255,47)',
						],
						borderWidth: 3
					}
				]
			},
			options: {
				scales: {
					xAxes: [{
						ticks: {
							beginAtZero:true,
						}
					}],
					yAxes: [{
						ticks: {
							maxRotation: 30,
							minRotation: 30
						}
					}] },
					responsive: true,
				},
			});
		});

			</script>

		@endsection
