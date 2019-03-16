@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
@endsection

@section('content')
<div class="main-heading">
	<ol class="breadcrumb">
		<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
		<li class="">Home</li>
		<li class="">
			<a href="{{ route('app.oic.index') }}">Officer In Charge</a>
		</li>
        <li class="">
			<a href="{{ route('app.oic.show',$oic->id) }}">{{ $oic->getAgent->fname }} {{ $oic->getAgent->lname }}</a>
		</li>
		<li class="active">Edit</li>
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
                        <form action="{{ route('app.oic.update', $oic->id) }}" method="POST" id="form_id">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}

                            @include('includes.notif')

                            <div class="row">
                                <div class="col-md-7">
                                    <div>
                                        <div class="col-md-3">Team</div>
                                        <div class="col-md-7">
											@if(count($teams) == 1)
												<select class="form-control select_enable" name="team_id" disabled>
													@foreach ($teams as $team)
														<option selected value="{{ $team['id'] }}">{{ $team['team_name'] }}</option>
													@endforeach
												</select>
											@else
												<select id="tl_id" class="form-control" name="team_id" onchange="enableSelectionAg()">
													<option value="">Select Team</option>
													@foreach ($teams as $team)
														<option {{ $oic['team_id'] == $team['id'] ? 'selected' : '' }} value="{{ $team['id'] }}">{{ $team['team_name'] }}</option>
													@endforeach
												</select>
											@endif
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Agent</div>
                                        <div class="col-md-7">
											@if(count($users) == 1)
												<select class="form-control select_enable" name="user_id" disabled>
	                                                @foreach ($users as $user)
	                                                    <option value="{{ ((int)$user['id']) }}">{{ $user['fname'] . ' ' . $user['lname'] }}</option>
	                                                @endforeach
	                                            </select>
											@else
												<select class="form-control" name="user_id" required>
	                                                @foreach ($users as $user)
                                                        <option {{ $oic->user_id == $user['id'] ? 'selected' : '' }} value="{{ ((int)$user['id']) }}">{{ $user['fname'] . ' ' . $user['lname'] }}</option>
                                                    @endforeach
	                                            </select>
											@endif

                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

									<div>
										<div class="col-md-3">Assign Date</div>
										<div class="col-md-3">
										  <input class="form-control" type="text" id="datepicker" name="assign_date" required autocomplete="off" value="{{ $oic->assign_date }}">
										</div>
									</div>
									<div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-7 text-right">
                                            <button class="btn btn-xs btn-success">Update <span class='fa fa-plus-circle'></span> </button>
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
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$(function() {
		$( "#datepicker" ).datepicker();
	});
</script>
<script>
	$('#form_id').submit(function() {
    	$(".select_enable").prop('disabled', false);
    })
</script>
@endsection
