@extends ('layouts.app')
@section('content')
    <div class="main-heading">
        <ol class="breadcrumb">
            <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
            <li class="">Attendance</li>
            <li class="active">User Attendance</li>
        </ol>
    </div>
    <div class="container-fluid half-padding">
        <div class="template template__blank">
            <div class="row">
                <div class="col-md-12">
                    {{-- {{ dd(!empty($dont_have_cl)) }} --}}
                    {{-- {{ dd(auth()->user()->role . ' = ' . base64_encode('administrator')) }} --}}
                    @if(!empty($dont_have_cl) && (auth()->user()->role != base64_encode('administrator')))
                        {{-- @if() --}}
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="panel-title">
                                            User Attendance
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group text-center">
                                    <label><h2><i class="fa fa-close text-danger"></i> Sorry you are not permitted to access this!</h2></label>
                                </div>
                                <div class="form-group text-center">
                                    <label>You are not associated to any cluster leaders.</label>
                                </div>
                            </div>
                        </div>
                        {{-- @endif --}}
                    @else
                        <div id="desktop-view">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h3 class="panel-title">
                                                User Attendance
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                @if((count(session()->get('_c')) == 0 && count(session()->get('_t')) == 0) && (auth()->user()->role != base64_encode('administrator')))
                                    <div class="panel-body">
                                        <div class="form-group text-center">
                                            <label><h2><i class="fa fa-close text-danger"></i> Sorry you are not permitted to access this!</h2></label>
                                        </div>
                                        <div class="form-group text-center">
                                            <label>Please make sure that you have Team/s and Cluster/s</label>
                                        </div>
                                    </div>
                                @else
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-1 col-md-2 col-sm-2">
                                                <button onclick="window.location  = '{{ url('app/attendance/list') . '?date=' . $date['previous'] }}'" class="btn btn-md btn-warning">Previous</button>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-4">
                                                <div class="input-group date">
                                                    <input class="form-control text-light" name="jump_to_date" type="text" id="jump_to_date" value="{{ $date['selected'] }}" max="2019-03-04" readonly>
                                                    <div class="input-group-addon" title="Jump to certain date" id="icon-container">
                                                        <div class="fa fa-calendar" title="Jump to certain date"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-2 col-sm-2">
                                                <button onclick="window.location  = '{{ url('app/attendance/list') . '?date=' . $date['next'] }}'" {{ $date['next'] == null ? 'disabled' : '' }} class="btn btn-md btn-warning">Next</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="display:none;" id="showpanel">
                                    <div class="form-group text-center">
                                        <label><h2><i class="fa fa-close text-danger"></i> Sorry you cannot access user attendance</h2></label>
                                    </div>
                                    <div class="form-group text-center">
                                        <label>You can only use it around 5:00 AM to 10:30 AM</label>
                                    </div>
                                </div>
                                <div class="panel-body" id="hidepanel">
                                        {{-- {{ dd(Carbon\Carbon::parse(request()->date)->format('F d, Y')) }} --}}
                                        <div class="panel-body">
                                            <div class="form-group text-center">
                                                @php
                                                $fucking_date = Carbon\Carbon::parse(request()->date)->diffInDays(Carbon\Carbon::parse(date('Y-m-d')), false);
                                                $fucking_message = '';
                                                if($fucking_date == 1){
                                                    $fucking_message = ' (Yesterday)';
                                                } else if($fucking_date == 0) {
                                                    $fucking_message = ' (Now)';
                                                }
                                                @endphp
                                                <label><h2><i class="fa fa-calendar text-success"></i> {{ Carbon\Carbon::parse(request()->date)->format('F d, Y')  . $fucking_message}}</h2></label>
                                            </div>
                                        </div>
                                        <div class="row">

            								@include('includes.filter')

                                            <div class="col-md-4 col-xs-4">
                                                <div class="form-inline">
                                                    <div class="form-group">
                                                        <label>Number of rows: </label>
                                                        <select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value)" class="form-control">
                                                            <option {{ !empty(request()->get('show') && request()->get('show') == 10) ? 'selected' : ''  }}
                                                                value="{{ request()->fullUrlWithQuery(['show' => '10']) }}">10
                                                            </option>
                                                            <option {{ !empty(request()->get('show') && request()->get('show') == 25) ? 'selected' : ''  }}
                                                                value="{{ request()->fullUrlWithQuery(['show' => '25']) }}">25
                                                            </option>
                                                            <option {{ !empty(request()->get('show') && request()->get('show') == 50) ? 'selected' : ''  }}
                                                                value="{{ request()->fullUrlWithQuery(['show' => '50']) }}">50
                                                            </option>
                                                            <option {{ !empty(request()->get('show') && request()->get('show') == 100) ? 'selected' : ''  }}
                                                                value="{{ request()->fullUrlWithQuery(['show' => '100']) }}">100
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div>
            								<div class="col-md-5 col-xs-5">
            									<form action="{{ request()->fullUrl() }}" method="GET">
            										<div class="input-group">
            											<input autofocus type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control"
            											placeholder="Search for first name, last name, email and role">
            											<span class="input-group-btn">
            												<button class="btn btn-primary"><span class='fa fa-search'></span> </button>
            											</span>
            										</div>
            									</form>
            								</div>
                                        </div>
                                        <div class="row">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hovered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>First Name</th>
                                                                <th>Last Name</th>
                                                                <th>Activities</th>
                                                                <th>Location</th>
                                                                <th>Remarks</th>
                                                                <th class="text-center">Status</th>
                                                                {{-- <th class="text-center">Team</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($attendance as $att)
                                                                <tr {!! $att['status'] == 1 ? 'title="Present"' : 'title="Absent"' !!}>
                                                                    <td>
                                                                        {{ $att['users']['fname'] }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $att['users']['lname'] }}
                                                                    </td>
                                                                    <td class="text-light">{{ $att['activities'] }}</td>
                                                                    <td class="text-light">{{ $att['location'] }}</td>
                                                                    <td class="text-light">{{ $att['remarks'] }}</td>

                                                                    <td class="text-light text-center">{!! $att['status'] == 1 ? '<span title="Present" class="fa fa-circle text-info"></span>' : '<span title="Absent" class="fa fa-circle text-danger"></span>' !!}</td>
                                                                    {{-- <td class="text-light">{{ $att['status'] == 1 ? 'Present' : 'Absent' }}</td> --}}
                                                                    {{-- <td class="text-light text-center">{{ $att['team_name'] }}</td> --}}
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-8 col-xs-8">
                                                        {{ $attendance->appends(request()->input())->links() }}
                                                    </div>
                                                    <div class="col-md-4 col-xs-4 text-right">
                                                        Total <b>{{ $total }}</b> result(s)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('.fa-calendar').click(function() {
        $("#jump_to_date").focus();
    });
    $('#icon-container').click(function() {
        $("#jump_to_date").focus();
    });

    $('#jump_to_date').datepicker ({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        endDate: '+0d',
    }).on('changeDate', function(){
        window.location = '{{ url('app/attendance/list') . '?date=' }}' + this.value;
    });
});
</script>
@endsection
