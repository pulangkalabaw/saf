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
                                {{-- {{dd(empty($attendance['unpresent']))}} --}}
                                {{-- {{ dd(count(session()->get('_t'))) }} --}}
                                {{-- {{ dd(count(session()->get('_c')) == 0 && count(session()->get('_t'))) }} --}}

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
                                                <button onclick="window.location  = '{{ url('app/attendance') . '?date=' . $date['previous'] }}'" class="btn btn-md btn-warning">Previous</button>
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
                                                <button onclick="window.location  = '{{ url('app/attendance') . '?date=' . $date['next'] }}'" {{ $date['next'] == null ? 'disabled' : '' }} class="btn btn-md btn-warning">Next</button>
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
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="active" role="presentation"><a href="#unpresent" aria-controls="unpresent" role="tab" data-toggle="tab">Roll call <span class="label">{{ count($attendance['unpresent']) }}</span> </a></li>
                                        <li role="presentation"><a href="#present" aria-controls="present" role="tab" data-toggle="tab">Present <span class="label">{{ count($attendance['present']) }}</span></a></li>
                                        <li role="presentation"><a href="#absent" aria-controls="absent" role="tab" data-toggle="tab">Absents <span class="label">{{ count($attendance['absent']) }}</span></a></li>
                                    </ul>
                                    <div class="tab-content" >
                                        {{-- {{ dd(Carbon\Carbon::parse(request()->date)->format('F d, Y')) }} --}}
                                        @if(!empty(request()->date))
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
                                        @endif
                                        <div class="tab-pane active" id="unpresent" role="tabpanel">
                                            {{-- {{ count($attendance['unpresent']) }} --}}
                                            @if(count($attendance['unpresent']) == 0)
                                            <div class="form-group text-center">
                                                <label><h4><i class="fa fa-info-circle text-info"></i> All agents have been checked</h4></label>
                                            </div>
                                            <div class="form-group text-center">
                                                <label>If you have any concerns or corrections, please contact your cluster lead</label>
                                            </div>
                                            @else
            								@include('includes.notif')
                                            @if (Session::has('message'))
                                            <div class="alert alert-dismissable alert-danger">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                    {{ Session::get('message') }}
                                            </div>
                                            @endif
                                            @if (Session::has('success'))
                                               <!-- <div class="alert alert-success">{{ Session::get('success') }}</div> -->
                                               <div class="alert alert-dismissable alert-success">
                                                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                   </button>
                                                       {{ Session::get('success') }}
                                               </div>
                                            @endif
                                            <form enctype="multipart/form-data" action="{{route('app.attendance.store')}}" method="POST">
                                                {{ csrf_field() }}
                                                {{-- <input type="hidden" name="cl_id" value="{{  }}"> --}}
                                                <div class="panel-body">
                                                    <input name="selected_date" type="hidden" value="{{ $date['selected'] }}">
                                                    <button class="btn btn-primary pull-right" id="buttonTop">Submit</button>
                                                    {{-- <button type="button" class="btn btn-danger pull-right" id="buttonTop">toggle</button> --}}
                                                </div>
                                                <div class="panel-body">
                                                    @if(base64_decode(auth()->user()->role) != 'administrator' && count(session()->get('_c')) == 0)
                                                        <label class="text-danger">You can't use attendance because it's passed 10:30am</label>
                                                    @endif
                                                    <div class="table-responsive">
                                                        <table class="table table-hovered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Status</th>
                                                                    <th>Name</th>
                                                                    <th>Activities</th>
                                                                    <th>Location</th>
                                                                    <th>Remarks</th>
                                                                    <th class="text-center">Team</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($attendance['unpresent'] as $index => $value)
                                                                    <tr id="tr-container-{{ $index }}" class="">
                                                                        <td class="text-center">
                                                                            <input type="hidden" hidden name="user[{{ $index }}][status]" class="setStatus" id="status_{{ $index }}"
                                                                            @if(!empty($value['value_btn']))
                                                                                @if($value['value_btn']['class'] == 'btn-info')
                                                                                    value="1"
                                                                                @elseif($value['value_btn']['class'] == 'btn-danger')
                                                                                    value="0"
                                                                                @endif
                                                                            @endif

                                                                            >
                                                                            <button type="button" name="changeStatus" id="changeStatus_{{ $value['id'] }}" onclick="changeButtonStatus('desktop', 'changeStatus_{{ $value['id'] }}', 'status_{{ $index }}' , 'user[{{ $index }}][activities]', 'user[{{ $index }}][location]', 'user[{{ $index }}][remarks]');
                                                                            @if(!empty($value['value_btn']))
                                                                                showClRemark('{{ $value['id'] }}', '{{ $value['value_location'] }}', '{{ $value['value_remarks'] }}', '{{ $value['value_activity'] }}', '{{ $value['value_btn']['label'] }}');
                                                                            @endif
                                                                            @if(!empty(request()->date))
                                                                                @if(request()->date != Carbon\Carbon::now()->toDateString())
                                                                                    showRemark('{{ $value['id'] }}');
                                                                                @endif
                                                                            @endif
                                                                            "
                                                                            class="btn
                                                                            @if(!empty($value['value_btn']))
                                                                                {{ $value['value_btn']['class'] }}
                                                                            @else
                                                                                btn-default
                                                                            @endif attendance
                                                                            "
                                                                            >@php
                                                                            if(!empty($value['value_btn'])){
                                                                                echo $value['value_btn']['label'];
                                                                            }else{
                                                                                echo 'Undecided';
                                                                            }
                                                                            @endphp</button>
                                                                        </td>
                                                                        <td class="text-light"><span class="margin-vertical {{ !empty($value['tl']) ? 'text-info' : '' }}">{{ $value['fname'] . ' ' . $value['lname']}}</span></td>
                                                                        {{-- <td>{{  }}</td> --}}
                                                                        <td>
                                                                            <input name="user[{{ $index }}][user_id]" class="form-control" type="hidden" value={{ $value['id'] }}>
                                                                            <select class="form-control input-gray" name="user[{{ $index }}][activities]" id="user_activity_{{ $value['id'] }}"
                                                                                @if(empty($value['value_activity']))
                                                                                    disabled
                                                                                @else
                                                                                    onchange="showClRemark('{{ $value['id'] }}', '{{ $value['value_location'] }}', '{{ $value['value_remarks'] }}', '{{ $value['value_activity'] }}', '{{ $value['value_btn']['label'] }}')"
                                                                                @endif
                                                                            >
                                                                                <option
                                                                                @if(!empty($value['value_activity']))
                                                                                    @if($value['value_activity'] == 'Blitz')
                                                                                        selected
                                                                                    @endif
                                                                                @endif
                                                                                >Blitz</option>
                                                                                <option
                                                                                @if(!empty($value['value_activity']))
                                                                                    @if($value['value_activity'] == 'Saturation')
                                                                                        selected
                                                                                    @endif
                                                                                @endif
                                                                                >Saturation</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input name="user[{{ $index }}][location]" id="user_location_{{ $value['id'] }}" class="form-control input-gray" required type="text"
                                                                                @if(!empty($value['value_location']))
                                                                                    value="{{ $value['value_location'] }}"
                                                                                    oninput="showClRemark('{{ $value['id'] }}', '{{ $value['value_location'] }}', '{{ $value['value_remarks'] }}', '{{ $value['value_activity'] }}', '{{ $value['value_btn']['label'] }}')"
                                                                                @else
                                                                                    disabled
                                                                                @endif
                                                                                >
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <input name="user[{{ $index }}][remarks]" id="user_remarks_{{ $value['id'] }}" class="form-control input-gray"  required type="text"
                                                                            @if(!empty($value['value_remarks']))
                                                                                value="{{ $value['value_remarks'] }}"
                                                                                oninput="showClRemark('{{ $value['id'] }}', '{{ $value['value_location'] }}', '{{ $value['value_remarks'] }}', '{{ $value['value_activity'] }}', '{{ $value['value_btn']['label'] }}')"
                                                                            @else
                                                                                disabled
                                                                            @endif
                                                                            >
                                                                        </td>

                                                                        <td class="text-light text-center">{{ $value['team_name'] }}</td>
                                                                    </tr>
                                                                    <tr class="bg-gray tr-accordion" id="tr-accordion-{{ $value['id'] }}">
                                                                        <td></td>
                                                                        <td colspan="5">
                                                                            <div class="collapse" id="accordion-container-{{ $value['id'] }}">
                                                                                <div class="row vertical-align">
                                                                                    <div class="col-md-2">
                                                                                        <label class="pull-right">Remarks:</label>
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        {{-- {{ dd($date['selected']) }} --}}
                                                                                        @if(!empty($value['value_location']) || $date['selected'] != Carbon\Carbon::now()->toDateString())
                                                                                            <input type="hidden" name="date" value="{{ $date['selected'] }}">
                                                                                            <input type="hidden" name="user[{{ $index }}][modified_status]" value="1">
                                                                                        @endif
                                                                                        {{-- @if(request()->date != Carbon\Carbon::now()->toDateString())
                                                                                            <input type="text" name="user[{{ $index }}][modified_status]" value="1">
                                                                                        @endif --}}
                                                                                        <input name="user[{{ $index }}][modified_remarks]" id="user_modified_remarks_{{ $value['id'] }}" class="form-control text-light" required disabled type="text">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <button class="btn btn-primary pull-right" id="buttonButtom">Submit</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <h4>Attach Image: </h4>
                                                        <input type="file" name="empImg" id="browseImg">
                                                    </div>
                                                </div>
                                            </form>
                                            @endif
                                        </div>
                                        <div class="tab-pane" id="present" role="tabpanel">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hovered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Activities</th>
                                                                <th>Location</th>
                                                                <th>Remarks</th>
                                                                <th class="text-center">Team</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($attendance['present'] as $present)
                                                            <tr>
                                                                <td class="text-light">{{ $present['users']['fname'] . ' ' . $present['users']['lname'] }}</td>
                                                                <td class="text-light">{{ $present['activities'] }}</td>
                                                                <td class="text-light">{{ $present['location'] }}</td>
                                                                <td class="text-light">{{ $present['remarks'] }}</td>
                                                                <td class="text-light text-center">{{ $present['team_name'] }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="absent" role="tabpanel">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hovered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Activities</th>
                                                                <th>Location</th>
                                                                <th>Remarks</th>
                                                                <th class="text-center">Team</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($attendance['absent'] as $absent)
                                                                <tr>
                                                                    <td class="text-light">{{ $absent['users']['fname'] . ' ' . $absent['users']['lname'] }}</td>
                                                                    <td class="text-light">{{ $absent['activities'] }}</td>
                                                                    <td class="text-light">{{ $absent['location'] }}</td>
                                                                    <td class="text-light">{{ $absent['remarks'] }}</td>
                                                                    <td class="text-light text-center">{{ $absent['team_name'] }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div id="mobile-view">
                            @if(count(session()->get('_c')) == 0 && count(session()->get('_t')) == 0)
                            <div class="panel-body">
                                <div class="form-group text-center">
                                    <label><h3><i class="fa fa-close text-danger"></i> Sorry you are not permitted to access this!</h3></label>
                                </div>
                                <div class="form-group text-center">
                                    <label>Please make sure that you have Team/s and Cluster/s</label>
                                </div>
                            </div>
                            @else

                            <div class="panel-body padding-top-0 padding-bottom-0">
                                <div class="form-group text-center">
                                    @php
                                    $fucking_date = Carbon\Carbon::parse(request()->date)->diffInDays(Carbon\Carbon::parse(date('Y-m-d')), false);
                                    $fucking_message = '';
                                    if($fucking_date == 1){ // IF THE FUCKING DATE IS EQUAL TO 1 THEN GIVE THE FUCKING MESSAGE "YESTERDAY". IF NOT, GIVE THE FUCKING MESSAGE "NOW"
                                        $fucking_message = ' (Yesterday)';
                                    } else if($fucking_date == 0) {
                                        $fucking_message = ' (Now)';
                                    }
                                    @endphp
                                    <label><h4><i class="fa fa-calendar text-success"></i> {{ Carbon\Carbon::parse(request()->date)->format('F d, Y')  . $fucking_message}}</h4></label>
                                </div>
                            </div>
                            <div class="panel-body padding-right-2">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-xs-6">
                                            <div class="input-group date">
                                                <input class="form-control text-light" name="jump_to_date" type="text" id="jump_to_date" value="{{ $date['selected'] }}" max="2019-03-04" readonly>
                                                <div class="input-group-addon" title="Jump to certain date" id="icon-container">
                                                    <div class="fa fa-calendar" title="Jump to certain date"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-3 col-xs-3">
                                            <button onclick="window.location  = '{{ url('app/attendance') . '?date=' . $date['previous'] }}'" class="btn btn-md btn-warning">Previous</button>
                                        </div>
                                        <div class="col-md-2 col-sm-3 col-xs-3">
                                            <button onclick="window.location  = '{{ url('app/attendance') . '?date=' . $date['next'] }}'" {{ $date['next'] == null ? 'disabled' : '' }} class="btn btn-md btn-warning pull-right">Next</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                    </div>
                                </div>
                                <div class="form-group">
                                    @if(!empty($clusters))
                                        @foreach(array_reverse($clusters) as $cluster)
                                            <button type="button" onclick="window.location='{{ url('attendance') . '?cl_id=' . $cluster['id'] }}'" class="btn btn-primary" id="mutliplebtn" {{ $selected_cluster == $cluster['id'] ? 'disabled' : '' }}>{{ $cluster['name'] }}</button>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="panel-body padding-left-0 padding-right-0">
                                <form enctype="multipart/form-data" action="{{ route('app.attendance.store') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="panel-body padding-left-0 padding-right-0">
                                            <button type="submit" class="btn btn-success btn-mobile-submit pull-right">Submit</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if(base64_decode(auth()->user()->role) != 'administrator' && count(session()->get('_c')) == 0)
                                            <label class="text-danger">You can't use attendance because it's passed 10:30am</label>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        @include('app.attendance.mobile-view')
                                    </div>
                                    <div class="form-group">
                                        <div class="panel-body padding-left-0 padding-right-0">
                                            <h4>Attach Image: </h4>
                                            <input type="file" name="empImg" id="browseImgMobile">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="panel-body padding-left-0 padding-right-0">
                                            <button type="submit" class="btn btn-success btn-mobile-submit pull-right">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$toggleButton = 0;

function changeButtonStatus(view = "desktop",buttonName = "", status, activities, location, remarks, footer = null){
    // alert(status);
    // how to count elements with same class in jquery
    $('.buttonStatus')
    $buttonText = $('#' + buttonName).text();
    // alert(footer);
    if(view == "desktop"){
        if($buttonText == 'Undecided'){
            $('#' + buttonName).removeClass('btn-default').addClass('btn-info');
            $('#' + buttonName).text('Present');
            $('#' + status).val(1);
            $('select[name="' + activities + '"]').prop("disabled", false);
            $('input[name="' + location + '"]').prop("disabled", false);
            $('input[name="' + remarks + '"]').prop("disabled", false);
        } else if($buttonText == 'Present'){
            $('#' + buttonName).removeClass('btn-info').addClass('btn-danger');
            $('#' + buttonName).text('Absent');
            $('#' + status).val(0);
            $('select[name="' + activities + '"]').prop("disabled", false);
            $('input[name="' + location + '"]').prop("disabled", false);
            $('input[name="' + remarks + '"]').prop("disabled", false);
        } else if($buttonText == 'Absent'){
            $('#' + buttonName).removeClass('btn-danger').addClass('btn-default');
            $('#' + buttonName).text('Undecided');
            $('#' + status).val(null);
            $('select[name="' + activities + '"]').prop("disabled", true);
            $('input[name="' + location + '"]').prop("disabled", true);
            $('input[name="' + location + '"]').val(null);
            $('input[name="' + remarks + '"]').prop("disabled", true);
            $('input[name="' + remarks + '"]').val(null);
        }
    } else {
        // alert(footer);
        if($buttonText == 'Undecided'){
            $('#' + buttonName).removeClass('btn-default').addClass('btn-info');
            $('#' + buttonName).text('Present');
            $('#user_mobile_status_' + footer).val(1);
            $('#user_mobile_activity_' + footer).prop('disabled', false);
            $('#user_mobile_location_' + footer).prop('disabled', false);
            $('#user_mobile_remarks_' + footer).prop('disabled', false);
            $('#panel-footer-' + footer).show();
        } else if($buttonText == 'Present'){
            $('#' + buttonName).removeClass('btn-info').addClass('btn-danger');
            $('#' + buttonName).text('Absent');
            $('#user_mobile_status_' + footer).val(0);
            $('#user_mobile_activity_' + footer).prop('disabled', false);
            $('#user_mobile_location_' + footer).prop('disabled', false);
            $('#user_mobile_remarks_' + footer).prop('disabled', false);
            $('#panel-footer-' + footer).show();
        } else if($buttonText == 'Absent'){
            $('#' + buttonName).removeClass('btn-danger').addClass('btn-default');
            $('#' + buttonName).text('Undecided');
            $('#user_mobile_status_' + footer).val(null);
            $('#panel-footer-' + footer).hide();
            $('#user_mobile_activity_' + footer).prop('disabled', true);
            $('#user_mobile_location_' + footer).prop('disabled', true);
            $('#user_mobile_remarks_' + footer).prop('disabled', true);
            $('#user_mobile_activity_' + footer).val('Blitz');
            $('#user_mobile_location_' + footer).val(null);
            $('#user_mobile_remarks_' + footer).val(null);s
            $('#user_mobile_status_' + footer).val(null);s
            $('#user_mobile_userid_' + footer).val(null);s
        }
    }
}

function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source) checkboxes[i].checked = source.checked;
    }
}

function agentAttendance(){
    // console.log($('#chb1').val() == "" && $('#ch6').val() == "");
    if($('#chb1').val() != "" && $('#ch6').val() != ""){
        $('#present').removeAttr('disabled');
        $('#absent').removeAttr('disabled');
    }else{
        $('#present').attr('disabled');
        $('#absent').attr('disabled');
    }
}
var today = new Date();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var thisMonth = (today.getUTCMonth() + 1) < 10 ? "0" + (today.getUTCMonth() + 1) : today.getUTCMonth()
var thisDate = today.getUTCDate() < 10 ? "0" + today.getUTCDate() : today.getUTCDate()
var date = today.getUTCFullYear() + "-" + thisMonth + "-" + thisDate;
var timein = ' {{ Carbon\Carbon::parse("10:30:00")->toTimeString() }}';
// alert(timein);
// alert(time + ">=" +  timein + " = " + time >= timein);
// alert(time);

// if(time <= timein){
//     $('#hidepanel').hide();
//     $('#showpanel').show();
//     $('#mutliplebtn').attr('disabled', true);
// }


jQuery(document).ready(function () {
    $('.fa-calendar').click(function() {
        $("#jump_to_date").focus();
    });
    $('#icon-container').click(function() {
        $("#jump_to_date").focus();
    });

    jQuery('#jump_to_date').datepicker ({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        endDate: '+0d',
    }).on('changeDate', function(){
        window.location = '{{ url('app/attendance') . '?date=' }}' + this.value;
    });
});
@if(base64_decode(auth()->user()->role) != 'administrator' && count(session()->get('_c')) == 0)
    if(time <= timein){
        $('#buttonButtom').attr('disabled', true);
        $('#buttonTop').attr('disabled', true);
        $('.attendance').attr('disabled', true);
        $('#browseImg').attr('disabled', true);
        $('#browseImgMobile').attr('disabled', true);
        $('.btn-mobile-submit').attr('disabled', true);
        $('.btn-mobile-status').attr('disabled', true);
    }
@endif

var selected_date = "{{ request()->date != null ? request()->date : Carbon\Carbon::now() }}";

@if(count(session()->get('_c')) == 0 )
    @if(auth()->user()->role == base64_encode('administrator'))
    if(selected_date < date){
        $('#buttonButtom').attr('disabled', false);
        $('#buttonTop').attr('disabled', false);
        $('.attendance').attr('disabled', false);
        $('#browseImg').attr('disabled', false);
        $('#browseImgMobile').attr('disabled', false);
        $('.btn-mobile-submit').attr('disabled', false);
        $('.btn-mobile-status').attr('disabled', false);
    }
    @else
    if(selected_date < date){
        $('#buttonButtom').attr('disabled', true);
        $('#buttonTop').attr('disabled', true);
        $('.attendance').attr('disabled', true);
        $('#browseImg').attr('disabled', true);
        $('#browseImgMobile').attr('disabled', true);
        $('.btn-mobile-submit').attr('disabled', true);
        $('.btn-mobile-status').attr('disabled', true);
    }
    @endif
@endif

function showClRemark(index, location, remarks, activity, buttonLabel){
    original_location = location;
    original_remarks = remarks;
    original_activity = activity;
    original_button = buttonLabel;

    if($('#changeStatus_' + index).text() == 'Undecided'){
        $('#accordion-container-' + index).collapse('hide');
        $('#user_modified_remarks_' + index).attr('disabled', true);
        $('#tr-accordion-' + index).hide();
    }
    else {
        if((original_remarks != $('#user_remarks_' + index).val()) || (original_location != $('#user_location_' + index).val()) || (original_activity != $('#user_activity_' + index).val()) || ($('#changeStatus_' + index).text() != original_button)){
            $('#tr-accordion-' + index).show();
            $('#user_modified_remarks_' + index).attr('disabled', false);
            $('#accordion-container-' + index).collapse('show');
        } else {
            $('#accordion-container-' + index).collapse('hide');
            $('#user_modified_remarks_' + index).attr('disabled', true);
            // $('#tr-accordion-' + index).attr('hidden', true);
            $('#tr-accordion-' + index).hide();
        }
    }
    // $('#accordion-container-' + index).collapse("show");
}

function showMobileClRemark(index, location, remarks, activity, buttonLabel){
    original_location = location;
    original_remarks = remarks;
    original_activity = activity;
    original_button = buttonLabel;

    // alert($('#user_mobile_remarks_' + index).val());
    if($('#changeMobileStatus_' + index).text() == 'Undecided'){
        $('#accordion-mobile-container-' + index).collapse('hide');
        $('#user_mobile_modified_remarks_' + index).attr('disabled', true);
        // $('#tr-accordion-' + index).hide();
    }
    else {
        // alert(original_remarks);
        // if((original_remarks != $('#user_mobile_remarks_' + index).val()) || (original_location != $('#user_mobile_location_' + index).val()) || (original_activity != $('#user_mobile_activity_' + index).val()) || ($('#changeMobileStatus_' + index).text() != original_button)){
        if((original_remarks != $('#user_mobile_remarks_' + index).val()) || (original_location != $('#user_mobile_location_' + index).val()) || (original_activity != $('#user_mobile_activity_' + index).val()) || ($('#changeMobileStatus_' + index).text() != original_button)){
            $('#user_mobile_modified_remarks_' + index).attr('disabled', false);
            $('#accordion-mobile-container-' + index).collapse('show');
        } else {
            $('#user_mobile_modified_remarks_' + index).attr('disabled', true);
            $('#accordion-mobile-container-' + index).collapse('hide');
        }
    }
    // $('#accordion-container-' + index).collapse("show");
}

function showRemark(index, view = 'desktop'){
    // alert(index);
    if(view == 'desktop'){
        if($('#changeStatus_' + index).text() == 'Undecided'){
            $('#accordion-container-' + index).collapse('hide');
            $('#user_modified_remarks_' + index).attr('disabled', true);
            $('#tr-accordion-' + index).hide();
        }
        else {
            $('#tr-accordion-' + index).show();
            $('#user_modified_remarks_' + index).attr('disabled', false);
            $('#accordion-container-' + index).collapse('show');
        }
    }
    else {
        if($('#changeMobileStatus_' + index).text() == 'Undecided'){
            $('#accordion-mobile-container-' + index).collapse('hide');
            $('#user_mobile_modified_remarks_' + index).attr('disabled', true);
            // $('#tr-accordion-' + index).hide();
        }
        else {
            // alert(index);
            // $('#tr-accordion-' + index).show();
            $('#user_mobile_modified_remarks_' + index).attr('disabled', false);
            $('#accordion-mobile-container-' + index).collapse('show');
        }
    }
}
</script>
@endsection
