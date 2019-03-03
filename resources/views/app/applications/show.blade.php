@extends ('layouts.app')

@section('content')

	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Home</li>
			<li class="">
				<a href="{{ route('app.applications.index') }}">Applications</a>
			</li>
			<li class="active">
				{{ $application->application_id }}
			</li>
		</ol>
	</div>

	<!-- Modal -->
	@if ($application_model->status != "-")
		<div class="modal fade" id="get_all_statuses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document" >
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">All Status</h4>
					</div>
					<div class="modal-body">
						<table class="table table-responsive" id="all_status">
							<thead>
								<tr>
									<th>Status</th>
									<th>Modified by</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($application_model->allStatus($application->application_id) as $appli)
									<tr>
										<td>
											{{ ucfirst($appli->status_id) }}
										</td>
										<td>
											{{ $application_status->addedBy($appli->application_id)->fname }}
											{{ $application_status->addedBy($appli->application_id)->lname }}
										</td>
										<td>{{ $appli->created_at->diffForHumans() }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	@endif



	<div class="container-fluid half-padding">
		<div class="template template__blank">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-8 col-xs-8">
									<h3 class="panel-title">
										SAF Application
									</h3>
								</div>
								<div class="col-md-4 col-xs-4 text-right">
									@if (accessControl(['encoder']))
										<a href="{{ route('app.applications.edit', $application->application_id) }}" class="btn btn-sm btn-success">
											<span class='fa fa-edit'></span>
										</a>
									@endif
									<a href="{{ route('app.applications.index') }}" class="btn btn-sm btn-default">
										<span class='fa fa-th-list'></span>
									</a>
								</div>
							</div>
						</div>
						<div class="panel-body">

							<div class="row">
								<div class="col-md-6">
									<h3>Application Form</h3>
									<br />

									<div>
										<div class="col-md-3 col-xs-3">Application #:</div>
										<div class="col-md-7 col-xs-7">
											{{ $application->application_id }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3 col-xs-3">Status:</div>
										<div class="col-md-7 col-xs-7">
											{{ ucfirst($application_model->recentStatusShort($application->application_id)) }}
											@if ($application_model->recentStatusShort($application->application_id) != "-")
												<button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#get_all_statuses">
													<span class='fa fa-info-circle' data-toggle="tooltip" title="Show all statuses"></span>
												</button>
											@endif
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3 col-xs-3">Team:</div>
										<div class="col-md-7 col-xs-7">
											{{ $application->getTeam->team_name }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3 col-xs-3">Encoded date:</div>
										<div class="col-md-7 col-xs-7">
											{{ $application->created_at }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3 col-xs-3">Customer name:</div>
										<div class="col-md-7 col-xs-7">
											{{ $application->customer_name }}
										</div>
									</div>
									<div class="clearfix"></div><br>


									<div>
										<div class="col-md-3 col-xs-3">Plan applied:</div>
										<div class="col-md-7 col-xs-7">
											@if (!empty($application->getPlan))
												{{ $application->getPlan->plan_name }}
											@else
												-
											@endif
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3 col-xs-3 col-xs-3">Sim/Device:</div>
										<div class="col-md-7 col-xs-7 col-xs-7">
											{{ empty($application->sim) ? '-' : $application->sim  }}
										</div>
									</div>
									<div class="clearfix"></div><br>


									<div>
										<div class="col-md-3 col-xs-3">SR #:</div>
										<div class="col-md-7 col-xs-7">
											{{ $application->sr_no == '' ? '-' : $application->sr_no }}
										</div>
									</div>
									<div class="clearfix"></div><br>

									<div>
										<div class="col-md-3 col-xs-3">SO #:</div>
										<div class="col-md-7 col-xs-7">
											{{ $application->so_no == '' ? '-' : $application->so_no }}
										</div>
									</div>
									<div class="clearfix"></div><br>



									<div>
										<div class="col-md-3 col-xs-3">Agent:</div>
										<div class="col-md-7 col-xs-7">
											{{ $application->getAgentName->fname . ' ' . $application->getAgentName->lname }}
										</div>
									</div>
									<div class="clearfix"></div><br>


								</div>

								<div class="col-md-6">

									<h3>Modified</h3><br />


									<div>
										<div class="col-md-3 col-xs-3">Added by</div>
										<div class="col-md-7 col-xs-7">
											{{ $application->getInsertBy->fname }}
											{{ $application->getInsertBy->lname }}
										</div>
									</div>
									<div class="clearfix"></div><br>


									@if (!empty($application->encoder_id) && !empty($application->encoded_at))
										<div>
											<div>
												<div class="col-md-3 col-xs-3">Encoder</div>
												<div class="col-md-7 col-xs-7">
													{{ $application->getEncoder($application->encoder_id)->fname }}
													{{ $application->getEncoder($application->encoder_id)->lname }}
												</div>
											</div>
											<div class="clearfix"></div><br>

											<div>
												<div class="col-md-3 col-xs-3">Encoded at</div>
												<div class="col-md-7 col-xs-7">
													{{ $application->encoded_at }}
												</div>
											</div>
											<div class="clearfix"></div><br>
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

@section ('scripts')
	@include ('partials.scripts._datatables')
	<script>
	$('#all_status').dataTable();
</script>
@endsection
