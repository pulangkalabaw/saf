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
	{{-- Product chart --}}
	@if (count($_w_product_chart) != 0)
		@if ($_w_product_chart[0]['count'] != 0)
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
							<h2>Product chart</h2>
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
			<div class="clearfix"></div><Br />
		@endif
	@endif


	{{-- Appplication Status --}}
	@if (count($_w_application_status_counter['application_status_c']) != 0)
		@if ($_w_application_status_counter['application_status_c'][0]['count'] != 0)

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
							<h2>Application statuses</h2>
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
			<div class="clearfix"></div><Br />
		@endif
	@endif

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
                                        <div class="col-md-2 col-xs-5">From<input type="date" name="from" value="{{ request()->get('from') }}" class="form-control input-sm"></div>
                                        <div class="col-md-2 col-xs-5">To<input type="date" name="to" value="{{ request()->get('to') }}" class="form-control input-sm"></div>
                                        <div class="col-md-2 col-xs-2"><br><button class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button></div>
                                    </form>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <br>
                                        @if( (app('request')->input('to') != null) && (app('request')->input('from') != null) )
                                            <b>From {{ Carbon\Carbon::parse(app('request')->input('from'))->format('M d Y') }} to {{ (app('request')->input('to') == null) ? '--': (Carbon\Carbon::parse(app('request')->input('to'))->format('M d Y')) }}</b>
                                        @elseif( (app('request')->input('to') == null) && (app('request')->input('from') != null) )
                                            <b>Data from {{ Carbon\Carbon::parse(app('request')->input('from'))->format('M d Y') }}</b>
                                        @elseif((app('request')->input('to') != null) && (app('request')->input('from') == null))
                                            <b>From {{ Carbon\Carbon::parse(app('request')->input('from'))->format('M d Y') }} to {{ Carbon\Carbon::parse(app('request')->input('to'))->format('M d Y') }}</b>
                                        @endif
                                    </div>
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
                                </div>
                                <!-- PUT FOREACH TEAM  -->
                                @foreach($clus->teams as $team)
                                    @if($team)
                                    <div class="col-md-3">
                                        <div class="breadcrumb">
                                            <h5><b>{{ $team->team_name }}</b></h5>
                                            <p>New Applications: <b>{{ (float)$team->getallsafthiscutoff['new'] }}</b></p>
                                            <p>Activated Applications: <b class="text-info">{{ (float)$team->getallsafthiscutoff['activated'] }}</b></p>
                                            <p>Paid Applications: <b class="text-primary">{{ (float)$team->getallsafthiscutoff['paid'] }}</b></p>
                                            <p>Total Target: <b class="text-success">{{ $team->total_target }}</b> <small class="text-muted">({{ (float)number_format($team->pat, 2) }}%)</small></p>
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

        <!-- START OF GRAPHS  -->
        <!-- <div class="row">
            <div class="col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Application counter (Cluster)</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="application_counter_by_cluster"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Application counter (Team)</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="application_counter_by_teams"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Status application</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="no_of_status_that_used"></canvas>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- END OF GRAPHS  -->
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

	var aps_ctx = document.getElementById("application_status").getContext('2d');

	var aps_labels = {!! collect($_w_application_status_counter['application_status_c'])->map(function ($r) {
		return '('.$r['count'].') '. ucfirst($r['status']);
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
        return $r['product'];
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

			</script>

		@endsection
