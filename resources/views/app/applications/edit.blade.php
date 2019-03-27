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
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">All Status</h4>
					</div>
					<div class="modal-body  table-responsive">
						<table class="table" id="all_status">
							<thead>
								<tr>
									<th>Status</th>
									<th>Modified by</th>
									<th>Date</th>
									<th>Time</th>
								</tr>
							</thead>
							<tbody>
								@foreach($application_status as $app)
								<tr>
									<td>{{ ucfirst($app->status_id) }}</td>
									<td>{{ $app->added_by->fname }} {{ $app->added_by->lname }}</td>
									<td>{{ $app->created_at->toDateString() }}</td>
									<td>{{ $app->created_at }}</td>
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
								<div class="col-md-8">
									<h3 class="panel-title">
										SAF Application / Edit
									</h3>
								</div>
								<div class="col-md-4 text-right">
									<a href="{{ route('app.applications.show', $application->application_id) }}" class="btn btn-sm btn-warning">
										<span class='fa fa-eye'></span>
									</a>
									<a href="{{ route('app.applications.index') }}" class="btn btn-sm btn-default">
										<span class='fa fa-th-list'></span>
									</a>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<form id="OnSubmit" class="" action="{{ route('app.applications.update', $application->application_id) }}" method="post">
								@include('includes.notif')

								{{ csrf_field() }}
								{{ method_field('PUT') }}
								<div class="row">
									<div class="col-md-6">
										<div>
											<div class="col-md-3 col-xs-3">Status:</div>
											<div class="col-md-7 col-xs-7">
												<div class="col-md-11 col-xs-11">
													<select name="status" class="form-control" required>
														{{ $application_model->recentStatusShort($application->id) }}
														<option {{ $application_model->recentStatusShort($application->application_id) == 'new' ? 'selected' : '' }} value="new">New</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'paid' ? 'selected' : '' }} value="paid">Paid</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'activated' ? 'selected' : '' }} value="activated">Activated</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled</option>
													</select>
												</div>
												<div class="col-md-1 col-xs-1">
													@if ($application_model->recentStatusShort($application->application_id) != "-")
														<button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#get_all_statuses">
															<span class='fa fa-info-circle' data-toggle="tooltip" title="Show all statuses"></span>
														</button>
													@endif
												</div>
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Customer name:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="customer_name" required class="form-control" value="{{ $application->customer_name }}">
											</div>
										</div>

										<div class="clearfix"></div><br>
										<div>
											<div class="col-md-3 col-xs-3">Plan applied:</div>
											<div class="col-md-7 col-xs-7">
												@if (!empty($application->getPlan))
													<select name="plan_id" class="form-control">
														@foreach ($plans as $plan)
															<option {{ $application->plan_id == $plan->id ? 'selected' : ''}} value="{{ $plan->id }}">{{ strtoupper($plan->product) }} - {{ $plan->plan_name }}</option>
														@endforeach
													</select>
												@else
													<select name="plan_id" class="form-control">
														<option value="" selected>Select plan (required)</option>
														@foreach ($plans as $plan)
															<option value="{{ $plan->id }}">{{ $plan->plan_name }}</option>
														@endforeach
													</select>
												@endif
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Contact:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="contact" required class="form-control" value="{{ $application->contact }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Address:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="address" required class="form-control" value="{{ $application->address }}">
												<input type="hidden" name="team_id" required class="form-control" value="{{ $application->team_id }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Agent:</div>
											<div class="col-md-7 col-xs-7">

												<select class="select2 form-control js-example-basic-single" id="agent" name="agent_id">
													@foreach ($users as $user)
														<option {{ $application->agent_id == $user->id ? "selected" : ""}} value="{{ $user->id }}">{{ $user->fname }} {{ $user->lname }}</option>
													@endforeach
												</select>

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
											<div class="col-md-3 col-xs-3">Remarks:</div>
											<div class="col-md-7 col-xs-7">
												<textarea name="remarks" rows="8" cols="80" class="form-control"></textarea>
											</div>
										</div>
										<div class="clearfix"></div><br>

									</div>

									<div class="col-md-6">

										<div>
											<div class="col-md-3 col-xs-3">SR #:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="sr_no" class="form-control" value="{{ $application->sr_no }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">SO #:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="so_no" class="form-control" value="{{ $application->so_no }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">MIN #:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="so_no" class="form-control" value="{{ $application->min_no }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">SAF #:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="so_no" class="form-control" value="{{ $application->saf_no }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">SIM:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="sim" class="form-control" value="{{ $application->sim }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">SIM_ID:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="sim_id" class="form-control" value="{{ $application->sim_id }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Device:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="device" class="form-control" value="{{ $application->device }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">IMEI:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="imei" class="form-control" value="{{ $application->imei }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Encoded date:</div>
											<div class="col-md-7 col-xs-7">
												{{ $application->created_at . ' ('.$application->created_at->diffForHumans().')' }}
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3"></div>
											<div class="col-md-7 text-right">
												<button class="btn btn-success btn-xs" name="button">
													<span class="fa fa-edit"></span>
													Update changes
												</button>
											</div>
										</div>
										<div class="clearfix"></div><br>


									</div>
								</div>

								<div class="row">
									<div class="col-md-12 text-right">

									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section ('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	@include ('partials.scripts._datatables')
	<script>
	$(document).ready(function(){
		$('#agent').select2();
	});
		$('#all_status').dataTable();
	</script>
@endsection
