@extends ('layouts.app')

@section('content')

	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Home</li>
			<li class="">
				<a href="{{ route('app.applications.index') }}">Applications</a>
			</li>
			<li class="active">Create</li>
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
										Application Form
									</h3>
								</div>
								<div class="col-md-4 col-xs-4 text-right">
									<a href="{{ route('app.applications.index') }}" class="btn btn-sm btn-default">
										<span class='fa fa-th-list'></span>
									</a>
								</div>
							</div>
						</div>
						{{-- Body start --}}

						<div class="panel-body">
							<form id="form_id" action="{{ route('app.applications.store') }}" method="POST" enctype="multipart/form-data">
								@include('includes.notif')

								{{ csrf_field() }}
								<div class="row">
									<div class="col-md-7">

										<div>
											<div class="col-md-3">Team</div>
											@if($errors->any())
												<span class="required">*</span>
											@endif
											<div class="col-md-7">
												@if(count($teams) == 1)
													<select class="form-control select_enable" name="team_id" disabled>
														@foreach ($teams as $team)
															<option selected value="{{ (int)$team['id'] }}">{{ $team['team_name'] }}</option>
														@endforeach
													</select>
												@else
													<select name="team_id" class="form-control">
														@if (empty(old('team_id')))
															<option value="" selected>Select Team (required)</option>
														@endif
														@foreach($teams as $team)
															<option {{ old('team_id') == $team['id'] ? 'selected' : '' }} value="{{ (int)$team['id'] }}">{{ $team['team_name'] }}</option>
														@endforeach
													</select>
												@endif

											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3">Customer</div>
											@if($errors->any())
												<span class="required">*</span>
											@endif
											<div class="col-md-7">
												<input type="text" name="customer_name" id="" class="form-control" required value="{{ old('customer_name') }} ">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3">Contact</div>
											@if($errors->any())
												<span class="required">*</span>
											@endif
											<div class="col-md-7">
												<input type="text" name="contact" id="" class="form-control" required value="{{ old('contact') }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3">Address</div>
											@if($errors->any())
												<span class="required">*</span>
											@endif
											<div class="col-md-7">
												<input type="text" name="address" id="" class="form-control" required value="{{ old('address') }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3">Plan</div>
											@if($errors->any())
												<span class="required">*</span>
											@endif
											<div class="col-md-7">
												<select name="plan_id" class="form-control">
													@if (empty(old('plan_id')))
														<option value="" selected>Select plan (required)</option>
													@endif
													@foreach ($plans as $plan)
														<option {{ old('plan_applied') == $plan->id ? 'selected' : ''}} value="{{ $plan->id }}">{{ strtoupper($plan->product) }} - {{ $plan->plan_name }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3">Sim\Device</div>
											<div class="col-md-7">
												<input type="text" name="sim" id="" class="form-control" value="{{ old('sim') }}">
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3">Agent</div>
											@if($errors->any())
												<span class="required">*</span>
											@endif
											<div class="col-md-7">
												@if (count($agents) == 1)
													<select class="form-control select_enable" name="user_id" disabled>
														@foreach ($agents as $agent)
															<option value="{{ $agent['id'] }}" selected>{{ $agent['fname'] }} {{ $agent['lname'] }}</option>
														@endforeach
													</select>
												@else
													<select name="user_id" class="form-control">
														@if (empty(old('id')))
															<option value="" selected>Select agent (required)</option>
														@endif
														@foreach ($agents as $agent)
															<option {{ old('id') == $agent['id'] ? 'selected' : '' }} value="{{ $agent['id'] }}">{{ $agent['fname'] }} {{ $agent['lname'] }}</option>
														@endforeach
													</select>
												@endif
											</div>
										</div>
										<div class="clearfix"></div><br>

										<div>
										<div>
											<div class="col-md-3">Attatch file</div>
											<input type="file" name="attached_files[]" multiple="multiple">
										</div>
										<div class="clearfix"></div><br>

										<div>
											<div class="col-md-3"></div>
											<div class="col-md-7 text-right">
												<button class="btn btn-sm btn-primary">Submit <span class='fa fa-plus-circle'></span> </button>
											</div>
										</div>
										<div class="clearfix"></div><br>

									</div>
								</div>
							</form>
						</div>

						{{-- Body end --}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
	<script>
	$('#form_id').submit(function() {
		$(".select_enable").prop('disabled', false);
	})
</script>
@endsection
