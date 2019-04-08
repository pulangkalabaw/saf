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
									<td>{{ $app->created_at->format("M d, Y") }}</td>
									<td>{{ $app->created_at->format("h:i:s A") }}</td>
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
							<form name="updateForm" id="OnSubmit" class="" action="{{ route('app.applications.update', $application->application_id) }}" method="post" onsubmit="return validateFields()">
								@include('includes.notif')

								{{ csrf_field() }}
								{{ method_field('PUT') }}
								<div class="row">
									<div class="col-md-6">
										<div>
											<div class="col-md-3 col-xs-3">Status:</div>
											<div class="col-md-7 col-xs-7">
												<div class="col-md-11 col-xs-11">
													<select id="status" name="status" class="form-control" required onchange="$('#update-application').removeAttr('disabled');">
														{{ $application_model->recentStatusShort($application->id) }}
														<option {{ $application_model->recentStatusShort($application->application_id) == 'new' ? 'selected' : '' }} value="new">New</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'rts' ? 'selected' : '' }} value="rts">RTS</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'saved_drafts' ? 'selected' : '' }} value="saved_drafts">Saved Drafts</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'sup' ? 'selected' : '' }} value="sup">For Validation (SUP)</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'crmo' ? 'selected' : '' }} value="crmo">For Validation (CRMO)</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'disapproved' ? 'selected' : '' }} value="disapproved">Disapproved</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'approved' ? 'selected' : '' }} value="approved">Approved</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'for_payment' ? 'selected' : '' }} value="for_payment">For Payment</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'paid' ? 'selected' : '' }} value="paid">Paid</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'for_activation' ? 'selected' : '' }} value="for_activation">For Activation</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'closed_activated' ? 'selected' : '' }} value="closed_activated">Closed/Activated</option>
														<option {{ $application_model->recentStatusShort($application->application_id) == 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled Application</option>

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
											<div class="col-md-3 col-xs-3">Awaiting Device:</div>
											<div class="col-md-7 col-xs-7">
												<input type="checkbox" name="awaiting_device" value="1" {{ ($application->awaiting_device == 1) ? 'checked' : '' }}>
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
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Agent:</div>
											<div class="col-md-4 col-xs-4">

												<select class="select2 form-control js-example-basic-single" name="agent_id" id="selected_user" onchange="showTeam($(this).val())">
													@foreach ($users as $user)
														<option {{ $application->agent_id == $user->id ? "selected" : ""}} value="{{ $user->id }}">{{ $user->fname }} {{ $user->lname }}</option>
													@endforeach
												</select>
												<input type="hidden" name="team_id" required class="form-control" value="{{ $application->team_id }}">

											</div>
											<div class="col-md-3 col-xs-3" id="agent_ref">
												<div {{ $agent->agent_referral == 0 ? "hidden" : ""}}>
													<span class='fa fa-info-circle'></span><small> Agent Referral</small>
												</div>
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Team:</div>
											<div class="col-md-7 col-xs-7" id="team_name">
												{{ $application->getTeam->team_name }}
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Remarks:</div>
											<div class="col-md-7 col-xs-7">
												<textarea name="remarks" rows="8" cols="80" class="form-control">{{ $application->remarks }}</textarea>
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
												<input type="text" name="min_no" class="form-control" value="{{ $application->min_no }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										{{-- <div>
											<div class="col-md-3 col-xs-3">SAF #:</div>
											<div class="col-md-7 col-xs-7">
												<input type="text" name="so_no" class="form-control" value="{{ $application->saf_no }}">
											</div>
										</div>
										<div class="clearfix"></div><br> --}}

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
											<div class="col-md-3 col-xs-3">Encoder:</div>
											<div class="col-md-7 col-xs-7">
												@if($application->encoder_id == null)
												<select class="select2 form-control js-example-basic-single" name="encoder_id">
													@foreach ($encoders as $encoder)
														<option {{ auth()->user()->id == $encoder->id ? "selected" : ""}} value="{{ $encoder->id }}">{{ $encoder->fname }} {{ $encoder->lname }}</option>
													@endforeach
												</select>
												@else
													{{ $application->getEncoderData->fname }} {{ $application->getEncoderData->lname }}
													<input type="hidden" name="encoder_id" value="{{ $application->getEncoderData->id }}">
												@endif

											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3 col-xs-3">Encoded date:</div>
											<div class="col-md-7 col-xs-7">
												{{ $application->created_at->format("M d, Y h:i:s A") . ' ('.$application->created_at->diffForHumans().')' }}
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3"></div>
											<div class="col-md-7 text-right">
												<button class="btn btn-success btn-xs" name="button" id="update-application">
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
	var $team = $('#team_name');
	// var $id = $("#selected_user option:selected").val();

	function showTeam(id){
		//alert(id);
		$(function (){
			$.ajax({
				url: '{{ url("app/api/available-users/") }}' + "/" +id,
				method: 'GET',
				success: function(data) {
					console.log(data);
					$team.html(data.team.team_name);
					$('input[name="team_id"]').val(data.team.id);

					console.log(data.user.agent_referral);
					if(data.user.agent_referral == 1){
						$('#agent_ref').show();
					} else {
						$('#agent_ref').hide();
					}
				}
			});
		});
	}

	function validateFields(){
		var validate = document.forms["updateForm"]["status"].value;
		if(validate == "new"){
			alert("Please change the Status New");
			$("#status").css({ "border": '#FF0000 1px solid'});
			$("#update-application").removeAttr("disabled");
			$("#update-application").prop("disabled", false);
			return false;
		}
	}
	</script>
@endsection
