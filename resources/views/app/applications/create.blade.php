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
								<div class="col-md-8">
									<h3 class="panel-title">
										Application Form
									</h3>
								</div>
								<div class="col-md-4 text-right">
									<a href="{{ route('app.applications.index') }}" class="btn btn-xs btn-default">
										<span class='fa fa-th-list'></span>
									</a>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<form action="{{ route('app.applications.store') }}" method="POST">

								@include('includes.notif')
								{{ csrf_field() }}

								<div style="overflow-x: auto;" class="table-reponsive">
									<table class="table" style="width: 1405px">
										<thead>
											<tr>
												<th width="5px"></th>
												<th width="200px">Customer <span class="required">*</span></th>
												<th width="200px">Contact <span class="required">*</span></th>
												<th width="200px">Address <span class="required">*</span></th>
												<th width="200px">Plan <span class="required">*</span></th>
												<th width="200px">Sim</th>
												<th width="200px">Device</th>
												<th width="200px">Agent <span class="required">*</span></th>
											</tr>
										</thead>
										<tbody>
											@for($i = 0; $i <= 9; $i++)
												<tr>
													<td>{{ ($i < 10) ? ($i + 1) : $i }}.</td>
													<td>
														<input type="text" placeholder="Customer" name="customer_name[]" id="" class="form-control" value="{{ old('customer_name.'.$i) }}">
													</td>
													<td>
														<input type="text" placeholder="Contact" name="contact[]" id="" class="form-control" value="{{ old('contact.'.$i) }}">
													</td>
													<td>
														<input type="text" placeholder="Address" name="address[]" id="" class="form-control" value="{{ old('address.'.$i) }}">
													</td>
													<td>
														<select name="plan_id[]" class="form-control">
															@if (empty(old('plan_id.'.$i)))
																<option value="" selected>Select plan (required)</option>
															@endif
															@foreach ($plans as $plan)
																<option {{ old('plan_applied.'.$i) == $plan->id ? 'selected' : ''}} value="{{ $plan->id }}">{{ $plan->plan_name }}</option>
															@endforeach
														</select>
													</td>
													<td>
														<input type="text" name="sim[]" placeholder="Sim" class="form-control" value="{{ old('sim.'.$i) }}">
													</td>
												</td>
												<td>
													<select name="device_id[]" class="form-control">
														<option value="" selected></option>
														@foreach ($devices as $device)
															<option {{ old('device_id.'.$i) == $device->device_id ? 'selected' : ''}} value="{{ $device->device_id }}">{{ $device->device_name }}</option>
														@endforeach
													</select>
												</td>
												<td>
													<select name="agent_id[]" class="form-control">
														@if (empty(old('agent_id.'.$i)))
															<option value="" selected>Select agent (required)</option>
														@endif
														@foreach ($agents as $agent)
															<option {{ old('agent_id.'.$i) == $agent->id ? 'selected' : ''}}  value="{{ $agent->id }}">{{ $agent->fname . ' ' . $agent->lname }}</option>
														@endforeach
													</select>
												</td>
											</tr>
										@endfor
									</tbody>
								</table>
							</div>
							<hr>
							<button class="btn btn-primary">Submit <span class='fa fa-plus-circle'></span> </button>
							<br>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
