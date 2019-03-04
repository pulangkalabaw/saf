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
                        <form action="{{ route('app.oic.store') }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-7">
                                    <div>
                                        <div class="col-md-3">Team</div>
                                        <div class="col-md-7">
                                            <select id="tl_id" class="form-control" name="team_id" onchange="enableSelectionAg()">
                                                <option value="">Select Team</option>
                                                @foreach ($teams as $team)
                                                    <option {{ old('team_id') == $team['id'] ? 'selected' : '' }} value="{{ $team['team_id'] }}">{{ $team['team_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Agent</div>
                                        <div class="col-md-7">
                                            <select class="form-control" name="user_id" disabled id="ag_id" required>
                                                <option value="">Select Agent</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ ((int)$user['id']) }}">{{ $user['fname'] . ' ' . $user['lname'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

									<div>
										<div class="col-md-3">Assign Date</div>
										<div class="col-md-3">
										  <input class="form-control" type="text" id="datepicker" name="assign_date" required>
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
    function enableSelectionAg() {
        document.getElementById("ag_id").disabled = false;
        var tl = document.getElementById("tl_id").value;
    }
</script>
@endsection
