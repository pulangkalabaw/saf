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
						{{-- Body start --}}

						<div class="panel-body">
	                        <form action="{{ route('app.applications.store') }}" method="POST">
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
												<select name="team_id" class="form-control">
													@if (empty(old('team_id')))
														<option value="" selected>Select Team (required)</option>
													@endif
													@foreach ($teams as $team)
														<option {{ old('team_id') == $team->id ? 'selected' : ''}} value="{{ $team->id }}">{{ $team->team_name }}</option>
													@endforeach
												</select>
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
														<option {{ old('plan_applied') == $plan->id ? 'selected' : ''}} value="{{ $plan->id }}">{{ $plan->plan_name }}</option>
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
												<select name="user_id" class="form-control">
													@if (empty(old('id')))
														<option value="" selected>Select agent (required)</option>
													@endif
													@foreach ($users as $user)
														<option {{ old('id') == $user->id ? 'selected' : ''}}  value="{{ $user->id }}">{{ $user->fname . ' ' . $user->lname }}</option>
													@endforeach
												</select>
	                                        </div>
	                                    </div>
	                                    <div class="clearfix"></div><br>

										<div>
	                                        <div class="col-md-3"></div>
	                                        <div class="col-md-7 text-right">
	                                            <button class="btn btn-xs btn-primary">Submit <span class='fa fa-plus-circle'></span> </button>
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
