@extends ('layouts.app')
@section('content')
bkit pre?
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
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="panel-title">
                                        User Attendance
                                    </h3>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="" class="btn btn-xs btn-default">
                                        <span class='fa fa-plus-circle'></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- {{dd(empty($attendance['unpresent']))}} --}}
                        @if(count(session()->get('_c')) == 0 && count(session()->get('_t')) == 0)
                        <div class="panel-body">
                            <div class="form-group text-center">
                                <label><h2><i class="fa fa-close text-danger"></i> Sorry you don't have any teams or clusters!</h2></label>
                            </div>
                            <div class="form-group text-center">
                                <label>Please make sure that you are a team leader or a cluster</label>
                            </div>
                        </div>
                        @else
                        <div class="panel-body">
                            <div class="form-group">
                                @if(!empty($clusters))
                                    @foreach(array_reverse($clusters) as $cluster)
                                        {{-- {{ dd(url('attendance') . '?team_id=' . $cluster['id']) }} --}}
                                            <button type="button" onclick="window.location='{{ url('attendance') . '?cl_id=' . $cluster['id'] }}'" class="btn btn-primary" id="mutliplebtn" {{ $selected_cluster == $cluster['id'] ? 'disabled' : '' }}>{{ $cluster['name'] }}</button>
                                    @endforeach
                                @endif
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
                                <div class="tab-pane active" id="unpresent" role="tabpanel">
                                    {{-- {{ count($attendance['unpresent']) }} --}}
                                    <div id="desktop-view">
                                        @if(count($attendance['unpresent']) == 0)
                                        <div class="form-group text-center">
                                            <label><h2><i class="fa fa-info-circle text-info"></i> All agents have been checked</h2></label>
                                        </div>
                                        <div class="form-group text-center">
                                            <label>If you have any concerns or corrections, please contact your cluster lead</label>
                                        </div>
                                        @else
        								@include('includes.notif')
                                        <form enctype="multipart/form-data" action="{{route('attendance.store')}}" method="POST">
                                            {{ csrf_field() }}
                                            {{-- <input type="hidden" name="cl_id" value="{{  }}"> --}}
                                            <div class="panel-body">
                                                <button class="btn btn-primary pull-right" id="buttonTop">Submit</button>
                                            </div>
                                            <div class="panel-body">
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
                                                                <tr>
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
                                                                        <button type="button" name="changeStatus" id="changeStatus_{{ $index }}" onclick="changeButtonStatus('desktop', 'changeStatus_{{ $index }}', 'status_{{ $index }}' , 'user[{{ $index }}][activities]', 'user[{{ $index }}][location]', 'user[{{ $index }}][remarks]')"
                                                                        class="btn
                                                                        @if(!empty($value['value_btn']))
                                                                            {{ $value['value_btn']['class'] }}
                                                                        @else
                                                                            btn-default
                                                                        @endif atendance
                                                                        ">@php
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
                                                                        <select class="form-control input-gray" name="user[{{ $index }}][activities]"
                                                                            @if(empty($value['value_activity']))
                                                                                disabled
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
                                                                            <input name="user[{{ $index }}][location]" id="user[{{ $index }}][location]" class="form-control input-gray" required type="text"
                                                                            @if(!empty($value['value_location']))
                                                                                value="{{ $value['value_location'] }}"
                                                                            @else
                                                                                disabled
                                                                            @endif
                                                                            >
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input name="user[{{ $index }}][remarks]" id="user[{{ $index }}][remarks]" class="form-control input-gray" required type="text"
                                                                        @if(!empty($value['value_remarks']))
                                                                            value="{{ $value['value_remarks'] }}"
                                                                        @else
                                                                            disabled
                                                                        @endif
                                                                        >
                                                                    </td>

                                                                    <td class="text-light text-center">{{ $value['team_name'] }}</td>
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
                                    <div id="mobile-view">
                                        <form enctype="multipart/form-data" action="{{ route('attendance.store') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                            <div class="form-group">
                                                @include('app.attendance.mobile-view')
                                            </div>
                                            <div class="form-group">
                                                <h4>Attach Image: </h4>
                                                <input type="file" name="empImg" id="browseImg">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </form>
                                    </div>
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
    console.log($('#chb1').val() == "" && $('#ch6').val() == "");
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
var timein = 10 + ":" + 30 + ":" + 00;

// alert(time + ">=" +  timein + " = " + time >= timein);
// alert(time);
// if(time <= timein){
//     $('#hidepanel').hide();
//     $('#showpanel').show();
//     $('#mutliplebtn').attr('disabled', true);
// }

if(time <= timein){
    $('#buttonButtom').attr('disabled', true);
    $('#buttonTop').attr('disabled', true);
    $('.atendance').attr('disabled', true);
    $('#browseImg').attr('disabled', true);
}

</script>
@endsection
