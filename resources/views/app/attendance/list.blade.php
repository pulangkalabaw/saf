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
                    @if(!empty($dont_have_cl))
                    {{-- {{ dd(!empty($dont_have_cl)) }} --}}
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
                                @if(count(session()->get('_c')) == 0 && count(session()->get('_t')) == 0)
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
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-hovered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Activities</th>
                                                            <th>Location</th>
                                                            <th>Remarks</th>
                                                            <th>Status</th>
                                                            {{-- <th class="text-center">Team</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($attendance as $att)
                                                        <tr {!! $att['status'] == 1 ? 'title="Present"' : 'title="Absent"' !!}>
                                                            <td class="text-light">{{ $att['users']['fname'] . ' ' . $att['users']['lname'] }}</td>
                                                            <td class="text-light">{{ $att['activities'] }}</td>
                                                            <td class="text-light">{{ $att['location'] }}</td>
                                                            <td class="text-light">{{ $att['remarks'] }}</td>

                                                            <td class="text-light">{!! $att['status'] == 1 ? '<span title="Present" class="fa fa-circle text-info"></span>' : '<span title="Absent" class="fa fa-circle text-danger"></span>' !!}</td>
                                                            {{-- <td class="text-light">{{ $att['status'] == 1 ? 'Present' : 'Absent' }}</td> --}}
                                                            {{-- <td class="text-light text-center">{{ $att['team_name'] }}</td> --}}
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
